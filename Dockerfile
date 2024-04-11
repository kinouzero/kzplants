FROM serversideup/php:beta-8.3-fpm-nginx

WORKDIR /var/www/html

COPY . .

# Aditionnal scripts
COPY --chmod=755 ./entrypoint.d/ /etc/entrypoint.d/

# Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_21.x | bash -
RUN apt-get install -y nodejs

# Composer
RUN composer install

# NPM
RUN npm i
RUN npm run build
RUN rm -rf public/npm && ln -s ../node_modules public/npm

LABEL "org.opencontainers.image.version"="0.0.0"
