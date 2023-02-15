# Entries checking project
## Server requirements
- Everything that is required by Laravel v9

## Installation guide
Guide was documented while using Laravel v9

- create an `.env` file and fullfill it
- Run commands 
```sh
composer install
php artisan key:generate
php artisan migrate:fresh --seed --seeder=UserSeeder
php artisan test
```

For local development server:
```sh
php -S localhost:8000 -t public
```
