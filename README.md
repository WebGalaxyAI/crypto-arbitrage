## Install project
- Copy cp .env.example .env

Run the following commands

```bash
composer install
php artisan key:generate
php artisan migrate --seed
```
