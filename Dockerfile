
# ==============================================================================
# Stage 1: Build Composer Dependencies
# ==============================================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
COPY . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-scripts \
    --optimize-autoloader

# ==============================================================================
# Stage 2: Build Frontend Assets (Vite + Tailwind CSS)
# ==============================================================================
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build


# ==============================================================================
# Stage 3: Production Runtime (Nginx + PHP-FPM)
# ==============================================================================
FROM php:8.4-fpm-alpine

ENV PORT=10000

# Laravel Production
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_URL=https://pos-kh.onrender.com

# Session Configuration
ENV SESSION_DRIVER=file
ENV SESSION_LIFETIME=120
ENV SESSION_ENCRYPT=false
ENV SESSION_SECURE_COOKIE=true
ENV SESSION_HTTP_ONLY=true
ENV SESSION_SAME_SITE=lax

# Cache and Queue
ENV CACHE_STORE=file
ENV QUEUE_CONNECTION=sync

# ==============================================================================
# Install Nginx and PHP Extensions
# ==============================================================================
RUN apk add --no-cache \
        nginx \
        icu-dev \
        libzip-dev \
        postgresql-dev \
        mariadb-connector-c-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pdo_mysql \
        zip \
        intl \
        bcmath \
        opcache \
        gd

WORKDIR /app


# ==============================================================================
# Copy Laravel Application and Dependencies
# ==============================================================================
COPY --from=vendor /app/vendor /app/vendor

COPY . /app

# Copy Production Vite Assets
COPY --from=assets /app/public/build /app/public/build

# Remove Vite Development Hot File
RUN rm -f /app/public/hot


# ==============================================================================
# Laravel Storage and Permissions
# ==============================================================================
RUN rm -rf /app/public/storage \
    && php artisan storage:link \
    && mkdir -p \
        /app/storage/framework/cache \
        /app/storage/framework/sessions \
        /app/storage/framework/views \
        /app/storage/logs \
        /app/bootstrap/cache \
    && chown -R www-data:www-data \
        /app/storage \
        /app/bootstrap/cache \
        /app/public \
    && chmod -R 775 \
        /app/storage \
        /app/bootstrap/cache \
        /app/public


# ==============================================================================
# Nginx Configuration
# ==============================================================================
RUN cat > /etc/nginx/http.d/default.conf <<'EOF'
server {
    listen 10000;
    server_name _;

    root /app/public;
    index index.php index.html;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri =404;

        include fastcgi_params;

        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;

        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $document_root;

        # Forward Render Proxy Headers
        fastcgi_param HTTP_X_FORWARDED_FOR $http_x_forwarded_for;
        fastcgi_param HTTP_X_FORWARDED_HOST $http_x_forwarded_host;
        fastcgi_param HTTP_X_FORWARDED_PORT $http_x_forwarded_port;
        fastcgi_param HTTP_X_FORWARDED_PROTO $http_x_forwarded_proto;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF


# ==============================================================================
# Laravel Startup Script
# ==============================================================================
RUN cat > /app/docker-entrypoint.sh <<'EOF'
#!/bin/sh

set -e

cd /app

echo "Starting Laravel Production..."

# Remove Vite Development File
rm -f /app/public/hot

# Create Laravel Runtime Directories
mkdir -p \
    /app/storage/framework/cache \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    /app/bootstrap/cache

# Ensure Storage Permissions
chown -R www-data:www-data \
    /app/storage \
    /app/bootstrap/cache

chmod -R 775 \
    /app/storage \
    /app/bootstrap/cache

# Clear Old Laravel Caches
php artisan optimize:clear
php artisan package:discover --ansi

# Cache Configuration at Runtime
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel configuration completed."

# Start PHP-FPM
php-fpm -D

# Start Nginx
exec nginx -g "daemon off;"
EOF

RUN chmod +x /app/docker-entrypoint.sh

EXPOSE 10000

CMD ["/app/docker-entrypoint.sh"]
