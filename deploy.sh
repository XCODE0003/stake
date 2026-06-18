#!/usr/bin/env bash
# Server-side deploy for the Stake Affiliate API (Docker / FrankenPHP).
# Usage (on the server, as root):  bash deploy.sh
set -euo pipefail

REPO="https://github.com/XCODE0003/stake.git"
DIR="/opt/stake"

echo "==> Ensuring Docker is installed"
if ! command -v docker >/dev/null 2>&1; then
    curl -fsSL https://get.docker.com | sh
fi

echo "==> Fetching code into ${DIR}"
if [ -d "${DIR}/.git" ]; then
    git -C "${DIR}" pull --ff-only
else
    git clone "${REPO}" "${DIR}"
fi
cd "${DIR}"

echo "==> Preparing .env"
if [ ! -f .env ]; then
    cp .env.production.example .env
fi
if ! grep -q '^APP_KEY=base64:' .env; then
    KEY="base64:$(openssl rand -base64 32)"
    sed -i "s|^APP_KEY=.*|APP_KEY=${KEY}|" .env
    echo "    generated APP_KEY"
fi
echo "    !! Set MAIL_PASSWORD (Zoho app password) in ${DIR}/.env before sending email."

echo "==> Building and starting containers"
docker compose up -d --build

echo "==> Seeding initial data (idempotent)"
docker compose exec -T app php artisan db:seed --force || true

docker compose ps
echo "==> Done. API: http://<server-ip>  (set SERVER_NAME=api.stakeaffilates12.com + DNS for HTTPS)"
