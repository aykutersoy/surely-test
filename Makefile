
# run docker containers
run: docker-stop docker-build

# deploy
deploy: init deploy-db

# initiliase
init:
	cd symfony; composer install
	[ -f symfony/app/config/parameters.yml ] && echo 'file parameters.yml already exist' || cp symfony/app/config/parameters.yml.dist symfony/app/config/parameters.yml;

# deploy-db
deploy-db:
	docker exec docker_apache_php_1 php bin/console doctrine:database:create
	docker exec docker_apache_php_1 php bin/console doctrine:migrations:migrate	

####################################
## DOCKER COMMANDS

docker-build:
	cd docker; docker-compose build
	cd docker; docker-compose -f docker-compose.yml up -d --build
docker-stop:
	cd docker; docker-compose -f docker-compose.yml stop

####################################