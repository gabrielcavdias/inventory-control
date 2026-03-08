FROM php:8.5-fpm

ARG user=app
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    sqlite3 \
    libsqlite3-dev

RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring \
    bcmath \
    pcntl \
    gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN useradd -u $uid -ms /bin/bash $user

WORKDIR /var/www

USER $user