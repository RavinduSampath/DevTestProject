#!/usr/bin/make

SHELL = /bin/sh

UID := $(shell id -u)
GID := $(shell id -g)

export UID
export GID


down:
	docker compose down --remove-orphans
shell:
	docker compose exec -u ${UID}:${UID} app sh
up:
	docker compose up --build --remove-orphans -d
up-f:
	docker compose up --build --remove-orphans
