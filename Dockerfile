# Use official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy your PHP files into the container
COPY . /var/www/html

# Enable Apache mod_rewrite (optional, for clean URLs)
RUN a2enmod rewrite

# Set permissions (if needed)
RUN chown -R www-data:www-data /var/www/html

# Expose port 10000 (Render uses 10000 by default)
EXPOSE 10000

# Start Apache in foreground (Render needs this)
CMD ["apache2-foreground"]
