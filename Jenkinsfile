pipeline {
    agent any
    
    tools {
        // Correct tool type for SonarQube Scanner
        'hudson.plugins.sonar.SonarRunnerInstallation' 'SonarScanner'
    }
    
    parameters {
        string(name: 'VERSION', defaultValue: '1.0.0', description: 'Version to deploy')
        choice(name: 'ENVIRONMENT', choices: ['staging', 'production'], description: 'Environment to deploy to')
    }
    
    environment {
        ARTIFACT_NAME = "php-crud-app"
        ARTIFACT_DIR = "artifacts"
        MYSQL_CREDS = credentials('mysql-credentials')
        // Only staging needs a public IP
        STAGING_PUBLIC_IP = "3.80.29.14"
        SONAR_TOKEN = credentials('sonar-token')
    }
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    withEnv(["SONAR_TOKEN=${SONAR_TOKEN}"]) {
                        // Using the SonarScanner tool installation
                        sh "${tool 'SonarScanner'}/bin/sonar-scanner \
                            -Dsonar.projectKey=php-crud-app \
                            -Dsonar.projectName=\"PHP CRUD Application\" \
                            -Dsonar.projectVersion=${params.VERSION} \
                            -Dsonar.sources=src \
                            -Dsonar.host.url=http://54.196.217.149:9000 \
                            -Dsonar.login=${SONAR_TOKEN}"
                    }
                }
            }
        }
        
        stage('Quality Gate') {
            steps {
                timeout(time: 1, unit: 'HOURS') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }
        
        stage('Prepare Artifact') {
            steps {
                script {
                    // Create artifacts directory
                    sh "mkdir -p ${ARTIFACT_DIR}"
                    
                    // Create a zip archive of the application
                    sh """
                        zip -r ${ARTIFACT_DIR}/${ARTIFACT_NAME}-${params.VERSION}.zip src/ sql/
                    """
                }
            }
        }
        
        stage('Store Artifact') {
            steps {
                // Here you would upload to Nexus or S3
                // For now, we'll just keep it locally
                echo "Artifact stored at: ${WORKSPACE}/${ARTIFACT_DIR}/${ARTIFACT_NAME}-${params.VERSION}.zip"
            }
        }
        
        stage('Deploy to Staging') {
            when {
                expression { params.ENVIRONMENT == 'staging' || params.ENVIRONMENT == 'both' }
            }
            steps {
                echo "Deploying version ${params.VERSION} to Staging environment"
                
                script {
                    env.app_version = params.VERSION
                    def artifactName = "${ARTIFACT_NAME}-${params.VERSION}.zip"

                    // Copy the artifact to the ansible directory
                    sh "mkdir -p ansible || true"
                    sh "cp ${ARTIFACT_DIR}/${artifactName} ansible/"
                    
                    // Use sshagent with the configured key
                    sshagent(['staging-ssh-key']) {
                        sh """
                            cd ansible
                            ansible-playbook playbook.yml -i inventory -e "target_env=staging app_version=${params.VERSION} artifact_name=${artifactName} artifact_path=./${artifactName}"
                        """
                    }
                }
                
                // Use the public IP for external access to staging
                echo "Staging deployment complete. Access at http://${env.STAGING_PUBLIC_IP}"
            }
            post {
                failure {
                    echo "Staging deployment failed. Check SSH connections and server availability."
                }
            }
        }
        
        stage('Approve Production Deployment') {
            when {
                expression { params.ENVIRONMENT == 'production' }
            }
            steps {
                // Manual approval step
                input message: "Deploy to Production?", ok: "Approve"
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

                    // Copy the artifact to the ansible directory
                    sh "mkdir -p ansible || true"
                    sh "cp ${ARTIFACT_DIR}/${artifactName} ansible/"
                    
                    // Use sshagent with the configured key
                    sshagent(['production-ssh-key']) {
                        sh """
                            cd ansible
                            ansible-playbook playbook.yml -i inventory -e "target_env=production app_version=${params.VERSION} artifact_name=${artifactName} artifact_path=./${artifactName}"
                        """
                    }
                }
                
                // Production is private, mention access through VPN or bastion
                echo "Production deployment complete. Access via internal network at http://172.31.23.26"
                echo "Note: Production server is only accessible from within the VPC or via VPN"
            }
            post {
                failure {
                    echo "Production deployment failed. Check SSH connections and server availability."
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
            // Clean workspace
            cleanWs()
        }
    }
}
