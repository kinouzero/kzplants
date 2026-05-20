# syntax=docker/dockerfile:1

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
  --no-dev \
  --prefer-dist \
  --no-interaction \
  --no-progress \
  --optimize-autoloader \
  --no-scripts

FROM node:20 AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY resources/ resources/
COPY public/ public/
COPY vite.config.js ./
RUN npm run build

FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=assets /app/public/build /var/www/html/public/build

RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j"$(nproc)" pdo_mysql zip gd \
  && rm -rf /var/cache/apk/*

COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

# Entry point
COPY --chmod=755 ./entrypoint.d/ /var/www/html/entrypoint.d/
COPY --chmod=755 ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV RUN_MIGRATIONS=true

LABEL "org.opencontainers.image.version"="0.0.8"

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
  CMD wget -qO- http://127.0.0.1:8000/health || exit 1
