DOCKER_COMPOSE := docker compose
THEME_DIR := /var/www/html/wp-content/themes/ron-ulrich-theme

.PHONY: help install clean_install start stop clean enter_php enter_phpmyadmin enter_node dev build setup_wordpress export_db export_db_staging import_db import_db_staging sync_to_staging sync_to_production sync_from_staging sync_from_production lint_php fix_php

.DEFAULT_GOAL := help

help: ## Show this help
	@echo "Usage: make <target>\n"
	@grep -E '^[a-zA-Z_]+:.*##' $(MAKEFILE_LIST) | awk -F ':.*## ' '{printf "  %-20s %s\n", $$1, $$2}'

install: ## Build images and (re)start all containers
	@$(DOCKER_COMPOSE) up -d --build --remove-orphans

clean_install: clean install ## Fresh install, removes volumes (local DB)

start: ## Start containers, recreating any that are out of date
	@$(DOCKER_COMPOSE) up -d

stop: ## Stop containers
	@$(DOCKER_COMPOSE) down

clean:
	@$(DOCKER_COMPOSE) down -v

enter_php: ## Shell into PHP container
	@$(DOCKER_COMPOSE) exec php bash

enter_phpmyadmin: ## Shell into phpMyAdmin container
	@$(DOCKER_COMPOSE) exec -w / phpmyadmin /bin/sh

enter_node: ## Shell into Node container
	@$(DOCKER_COMPOSE) exec node sh

dev: start ## Run Vite dev server (HMR on port 5174)
	@# Stop orphaned vite from a previous session (docker exec does not always forward the kill)
	@$(DOCKER_COMPOSE) exec node pkill -f node_modules/.bin/vite >/dev/null 2>&1 && echo "Stopped leftover vite process." || true
	@HOST_LAN_IP=$$(ipconfig getifaddr en0 2>/dev/null || ipconfig getifaddr en1 2>/dev/null); \
	$(DOCKER_COMPOSE) exec -e HOST_LAN_IP=$$HOST_LAN_IP node yarn dev

build: start ## Production build of theme assets
	@$(DOCKER_COMPOSE) exec node yarn build

setup_wordpress: ## Install WordPress core and activate theme
	@$(DOCKER_COMPOSE) exec php /devops/scripts/setup-wordpress.sh

export_db: ## Export DB with production domain search-replace
	@$(DOCKER_COMPOSE) exec -e TARGET=production php /devops/scripts/db-export.sh

export_db_staging: ## Export DB with staging domain search-replace
	@$(DOCKER_COMPOSE) exec -e TARGET=staging php /devops/scripts/db-export.sh

import_db: ## Import DB with production domain search-replace
	@$(DOCKER_COMPOSE) exec -e TARGET=production php /devops/scripts/db-import.sh

import_db_staging: ## Import DB with staging domain search-replace
	@$(DOCKER_COMPOSE) exec -e TARGET=staging php /devops/scripts/db-import.sh

sync_to_staging: ## Push local DB and uploads to the staging deployment on Coolify
	@TARGET=staging ./devops/scripts/sync-to-env.sh

sync_to_production: ## Push local DB and uploads to production (asks for confirmation)
	@TARGET=production ./devops/scripts/sync-to-env.sh

sync_from_staging: ## Pull staging DB and uploads into the local environment
	@SOURCE=staging ./devops/scripts/sync-from-env.sh

sync_from_production: ## Pull production DB and uploads into the local environment
	@SOURCE=production ./devops/scripts/sync-from-env.sh

lint_php: ## Run php-cs-fixer (dry run)
	@$(DOCKER_COMPOSE) exec -w $(THEME_DIR) php php-cs-fixer fix --dry-run --diff

fix_php: ## Run php-cs-fixer (apply fixes)
	@$(DOCKER_COMPOSE) exec -w $(THEME_DIR) php php-cs-fixer fix
