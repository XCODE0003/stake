# Deployment

The backend (Laravel API + Filament admin) is containerised with **FrankenPHP**
and runs on the server. The **frontend SPA** is built separately and hosted as
static files (see `frontend/`).

## Backend (API + admin)

Served by a single container:

- `Dockerfile` — FrankenPHP (PHP 8.3) image; installs PHP/Node, builds assets,
  installs Composer deps.
- `docker-compose.yml` — runs the app on ports 80/443 with a persisted
  `app-storage` volume (holds the SQLite DB at `storage/app/database.sqlite`)
  and Caddy cert volumes.
- `docker/Caddyfile` — site address from `SERVER_NAME` (defaults to `:80`).
- `docker/start.sh` — migrates, publishes Filament assets, caches config/views,
  then runs FrankenPHP.

### One-shot deploy

```bash
ssh root@144.31.148.161
curl -fsSL https://raw.githubusercontent.com/XCODE0003/stake/main/deploy.sh | bash
# or: git clone https://github.com/XCODE0003/stake.git /opt/stake && cd /opt/stake && bash deploy.sh
```

`deploy.sh` installs Docker, clones the repo, creates `.env` from
`.env.production.example`, generates `APP_KEY`, builds and starts the container,
and seeds the admin user.

### After deploy

1. **Email:** edit `/opt/stake/.env` and set `MAIL_PASSWORD` to a Zoho
   app‑password for `no-reply@stakeaffilates12.com`, then
   `docker compose restart`.
2. **HTTPS:** point a DNS **A record** `api.stakeaffilates12.com → 144.31.148.161`.
   With `SERVER_NAME=api.stakeaffilates12.com` in `.env`, FrankenPHP/Caddy
   obtains a Let's Encrypt certificate automatically. Until DNS resolves, the
   API is reachable over HTTP on the server IP.
3. **Seeded accounts** (password `password` — change them):
   `admin@stake.test` (manager / Filament `/admin`).

## Frontend (SPA)

```bash
cd frontend
npm ci
npm run build          # uses .env.production -> VITE_API_URL=https://api.stakeaffilates12.com/api
# deploy the dist/ folder to any static host (configure SPA fallback to index.html)
```

A prebuilt archive is provided as `stake-frontend-dist.zip`.
