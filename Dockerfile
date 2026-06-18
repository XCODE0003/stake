# syntax=docker/dockerfile:1
# Stake Affiliate API — Laravel + Filament served by FrankenPHP (auto HTTPS).
FROM dunglas/frankenphp:1-php8.3

# PHP extensions required by Laravel / Filament / Sanctum.
RUN install-php-extensions \
    pcntl \
    pdo_sqlite \
    intl \
    zip \
    bcmath \
    gd \
    exif \
    opcache

# Node (to build the bundled Inertia/Filament Vite assets) + tooling.
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip ca-certificates curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

# Install PHP deps (runs package:discover), generate a build-time key so the
# wayfinder Vite plugin can boot artisan, build assets, then drop the toolchain.
RUN cp .env.example .env \
    && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && php artisan key:generate --force \
    && npm ci \
    && npm run build \
    && rm -rf node_modules .env

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

COPY docker/Caddyfile /etc/caddy/Caddyfile
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80 443

ENTRYPOINT ["start.sh"]
