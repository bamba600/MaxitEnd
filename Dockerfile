# Utilise une image PHP officielle avec Apache
FROM php:8.2-apache

# Active les modules Apache nécessaires
RUN a2enmod rewrite
RUN a2enmod headers

# Copie le code source dans le conteneur
COPY . /var/www/html

# Définit le dossier public comme racine du serveur
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Adapte la config Apache pour pointer sur /public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Installe les extensions PHP nécessaires (exemple : mysqli, pdo_mysql)
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql

# Expose le port 8080 (Render utilise ce port)
EXPOSE 8080

# Commande de démarrage
CMD ["apache2-foreground"]