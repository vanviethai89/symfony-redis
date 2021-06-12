FROM php:7.2-fpm

# Install PHP and composer dependencies
RUN apt-get update && apt-get install -qq git curl libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev zip unzip

# Clear out the local repository of retrieved package files
RUN apt-get clean

RUN docker-php-ext-install pdo_mysql zip
RUN pecl install xdebug mcrypt-1.0.2

RUN docker-php-ext-enable xdebug mcrypt

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

