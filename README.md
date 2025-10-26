# Ron Ulrich

Website for [ron-ulrich.de](https://www.ron-ulrich.de) — plain WordPress theme with a Docker dev environment.

## Tech Stack

- **Backend:** WordPress, PHP 8.4, [ACF](https://wordpress.org/plugins/advanced-custom-fields/) (Advanced Custom Fields)
- **Frontend:** TypeScript, SCSS, [Vite](https://vitejs.dev) with HMR
- **Infrastructure:** Docker Compose (nginx, PHP-FPM, MariaDB, phpMyAdmin, Node)
- **Tooling:** [Biome](https://biomejs.dev) for JS/TS

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

## Make Targets

Run `make help` for the full list. Most used:

- `make install`: stop, build, and start all containers.
- `make dev`: run Vite dev server inside the node container.
- `make build`: production build of theme assets.
- `make setup_wordpress`: install WordPress core and activate the theme.
- `make import_db` / `make export_db`: DB import/export against the production domain.
- `make sync_to_staging` / `make sync_to_production`: push local DB + uploads to a Coolify deployment.
- `make enter_php` / `make enter_node`: shell into the given container.

## Project Structure

```
theme/                 WordPress theme (ron-ulrich-theme)
  functions.php        Theme bootstrap, enqueues, shared helpers
  inc/                 Theme setup, ACF options page, template helpers
  template-parts/      Reusable template partials
  src/                 Frontend source (TypeScript, SCSS, images)
  assets/              Vite build output (git-ignored)
  vite.config.ts       Vite config
devops/                Docker config (nginx, PHP, node Dockerfiles, scripts)
  Dockerfile.prod      Self-contained production image for Coolify
uploads/               WordPress uploads (mounted into the container)
wordpress/             WordPress core (git-ignored, installed via make)
```

## Deployment

Deployment is handled by [Coolify](https://coolify.io): it pulls this repo and
builds `devops/Dockerfile.prod` (see `docker-compose.staging.yml`). No CI
pipeline is needed — the image contains WordPress core, all plugins (pinned in
`devops/plugins.txt`), the theme, and the compiled assets.

## License

MIT, see [LICENSE.md](LICENSE.md).
