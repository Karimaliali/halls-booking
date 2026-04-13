#!/bin/sh

cd "$(dirname "$0")"

# Ensure environment file exists
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate or use existing APP_KEY
if [ -z "$APP_KEY" ]; then
  # Try to get from .env file
  if ! grep -q '^APP_KEY=' .env || [ "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" = "" ]; then
    # Generate new key
    php artisan key:generate --force 2>/dev/null || true
  else
    # Export from .env
    export $(grep '^APP_KEY=' .env | xargs)
  fi
fi

# Ensure APP_KEY is set in environment (generate if missing)
if [ -z "$APP_KEY" ]; then
  export APP_KEY="base64:$(openssl rand -base64 32)"
  echo "APP_KEY=$APP_KEY" >> .env
fi

# Ensure database directory exists
mkdir -p database
chmod -R 755 storage database 2>/dev/null || true

# Run migrations in background (don't block server start)
(sleep 2 && php artisan migrate --force 2>/dev/null) &

# Start Laravel server on the specified port
PORT=${PORT:-8000}
echo "Starting PHP built-in server on 0.0.0.0:$PORT"
exec php -S 0.0.0.0:"$PORT" -t public public/index.php
