# Some tips

- `npm install; npm run build; composer install`: Installs dependencies;
- `./vendor/bin/sail down -v`: Deletes the Docker volume data;
- `./vendor/bin/sail up --build -d`: Start the docker containers (`--build` to rebuild images, `-d` to run detached);
- `./vendor/bin/sail artisan <command>`: Use this instead of `php artisan <command>` to assure it runs in the container;
- `./vendor/bin/sail artisan migrate`: Migration command.
