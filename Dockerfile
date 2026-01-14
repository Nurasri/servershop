FROM php:8.1-apache

# Disable semua MPM dulu
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

ENV APACHE_DOCUMENT_ROOT /var/www/html

COPY . /var/www/html/

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri 's/80/${PORT}/g' /etc/apache2/ports.conf \
    && sed -ri 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

CMD ["sh", "-c", "apache2-foreground"]
