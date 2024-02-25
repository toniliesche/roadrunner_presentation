SHELL := /bin/bash
build-docker: build-nginx build-php-fpm build-php-fpm-dev build-php-rr build-php-rr-dev build-mariadb build-dev-cli build-otel-collector build-zipkin build-traefik

build-dev-cli: build-php-fpm
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain dev-cli

build-mariadb:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain mariadb

build-nginx:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain nginx

build-otel-collector:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain otel-collector

build-php-fpm:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain php

build-php-fpm-dev: build-php-fpm
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain php-dev

build-php-rr: build-php-fpm
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain roadrunner

build-php-rr-dev: build-php-rr
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain roadrunner-dev

build-traefik:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain traefik

build-zipkin:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain zipkin

get-bash:
	source docker/.env && docker run --rm -it -v .:/var/www/webapp -w /var/www/webapp phpughh/roadrunner/php:$${PHP_VERSION}-cli bash

images:
	docker images | grep phpughh

clean:
	if [ -d tmp/cache ]; then sudo rm -rf tmp/cache/*; fi
	if [ -d tmp/log ]; then sudo rm -rf tmp/log/*; fi
	if [ -d tmp/proxies ]; then sudo rm -rf tmp/proxies/*; fi

logs:
	tail -n 100 -f tmp/log/*.log

audit-logs:
	tail -n 100 -f tmp/log/*audit.log

app-logs:
	tail -n 100 -f tmp/log/*app.log

sql-logs:
	tail -n 100 -f tmp/log/*sql.log

nginx-logs:
	docker logs --tail 100 -f nginx

down:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh down

stop:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh stop

pre-up:
	sudo mkdir -p tmp/{cache,log,log,proxies}
	sudo chown -R 82:82 tmp/*

up: pre-up
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh up -d --remove-orphans

up-dev: pre-up
	docker compose --env-file=docker/.env -f docker/docker-compose.run.dev.yml -p phpughh up -d --remove-orphans

up-rr: configure-rr-live pre-up
	docker compose --env-file=docker/.env -f docker/docker-compose.run.rr.yml -p phpughh up -d --remove-orphans

up-rr-dev: configure-rr-dev pre-up
	docker compose --env-file=docker/.env -f docker/docker-compose.run.rr.dev.yml -p phpughh up -d --remove-orphans

init-db:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh -c "mysql < roadrunner/res/init.sql"

mysql:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli mysql

cli:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh

rr:
	docker exec php rr reset

configure-rr-dev:
	rm -f .rr.yaml
	ln -s .rr.dev.yaml .rr.yaml

configure-rr-live:
	rm -f .rr.yaml
	ln -s .rr.live.yaml .rr.yaml