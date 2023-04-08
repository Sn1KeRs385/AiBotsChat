FROM php:8.2.4-fpm-alpine

WORKDIR /var/www

RUN apk update && apk add freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libpng-dev \
    imagemagick-dev \
    libmcrypt-dev \
    oniguruma-dev \
    libmemcached-dev \
    icu-dev \
    libpq-dev \
    imap-dev \
    krb5-dev \
    postgresql \
    curl \
    git \
    tzdata \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install exif
#RUN docker-php-ext-configure opcache --enable-opcache \
#    && docker-php-ext-install opcache
#RUN docker-php-ext-install zip

RUN pecl install memcached \
    && docker-php-ext-enable memcached

RUN pecl install redis \
    && docker-php-ext-enable redis

#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug

RUN pecl install -n mcrypt \
    && docker-php-ext-enable mcrypt

RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

RUN curl -L -o /tmp/composer-setup.php "https://getcomposer.org/installer" \
    && php /tmp/composer-setup.php --install-dir=/bin --filename=composer \
    && rm /tmp/composer-setup.php

RUN apk update && apk add supervisor libxml2-dev gmp-dev libzip-dev

RUN docker-php-ext-install gmp soap pcntl zip bcmath sockets

RUN apk add libpng-dev jpeg-dev libjpeg-turbo-dev freetype-dev
RUN docker-php-ext-install gd && \
  docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp  && \
  docker-php-ext-install gd

RUN apk add jpegoptim optipng pngquant gifsicle libwebp libwebp-tools

RUN echo "UTC" > /etc/timezone

COPY supervisord.conf /etc/supervisord.conf
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]

WORKDIR /etc/supervisor/conf.d/
