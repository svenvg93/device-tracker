# Use an official PHP runtime as a parent image
FROM php:8.2-cli

# Install system dependencies, SQLite extension, and Composer
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    && docker-php-ext-install pdo_sqlite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install pdo_sqlite

# Set the working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies and generate autoload files
RUN composer install --no-interaction --optimize-autoloader --no-scripts --ignore-platform-reqs

# Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create SQLite database file if it doesn't exist, run migrations, seed the database, and start the server
CMD bash -c "if [ ! -f /var/www/html/database/database.sqlite ]; then \
               touch /var/www/html/database/database.sqlite; \
               fi && php artisan migrate --force && php artisan db:seed && php artisan serve --host=0.0.0.0 --port=8000"

# Expose port 8000 for the web server
EXPOSE 8000