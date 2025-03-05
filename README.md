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
- `sail artisan test --coverage` or `docker compose exec laravel.test php artisan test --coverage` can be used, but they are slow
- Enter the Sail Laravel container directly with `sail shell`, then run `php artisan test --coverage` within the shell; very fast
