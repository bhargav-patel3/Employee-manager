FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install mysqli intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (needed for CodeIgniter's .htaccess routing)
RUN a2enmod rewrite

# Point Apache's document root to the CodeIgniter "public" folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN printf '<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>\n' > /etc/apache2/conf-available/ci4-public.conf \
    && a2enconf ci4-public

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

EXPOSE 80

CMD ["apache2-foreground"]
