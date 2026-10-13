# AGENTS.md: guide for AI agents and contributors

WordPress theme for ron-ulrich.de. Classic theme (not a block theme), Vite + TypeScript + UnoCSS frontend, everything runs in Docker Compose and is controlled with `make`. Read this file first, then [README.md](README.md) and the [Makefile](Makefile).

## Golden rules

- **Never commit or push without explicit user confirmation.** The same applies to `make sync_to_*`, `make import_db*`, `make clean_install`, and anything else that overwrites data.
- Do not use em dashes (`—`, `U+2014`). Rephrase instead of substituting with hyphens.
- Don't modify `wordpress/` (core, git-ignored) or `theme/assets/` (build output).
- Use yarn inside the node container only, never npm or pnpm, and never install `node_modules` on the host.
- No custom-field plugin: post teasers/leads are the native excerpt, the header portrait is the core `custom-logo`.

## Tech stack

- **Backend:** PHP, WordPress
- **Frontend:** TypeScript, UnoCSS (`presetWind3`), SCSS for the few things templates can't reach, Vite with HMR
- **Libraries:** none (vanilla JS, utility-first CSS)
- **Infrastructure:** Docker Compose (nginx, PHP-FPM, MariaDB, phpMyAdmin, Node), Coolify for staging/production
- **Tooling:** Biome (TS lint + format), php-cs-fixer (PHP)

## Workspace map

```text
theme/                      WordPress theme (ron-ulrich-theme)
  functions.php             Bootstrap, enqueues assets via inc/vite.php
  inc/                      PHP modules (Vite loader, theme setup, template helpers)
  template-parts/           Partials (content-*, page-header)
  src/ts/main.ts            TS entry, feature modules in src/ts/modules/
  src/css/main.scss         Base typography, WP-generated markup, Fluent Forms
  public/                   Static files copied verbatim into the build (images referenced from PHP)
  uno.config.ts             Breakpoints (Bootstrap 4) and shortcuts (container, tag, alert-warning)
  assets/                   Vite build output (git-ignored)
devops/
  Dockerfile                Multi-stage: node-base/php-base, dev targets node-dev/php-dev, prod php/nginx. Pins WP, WP-CLI, Composer, php-cs-fixer
  plugins.txt               Single source of truth for plugins (slug:version[:activate])
  nginx/conf.d/             nginx site config (dev and prod)
  php/wp-config.php         Env-driven wp-config for all environments
  php/entrypoint.prod.sh    Prod entrypoint (upload ownership, first-boot install, plugin activation)
  php/mariadb-wrapper.sh    Forces --skip-ssl for WP-CLI DB commands
  scripts/                  setup-wordpress, db-export/import, sync-to-env/sync-from-env (+ sync-common)
db/                         Local DB dumps, mounted at /db in the php container
uploads/                    WordPress uploads (git-ignored)
docker-compose.yml          Local stack (docker-compose.staging.yml for Coolify staging)
```

## Development

| Command | Purpose |
|---|---|
| `make install` / `make start` / `make stop` | Build and (re)start / start / stop the stack |
| `make clean_install` | Fresh install, **removes volumes (local DB)** |
| `make dev` | Vite dev server (HMR on port 5174) |
| `make build` | Production build of theme assets |
| `make setup_wordpress` | Install WordPress core and activate the theme |
| `make import_db` / `make export_db` (+ `_staging`) | DB import/export with domain search-replace |
| `make sync_to_staging` / `make sync_to_production` | Push local DB + uploads to Coolify |
| `make sync_from_staging` / `make sync_from_production` | Pull DB + uploads into the local stack |
| `make enter_php` / `make enter_node` / `make enter_phpmyadmin` | Shell into a container |

WordPress: http://localhost:8090, Vite: http://localhost:5174, phpMyAdmin: http://localhost:8091

## Code patterns

- Text domain `ron-ulrich`: wrap every user-facing string, e.g. `__('text', 'ron-ulrich')`.
- Asset loading auto-detects dev/prod: if `theme/assets/.vite/manifest.json` exists, built files are enqueued, otherwise the Vite dev server on port 5174 is loaded.
- Styling is UnoCSS utilities written directly in the PHP templates, with plain flex/grid layouts. Only use `main.scss` for markup templates cannot touch: base typography, WP-generated classes/blocks, `paginate_links()` output, Fluent Forms (its default skin is disabled in `inc/vite.php`). The wp-admin bar offset is utilities on `<html>` in `header.php`.
- Images referenced from PHP templates (outside the Vite module graph) live in `theme/public/`, not `theme/src/`.
- Plugins are defined once in `devops/plugins.txt`; the dev setup script, prod Dockerfile and prod entrypoint all read it.
- UI-facing issues get "Verified on Safari desktop + iOS" as an acceptance criterion.

## Before you finish a task

Run the checks that cover your change (stack must be running, `make start`):

- TS/CSS: `docker compose exec node yarn lint` (fix with `yarn lint:fix`)
- PHP: `make lint_php` (fix with `make fix_php`)
- Build: `make build`

## Releases

- Bump `Version` in `theme/style.css` and `version` in `theme/package.json` and refresh the lockfile with `docker compose exec node yarn install`.
- Patch for fixes and refinements, minor for new features. Propose the version and wait for confirmation.
- Build with `make build`, then commit only the version files and lockfiles as `chore: release v<version>`, create a lightweight tag `v<version>` and push with `--tags` after confirmation, then publish a GitHub release (`GH_PAGER=cat gh release create v<version> --notes ...`) with Added/Fixed/Changed notes summarizing the commits since the last tag.

## Commits

- [Conventional Commits](https://www.conventionalcommits.org/): `feat:`, `fix:`, `refactor:`, `style:`, `perf:`, `test:`, `docs:`, `chore:`
- Title: short imperative summary, lowercase after the prefix (`feat: add image lazy loading`). Don't repeat the prefix as a verb (`fix: broken scroll`, not `fix: fix broken scroll`).
- Body only when needed: flat bullet list starting with capital letters, no category headers, max 10 items. Use `->` for version transitions (`vite 7.1->8.0`).
- No `Co-authored-by` trailer for Copilot or any other AI.
