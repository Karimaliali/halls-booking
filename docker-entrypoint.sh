#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

if ! grep -q '^APP_KEY=' .env || [ "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" = "" ]; then
  php artisan key:generate --force
fi

if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -d node_modules ]; then
  npm install
fi

if [ ! -f public/build/manifest.json ]; then
  npm run build
fi

RETRY_COUNT=0
until mysqladmin ping -h db -P 3306 --silent; do
  echo "Waiting for MySQL..."
  sleep 2
  if [ "$RETRY_COUNT" -ge 30 ]; then
    echo "MySQL did not become available in time"
    exit 1
  fi
  RETRY_COUNT=$((RETRY_COUNT+1))
done

php artisan migrate --force

exec "$@"
