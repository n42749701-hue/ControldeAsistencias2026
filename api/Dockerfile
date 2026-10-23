FROM php:8.3-apache

# 1. Instalar extensiones y activar modulo rewrite
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

# 2. Configurar correctamente la raiz del documento en Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Configurar el directorio de trabajo y copiar archivos
WORKDIR /var/www/html
COPY . /var/www/html/

# 4. Asignar permisos para el usuario de Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]