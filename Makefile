up:
	docker-compose up -d

down:
	docker-compose down

make migrate:
	docker exec -it php83 /bin/bash -c "php bin/console doctrine:migrations:migrate"

make test:
	docker exec -it php83 /bin/bash -c "php bin/phpunit"

make coverage:
	docker exec -it php83 /bin/bash -c "XDEBUG_MODE=coverage php bin/phpunit --coverage-html coverage"

cli:
	docker exec -it php83 /bin/bash

fake-data:
	docker exec -it php83 /bin/bash -c "php bin/console app:generate-fake-data 3"