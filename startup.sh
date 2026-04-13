#!/bin/sh
set -e

cd "$(dirname "$0")"

# Ensure environment file exists
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate application key if missing, then ensure it's in the actual env
if ! grep -q '^APP_KEY=' .env || [ "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" = "" ]; then
  php artisan key:generate --force
fi

# Make sure APP_KEY is exported for the current process
export $(grep '^APP_KEY=' .env | xargs)

# Ensure database file exists for SQLite if SQLite is configured
DB_CONNECTION=$(grep '^DB_CONNECTION=' .env | cut -d '=' -f2 | tr -d '\r')
if [ "$DB_CONNECTION" = "sqlite" ]; then
  DB_PATH=$(php -r 'echo trim(getenv("DB_DATABASE"));' 2>/dev/null)
  if [ -z "$DB_PATH" ]; then
    DB_PATH="database/database.sqlite"
  fi
  if [ ! -f "$DB_PATH" ]; then
    mkdir -p "$(dirname "$DB_PATH")"
    touch "$DB_PATH"
  fi
fi

# Run database migrations if possible
php artisan migrate --force 2>/dev/null || php artisan migrate:refresh --force 2>/dev/null || true

# Start Laravel server on the specified port
PORT=${PORT:-8000}
echo "Starting PHP built-in server on 0.0.0.0:$PORT"
exec php -S 0.0.0.0:"$PORT" -t public public/index.php
