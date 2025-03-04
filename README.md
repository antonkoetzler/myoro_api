# Some tips

1. Building and sail commands

- `npm install; npm run build; composer install`: Installs dependencies
- `sail down -v`: Deletes the Docker volume data
- `sail up --build -d`: Start the docker containers (`--build` to rebuild images, `-d` to run detached)
- `sail artisan <command>`: Use this instead of `php artisan <command>` to assure it runs in the container
- `sail artisan migrate`: Migration command

1. Code commands (file creation)

- Resource controllers: `sail artisan make:controller Foo --resource` (fk inheritance)
- Models: `sail artisan make:model Foo -m` (`-m`: Create a migration file for the model)

1. Testing

- Make sure `sail up` was called
- `sail artisan test` can be used, but it is slow... Use `docker compose exec laravel.test php artisan test` or `sail shell; php artisan test`

# NEW WAY TO START DOCKER TELL UR FRIENDS

`docker compose up --build -d`

# Fixing and formatting
Formatting: `PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix <app, tests, routes, and database>`
Fixing: `./vendor/bin/phpstan analyse <app, tests, routes and database> --level=max --memory-limit=1G`
