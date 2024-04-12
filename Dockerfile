FROM serversideup/php:beta-8.3-fpm-nginx

WORKDIR /var/www/html

ARG PUID
ARG PGID
RUN docker-php-serversideup-set-id www-data ${PUID} ${PGID}

COPY --chown=www-data:www-data . .

# Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_21.x | bash -
RUN apt-get install -y nodejs

# Composer
RUN composer install

# NPM
RUN npm i
RUN npm run build
RUN rm -rf public/npm && ln -s ../node_modules public/npm

LABEL "org.opencontainers.image.version"="0.0.3"
