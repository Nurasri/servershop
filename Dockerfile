FROM php:8.1-cli

WORKDIR /app
COPY . .

# Railway pakai PORT env
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT"]
