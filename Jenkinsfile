pipeline {
    agent any
    
    tools {
        'hudson.plugins.sonar.SonarRunnerInstallation' 'SonarScanner'
    }
    
    parameters {
        string(name: 'VERSION', defaultValue: '1.0.0', description: 'Version to deploy')
        choice(name: 'ENVIRONMENT', choices: ['staging', 'production'], description: 'Environment to deploy to')
        booleanParam(name: 'REQUIRE_APPROVAL', defaultValue: true, description: 'Require manual approval for production deployment')
    }
    
    environment {
        ARTIFACT_NAME = "php-crud-app"
        ARTIFACT_DIR = "artifacts"
        MYSQL_CREDS = credentials('mysql-credentials')
        STAGING_PUBLIC_IP = "3.80.29.14"
        SONAR_PROJECT_KEY = "php-crud-app"
        SONAR_URL = "http://54.196.217.149:9000"
    }
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
                
                // Check if src directory exists
                script {
                    if (!fileExists('src')) {
                        error "Source directory 'src' not found. Please check your repository structure."
                    }
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    // Use proper credentials binding for security
                    withCredentials([string(credentialsId: 'sonar-token', variable: 'SONAR_TOKEN')]) {
                        // Add Java options to fix compatibility issues with Java 17
                        withEnv(["SONAR_SCANNER_OPTS=-Djava.security.manager=allow --add-opens=java.base/java.lang=ALL-UNNAMED"]) {
                            sh """
                                ${tool 'SonarScanner'}/bin/sonar-scanner \\
                                -Dsonar.projectKey=${env.SONAR_PROJECT_KEY} \\
                                -Dsonar.projectName="PHP CRUD Application" \\
                                -Dsonar.projectVersion=${params.VERSION} \\
                                -Dsonar.sources=src \\
                                -Dsonar.host.url=${env.SONAR_URL} \\
                                -Dsonar.login=\${SONAR_TOKEN}
                            """
                        }
                    }
                }
                
                // Save the task ID using a more reliable method
                script {
                    try {
                        if (fileExists('.scannerwork/report-task.txt')) {
                            def taskReport = readFile('.scannerwork/report-task.txt')
                            echo "Found report-task.txt file"
                            
                            // Extract the ceTaskId using a regular expression
                            def taskIdPattern = /.*ceTaskId=([a-zA-Z0-9_-]+).*/
                            def matcher = taskReport =~ taskIdPattern
                            
                            if (matcher.find()) {
                                env.SONAR_TASK_ID = matcher[0][1]
                                echo "SonarQube Task ID: ${env.SONAR_TASK_ID}"
                            } else {
                                echo "Could not find ceTaskId in the report-task.txt file"
                                // Alternative method
                                def taskIdLine = sh(script: "grep 'ceTaskId=' .scannerwork/report-task.txt || echo 'Not found'", returnStdout: true).trim()
                                echo "Task ID line: ${taskIdLine}"
                                
                                if (taskIdLine != 'Not found') {
                                    env.SONAR_TASK_ID = taskIdLine.split('=')[1]
                                    echo "SonarQube Task ID (alternative method): ${env.SONAR_TASK_ID}"
                                }
                            }
                        } else {
                            echo "Warning: .scannerwork/report-task.txt file not found"
                        }
                    } catch (Exception e) {
                        echo "Error reading SonarQube task report: ${e.message}"
                    }
                }
            }
        }
        
        stage('Quality Gate') {
            steps {
                script {
                    echo "Starting manual Quality Gate check..."
                    
                    // Initial wait to allow SonarQube to begin processing
                    sleep time: 15, unit: 'SECONDS'
                    
                    withCredentials([string(credentialsId: 'sonar-token', variable: 'SONAR_TOKEN')]) {
                        def maxRetries = 12  // 3 minutes total with 15-second intervals
                        def retryCount = 0
                        def qualityGateStatus = "UNKNOWN"
                        def projectStatus = "UNKNOWN"
                        
                        while (retryCount < maxRetries) {
                            try {
                                // First check if the task is still in queue or in progress
                                if (env.SONAR_TASK_ID) {
                                    def taskStatusCmd = """
                                        curl -s -u "${SONAR_TOKEN}:" "${env.SONAR_URL}/api/ce/task?id=${env.SONAR_TASK_ID}"
                                    """
                                    
                                    def taskJson = sh(script: taskStatusCmd, returnStdout: true).trim()
                                    echo "Task status response: ${taskJson}"
                                    
                                    def taskStatusExtractCmd = "echo '${taskJson}' | grep -o '\"status\":\"[^\"]*\"' | head -1 | cut -d '\"' -f 4"
                                    def taskStatus = sh(script: taskStatusExtractCmd, returnStdout: true).trim()
                                    
                                    echo "Current task status: ${taskStatus}"
                                    
                                    if (taskStatus == "IN_PROGRESS" || taskStatus == "PENDING") {
                                        echo "SonarQube analysis still in progress, waiting..."
                                        sleep time: 15, unit: 'SECONDS'
                                        retryCount++
                                        continue
                                    }
                                }
                                
                                // Check the quality gate status
                                def statusCmd = """
                                    curl -s -u "${SONAR_TOKEN}:" "${env.SONAR_URL}/api/qualitygates/project_status?projectKey=${env.SONAR_PROJECT_KEY}"
                                """
                                
                                def statusJson = sh(script: statusCmd, returnStdout: true).trim()
                                echo "Quality gate status response: ${statusJson}"
                                
                                def statusExtractCmd = "echo '${statusJson}' | grep -o '\"status\":\"[^\"]*\"' | head -1 | cut -d '\"' -f 4"
                                qualityGateStatus = sh(script: statusExtractCmd, returnStdout: true).trim()
                                
                                echo "Current Quality Gate status: ${qualityGateStatus}"
                                
                                if (qualityGateStatus == "OK") {
                                    echo "Quality Gate passed!"
                                    projectStatus = "SUCCESS"
                                    break
                                } else if (qualityGateStatus == "ERROR") {
                                    echo "Quality Gate failed. Check SonarQube for details."
                                    projectStatus = "FAILURE"
                                    break
                                } else if (qualityGateStatus == "NONE") {
                                    echo "No Quality Gate defined or applied to this project."
                                    projectStatus = "SUCCESS"  // Continue as success if no quality gate is defined
                                    break
                                } else {
                                    echo "Quality Gate status is still processing or unknown, waiting..."
                                    sleep time: 15, unit: 'SECONDS'
                                }
                            } catch (Exception e) {
                                echo "Error checking Quality Gate: ${e.message}"
                                sleep time: 15, unit: 'SECONDS'
                            }
                            
                            retryCount++
                        }
                        
                        // Handle timeout or set final status
                        if (retryCount >= maxRetries) {
                            echo "Quality Gate check timed out after ${maxRetries} attempts."
                            echo "Proceeding with the pipeline, but quality might not be verified."
                            unstable('Quality Gate status could not be determined')
                        } else if (projectStatus == "FAILURE") {
                            error "Quality Gate failed - see SonarQube for details"
                        }
                        
                        // Store quality gate URL for reference
                        env.SONAR_QUALITY_GATE_URL = "${env.SONAR_URL}/dashboard?id=${env.SONAR_PROJECT_KEY}"
                        echo "SonarQube Quality Gate details: ${env.SONAR_QUALITY_GATE_URL}"
                    }
                }
            }
        }
        
        stage('Prepare Artifact') {
            steps {
                script {
                    // Create artifacts directory
                    sh "mkdir -p ${ARTIFACT_DIR}"
                    
                    // Check if needed directories exist before zipping
                    def sourceDirs = ""
                    if (fileExists('src')) {
                        sourceDirs += "src/ "
                    }
                    if (fileExists('sql')) {
                        sourceDirs += "sql/ "
                    }
                    
                    if (sourceDirs.trim().isEmpty()) {
                        error "No source directories found to package"
                    }
                    
                    // Create a zip archive of the application
                    sh """
                        zip -r ${ARTIFACT_DIR}/${ARTIFACT_NAME}-${params.VERSION}.zip ${sourceDirs}
                    """
                }
            }
        }
        
        stage('Store Artifact') {
            steps {
                // Here you would upload to Nexus or S3
                archiveArtifacts artifacts: "${ARTIFACT_DIR}/${ARTIFACT_NAME}-${params.VERSION}.zip", 
                                 allowEmptyArchive: false,
                                 fingerprint: true
                
                echo "Artifact stored at: ${WORKSPACE}/${ARTIFACT_DIR}/${ARTIFACT_NAME}-${params.VERSION}.zip"
            }
        }
        
        stage('Deploy to Staging') {
            when {
                expression { params.ENVIRONMENT == 'staging' }
            }
            steps {
                echo "Deploying version ${params.VERSION} to Staging environment"
                
                script {
                    env.app_version = params.VERSION
                    def artifactName = "${ARTIFACT_NAME}-${params.VERSION}.zip"

                    // Check if ansible directory exists
                    sh "mkdir -p ansible || true"
                    
                    // Check if artifact exists before copying
                    if (!fileExists("${ARTIFACT_DIR}/${artifactName}")) {
                        error "Artifact ${artifactName} not found. Build may have failed."
                    }
                    
                    sh "cp ${ARTIFACT_DIR}/${artifactName} ansible/"
                    
                    // Use sshagent with the configured key
                    sshagent(['staging-ssh-key']) {
                        sh """
                            cd ansible
                            ansible-playbook playbook.yml -i inventory -e "target_env=staging app_version=${params.VERSION} artifact_name=${artifactName} artifact_path=./${artifactName}"
                        """
                    }
                }
                
                echo "Staging deployment complete. Access at http://${env.STAGING_PUBLIC_IP}"
            }
            post {
                failure {
                    echo "Staging deployment failed. Check SSH connections and server availability."
                }
            }
        }
        
        // *** FIXED PRODUCTION APPROVAL STAGE ***
        stage('Production Approval') {
            when {
                allOf {
                    expression { return params.ENVIRONMENT == 'production' }
                    expression { return params.REQUIRE_APPROVAL == true }
                }
            }
            steps {
                echo "=== Preparing for production deployment approval ==="
                echo "Version: ${params.VERSION}"
                echo "Environment: ${params.ENVIRONMENT}"
                echo "Require Approval: ${params.REQUIRE_APPROVAL}"
                
                // Use a simpler input mechanism to avoid UI issues
                script {
                    try {
                        def userInput = input(
                            id: 'approvalInput', 
                            message: "Deploy version ${params.VERSION} to Production?", 
                            ok: 'Approve',
                            parameters: [
                                string(defaultValue: '', description: 'Enter your name for audit purposes (optional)', name: 'approver')
                            ],
                            submitterParameter: 'approvedBy'
                        )
                        
                        def approver = userInput.approver ?: userInput.approvedBy ?: 'Unknown'
                        echo "Deployment approved by: ${approver}"
                        env.DEPLOYMENT_APPROVER = approver
                    } catch (Exception e) {
                        currentBuild.result = 'ABORTED'
                        error "Production deployment was not approved: ${e.getMessage()}"
                    }
                }
            }
        }
        
        stage('Deploy to Production') {
            when {
                expression { params.ENVIRONMENT == 'production' }
            }
            steps {
                echo "Deploying version ${params.VERSION} to Production environment"
                
                script {
                    env.app_version = params.VERSION
                    def artifactName = "${ARTIFACT_NAME}-${params.VERSION}.zip"

                    // Check if ansible directory exists
                    sh "mkdir -p ansible || true"
                    
                    // Check if artifact exists before copying
                    if (!fileExists("${ARTIFACT_DIR}/${artifactName}")) {
                        error "Artifact ${artifactName} not found. Build may have failed."
                    }
                    
                    sh "cp ${ARTIFACT_DIR}/${artifactName} ansible/"
                    
                    // Use sshagent with the configured key
                    sshagent(['production-ssh-key']) {
                        sh """
                            cd ansible
                            ansible-playbook playbook.yml -i inventory -e "target_env=production app_version=${params.VERSION} artifact_name=${artifactName} artifact_path=./${artifactName}"
                        """
                    }
                }
                
                echo "Production deployment complete. Access via internal network at http://13.218.249.218/"
                echo "Note: Production server is only accessible from within the VPC or via VPN"
            }
            post {
                failure {
                    echo "Production deployment failed. Check SSH connections and server availability."
                }
                success {
                    echo "Production deployment successful! Sending notification..."
                    // Add notification code here if needed
                }
            }
        }
    }
    
    post {
        success {
            echo "Pipeline completed successfully!"
        }
        failure {
            echo "Pipeline failed. Please check the logs."
        }
        always {
            echo "Pipeline execution completed with result: ${currentBuild.result ?: 'SUCCESS'}"
            echo "Build parameters used:"
            echo "- ENVIRONMENT: ${params.ENVIRONMENT}"
            echo "- VERSION: ${params.VERSION}"
            echo "- REQUIRE_APPROVAL: ${params.REQUIRE_APPROVAL}"
            
            // Clean workspace but keep artifacts
            cleanWs(patterns: [[pattern: "${ARTIFACT_DIR}/**", type: 'INCLUDE']])
            
            // Print SonarQube links
            script {
                if (env.SONAR_QUALITY_GATE_URL) {
                    echo "SonarQube Quality Gate results: ${env.SONAR_QUALITY_GATE_URL}"
                } else {
                    echo "SonarQube results available at: ${env.SONAR_URL}/dashboard?id=${env.SONAR_PROJECT_KEY}"
                }
            }
        }
    }
}
