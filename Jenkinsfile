pipeline {
    agent any
    
    parameters {
        string(name: 'VERSION', defaultValue: '1.0.0', description: 'Version to deploy')
        choice(name: 'ENVIRONMENT', choices: ['staging', 'production'], description: 'Environment to deploy to')
    }
    
    environment {
        ARTIFACT_NAME = "php-crud-app"
        ARTIFACT_DIR = "artifacts"
        MYSQL_CREDS = credentials('mysql-credentials')
    }
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
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

                // AWS Credentials added here for S3 upload
                /*
                withAWS(credentials: 'aws-s3-credentials') {
                    s3Upload(
                        bucket: 'your-s3-bucket-name',
                        file: "${ARTIFACT_DIR}/${ARTIFACT_NAME}-${params.VERSION}.zip",
                        path: "artifacts/${ARTIFACT_NAME}-${params.VERSION}.zip"
                    )
                }
                */
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
                    
                    // Use sshagent with the configured key and let Ansible handle host checking
                    // using the options from inventory file
                    sshagent(['staging-ssh-key']) {
                        sh """
                            cd ansible
                            ansible-playbook playbook.yml -i inventory -e "target_env=staging app_version=${params.VERSION} artifact_name=${artifactName} artifact_path=./${artifactName}"
                        """
                    }
                }
                
                // Use the IP from inventory file
                echo "Staging deployment complete. Access at http://172.31.27.239"
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
                    
                    // Use sshagent with the configured key and let Ansible handle host checking
                    // using the options from inventory file
                    sshagent(['production-ssh-key']) {  // Using a separate key for production
                        sh """
                            cd ansible
                            ansible-playbook playbook.yml -i inventory -e "target_env=production app_version=${params.VERSION} artifact_name=${artifactName} artifact_path=./${artifactName}"
                        """
                    }
                }
                
                // Use the IP from inventory file
                echo "Production deployment complete. Access at http://172.31.23.26"
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
