ARG php_version
ARG roadrunner_version
FROM ghcr.io/roadrunner-server/roadrunner:${roadrunner_version} AS roadrunner

FROM phpughh/roadrunner/php:${php_version}-fpm

COPY files/run.sh /run.sh

COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr

CMD ["/run.sh"]
