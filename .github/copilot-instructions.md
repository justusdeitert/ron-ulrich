# Copilot Instructions for ron-ulrich

## Style

- Do not use em dashes (`—`, `U+2014`). Rephrase instead of substituting with hyphens.

## Git Safety

- Never commit (`git commit`) or push (`git push`) without explicit user confirmation.

## Overview

- WordPress theme for ron-ulrich.de (classic theme, not a block theme).
- ACF (acf-json field group sync, options page) for content management.
- Vite + TypeScript for frontend assets with HMR in development.
- Migrated 2026-09 from a Bedrock/Sage setup to this plain theme + Docker structure (mirrors the `una` project).

## Tech Stack

- **Backend:** PHP, WordPress, ACF
- **Frontend:** TypeScript, UnoCSS, Vite
- **Libraries:** none (vanilla JS, utility-first CSS)
- **Infrastructure:** Docker Compose (nginx, PHP-FPM, MariaDB, phpMyAdmin, Node)
- **Package manager:** yarn (inside the Docker node container)
- **Tooling:** Biome (JS/TS lint + format)

## Project Structure

- `theme/` - WordPress theme (`ron-ulrich-theme`)
  - `functions.php` - Theme bootstrap, enqueues assets via `inc/vite.php`
  - `inc/` - PHP modules (Vite loader, theme setup, ACF options page, template helpers)
  - `acf-json/` - ACF field group JSON sync
  - `template-parts/` - Template partials (content-*, page-header)
  - `src/` - Frontend source (`ts/main.ts` + `ts/modules/`, `css/main.scss`, images)
  - `public/` - Static files copied verbatim into the Vite build (`publicDir`)
  - `assets/` - Vite build output (production only, git-ignored)
- `devops/` - Docker configuration
  - `Dockerfile` - Multi-stage build: shared `node-base` and `php-base`, dev targets `node-dev` (Vite) and `php-dev` (local PHP-FPM), prod `php` and `nginx` targets. Pins the WordPress, WP-CLI, Composer and php-cs-fixer versions
  - `plugins.txt` - Single source of truth for plugin slugs, versions, and activation state
  - `nginx/conf.d/` - nginx site config (shared by dev and prod)
  - `php/entrypoint.prod.sh` - Production entrypoint (WP install, plugin activation, menu mapping)
  - `php/wp-config.prod.php` - Production wp-config (env-driven)
  - `php/mariadb-wrapper.sh` - Forces `--skip-ssl` so WP-CLI DB commands work against MariaDB
  - `scripts/setup-wordpress.sh` - Dev WordPress setup, run by the dev entrypoint (reads `plugins.txt`)
  - `scripts/db-export.sh` / `scripts/db-import.sh` - DB export/import with domain search-replace (run inside the php container)
  - `scripts/sync-to-env.sh` - Push local DB and uploads to staging or production (runs on the host)
- `db/` - Local DB dumps (`db-import.sql`, `db-export.sql`), mounted at `/db` in the php container
- `uploads/` - WordPress uploads directory
- `wordpress/` - WordPress core (git-ignored, installed via setup script)
- `docker-compose.yml` / `docker-compose.staging.yml` - Local stack / Coolify staging

## Development

- Runs via Docker Compose, controlled with `make` commands
- `make install` - Build images and (re)start all containers
- `make start` / `make stop` - Start or stop the stack
- `make clean_install` - Fresh install, removes volumes (local DB)
- `make dev` - Vite dev server inside the node container (HMR on port 5174)
- `make build` - Production build of theme assets
- `make setup_wordpress` - Install WordPress core and activate the theme
- `make import_db` / `make export_db` - DB import/export with domain search-replace (plus `_staging` variants)
- `make sync_to_staging` / `make sync_to_production` - Sync DB + uploads to a Coolify server
- `make enter_php` / `make enter_node` / `make enter_phpmyadmin` - Shell into the given container
- WordPress: http://localhost:8090 | Vite HMR: http://localhost:5174 | phpMyAdmin: http://localhost:8091

## Code Patterns

- Text domain: `ron-ulrich` - use `__('text', 'ron-ulrich')` for all translatable strings.
- Asset loading auto-detects dev/prod in `functions.php`: if `theme/assets/.vite/manifest.json` exists, built files are enqueued, otherwise the Vite dev server is loaded from port 5174.
- Images referenced as static paths in PHP templates (not part of the Vite module graph) live in `theme/public/` and are copied verbatim into the build output.
- `node_modules` lives inside Docker volumes. Run installs inside the node container (`make enter_node`).
- TypeScript entry is `theme/src/ts/main.ts`, feature modules live in `theme/src/ts/modules/`.
- Styling is UnoCSS (`presetWind3`) with utilities written directly in the PHP templates; `theme/uno.config.ts` holds Bootstrap-4-matching breakpoints and shortcuts (`container`, `tag`, `alert-warning`). Layouts use plain flex/grid utilities.
- `theme/src/css/main.scss` is SCSS reserved for markup templates cannot touch: base typography, WP-generated classes/blocks, `paginate_links()` output, Contact Form 7. The wp-admin bar offset is utilities on `<html>` in `header.php`.
- Plugin slugs, versions, and activation flags are defined once in `devops/plugins.txt` (`slug:version[:activate]`). The dev setup script, prod Dockerfile, and prod entrypoint all read from this file.
- Legacy ACF quirk: the migrated database stores some image fields as plain URL strings instead of arrays. Templates must handle both (see `theme/header.php`).

## Don'ts

- Don't modify files inside `wordpress/` or `theme/assets/` - they are not version-controlled source code.
- Don't use npm or pnpm for theme dependencies - yarn inside the node container only.

## Installed Tools

- **CLI Tools:** git, gh (GitHub CLI), Docker, make, wp-cli, imagemagick, ffmpeg
