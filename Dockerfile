FROM php:8.1-cli

# install mysqli extension
RUN docker-php-ext-install mysqli

WORKDIR /app
COPY . .

CMD ["sh", "-c", "php -S 0.0.0.0:$PORT"]
