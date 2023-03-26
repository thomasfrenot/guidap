# Requirements
* Install [Docker (v20.10+)](https://docs.docker.com/engine/install/)
* Install [Docker Compose (v2.10+)](https://docs.docker.com/compose/install/)

# Install the project for the first time
1. Run `docker-compose build`
2. Run `docker run -it --rm -v $PWD:/srv/app coding-assessment-php_symfony /bin/sh -c 'composer install'`

# Configuration environnement
ajoute le fichier .env.local avec les éléments suivants configurés :
```
API_GEOCODING=YOUR_MAPBOX_API_KEY
JWT_PASSPHRASE=A_SECRET_PASSPHRASE
```
# Start the project
1. Run `docker-compose up -d`
2. Go to API Doc http://localhost:8080/apidoc or http://localhost:8080/apidoc.json

# Stopping all the containers
1. Ctrl+C when the containers are running
2. Run `docker-compose down`

# Versions
* Symfony version: `5.4`
* PHP version: `7.4.33`
