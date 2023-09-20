# SONICOM Ecosystem in Laravel

## Requirements

php 8.1
composer 2.2
mailpit

## Install

### Installing an existing Laravel

If you already have Laravel in a git repository, then you will need to run a few things to get the code working.

- Run `npm install`
- Run `php artisan migrate` to create database tables
- Run `php artisan db:seed` to create menu entries

### Installing a fresh Laravel

Install Laravel in an empty repository

	composer create-project laravel/laravel sonicom

or if you have already got laravel installed, then run

	composer install

Set .env `DB_CONNECTION=sqlite`

Install Breeze - a minimal, simple implementation of all of Laravel's authentication features, including login, registration, password reset, email verification, and password confirmation.

	composer require laravel/breeze --dev

	php artisan breeze:install blade

Start the Vite development server to automatically recompile our CSS and refresh the browser when we make changes to our Blade templates

	npm install
	npm run dev

Populate database with Laravel and Breeze default tables:

	php artisan migrate

Other


### Tinker

<https://tighten.com/insights/supercharge-your-laravel-tinker-workflow/>

### Menus

<https://coding-lesson.com/creating-dynamic-nested-menus-in-laravel-with-relationships/>

## Debugging

### Linux

Run

	npm run dev
	php artisan serve

In visual studio code, start a 'Listen for xdebug' session.

### Windows

The same as for Linux

## git

If you are pushing to a remote git repository, then a post-receive hook should run:

* composer install
* php artisan migrate
* php artisan db:seed

## TODO

[ ] remove Chirps directory
[x] rename 'dataset' directory to 'datasets'
[x] add 'delete' to dataset controller
[ ] Check that we're only using software with EUPL
[ ] Allow comments on Database
[ ] User registration must be confirmed by curator!
[ ] Terminology: one user can have many databases, each with a specific dataset definition (datasetdef).
[ ] The datasetdef can be any combination of supported file types (not extensions)
[ ] A datasetdef's file types can have a specific visualisation specified from a supported list

## Place to push stuff when testing git deployment

## Configuration

### Storage

You must map the SONICOM-DATA share to storage/app/public/data

## Structure

### Dataset

A dataset is (like for RADAR) a set of data. We use a table 'dataset' to save information about our datasets. Each dataset has a unique id and a user associated with it. The following can be used to manipulate datasets:

- Dataset model
- Dataset controller


## Database

### Tables

#### datasets

Local datasets which may or may not have been uploaded to RADAR

- title
- description
- uploader_id

#### files

Files associated with datasets which are cached locally.


## Development

### Modifying a table (dropping a table in order to recreate/seed)

- Remove migration entry in 'migrations' table
- Drop table in your sql editor of choice
- Run migration again (php artisan migrate) which will just run those not yet in the migrations table
- Reseed using php artisan db:seed --class=<yourclassseeder, e.g. DatasetSeeder>

