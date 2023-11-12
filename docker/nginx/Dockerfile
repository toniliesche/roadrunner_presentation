ARG nginx_version
FROM nginx:${nginx_version}-alpine
LABEL org.opencontainers.image.authors="docker@toniliesche.de"

COPY files/etc/nginx/conf.d/vhost.conf /etc/nginx/conf.d/default.conf
