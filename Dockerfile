# Stage 1: Build Symfony app
FROM php:8.5-fpm-alpine AS builder

RUN apk add --no-cache postgresql-dev git unzip zip curl bash \
    && docker-php-ext-install pdo_pgsql

WORKDIR /app

COPY composer.json composer.lock ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV DATABASE_URL=""

RUN php bin/console cache:clear --env=prod --no-warmup

# Stage 2: Production container with Nginx + PHP-FPM
FROM php:8.5-fpm-alpine

RUN apk add --no-cache nginx bash curl postgresql-dev \
    && docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

COPY --from=builder /app /var/www/html

RUN chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var

# Copy full nginx.conf (not snippet)
COPY docker/nginx/default.conf /etc/nginx/nginx.conf

# Ensure log dirs exist
RUN mkdir -p /var/log/nginx /var/run

EXPOSE 80

# Start both PHP-FPM and Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
