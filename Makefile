include .env
export

DOCKER_COMPOSE = docker compose
EXEC = $(DOCKER_COMPOSE) exec
PHP = $(EXEC) php
CERTS_PATH := devops/caddy/certs/
CONSOLE = $(PHP) bin/console
CONTAINER_PHP = $(COMPOSE_PROJECT_NAME)-php
COMPOSER = $(PHP) composer
BRANCH := $(shell git rev-parse --abbrev-ref HEAD)

# 🎨 Colors
RED := $(shell tput -Txterm setaf 1)
GREEN := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
BLUE := $(shell tput -Txterm setaf 4)
ORANGE=$(shell tput setaf 172)
LIME_YELLOW=$(shell tput setaf 190)
RESET=$(shell tput sgr0)
BOLD=$(shell tput bold)
REVERSE=$(shell tput smso)

## —— 📦 Install dependencies ——
.PHONY: vendor
vendor: ## Install PHP dependencies
vendor: .env.local
	$(COMPOSER) install

## —— 🔥 Project ——
.env.local: ## 📄📄 Create or update .env.local file
.env.local: .env
	@if [ -f .env.local ]; then \
		if ! cmp -s .env .env.local; then \
			echo "${LIME_YELLOW}ATTENTION: ${RED}${BOLD}.env file and .env.local are different, check the changes bellow:${RESET}${REVERSE}"; \
			diff -u .env .env.local | grep -E "^[\+\-]"; \
			echo "${RESET}---\n"; \
			echo "${LIME_YELLOW}ATTENTION: ${ORANGE}This message will only appear once if the .env file is updated again.${RESET}"; \
			touch .env.local; \
			exit 1; \
		fi \
	else \
		cp .env .env.local; \
		echo "${GREEN}.env.local file has been created."; \
		echo "${ORANGE}Modify it according to your needs and continue.${RESET}"; \
		exit 1; \
	fi

.PHONY: install
install: ## 🚀 Project installation
install: .env.local ssl build start vendor open
	@echo "${BLUE}The application is available at the url: $(SERVER_NAME)$(RESET)";

## —— 🖥️ Console ——
.PHONY: console
console: ## Execute console command to accept arguments that will complete the command
	$(CONSOLE) $(filter-out $@,$(MAKECMDGOALS))

## —— 🎩 Composer ——
.PHONY: composer
composer: ## Execute composer command
	$(COMPOSER)

## —— 🔐 TLS certificate ——
.PHONY: ssl
ssl: ## Create tls certificates via mkcert library: https://github.com/FiloSottile/mkcert
	@if [ -d $(CERTS_PATH) ]; then \
		echo "${YELLOW}Removing existing certificates...${RESET}"; \
		rm -rf $(CERTS_PATH); \
	fi
	mkdir -p $(CERTS_PATH)
	@echo "${REVERSE}"
	cd $(CERTS_PATH) && mkcert $(SERVER_NAME)
	@echo "${RESET}"

## —— 🐳 Docker ——
.PHONY: build
build: ## 🏗️ Build the container
	$(DOCKER_COMPOSE) build --build-arg APP_ENV=$(APP_ENV)

.PHONY: start
start: ## ▶️ Start the containers
start: .env.local
	$(DOCKER_COMPOSE) up -d --remove-orphans

.PHONY: stop
stop: ## ⏹️ Stop the containers
	$(DOCKER_COMPOSE) stop

.PHONY: restart
restart: ## 🔄 restart the containers
restart: stop start

.PHONY: kill
kill: ## ❌ Forces running containers to stop by sending a SIGKILL signal
	$(DOCKER_COMPOSE) kill

.PHONY: down
down: ## ⏹️🧹 Stop containers and clean up resources
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

.PHONY: reset
reset: ## Stop and start a fresh install of the project
reset: kill down build start

## —— 🔨 Tools ——
.PHONY: cache
cache: ## 🧹 Clear Symfony cache
	$(CONSOLE) cache:clear

## —— ✅ Testing ——
.PHONY: test
test: ## Run tests
	$(PHP) ./vendor/bin/phpunit

## —— 🛠️ Others ——
.PHONY: open
open: ## Open the project in the browser
	@echo "${GREEN}Opening https://$(SERVER_NAME)"
	@open https://$(SERVER_NAME)/move

.DEFAULT_GOAL := help
.PHONY: help
help: ## Describe targets
	@grep -E '(^[a-z0-9A-Z_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
