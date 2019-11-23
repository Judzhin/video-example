start:
    docker-compose up -d

log:
    docker ps

restart:
    docker-compose stop && docker-compose rm -f && docker-compose up -d

rebuild:
    docker-compose up -d --force-recreate --build

stop:
    docker-compose rm --all

remove:
    docker-compose stop && docker-compose rm -f

dev-run:
	docker run --rm -v ${PWD}:/app --workdir=/app php public/index.php

prod-build:
	docker build --file=Dockerfile --tag ms-rabbitmq ./

prod-run:
	docker run --rm ms-rabbitmq php public/index.php

show ip:
    docker inspect --format='{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' 708716cc7e20

kafka:
    docker-compose exec php-cli php bin/console.php demo:kafka:producer a51a6af1-f0d4-4556-8338-01f55e48b2c0

kafka:
    ./bin/kafka-topics.sh --create --zookeeper localhost:2181 --replication-factor 1 --partitions 1 --topic test --config retention.ms=1680000