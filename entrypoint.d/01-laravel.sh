#!/bin/sh
set -e

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
fi

if [ "${RUN_DB_RESET:-false}" = "true" ]; then
  php artisan migrate:fresh --force
fi

if [ "${RUN_PACKAGE_DISCOVER:-true}" = "true" ]; then
  php artisan package:discover --ansi
fi

if [ "${APP_ENV:-production}" != "local" ] && [ "${APP_DEBUG:-false}" != "true" ]; then
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi
