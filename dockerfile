FROM richarvey/nginx-php-fpm:latest

# Basic image config
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Copy app into container
COPY . /var/www/html

WORKDIR /var/www/html

# Install composer dependencies at build time (ensures vendor exists)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy any startup scripts into the entrypoint dir the base image runs
# (make sure scripts are executable)
COPY scripts/ /docker-entrypoint.d/
RUN chmod +x /docker-entrypoint.d/*.sh || true

# Ensure storage & cache are writable
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start the base image's start script
CMD ["/start.sh"]
