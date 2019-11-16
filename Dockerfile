# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target
# https://docs.docker.com/compose/environment-variables/

# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact

FROM php:7.3-fpm as php

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    libwebp-dev \
    libjpeg-dev \
    libbz2-dev \
    libcurl4-openssl-dev \
    libpng-dev \
    libmcrypt-dev \
    libpq-dev \
    libsqlite3-dev \
    libedit-dev \
    libzmq3-dev \
    procps \
    libfcgi-bin \
    libzip-dev \
    libicu-dev \
    rsync && apt-get clean

RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    bz2 \
    calendar \
    curl \
    gd \
    json \
    mbstring \
    opcache \
    readline \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    zip \
    pcntl \
    mysqli \
    intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer --ansi --version --no-interaction

# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

#HEALTHCHECK
ADD ./docker/php/php-fpm.d/zzz-01-healthcheck.conf /usr/local/etc/php-fpm.d/zzz-01-healthcheck.conf
ADD ./docker/php/php-fpm-healthcheck.sh /usr/local/bin/php-fpm-healthcheck
RUN chmod 755 /usr/local/bin/php-fpm-healthcheck

HEALTHCHECK --interval=30s --timeout=5s --retries=5 CMD php-fpm-healthcheck


RUN rm -rf /tmp/*

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM php as php_final

FROM php_final as php_final_for_dev_team

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN echo "xdebug.remote_enable = 1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back = 1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


FROM nginx:1.17 AS nginx

RUN apt-get update && apt-get install -y \
    curl

HEALTHCHECK --interval=30s --timeout=5s --retries=5 CMD curl --fail http://localhost || exit 1


FROM nginx as nginx_final
