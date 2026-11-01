
# ==============================================================================
# Stage 1: Build Composer Dependencies
# ==============================================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

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

# Build CSS and JavaScript for Production
RUN npm run build


# ==============================================================================
# Stage 3: Production Runtime (Nginx + PHP-FPM)
# ==============================================================================
FROM php:8.4-fpm-alpine

ENV PORT=10000
ENV SESSION_DRIVER=file
ENV CACHE_STORE=file
ENV QUEUE_CONNECTION=sync

# Install Nginx and PHP extensions
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
# Copy Application and Dependencies
# ==============================================================================
COPY --from=vendor /app/vendor /app/vendor

COPY . /app

# Copy production Vite assets
COPY --from=assets /app/public/build /app/public/build

# Remove Vite development hot file
RUN rm -f /app/public/hot


# ==============================================================================
# Laravel Storage and Permissions
# ==============================================================================
RUN rm -rf /app/public/storage \
    && php artisan storage:link \
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

        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;

        include fastcgi_params;

        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $document_root;

        # Forward Render HTTPS information to Laravel
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
# Startup Script
# ==============================================================================
RUN cat > /app/docker-entrypoint.sh <<'EOF'
#!/bin/sh

set -e

cd /app

echo "Starting Laravel Production..."

# Remove Vite development file
rm -f /app/public/hot

# Clear old Laravel caches
php artisan optimize:clear

# Generate production caches using runtime environment variables
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set runtime permissions
chown -R www-data:www-data \
    /app/storage \
    /app/bootstrap/cache

# Start PHP-FPM
php-fpm -D

# Start Nginx in foreground
exec nginx -g "daemon off;"
EOF

RUN chmod +x /app/docker-entrypoint.sh

EXPOSE 10000

CMD ["/app/docker-entrypoint.sh"]
