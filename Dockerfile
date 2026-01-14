FROM php:8.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html

COPY . /var/www/html/

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 8080

CMD ["apache2-foreground"]
