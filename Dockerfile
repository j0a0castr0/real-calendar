# Stage 1: Build Symfony app
FROM php:8.5-fpm-alpine AS builder

# Install build deps
RUN apk add --no-cache postgresql-dev git unzip zip curl bash \
    && docker-php-ext-install pdo_pgsql

WORKDIR /app

COPY composer.json composer.lock ./

# Install dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-scripts

# Copy app source
COPY . .

# Set env
ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV DATABASE_URL=""

# Clear cache
RUN php bin/console cache:clear --env=prod --no-warmup

# Stage 2: Production container
FROM php:8.5-fpm-alpine

# Runtime deps
RUN apk add --no-cache nginx bash postgresql-dev \
    && docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

COPY --from=builder /app /var/www/html

# Make var folder writable
RUN chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var

# Copy Nginx config
COPY docker/nginx/default.conf /etc/nginx/nginx.conf

# Ensure log dirs exist
RUN mkdir -p /var/log/nginx /var/run

EXPOSE 80

# Start both PHP-FPM and Nginx properly
CMD php-fpm & nginx -g 'daemon off;'
