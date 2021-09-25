start:
	php artisan serve --host 127.0.0.1

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	npm i --no-optional
	npm run dev

seed:
	php artisan db:seed
	php artisan db:seed TaskStatusSeeder
	php artisan db:seed LabelSeeder
	php artisan db:seed TaskSeeder

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku main

lint:
	composer phpcs .

lint-fix:
	composer phpcbf

stan:
	./vendor/bin/phpstan analyse
