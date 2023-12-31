#!/bin/sh

if [ ! -z "${XDEBUG_HOST}" ]; then
  cp /res/phpug-xdebug.ini.tpl /usr/local/etc/php/conf.d/phpug-xdebug.ini

  if [ "${XDEBUG_CONNECT_ALWAYS}" == "yes" ]; then
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/phpug-xdebug.ini
  fi

  sed -i -e "s/__PLACEHOLDER__XDEBUGPORT/${XDEBUG_PORT:-9000}/g" /usr/local/etc/php/conf.d/phpug-xdebug.ini
  sed -i -e "s/__PLACEHOLDER__XDEBUGHOST/${XDEBUG_HOST}/g" /usr/local/etc/php/conf.d/phpug-xdebug.ini
fi
