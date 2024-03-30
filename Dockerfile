FROM serversideup/php:8.2-fpm-nginx-v2.2.1
WORKDIR /var/www/html
COPY . .

# Install Node.js (if necessary)
RUN curl -sL https://deb.nodesource.com/setup_21.x | bash -
RUN apt-get install -y nodejs

# Install pgsql
RUN apt-get update && apt-get install -y php8.2-pgsql

# Composer install
RUN rm -rf vendor && composer install

# Npm install
RUN rm -rf node_modules && npm i

# Link node_modules directory in public directory
RUN cd public && rm -rf npm && ln -s ../node_modules npm

LABEL "org.opencontainers.image.version"="0.0.2"
