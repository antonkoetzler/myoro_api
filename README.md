# Some tips

1. Building and sail commands

- `npm install; npm run build; composer install`: Installs dependencies;
- `sail down -v`: Deletes the Docker volume data;
- `sail up --build -d`: Start the docker containers (`--build` to rebuild images, `-d` to run detached);
- `sail artisan <command>`: Use this instead of `php artisan <command>` to assure it runs in the container;
- `sail artisan migrate`: Migration command.

1. Code commands (file creation)

- Resource controllers: `sail artisan make:controller Foo --resource` (fk inheritance);
- Models: `sail artisan make:model Foo -m` (`-m`: Create a migration file for the model).

1. Testing

- Make sure `sail up` was called;
- `sail artisan test`.

# NEW WAY TO START DOCKER TELL UR FRIENDS

`docker compose up --build -d`
