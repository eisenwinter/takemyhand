FROM php:7.2-fpm-alpine3.7

#install
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug \
  && pecl install redis-4.0.1 \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-enable xdebug redis \
  && apk del pcre-dev ${PHPIZE_DEPS}

WORKDIR "/application"
