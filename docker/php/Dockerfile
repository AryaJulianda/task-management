FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    git \
    curl \
    nodejs \
    npm \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev \
    postgresql-dev \
    ffmpeg \
    bash \
    build-base \
    zlib-dev \
    autoconf \
    php-pear \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && rm -rf /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --optimize-autoloader

RUN mkdir -p /var/www/storage/logs \
    /var/www/storage/framework/cache \
    /var/www/storage/app/temp \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/bootstrap/cache

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
