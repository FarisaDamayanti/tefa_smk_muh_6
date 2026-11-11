#!/bin/bash

if [ ! -f /var/www/.env ]; then
    touch /var/www/.env
fi

# Wait for database
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    echo "Waiting for database connection..."
    sleep 5
done

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating APP_KEY..."
    APP_KEY=$(php /var/www/artisan key:generate --show)
    export APP_KEY=$APP_KEY
fi

if grep -q '^APP_KEY=' /var/www/.env; then
    sed -i "s/^APP_KEY=.*/APP_KEY=$APP_KEY/" /var/www/.env
else
    echo "APP_KEY=$APP_KEY" >> /var/www/.env
fi

exec "$@"
