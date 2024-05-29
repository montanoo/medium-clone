@Library('jenkins-build-helpers') _
setupEnvironment(['business_unit': 'corp'])

def createTestingEnvironment() {
    return setupContainers([
        [
            'name': 'main',
            // WARNING: rename this to follow $username-testing-image.
            'image': 'ike-docker-local.artifactory.internetbrands.com/corp/levelup-academy:fmontano-testing-image',
            'imagePullPolicy': 'Always',
            'env': [
                ['name': 'DB_HOST',     'value': 'localhost'],
                ['name': 'PGPASSWORD',  'value': 'password'],
                ['name': 'DB_DATABASE', 'value': 'testing'],
                ['name': 'DB_USERNAME', 'value': 'sail'],
                ['name': 'DB_PASSWORD', 'value': 'password'],
                ['name': 'LOG_CHANNEL', 'value': 'single'],
                ['name': 'LOG_LEVEL',   'value': 'debug'],
            ],
        ],[
           'name': 'pgsql',
           'image': 'postgres:14',
           'env': [
                ['name': 'PGPASSWORD',        'value': 'password'],
                ['name': 'POSTGRES_DB',       'value': 'testing'],
                ['name': 'POSTGRES_USER',     'value': 'sail'],
                ['name': 'POSTGRES_PASSWORD', 'value': 'password'],
          ]
        ]
   ])
}

pipeline {
    agent none

    options {
        gitLabConnection('IB Gitlab')
    }

    // hello, something changed

    stages {
        stage('Build pipeline testing image') {
            agent {
                kubernetes {
                    yaml dockerContainerImageBuildAndPushPodManifest()
                }
            }
            steps {
                container('builder') {
                    dockerContainerImageBuildAndPush([
                        'docker_repo_host': 'ike-docker-local.artifactory.internetbrands.com',
                        'docker_repo_credential_id': 'artifactory-ike',
                        'dockerfile': './pipeline/Dockerfile',
                        'docker_image_name': 'levelup-academy',
                        'docker_image_tag': 'fmontano-testing-image' // WARNING: rename this to follow $username-testing-image
                    ])
                }
            }
        }

        stage('Run PHP tests') {
            when { not { branch 'master' } }
            agent {
                kubernetes {
                    yaml createTestingEnvironment()
                }
            }
            post {
                success {
                    updateGitlabCommitStatus name: 'php-tests', state: 'success'
                }
                failure {
                    updateGitlabCommitStatus name: 'php-tests', state: 'failed'
                }
            }
            steps {
                container('main') {
                    // For reference see: https://plugins.jenkins.io/gitlab-branch-source/#plugin-content-environment-variables
                    // (variables such as this will be useful for your homework).
                    sh 'echo "Branch $BRANCH_NAME going into $CHANGE_TARGET implemented by $CHANGE_AUTHOR"'
                    sh '''
                        composer install
                    '''
                    sh '''
                        APP_ENV=testing php artisan test --env=testing
                    '''
                }
            }
        }

        stage('MR changed') {
            when {
                // so that my branch only contains commits from jenkinsfile, my last homework has not been reviewed.
                // I'll once again get branches from main when my MRs are approved by Doma and you! :happy:
                // changeRequest target:  'feature/LVLUP-781-storage-oop'
                changeRequest target:  'main'
            }
            post {
                success {
                    updateGitlabCommitStatus name: 'mr-checks', state: 'success'
                }
                failure {
                    updateGitlabCommitStatus name: 'mr-checks', state: 'failed'
                }
            }
            parallel{
                //
                stage('Branch name check') {
                    agent any
                    steps {
                        script {
                            echo "$env.CHANGE_BRANCH"
                            if (env.CHANGE_BRANCH.startsWith('feature')
                                || env.CHANGE_BRANCH.startsWith('feat')
                                || env.CHANGE_BRANCH.startsWith('build')
                                || env.CHANGE_BRANCH.startsWith('build')
                                || env.CHANGE_BRANCH.startsWith('ci')
                                || env.CHANGE_BRANCH.startsWith('docs')
                                || env.CHANGE_BRANCH.startsWith('fix')
                                || env.CHANGE_BRANCH.startsWith('pref')
                                || env.CHANGE_BRANCH.startsWith('refactor')
                                || env.CHANGE_BRANCH.startsWith('style')
                                || env.CHANGE_BRANCH.startsWith('test'))
                            {
                                echo 'branch naming convention seems to be fine.'
                            } else {
                                error('branch naming is not valid.')
                            }

                        }
                    }
                }
                stage('Email Check') {
                    agent any
                    steps {
                        script {
                            def commits = sh(script:"git log --pretty=format:%ae remotes/origin/${env.CHANGE_TARGET}..remotes/origin/${env.BRANCH_NAME}", returnStdout: true).trim().split('\n')
                            for(email in commits){
                                if (!email.endsWith('@internetbrands.com')) {
                                    error('one (or more) commit author emails are not from our domain.')
                                }
                                else {
                                    echo "all commits are from an email that belongs to our domain."
                                }
                            }
                        }
                    }
                }
            }

        }
    }
}
