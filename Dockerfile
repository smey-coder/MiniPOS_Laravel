
# ==========================================
# Stage 1: Build Frontend Assets
# ==========================================
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build


# ==========================================
# Stage 2: Install Laravel Dependencies
# ==========================================
FROM php:8.3-cli-bookworm AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        curl \
        dom \
        exif \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo_mysql \
        simplexml \
        xml \
        xmlreader \
        xmlwriter \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

COPY . .

RUN composer dump-autoload \
    --no-dev \
    --optimize \
    --no-interaction \
    --no-scripts \
    --classmap-authoritative


# ==========================================
# Stage 3: Laravel Production Runtime
# ==========================================
FROM php:8.3-apache-bookworm AS runtime

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        curl \
        dom \
        exif \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo_mysql \
        simplexml \
        xml \
        xmlreader \
        xmlwriter \
        zip \
    && a2enmod rewrite headers \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# Copy Laravel source code
COPY . .

# Copy Composer dependencies
COPY --from=vendor /app/vendor ./vendor

# Copy compiled Vite assets
COPY --from=assets /app/public/build ./public/build


# Configure Apache for Laravel public directory
RUN sed -ri \
    -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf \
    && printf '%s\n' \
        '<Directory /var/www/html/public>' \
        '    AllowOverride All' \
        '    Options FollowSymLinks' \
        '    Require all granted' \
        '</Directory>' \
        >> /etc/apache2/apache2.conf \
    && mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache


# Production Environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

EXPOSE 80

# Start Laravel and Apache
CMD php artisan package:discover --ansi \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && exec apache2-foreground
