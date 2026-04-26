#!/usr/bin/env sh
set -e

cd /var/www/html

mkdir -p storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

if [ ! -f vendor/autoload.php ] && [ "${SKIP_COMPOSER_INSTALL:-0}" != "1" ]; then
  echo "[entrypoint] vendor not found, running composer install..."
  su -s /bin/sh www-data -c "composer install --no-interaction --prefer-dist"
fi

exec "$@"
