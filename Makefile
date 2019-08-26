up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear manager-clear docker-pull docker-build docker-up manager-init
test: manager-test
test-coverage: manager-test-coverage
test-unit: manager-test-unit
test-unit-coverage: manager-test-unit-coverage

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

manager-init: manager-composer-install manager-assets-install manager-wait-db manager-migrations manager-fixtures manager-ready

manager-clear:
	docker run --rm -v ${PWD}/manager:/app --workdir=/app alpine rm -f .ready

manager-composer-install:
	docker-compose run --rm manager-php-cli composer install

manager-assets-install:
	docker-compose run --rm manager-node yarn install
	docker-compose run --rm manager-node npm rebuild node-sass

manager-wait-db:
	until docker-compose exec -T manager-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

manager-migrations:
	docker-compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate --no-interaction

manager-fixtures:
	docker-compose run --rm manager-php-cli php bin/console doctrine:fixtures:load --no-interaction

manager-ready:
	docker run --rm -v ${PWD}/manager:/app --workdir=/app alpine touch .ready

manager-assets-dev:
	docker-compose run --rm manager-node npm run dev

manager-rss:
	docker-compose run --rm manager-php-cli php bin/console parser:rss

