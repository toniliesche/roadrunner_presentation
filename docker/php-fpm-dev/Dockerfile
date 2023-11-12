ARG php_version=8.2
FROM phpughh/roadrunner/php:${php_version}-fpm

RUN mkdir -p /usr/src/packages && \
  cd /usr/src/packages && \
  pecl bundle xdebug && \
  docker-php-ext-install \
  /usr/src/packages/xdebug && \
  rm -Rf /usr/src/packages/*

COPY files/entry-point.d/10-xdebug.sh /entry-point.d/10-xdebug.sh
COPY files/res/phpug-xdebug.ini.tpl /res/phpug-xdebug.ini.tpl
