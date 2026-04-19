#!/bin/sh
set -e

cd "$(dirname "$0")"

# Ensure environment file exists
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Load APP_KEY from .env if present
if [ -z "$APP_KEY" ] && grep -q '^APP_KEY=' .env; then
  export $(grep '^APP_KEY=' .env | head -n 1)
fi

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
  if command -v php >/dev/null 2>&1; then
    php artisan key:generate --force >/dev/null 2>&1 || true
  fi
fi

if [ -z "$APP_KEY" ]; then
  export APP_KEY="base64:$(openssl rand -base64 32)"
  echo "APP_KEY=$APP_KEY" >> .env
fi

# Ensure permissions and directories exist
mkdir -p database
chmod -R 755 storage database 2>/dev/null || true

# Install dependencies if vendor is missing
if [ ! -f vendor/autoload.php ] && command -v composer >/dev/null 2>&1; then
  composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist >/dev/null 2>&1 || true
fi

# Ensure SQLite database file exists
touch database/database.sqlite

# Run migrations in background without blocking server start
if command -v php >/dev/null 2>&1; then
  (sleep 2 && php artisan migrate --force >/dev/null 2>&1) &
fi

# Start Laravel built-in server on Railway port
PORT=${PORT:-8080}
echo "Starting PHP built-in server on 0.0.0.0:$PORT"
exec php -S 0.0.0.0:"$PORT" -t public public/index.php
