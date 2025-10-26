FROM php:8.4-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    less \
    mariadb-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    tree \
    zsh \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) mysqli gd zip intl opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

# Install wait-for-it script
ADD --chmod=755 https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /usr/local/bin/wait-for-it.sh

# Install Oh My Zsh
RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended \
    && chsh -s $(which zsh)

# Copy scripts
COPY --chmod=755 devops/php/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY --chmod=755 devops/php/setup-wordpress.sh /usr/local/bin/setup-wordpress.sh
COPY devops/plugins.txt /usr/local/etc/plugins.txt
COPY --chmod=755 devops/php/search-replace-export-db.sh /usr/local/bin/search-replace-export-db.sh
COPY --chmod=755 devops/php/search-replace-import-db.sh /usr/local/bin/search-replace-import-db.sh

WORKDIR /var/www/html

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
