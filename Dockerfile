FROM php:8.1-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html/

EXPOSE 8080

CMD ["apache2-foreground"]
