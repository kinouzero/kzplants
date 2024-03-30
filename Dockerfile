# Utilisez l'image PHP de ServerSideUp
FROM serversideup/php:latest

# Définissez le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copiez tous les fichiers du répertoire local (où se trouve le Dockerfile) dans le conteneur
COPY . .

# Installez Node.js (si nécessaire)
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs

# Installez Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installez les dépendances PHP via Composer
RUN composer install

# Exécutez d'autres commandes nécessaires pour votre application Laravel, par exemple, générer une clé d'application
RUN php artisan key:generate

RUN cd public && ln -s ../node_modules npm
