APP_CONTAINER=task_management_web

up:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose down && docker compose up -d

bash:
	docker exec -it $(APP_CONTAINER) bash

install:
	docker exec -it $(APP_CONTAINER) composer install

key:
	docker exec -it $(APP_CONTAINER) php artisan key:generate

migrate:
	docker exec -it $(APP_CONTAINER) php artisan migrate

fresh:
	docker exec -it $(APP_CONTAINER) php artisan migrate:fresh --seed

logs:
	docker compose logs -f

setup: up install key migrate
	@echo "🚀 Laravel ready!"
