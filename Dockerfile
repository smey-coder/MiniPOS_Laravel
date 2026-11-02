# ==============================================================================
# Laravel Startup Script
# ==============================================================================
RUN cat > /app/docker-entrypoint.sh <<'EOF'
#!/bin/sh

set -e

cd /app

echo "Starting Laravel Production..."

if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is required in the Render environment."
    exit 1
fi

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

# Run database migrations first so session/cache tables exist
php artisan migrate --force

# Clear and rebuild caches properly for production
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

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