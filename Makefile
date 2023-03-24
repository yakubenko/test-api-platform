DOCKER_COMPOSE:=docker-compose
DOCKER_COMPOSE_FILE:=docker/docker-compose.yml
ENV_FILE:=docker/.env

.PHONY: ps up build build-nc cmd start start-local stop restart logs down

ps:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) ps

up:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) up $(c)

build:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) build $(c)

build-nc:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) build --no-cache $(c)

cmd:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) exec $(c) $(cmd)

init: | install-deps create-db run-migrations seed-initial-data

install-deps:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) exec phpfpm composer install

create-db:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) exec phpfpm bin/console doctrine:database:create

run-migrations:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) exec phpfpm bin/console doctrine:migrations:migrate

seed-initial-data:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) exec phpfpm bin/console app:seed-initial-data

build-and-start:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) up -d  --build $(c)

start:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE)  up -d $(c) --remove-orphans

stop:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE)  --env-file $(ENV_FILE) stop $(c)

restart:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) stop $(c)
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE) up $(c) -d

logs:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE)  logs --tail=100 -f $(c)

down:
	@$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) --env-file $(ENV_FILE)  down
