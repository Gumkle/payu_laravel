# How to install
- Download the files,
- run the `composer install` command in terminal,
- go to /app/Providers/ and change name of AppServiceProvider-default.php to AppServiceProvider.php,
- go to /config/database.php and pass in data necessary for your database connection,
- run `php artisan migrate:fresh --seed` to take care of the database content,
- profit
