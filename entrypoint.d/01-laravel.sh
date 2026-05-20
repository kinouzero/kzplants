#!/bin/sh
set -e

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
fi

if [ "${RUN_STORAGE_LINK:-true}" = "true" ]; then
  php artisan storage:link --force
fi

if [ -z "${AWS_ACCESS_KEY_ID:-}" ] || [ -z "${AWS_SECRET_ACCESS_KEY:-}" ] || [ -z "${AWS_BUCKET:-}" ]; then
  if [ -n "${FILES_LOCAL_ROOT:-}" ]; then
    mkdir -p "${FILES_LOCAL_ROOT}"
  fi
fi

if [ "${APP_ENV:-production}" != "local" ] && [ "${APP_DEBUG:-false}" != "true" ]; then
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi
