# How to install
- Download the files,
- run the `composer install` command in terminal,
- go to /app/Providers/ and change name of AppServiceProvider-default.php to AppServiceProvider.php,
- go to /config/database.php and pass in data necessary for your database connection,
- go to .env and pass in data necessary for your local development,
- run `php artisan migrate:fresh --seed` to take care of the database content,
- profit

# What is this btw?
This project has been started to be testing environment of PayU API, but I'm gonna develop it further anyway, for the sake of PHP, Laravel and PayU API learning.

# Can I use it?
If I'd ever get to the point when the content of this project will become serious and possibly moneymakable - then I'll cosider moving it or changing rights. Meanwhile feel free to use any of this learning-dummy-garbage, it would also be nice if you'll mention me somewhere and let me know, if you'll build something amazing!
