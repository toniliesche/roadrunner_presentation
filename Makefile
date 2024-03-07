SHELL := /bin/bash
build-docker: build-nginx-base build-nginx build-php-fpm build-php-fpm-dev build-php-rr build-php-rr-dev build-mariadb build-dev-cli build-otel-collector build-zipkin build-traefik build-grafana build-prometheus build-portainer build-graylog build-mongo build-opensearch build-filebeat build-ubuntu build-graylog-sidecar build-checkmk

build-checkmk:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain checkmk

build-dev-cli: build-php-fpm
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain dev-cli

build-filebeat:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain filebeat

build-grafana:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain grafana

build-graylog:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain graylog

build-graylog-sidecar: build-ubuntu
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain graylog-sidecar

build-mariadb:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain mariadb

build-mongo:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain mongo

build-nginx-base:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain nginx-base

build-nginx: build-nginx-base
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain nginx

build-opensearch:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain opensearch

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

build-portainer:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain portainer

build-prometheus:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain prometheus

build-traefik:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain traefik

build-ubuntu:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain ubuntu

build-zipkin:
	docker compose --env-file=docker/.env -f docker/docker-compose.build.yml build --progress=plain zipkin

get-bash:
	source docker/.env && docker run --rm -it -v .:/var/www/webapp -w /var/www/webapp phpughh/roadrunner/php:$${PHP_VERSION}-cli bash

images:
	docker images | grep phpughh

clean:
	docker volume prune -a -f
	if [ -d tmp/cache ]; then sudo rm -rf tmp/cache/*; fi
	if [ -d tmp/log ]; then sudo rm -rf tmp/log/*; fi
	if [ -d tmp/proxies ]; then sudo rm -rf tmp/proxies/*; fi

logs:
	tail -n 100 -f tmp/log/roadrunner*.log

audit-logs:
	tail -n 100 -f tmp/log/roadrunner_audit.log

app-logs:
	tail -n 100 -f tmp/log/roadrunner.log

sql-logs:
	tail -n 100 -f tmp/log/roadrunner_sql.log

nginx-logs:
	docker logs --tail 100 -f nginx

rr-logs:
	docker logs --tail 100 -f php

down:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh down

stop:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh stop

pre-up:
	sudo mkdir -p tmp/{cache,log,log,proxies}
	sudo chown -R 82:82 tmp/*

up: pre-up configure-app-dev
	MODE=dev docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh up -d --remove-orphans

up-dev: pre-up configure-app-dev
	MODE=dev docker compose --env-file=docker/.env -f docker/docker-compose.run.dev.yml -p phpughh up -d --remove-orphans

up-perf: pre-up configure-app-perf
	MODE=perf docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh up -d --remove-orphans

up-rr: configure-rr-live pre-up configure-app-dev
	MODE=dev docker compose --env-file=docker/.env -f docker/docker-compose.run.rr.yml -p phpughh up -d --remove-orphans

up-rr-dev: configure-rr-dev pre-up configure-app-dev
	MODE=dev docker compose --env-file=docker/.env -f docker/docker-compose.run.rr.dev.yml -p phpughh up -d --remove-orphans

up-rr-perf: configure-rr-live pre-up configure-app-perf
	MODE=perf docker compose --env-file=docker/.env -f docker/docker-compose.run.rr.yml -p phpughh up -d --remove-orphans

init-db:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh -c "mysql < roadrunner/res/init.sql"
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh -c "wget https://github.com/openzipkin/zipkin/raw/master/zipkin-storage/mysql-v1/src/main/resources/mysql.sql -O roadrunner/res/zipkin.sql"
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh -c "mysql < roadrunner/res/init_zipkin.sql"
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh -c "mysql zipkin < roadrunner/res/zipkin.sql"
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh -c "mysql < roadrunner/res/init_zipkin_part2.sql"

init-mongo:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec mongo sh -c "mongosh -u root -p phpughh < /graylog.js"

mysql:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli mysql

cli:
	docker compose --env-file=docker/.env -f docker/docker-compose.run.yml -p phpughh exec dev-cli sh

rr:
	docker exec php rr reset

configure-app-dev:
	rm -f res/config.yaml
	ln -s config.dev.yaml res/config.yaml

configure-app-perf:
	rm -f res/config.yaml
	ln -s config.live.yaml res/config.yaml

configure-rr-dev:
	rm -f .rr.yaml
	ln -s .rr.dev.yaml .rr.yaml

configure-rr-live:
	rm -f .rr.yaml
	ln -s .rr.live.yaml .rr.yaml