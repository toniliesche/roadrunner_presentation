ARG php_version
ARG roadrunner_version
FROM ghcr.io/roadrunner-server/roadrunner:${roadrunner_version} AS roadrunner

FROM phpughh/roadrunner/php:${php_version}-fpm

COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr

WORKDIR /var/www/webapp
USER root
CMD [ "rr", "serve" ]
#CMD ["su", "-c", "rr --pid serve", "-s", "/bin/sh", "www-data"]
