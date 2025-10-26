# PHP 7.4 is the newest version that runs this 2019-era Bedrock/WordPress 5.0 stack cleanly
FROM php:7.4-apache

# PHP extensions needed by WordPress + tools Composer needs to unpack packages
# (bullseye is EOL: use archive.debian.org and skip the Release expiry check)
RUN sed -i -e 's|deb.debian.org|archive.debian.org|g' -e '/bullseye-updates/d' -e '/bullseye-security/d' /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-valid-until \
    && apt-get update && apt-get install -y --no-install-recommends unzip git \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mysqli opcache

# Apache: mod_rewrite + point the docroot at Bedrock's web/ directory
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!/var/www/html/web!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/docker-php.conf

# Composer 1 (lock file predates Composer 2) + WP-CLI
COPY --from=composer:1 /usr/bin/composer /usr/bin/composer
RUN curl -sSLo /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp

WORKDIR /var/www/html

# Install PHP dependencies first (cached layer unless composer files change)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# Copy the rest of the project and finish the autoloader
COPY . .
RUN composer dump-autoload --no-dev --optimize \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80
