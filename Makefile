install:
	docker run --rm \
        -u "${uid}:${gid}" \
        -v ".:/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
	cp .env.example .env

generate-key:
	./vendor/bin/sail artisan key:generate

up:
	./vendor/bin/sail up -d
	./vendor/bin/sail artisan migrate:refresh
	./vendor/bin/sail artisan db:seed

down:
	./vendor/bin/sail down

test:
	./vendor/bin/sail test
