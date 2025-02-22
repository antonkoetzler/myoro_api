# Some tips

1. Building and sail commands

- `npm install; npm run build; composer install`: Installs dependencies;
- `./vendor/bin/sail down -v`: Deletes the Docker volume data;
- `./vendor/bin/sail up --build -d`: Start the docker containers (`--build` to rebuild images, `-d` to run detached);
- `./vendor/bin/sail artisan <command>`: Use this instead of `php artisan <command>` to assure it runs in the container;
- `./vendor/bin/sail artisan migrate`: Migration command.

1. Code commands (file creation)

- Resource controllers: `php artisan make:controller Foo --resource` (fk inheritance);
- Models: `php artisan make:model Foo -m` (`-m`: Create a migration file for the model).

# NEW WAY TO START DOCKER TELL UR FRIENDS

`docker compose up --build -d`
