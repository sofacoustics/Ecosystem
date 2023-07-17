# SONICOM Ecosystem in Laravel

## Requirements

composer
mailpit

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

Other
### Tinker

<https://tighten.com/insights/supercharge-your-laravel-tinker-workflow/>

### Menus

<https://coding-lesson.com/creating-dynamic-nested-menus-in-laravel-with-relationships/>
