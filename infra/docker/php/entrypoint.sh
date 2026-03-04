#!/bin/sh
set -e

# ── Ensure writable directories exist and belong to www-data ──────────────────
mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# ── Install Composer deps if vendor is missing (first boot or volume reset) ───
if [ ! -f vendor/autoload.php ]; then
    echo "[menusnap] vendor/ not found – running composer install..."
    composer install \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts
    php artisan package:discover --ansi 2>/dev/null || true
fi

# ── Start PHP-FPM (master=root, workers=www-data per default fpm pool config) ─
exec php-fpm
