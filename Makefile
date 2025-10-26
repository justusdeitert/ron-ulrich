DOCKER_COMPOSE := docker compose
ENSURE_UP = @if [ -z "$$($(DOCKER_COMPOSE) ps --services --filter status=running | grep -x node)" ]; then $(DOCKER_COMPOSE) up -d; fi

STAGING_SSH_HOST ?= hetzner
PRODUCTION_SSH_HOST ?=

.PHONY: help install start stop build_container clean_install enter_php enter_phpmyadmin enter_node dev build setup_wordpress export_db export_db_staging import_db import_db_staging sync_to_staging sync_to_production

.DEFAULT_GOAL := help

help: ## Show this help
	@echo "Usage: make <target>\n"
	@grep -E '^[a-zA-Z_]+:.*##' $(MAKEFILE_LIST) | awk -F ':.*## ' '{printf "  %-18s %s\n", $$1, $$2}'

install: stop build_container start ## Stop, build, and start all containers
clean_install: stop clean build_container start ## Fresh install, removes volumes and network

start: ## Start containers
	@$(DOCKER_COMPOSE) up -d

stop: ## Stop containers
	@$(DOCKER_COMPOSE) down

build_container:
	@$(DOCKER_COMPOSE) build

clean:
	@$(DOCKER_COMPOSE) down -v
	@docker network prune -f

enter_php: ## Shell into PHP container
	@$(DOCKER_COMPOSE) exec -w /var/www/html php /bin/zsh

enter_phpmyadmin: ## Shell into phpMyAdmin container
	@$(DOCKER_COMPOSE) exec -w / phpmyadmin /bin/sh

enter_node: ## Shell into Node container
	@$(DOCKER_COMPOSE) exec -w /usr/src/theme node /bin/zsh

dev: ## Run Vite dev server (HMR on port 5174)
	$(ENSURE_UP)
	@$(DOCKER_COMPOSE) exec -w /usr/src/theme node sh -c "pkill -f '[v]ite' >/dev/null 2>&1; exit 0"
	@HOST_LAN_IP=$$(ipconfig getifaddr en0 2>/dev/null || ipconfig getifaddr en1 2>/dev/null); \
	$(DOCKER_COMPOSE) exec -e HOST_LAN_IP=$$HOST_LAN_IP -w /usr/src/theme node yarn dev

build: ## Production build of theme assets
	$(ENSURE_UP)
	@$(DOCKER_COMPOSE) exec -w /usr/src/theme node yarn build

setup_wordpress: ## Install WordPress core and activate theme
	@$(DOCKER_COMPOSE) exec php /usr/local/bin/setup-wordpress.sh

export_db: ## Export DB with production domain search-replace
	@$(DOCKER_COMPOSE) exec -e TARGET=production php /usr/local/bin/search-replace-export-db.sh

export_db_staging: ## Export DB with staging domain search-replace
	@$(DOCKER_COMPOSE) exec -e TARGET=staging php /usr/local/bin/search-replace-export-db.sh

import_db: ## Import DB from production dump
	@$(DOCKER_COMPOSE) exec -e TARGET=production php /usr/local/bin/search-replace-import-db.sh

import_db_staging: ## Import DB from staging dump
	@$(DOCKER_COMPOSE) exec -e TARGET=staging php /usr/local/bin/search-replace-import-db.sh

sync_to_staging: ## Sync local DB + uploads to staging (Coolify)
	@TARGET=staging STAGING_SSH_HOST="$(STAGING_SSH_HOST)" ./devops/sync-to-env.sh

sync_to_production: ## Sync local DB + uploads to production (Coolify, asks for confirmation)
	@TARGET=production PRODUCTION_SSH_HOST="$(PRODUCTION_SSH_HOST)" ./devops/sync-to-env.sh
