include .env.local
export

# Variables
DOCKER 	        = docker
DOCKER_COMPOSE 	= docker compose
EXEC 			= $(DOCKER) exec
APP				= $(EXEC) -it twig_component_demo_php
COMPOSER		= $(EXEC) -it -e COMPOSER_MEMORY_LIMIT=-1 twig_component_demo_php
CONSOLE			= $(APP) bin/console

# Colors
GREEN := $(shell tput -Txterm setaf 2)
RED := $(shell tput -Txterm setaf 1)
YELLOW := $(shell tput -Txterm setaf 3)

## —— 🔥 Project ——
.env.local: .env
	@if [ -f .env.local ]; then \
		echo '${YELLOW}The ".env" has changed. You may want to update your copy .env.local accordingly (this message will only appear once).'; \
		touch .env.local; \
		exit 1; \
	else \
		cp .env .env.local; \
		echo "${YELLOW}Modify it according to your needs and rerun the command."; \
		exit 1; \
	fi

.PHONY: install
install: ## Project Installation
install: .env.local start vendor reset-db install-assets build-assets
	@echo "${GREEN}The application is available at: https://localhost"

.PHONY: cache-clear
cache-clear: ## Clear cache
	$(CONSOLE) cache:clear

## —— 🐳 Docker ——
.PHONY: start
start: ## Start the containers
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans

.PHONY: stop
stop: ## Stop the containers
	$(DOCKER_COMPOSE) stop

.PHONY: restart
restart: ## restart the containers
restart: stop start

.PHONY: kill
kill: ## Forces running containers to stop by sending a SIGKILL signal
	$(DOCKER_COMPOSE) kill

.PHONY: down
down: ## Stops containers
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

.PHONY: reset
reset: ## Stop and start a fresh install of the project
reset: kill down install

.PHONY: php-shell
php-shell: ## Open a new shell in php container
	$(APP) /bin/bash

## —— 🎻 Composer ——
vendor: ## Install dependencies
vendor: .env.local composer.lock
	$(COMPOSER) composer install

.PHONY: composer-update
composer-update: ## Update dependencies
	$(COMPOSER) composer update

## —— 📊 Database ——
.PHONY: reset-test-db
reset-test-db: ## Reset Database before test
reset-test-db: vendor
    # Needs database container to be running (run "make start" if needed)
	$(CONSOLE) doctrine:database:drop --force --env=test
	$(CONSOLE) doctrine:schema:create --env=test
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --env=test

.PHONY: reset-db
reset-db: ## Reset Database
reset-db: vendor
    # Needs database container to be running (run "make start" if needed)
	$(CONSOLE) doctrine:database:drop --force
	$(CONSOLE) doctrine:database:create --if-not-exists --no-interaction
	$(CONSOLE) doctrine:migrations:migrate --no-interaction


## —— Assets ——
.PHONY: install-assets
install-assets: ## Install assets
install-assets: vendor
	$(CONSOLE) importmap:install

.PHONY: build-assets
build-assets: ## Build assets
build-assets: vendor
	$(CONSOLE) tailwind:build
	$(CONSOLE) asset-map:compile

## —— ✅ Test ——
.PHONY: tests
tests: ## Run all tests
tests: reset-test-db functional-tests

.PHONY: functional-tests
functional-tests: ## Run functional tests
functional-tests:
	$(APP) vendor/bin/phpunit

## —— ✨ Code Quality ——
.PHONY: lint-yaml
lint-yaml: ## Lints YAML files
	# Need PHP dependencies (run "make composer-install" if needed)
	$(CONSOLE) lint:yaml config

.PHONY: lint-twig
lint-twig:## Lints Twig files
	# Need PHP dependencies (run "make composer-install" if needed)
	$(CONSOLE) lint:twig templates

.PHONY: lint-container
lint-container: ## Lints containers
	# Need PHP dependencies (run "make composer-install" if needed)
	$(CONSOLE) lint:container

## —— 🛠️ Others ——
.PHONY: help
help: ## List of commands
	@grep -E '(^[a-z0-9A-Z_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
