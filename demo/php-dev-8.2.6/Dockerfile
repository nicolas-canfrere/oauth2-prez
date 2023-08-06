FROM php:8.2.6-fpm

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update
RUN cp /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo "Europe/Paris" > /etc/timezone
ARG IMG_BASE_DEPS="git gnupg apt-utils apt-transport-https build-essential openssh-client rsync sqlite3 zip unzip vim"
RUN apt-get install --no-install-recommends -y $IMG_BASE_DEPS $PHPIZE_DEPS

ARG IMG_PHP_EXTENSION_DEPS="libzip-dev libgmp-dev libjpeg-dev libpng-dev libwebp-dev libxpm-dev libfreetype6-dev libc-client-dev libkrb5-dev libmagickwand-dev librabbitmq-dev libmpdec-dev"
RUN apt-get install --no-install-recommends -y $IMG_PHP_EXTENSION_DEPS
RUN ln -s /usr/lib/x86_64-linux-gnu/libsybdb.a /usr/lib/libsybdb.a
RUN ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/libsybdb.so
RUN docker-php-ext-configure zip
RUN docker-php-ext-configure gmp
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-xpm --with-webp
RUN docker-php-ext-configure imap --with-imap --with-kerberos --with-imap-ssl
ARG IMG_PHP_MODULE_DEPS="libbz2-dev libenchant-2-dev libicu-dev libldap2-dev freetds-dev firebird-dev libpq-dev libpspell-dev libsqlite3-dev libtidy-dev libxslt-dev"
RUN apt-get install --no-install-recommends -y $IMG_PHP_MODULE_DEPS
ARG IMG_PHP_MODULES="bcmath bz2 calendar dba enchant exif gd gettext gmp imap intl ldap mysqli opcache pcntl pdo_dblib pdo_firebird pdo_mysql pdo_pgsql pgsql pspell shmop soap sockets sysvmsg sysvsem sysvshm tidy xsl zip"
RUN docker-php-ext-install $IMG_PHP_MODULES
COPY configs/xxxx-custom.ini $PHP_INI_DIR/conf.d/xxxx-custom.ini
COPY configs/yyyy-xdebug.ini $PHP_INI_DIR/conf.d/yyyy-xdebug.ini
RUN sed -i -e "s/<policy domain=\"coder\" rights=\"none\" pattern=\"PDF\" \/>/<policy domain=\"coder\" rights=\"read|write\" pattern=\"PDF\" \/>/g" /etc/ImageMagick-6/policy.xml
RUN docker-php-ext-enable sodium
RUN pecl install imagick-3.7.0 && docker-php-ext-enable imagick
RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install redis-5.3.7 && docker-php-ext-enable redis
RUN pecl install amqp-1.11.0 && docker-php-ext-enable amqp
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl install decimal && docker-php-ext-enable decimal
RUN pecl install xdebug-3.2.0 && docker-php-ext-enable xdebug
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false
RUN apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
