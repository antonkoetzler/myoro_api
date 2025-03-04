# MyoroAPI ~ Centralized API for Myoro Applications

## Building

`npm install; npm run build; composer install`

## Starting and stopping

- `sail up --build -d`: Start the docker containers (`--build` to rebuild images, `-d` to run detached)
- `sail down -v`: Stops the docker container

## Running migrations
`sail artisan migrate`

## Artisan commands

- In general, always use `sail artisan ...` instead of `php artisan ...`;
- Resource controllers: `sail artisan make:controller Foo --resource` (fk inheritance)
- Models: `sail artisan make:model Foo -m` (`-m`: Create a migration file for the model)

## Testing

1. Make sure the API is running with `sail up`
2. Options for the running tests:
- `sail artisan test` or `docker compose exec laravel.test php artisan test` can be used, but they are slow
- Enter the Sail Laravel container directly with `sail shell`, then run `php artisan test` within the shell; very fast

## (TODO: Remove this from this file) Fixing and formatting
Formatting: `PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix <app, tests, routes, and database>`
Fixing: `./vendor/bin/phpstan analyse <app, tests, routes and database> --level=max --memory-limit=1G`
