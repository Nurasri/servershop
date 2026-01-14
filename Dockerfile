FROM php:8.1-apache

# HAPUS SEMUA MPM
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.load \
    && rm -f /etc/apache2/mods-enabled/mpm_prefork.load

# AKTIFKAN HANYA PREFORK
RUN ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load

# PHP extensions (aman walau belum pakai DB)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Railway pakai PORT env
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf \
    && sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

CMD ["apache2-foreground"]
