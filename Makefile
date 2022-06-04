build:
	docker-compose build --no-cache

setup:
	docker exec -it ebanx-php-fpm chmod 777 -R storage
	docker exec -it ebanx-php-fpm composer install
	docker exec -it ebanx-php-fpm php artisan migrate

start-app:
	docker-compose up -d

stop-app:
	docker-compose stop

run-tests:
	docker-compose run -e XDEBUG_MODE=coverage ebanx-php-fpm ./vendor/phpunit/phpunit/phpunit --colors --testdox --coverage-html=.coverage