DOCKER_COMPOSE = docker compose
DOCKER_EXEC = docker exec -it app_php

init:
	${DOCKER_COMPOSE} up --build -d
	${DOCKER_EXEC} composer install --no-interaction

down:
	${DOCKER_COMPOSE} down

up:
	${DOCKER_COMPOSE} up -d
