## Installation

This installation assumes there is already a web server set up, like for example Apache, and the connection to the sever and MySQL database is successfull.


Type in the following:

<br>

Install the composer
```php
composer install
```

<br>

Install npm
```php
npm install
```

<br>

Copy the contents from .env.example into your .env and replace the DB_DATABASE, DB_USERNAME and DB_PASSWORD with your mysql credentials.
```php
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

<br>

Clear Laravel config cache 
```php
php artisan config:cache
```

<br>

Generate Laravel Key 
```php
php artisan key:generate
```

<br>

Generate mysql database and fill it with dummy data
```php
php artisan migrate:fresh --seed
```

<br>

Run app on PHP development server
```php
php artisan serve
```

<br>

You should now be able to open the app in the browser

http://127.0.0.1:8000/ or http://localhost:8000/

<br>

Login credentials:

username:admin@admin.com

password:password

<br>

[Laravel OpenApi Demo Documentation](http://127.0.0.1:8000/api/documentation)

or

website_url/api/documentation
