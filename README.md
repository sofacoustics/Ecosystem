# SONICOM Ecosystem in Laravel

## Install 

Install Laravel

	composer create-project laravel/laravel sonicom

Set .env `DB_CONNECTION=sqlite`

Install Breeze - a minimal, simple implementation of all of Laravel's authentication features, including login, registration, password reset, email verification, and password confirmation.

	composer require laravel/breeze --dev
 
	php artisan breeze:install blade

Start the Vite development server to automatically recompile our CSS and refresh the browser when we make changes to our Blade templates

	npm run dev 

Populate database with Laravel and Breeze default tables:

	php artisan migrate

## Design

### Routes

Here is a list of what sort of URLs the site will support:

#### User

/user/login
/user/logout
/user/profile

####
