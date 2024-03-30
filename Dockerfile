FROM serversideup/php:8.2-fpm-nginx
WORKDIR /var/www/html
COPY . .
RUN npm install
RUN composer install
RUN cd public && ln -s ../node_modules npm
