LARAVEL 8 dengan JWT 

1. Install all the dependencies using composer

composer install

2. Copy the example env file and make the required configuration changes in the .env file

cp .env.example .env

3. Generate a new application key

php artisan key:generate

4. Generate a new JWT authentication secret key

php artisan jwt:secret

5. Run the database migrations (Set the database connection in .env before migrating)

php artisan migrate

5. Start the local development server

php artisan serve

6. you can open folder postman or thunder client for URL REST API and register or sign up in front end website