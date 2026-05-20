#!/bin/sh
set -e

if [ -f /var/www/html/entrypoint.d/01-laravel.sh ]; then
  /bin/sh /var/www/html/entrypoint.d/01-laravel.sh
fi

exec "$@"
