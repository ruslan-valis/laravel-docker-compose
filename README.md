# laravel-docker-compose
Simple docker-compose environment for Laravel application with health check

## Getting Started

### Overview

Docker is used for containerisation, docker-compose is used for containers orchestration.

Basic docker configurations are provided in `docker/` directory, separated by necessary services for the system running, aka:
- nginx
- php-fpm
- mysql
- redis

Each directory contains `Dockerfile` that allows to build specific docker image that then can be orchestrated via docker-compose.
Default project services orchestration settings are located at `docker-compose.yml`.

`php-fpm` + `nginx` setup has been chosen over artisan self-serving to provide more flexibilty for future extension, however it can be replaced in case if we need more simplicity for some specific project.

### Initial configuration
Environment variables via `.env` files are used to manage both docker and application environment settings.

Basic list of environment variables is provided in `.env.dist`.
However, in order to run project locally, you need to have `.env.local`, which can be copied from `.env.dist` and updated with necessary environment variables.

By default, `.env.dist` should contain values to start project out of the box locally.

### Building environment
In order to build and run the project using docker-compose, you can use the next command:
```bash
docker-compose up -d --build
```

### Run environment
If you'd like to run project without rebuild you can just run:
```bash
docker-compose up -d
```

### Shell environment
All commands should be executed from php-fpm container, in order to do that you can use following command to login to php-fpm container shell:
```shell
docker exec -it php-fpm /bin/bash
```
Alternatively, you can setup default php interpreter in your IDE to use docker php version, then you can just run commands directly from the IDE.

## Development
All commands listed in this section should be executed from php-fpm docker container shell.

### Dependencies
In order to install packages execute command:
```bash
composer install
```

### Migrations
In order to run migrations execute command:
```bash
php artisan migrate
```

### Cache
In order to clean cache execute command:
```bash
php artisan cache:clear
```

## Licensing
The code in this project is licensed under [Apache License](LICENSE) license.
