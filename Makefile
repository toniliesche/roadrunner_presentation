SHELL := /bin/bash
build-docker: build-nginx build-php-fpm build-php-rr build-dev-cli

build-dev-cli: build-php-fpm
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain dev-cli

build-nginx:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain nginx

build-php-fpm:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain php

build-php-rr: build-php-fpm
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain roadrunner

get-bash:
	source docker/.env && docker run --rm -it -v .:/var/www/webapp -w /var/www/webapp phpughh/roadrunner/php:$${PHP_VERSION}-cli bash