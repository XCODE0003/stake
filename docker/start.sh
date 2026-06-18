#!/bin/sh
set -e

cd /app

# SQLite lives in the persisted storage volume.
mkdir -p storage/app storage/framework/cache storage/framework/sessions storage/framework/views
[ -f storage/app/database.sqlite ] || touch storage/app/database.sqlite

# Apply database migrations (idempotent).
php artisan migrate --force

# Publish Filament assets and cache config/views for production.
php artisan filament:assets >/dev/null 2>&1 || true
php artisan config:cache >/dev/null 2>&1 || true
php artisan view:cache >/dev/null 2>&1 || true
# Note: route:cache is skipped — a closure route (passkey well-known) is not cacheable.

exec frankenphp run --config /etc/caddy/Caddyfile
