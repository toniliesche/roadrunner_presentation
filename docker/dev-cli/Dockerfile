ARG php_version
FROM phpughh/roadrunner/php:${php_version}-fpm

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
  HASH=`curl -sS https://composer.github.io/installer.sig` && \
  php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \ && \
  php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
  rm -f /tmp/composer-setup.php

RUN apk add --no-cache bash && \
  curl -sS https://get.symfony.com/cli/installer | bash && \
  mv /root/.symfony5/bin/symfony /usr/local/bin

RUN apk add --no-cache nodejs npm

ARG codeception_version
ARG codeception_asserts_version
RUN composer global config minimum-stability dev && \
 composer global config prefer-stable true && \
 composer global require --prefer-stable codeception/codeception:${codeception_version} codeception/module-asserts:${codeception_asserts_version}

