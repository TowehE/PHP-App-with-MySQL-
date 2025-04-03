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
                withAWS(credentials: 'aws-s3-credentials') {  // <-- Opening the AWS credentials block
                    s3Upload(
                        bucket: 'your-s3-bucket-name',  // <-- Specify your S3 bucket
                        file: "${ARTIFACT_DIR}/${ARTIFACT_NAME}-${params.VERSION}.zip",
                        path: "artifacts/${ARTIFACT_NAME}-${params.VERSION}.zip"
                    )
                }  // <-- Close the withAWS block here
                */
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
                    
                    // Run Ansible playbook for staging
                    sh """
                        cd ansible
                        ansible-playbook playbook.yml -i inventory -e "target_env=staging app_version=${params.VERSION}"
                    """
                }
                
                echo "Staging deployment complete. Access at http://[staging-server-ip]"
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
                    
                    // Run Ansible playbook for production
                    sh """
                        cd ansible
                        ansible-playbook playbook.yml -i inventory -e "target_env=production app_version=${params.VERSION}"
                    """
                }
                
                echo "Production deployment complete. Access at http://[production-server-ip]"
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
