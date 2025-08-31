#!/usr/bin/env bash
set -e

echo "Running post-start tasks..."

# Ensure correct working dir
cd /var/www/html

# (Optional) don't re-run composer here if you already ran it during build
# composer install --no-dev --working-dir=/var/www/html --no-interaction --no-progress

# Wait for DB? (optional small loop — but consider Render release command instead)
# for i in {1..10}; do
#   php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306), getenv('DB_USERNAME'), getenv('DB_PASSWORD')) && exit(0);" && break || sleep 2
# done

echo "Caching config & routes (only if env is present)..."
# Only run these if APP_KEY exists to avoid failing startup
if [ -n "${APP_KEY:-}" ]; then
  php artisan config:cache || true
  php artisan route:cache || true
else
  echo "APP_KEY missing — skipping config cache"
fi

# Do NOT run migrations here in the entrypoint unless you guarantee DB readiness.
# Prefer Render's Release Command for migrations (see below).
