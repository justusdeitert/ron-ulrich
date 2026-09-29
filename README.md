# Ron Ulrich

[![WordPress](https://img.shields.io/badge/WordPress-21759B?logo=wordpress&logoColor=white)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![TypeScript](https://img.shields.io/badge/TypeScript-3178C6?logo=typescript&logoColor=white)](https://www.typescriptlang.org)
[![Vite](https://img.shields.io/badge/Vite-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![UnoCSS](https://img.shields.io/badge/UnoCSS-333333?logo=unocss&logoColor=white)](https://unocss.dev)
[![Sass](https://img.shields.io/badge/Sass-CC6699?logo=sass&logoColor=white)](https://sass-lang.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white)](https://www.docker.com)
[![Biome](https://img.shields.io/badge/Biome-60A5FA?logo=biome&logoColor=white)](https://biomejs.dev)

A WordPress theme and Docker dev environment powering the website of author, editor and reporter Ron Ulrich.

🌐 **Live:** [ron-ulrich.de](https://www.ron-ulrich.de)<br>
🧪 **Staging:** [ron-ulrich.justusdeitert.de](https://ron-ulrich.justusdeitert.de)

## Tech Stack

- **Backend:** WordPress, PHP 8.4
- **Frontend:** TypeScript, [UnoCSS](https://unocss.dev), SCSS, [Vite](https://vitejs.dev) with HMR
- **Infrastructure:** Docker Compose (nginx, PHP-FPM, MariaDB, phpMyAdmin, Node)
- **Tooling:** [Biome](https://biomejs.dev) for JS/TS, php-cs-fixer for PHP

## Requirements

- Docker (with Docker Compose)
- GNU Make

## Quick Start

```bash
cp .env.dist .env        # adjust values as needed
make install             # build containers and start the stack
make setup_wordpress     # install WordPress core, activate theme
make dev                 # start Vite dev server with HMR
```

Services:

- WordPress: http://localhost:8090
- Vite HMR: http://localhost:5174
- phpMyAdmin: http://localhost:8091

## Configuration

Copy `.env.dist` to `.env` and fill in the values. The most important ones:

- `WORDPRESS_ADMIN_USER` / `WORDPRESS_ADMIN_PASSWORD`: credentials created by `make setup_wordpress`.
- `LOCAL_DOMAIN` / `STAGING_DOMAIN` / `PRODUCTION_DOMAIN`: used by the DB import/export search-replace scripts.

## Make Targets

Run `make help` for the full list. Most used:

- `make install`: build images and (re)start all containers.
- `make start` / `make stop`: start or stop the stack.
- `make clean_install`: fresh install, removes volumes (local DB).
- `make dev`: run Vite dev server inside the node container.
- `make build`: production build of theme assets.
- `make setup_wordpress`: install WordPress core and activate the theme.
- `make import_db` / `make export_db`: DB import/export against the production domain. Dumps live in `db/` (`db/db-import.sql`, `db/db-export.sql`).
- `make import_db_staging` / `make export_db_staging`: same, but against the staging domain.
- `make sync_to_staging` / `make sync_to_production`: push local DB + uploads to a Coolify deployment.
- `make sync_from_staging` / `make sync_from_production`: pull DB + uploads from a Coolify deployment into the local stack (local DB is backed up to `db/` first).
- `make enter_php` / `make enter_node` / `make enter_phpmyadmin`: shell into the given container.
- `make lint_php` / `make fix_php`: run php-cs-fixer against the theme.

## Project Structure

```
theme/                 WordPress theme (ron-ulrich-theme)
  functions.php        Theme bootstrap, enqueues, shared helpers
  inc/                 Theme setup, Vite loader, editor, template helpers
  template-parts/      Reusable template partials
  src/                 Frontend source (TypeScript, SCSS, images)
  public/              Static files copied verbatim into the build
  scripts/             Build scripts (theme.json generation)
  vite-plugins/        Custom Vite plugins
  assets/              Vite build output (git-ignored)
  vite.config.ts       Vite config
  uno.config.ts        UnoCSS config
devops/                Docker config (nginx, PHP, scripts)
  Dockerfile           Multi-stage image: dev targets and Coolify prod targets
  plugins.txt          Pinned WordPress plugins
  scripts/             WordPress setup, DB import/export, sync to Coolify
db/                    Local DB dumps (git-ignored)
uploads/               WordPress uploads (mounted into the container)
wordpress/             WordPress core (git-ignored, installed via make)
```

## Deployment

Deployment is handled by [Coolify](https://coolify.io): it pulls this repo and
builds the `php` and `nginx` targets of `devops/Dockerfile` (see
`docker-compose.staging.yml`). No CI pipeline is needed: the image contains
WordPress core, all plugins (pinned in `devops/plugins.txt`), the theme, and
the compiled assets.
