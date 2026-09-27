# syntax=docker/dockerfile:1.7

FROM node:22-alpine AS node-base


FROM node-base AS node-dev

WORKDIR /usr/src/theme

# Idle container; `make dev` starts Vite via docker compose exec
CMD ["tail", "-f", "/dev/null"]


FROM node-base AS theme-builder

WORKDIR /theme

COPY theme/package.json theme/yarn.lock ./
RUN yarn install --frozen-lockfile

COPY theme/ ./
RUN yarn build


FROM php:8.4-fpm AS php-base

# Single source of the WordPress core version for dev and prod
ENV WORDPRESS_VERSION=7.1
ARG WP_CLI_VERSION=2.12.0

RUN apt-get update && apt-get install -y --no-install-recommends \
        curl \
        ca-certificates \
        less \
        mariadb-client \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libwebp-dev \
        libavif-dev \
        libzip-dev \
        libicu-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
    && docker-php-ext-install -j"$(nproc)" mysqli gd zip intl opcache \
    && rm -rf /var/lib/apt/lists/*

# WP-CLI calls mariadb with --no-defaults, so config files can't disable SSL
RUN mv /usr/bin/mariadb /usr/bin/mariadb-real
COPY --chmod=755 devops/php/mariadb-wrapper.sh /usr/bin/mariadb

RUN curl -fsSL -o /usr/local/bin/wp "https://github.com/wp-cli/wp-cli/releases/download/v${WP_CLI_VERSION}/wp-cli-${WP_CLI_VERSION}.phar" \
    && chmod +x /usr/local/bin/wp

WORKDIR /var/www/html


FROM php-base AS php-dev

ARG PHP_CS_FIXER_VERSION=3.95.27

RUN apt-get update && apt-get install -y --no-install-recommends git tree zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.10.3 /usr/bin/composer /usr/local/bin/composer

RUN composer global require "friendsofphp/php-cs-fixer:${PHP_CS_FIXER_VERSION}" --no-interaction \
    && ln -s /root/.composer/vendor/bin/php-cs-fixer /usr/local/bin/php-cs-fixer

# Scripts are bind-mounted from ./devops (see docker-compose.yml)
ENTRYPOINT ["/devops/php/entrypoint.sh"]


FROM php-base AS wp-base

RUN wp core download --version="${WORDPRESS_VERSION}" --allow-root --skip-content \
    && mkdir -p wp-content/themes wp-content/plugins wp-content/uploads \
    && chown -R www-data:www-data /var/www/html

COPY devops/plugins.txt /usr/local/etc/plugins.txt
RUN set -eux; \
    cd wp-content/plugins; \
    while IFS=: read -r slug version _rest; do \
        [ -z "$slug" ] && continue; \
        echo "==> Installing plugin: $slug ($version)"; \
        curl -fsSL -o "${slug}.zip" "https://downloads.wordpress.org/plugin/${slug}.${version}.zip"; \
        unzip -q "${slug}.zip"; \
        rm "${slug}.zip"; \
    done < /usr/local/etc/plugins.txt; \
    chown -R www-data:www-data .

COPY --chown=www-data:www-data theme/ wp-content/themes/ron-ulrich-theme/
COPY --chown=www-data:www-data --from=theme-builder /theme/assets/ wp-content/themes/ron-ulrich-theme/assets/

COPY --chown=www-data:www-data devops/php/wp-config.php wp-config.php
COPY devops/php/php.prod.ini /usr/local/etc/php/php.ini


FROM wp-base AS php

COPY --chmod=755 devops/php/entrypoint.prod.sh /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]


FROM nginx:1.27-alpine AS nginx

COPY devops/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY --from=wp-base /var/www/html /var/www/html

EXPOSE 80
