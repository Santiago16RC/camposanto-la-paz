FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
        postgresql-dev \
        libpng-dev \
        libzip-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        icu-dev \
        oniguruma-dev \
        sqlite-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        pdo_sqlite \
        gd \
        zip \
        bcmath \
        intl \
        mbstring \
        opcache \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY . .

RUN composer install --optimize-autoloader --no-interaction --no-progress \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
