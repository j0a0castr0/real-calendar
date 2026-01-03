# Stage 1: Build Symfony app
FROM php:8.5-fpm-alpine AS builder

# Install dependencies
RUN apk add --no-cache postgresql-dev git unzip zip curl bash \
    && docker-php-ext-install pdo_pgsql

WORKDIR /app

# Copy composer files and install dependencies
COPY composer.json composer.lock ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-scripts

# Copy the app
COPY . .

# Set environment variables
ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV DATABASE_URL=""

# Clear cache
RUN php bin/console cache:clear --env=prod --no-warmup

# ----------------------------
# Stage 2: Production container
# ----------------------------
FROM php:8.5-fpm-alpine

# Install only runtime dependencies
RUN apk add --no-cache bash curl postgresql-dev \
    && docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

# Copy Symfony app from builder
COPY --from=builder /app /var/www/html

# Ensure var folder is writable
RUN chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var

# Expose port for ECS / ALB
EXPOSE 80

# Run Symfony built-in server on 0.0.0.0:80
# This avoids Nginx + PHP-FPM race conditions
CMD php -S 0.0.0.0:80 -t public
