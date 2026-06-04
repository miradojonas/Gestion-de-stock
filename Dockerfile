# Image de base avec PHP 8.2 et Apache
FROM php:8.2-apache

# Installation des dépendances nécessaires
RUN apt-get update && apt-get install -y \
    default-libmysqlclient-dev \
    libmagic1 \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activation du module Apache rewrite
RUN a2enmod rewrite

# Copie des fichiers de l'application
WORKDIR /var/www/html
COPY . /var/www/html/

# Création du répertoire uploads et définition des permissions
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

# Exposition du port 80
EXPOSE 80
CMD ["apache2-foreground"]

