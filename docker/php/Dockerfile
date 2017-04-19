FROM php:7.0-fpm-alpine

RUN apk add --no-cache git

# MariaDB client :
RUN apk add --no-cache mariadb-client postgresql-dev

# PDO MySQL & PGSQL :
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Composer:
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
