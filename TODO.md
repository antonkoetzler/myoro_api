# TODO

1. Still have to (feature) test UserController::login and UserController::signup;
1. Test, format and fix GitHub Action;
{
  "scripts": {
    "ci": [
      "PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix app tests routes database --dry-run --diff", <-- **DRY RUN**
      "./vendor/bin/phpstan analyse app tests routes database --level=max --memory-limit=1G"
    ]
  }
}
1. Make sure tests aren't being done on the prod database;
1. Try to deploy on the thinking paddy lappy tappy;
1. Nice, you get to take a break now and create MyoroFinance with Next.
