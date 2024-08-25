# Dockerfile
FROM php:8-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1
# Installer les extensions PHP et composer
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    zip \
    curl \
    sudo \
    unzip \
    && docker-php-ext-install \
    pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN apt-get -y update \
&& apt-get install -y libicu-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl

RUN pecl install  redis \
&& rm -rf /tmp/pear \
&& docker-php-ext-enable redis


# Copier le code de l'application
WORKDIR /var/www/html
COPY . .

# Installer les dépendances
RUN composer install