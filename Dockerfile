FROM php:8.2-fpm-bullseye

ARG WWWUSER=1000
ARG WWWGROUP=1000

ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/tmp/composer
ENV COMPOSER_CACHE_DIR=/tmp/composer-cache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libexif-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
      pdo \
      pdo_mysql \
      mbstring \
      exif \
      pcntl \
      bcmath \
      gd \
      zip \
      intl \
      opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN if getent group "${WWWGROUP}" >/dev/null; then \
            EXISTING_GROUP="$(getent group "${WWWGROUP}" | cut -d: -f1)"; \
            usermod -u "${WWWUSER}" -g "${EXISTING_GROUP}" www-data; \
        else \
            groupmod -g "${WWWGROUP}" www-data \
            && usermod -u "${WWWUSER}" -g www-data www-data; \
        fi

WORKDIR /var/www/html

RUN mkdir -p /tmp/composer-cache \
    && chown -R www-data:www-data /tmp/composer-cache

CMD ["php-fpm"]
