ARG php_version=8.2
FROM php:${php_version}-fpm-alpine
LABEL org.opencontainers.image.authors="docker@toniliesche.de"

RUN apk add --no-cache \
gettext-dev \
  icu-dev \
  gettext-dev \
  ldb-dev \
  libffi-dev \
  libmemcached-dev \
  libzip-dev \
  linux-headers \
  yaml-dev && \
  mkdir -p /usr/src/packages && \
  cd /usr/src/packages && \
  pecl bundle apcu && \
  pecl bundle igbinary && \
  pecl bundle memcached && \
  pecl bundle msgpack && \
  pecl bundle mongodb && \
  pecl bundle redis && \
  pecl bundle yaml && \
  docker-php-ext-install \
  bcmath \
  exif \
  ffi \
  gettext \
  intl \
  mysqli \
  opcache \
  pdo_mysql \
  pcntl \
  pdo_mysql \
  sockets \
  zip \
  /usr/src/packages/apcu \
  /usr/src/packages/igbinary \
  /usr/src/packages/memcached \
  /usr/src/packages/mongodb \
  /usr/src/packages/msgpack \
  /usr/src/packages/redis \
  /usr/src/packages/yaml && \
  rm -Rf /usr/src/packages/* && \
  mkdir -p /entry-point.d

COPY files/entry-point.sh /entry-point.sh

ENTRYPOINT [ "/entry-point.sh" ]
CMD [ "/usr/local/sbin/php-fpm", "-F" ]
