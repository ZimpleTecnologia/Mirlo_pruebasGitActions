FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar configuración personalizada de Apache (opcional)
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html 