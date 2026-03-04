# ──────────────────────────────────────────────────────────────────────────────
# MenuSnap – PHP-FPM runtime image
# Build context: repo root
# ──────────────────────────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine

# ── System libraries ──────────────────────────────────────────────────────────
RUN apk add --no-cache \
        bash \
        su-exec \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        freetype-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        openssl-dev \
        unzip \
        git \
    && docker-php-ext-configure gd \
            --with-freetype \
            --with-jpeg \
            --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
            pdo \
            pdo_mysql \
            mbstring \
            intl \
            zip \
            gd \
            bcmath \
            opcache \
            pcntl \
    && rm -rf /var/cache/apk/*

# ── Composer binary ───────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── PHP runtime config ────────────────────────────────────────────────────────
COPY infra/docker/php/php.ini /usr/local/etc/php/conf.d/99-menusnap.ini

# ── Entrypoint ────────────────────────────────────────────────────────────────
COPY infra/docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# Run as root so entrypoint can chown volumes; php-fpm workers use www-data
EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
