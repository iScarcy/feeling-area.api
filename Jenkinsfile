pipeline {
  agent any
  options { timestamps() }

  environment {
    // Host FTP Aruba (metti quello corretto, spesso tipo ftp.tuodominio.it)
    FTP_HOST   = "ftp.osteopatamuscolinoantonio.it"

    // Cartella locale nel repo che vuoi pubblicare
    LOCAL_DIR  = "api"

    // Cartella remota FTP dove vuoi pubblicare
    REMOTE_DIR = "/www.osteopatamuscolinoantonio.it/api"
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Build (Composer)') {
      steps {
        dir("${env.LOCAL_DIR}") {
          sh '''
            composer install --no-dev --prefer-dist --no-interaction
            composer dump-autoload -o
          '''
        }
      }
    }

    stage('Deploy (FTP mirror)') {
      steps {
        withCredentials([usernamePassword(credentialsId: 'aruba-ftp', usernameVariable: 'FTP_USER', passwordVariable: 'FTP_PASS')]) {
          sh '''
            lftp -c "
              set ftp:passive-mode true;
              set net:max-retries 2;
              set net:timeout 20;
              set cmd:fail-exit true;

              open -u $FTP_USER,$FTP_PASS $FTP_HOST;

              # sincronizza locale -> remoto
              mirror -R --delete --verbose \
                --exclude-glob .git* \
                --exclude-glob .DS_Store \
                --exclude-glob tests/ \
                --exclude-glob .env \
                ${LOCAL_DIR}/ ${REMOTE_DIR}/
            "
          '''
        }
      }
    }

    stage('Smoke test') {
      steps {
        sh '''
          curl -sSf https://www.osteopatamuscolinoantonio.it/api/servizi > /dev/null
        '''
      }
    }
  }
}