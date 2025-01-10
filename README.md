# SONICOM Ecosystem in Laravel

## Requirements

php 8.1
php8.1-xml
php8.1-curl
php8.1-sqlite3
composer 2.2
mailpit
nodejs
npm
supervisor

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

You can create objects on the command line: <https://medium.com/@miladev95/laravel-tinker-1035402993c3>

You can dispatch jobs: <https://dev.to/jiteshdhamaniya/dispatch-a-job-from-tinker-in-laravel-13o7>

### Menus

<https://coding-lesson.com/creating-dynamic-nested-menus-in-laravel-with-relationships/>

## certbot

Managed to generate a certificate for a :80 site using the following:

    certbot certonly --webroot -w /var/www/sonicom.amtoolbox.org -d sonicom.amtoolbox.org

Test renewal:

    certbot renew --dry-run

## Ansible

There is an Ansible role 'sonicom-ecosystem' which can be used to set up the proxy and the backend web servers. This is currently in the ISF SVN repository svn-scripts

## Debugging

### Linux

Run

	npm run dev
	php artisan serve

In visual studio code, start a 'Listen for xdebug' session.

You have to make sure that the xdebug module is loaded. check `php -m` to see if it is. You can possibly enable it by adding `zend_extension=xdebug` to e.g. /etc/php/8.2/cli/php.ini
Add an [xdebug] section to /etc/php/8.2/cli/php.ini with at minimum the following:

    xdebug]
    xdebug.mode=debug
    xdebug.start_with_request=yes

You can check the xdebug information via the php command `xdebug_info()` (much like `php_info()`).

### Windows

The same as for Linux

### WSL

Got xdebug working with the following php.ini configuration

	[Xdebug]
	;zend_extension=xdebug
	xdebug.remote_enable=on
	xdebug.remote_autostart=1;
	xdebug.log=/tmp/xdebug.log
	xdebug.mode=debug
	xdebug.start_with_request=yes

And the default .launch Listen for debug

	{
		"name": "Listen for Xdebug",
		"type": "php",
		"request": "launch",
		"port": 9003
	},

## git

If you are pushing to a remote git repository, then a post-receive hook should run:

* composer install
* php artisan migrate
* php artisan db:seed

## TODO

- [ ] remove Chirps directory
- [x] rename 'dataset' directory to 'datasets'
- [x] add 'delete' to dataset controller
- [ ] Check that we're only using software with EUPL
- [ ] Allow comments on Database
- [ ] User registration must be confirmed by curator!
- [ ] Terminology: one user can have many databases, each with a specific dataset definition (datasetdef).
- [ ] The datasetdef can be any combination of supported file types (not extensions)
- [ ] A datasetdef's file types can have a specific visualisation specified from a supported list
- [ ] Let user specify file nomancleture when defining datasetdef
- [ ] Start using github issues
- [ ] Users: Add users to specific group on creation
- [ ] Implement Sub menus
- [x] Test filament (https://filamentphp.com/docs) for database table views
- [ ] PM: Provide examples to JS (Database/Dataset/Files, with links for visu and with cached visu results, and metadata [which?])
- [ ] PM: provide a list of files to be processed to JS
- [ ] PM: check which metadata will be stored in the database (=information quickly available without loading the dataset files)
- [x] JW: rename 'title' column to 'name' in Database table
- [x] JW: Add RADAR credentials to ansible role env creation.
- [x] JW: Put RADAR URL and workspace in .env file (and ansible)
- [ ] JW: Implement dataset definition mask and dataset upload
- [ ] JW: Add additional subjectAreas with button
- [ ] JW: Implement ORCID and ROR schemes
- [ ] JW: Check that reverb / supervisorctl tasks work for multiple sites on the same machine
- [ ] JW: Add git revision / branch to about.
- [ ] JW: Add supervisorctl for npm run dev (https://stackoverflow.com/questions/28108728/how-to-npm-start-with-supervisord)

## Place to push stuff when testing git deployment

## Configuration

### Reverse Proxy

You must set the trusted proxies in app/Http/Middleware/TrustedProxies.php, otherwise URLs are not rewritten correctly

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


## Octave

Note that the snap package wouldn't run the script - can't find nc-config

Modify /etc/octaverc on Ubuntu to add paths

### Problems

## Terminology

### database

A 'database' is a collection of data in a specific collation of formats. E.g. a collection of HRTF data, where each database record contains one HRTF and one PNG.

- It has a title
- It is uploaded/managed by one user
- It can contain any number of datasets as defined in it's dataset definition ('datasetdef').
- May be linked to a RADAR dataset
- Will be linked to visualisations and tools via its datasetdef.

### dataset

A 'dataset' is one record in the database. One record may contain multiple files of different datatypes. The exact composition of a 'dataset' is defined in the database's 'datasetdef'.

### datatype

A 'datatype' is one of a predefined type of data which can be assigned to a datasetdef. E.g. there may be 'HRTF', 'PNG',of 'CSV'.

- It has a title. E.g. 'HRTF'
- It may be associated tools (e.g. Sofatoolbox)
- It may be associated visualisations (e.g. octave sofa ...)

### datasetdef

A 'datasetdef' is a definition of which datatypes are in each dataset. E.g. a dataaset may contain two different hrtfs and one png per record.

### datafile

A datafile associated with a dataset. E.g. one SOFA file or one PNG

## Laravel

### Logs

Log files are found in laravel/storage/logs

- reverb.log
- worker.log
- laravel.log

### artisan

#### database

Drop all tables and reseed database with:

	php artisan migrate:fresh --seed

### events/listeners/observers/jobs

You can observe for database changes in an 'Observer' and dispatch a Job from that observer.
An alternative would be to use a 'listener' and an 'event'.

<https://www.slingacademy.com/article/observers-and-event-listeners-in-laravel-a-practical-guide/#Setting_Up_Observers>

If you want to *debug* a job in a queue, you need to use the 'sync' QUEUE_CONNECTION, since the php debugger won't stop in the event/listener/job code, if you use, e.g., 'database'

### Filament

Filament is a framework/stack with a panel, table and form builder. This may make it *much* easier to create interfaces to database tables.

Create a resource for a table with

	php artisan make:filament-resource Dataset --generate

Note that for the FileUpload, the upload_max_filesize php setting must be large enough!

### Laravel Blade Components

<https://dev.to/jump24/laravel-blade-components-3hbh>

You can format blade templates using the following tool: <https://github.com/shufo/blade-formatter>

e.g. `node_modules/.bin/blade-formatter --write resources/views/livewire/radar/dataset.blade.php`

### Reverb

<https://redberry.international/laravel-reverb-real-time-communication/>
<https://novu.co/blog/the-ultimate-guide-to-laravel-reverb/>

Event classes appear to need to the 'implements ShouldBroadcast'.

#### Nested Resources

https://laraveldaily.com/post/filament-v3-nested-resources-trait-pages
https://github.com/GuavaCZ/filament-nested-resources
<https://laraveldaily.com/post/nested-resource-controllers-and-routes-laravel-crud-example>


## Services

These services must run for everything to work:

### Reverb

The reverb server should be running via supervisorctl

## Deploy

You can deploy to the test server <http://sonicom-test.kfs.oeaw.ac.at> which will do a composer install and artisan migrate:fresh --seed in the post-receive hook

	git push sonicom-test

## RADAR

XML Schema: <https://www.radar-service.eu/schemas/descriptive/radar/v09/radar-elements>

### RADAR models

Create a model with it's migration and seeder with the following command:

    ./artisan make:model -m -s <modelname-in-the-singular>

## Livewire

You can create a Livewire form and component (https://fly.io/laravel-bytes/livewire-3-forms/) so you can submit the form without leaving the page.

## Sites

### Development Server

URL: <https://sonicom.amtoolbox.org>
Host: ubuntu-vm-1.kfs.oeaw.ac.at


## Firewall

The following ports should be open:


Note: these ports were open on ubuntu-vm-1 before debugging with Gerhard Bauer:

root@ubuntu-vm-1:~# ufw status
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     LIMIT       193.171.195.0/25           # Ansible role linux-defaults ISF SSH (default)
22/tcp                     LIMIT       10.4.245.0/25              # Ansible role linux-defaults ISF SSH (default)
22/tcp                     LIMIT       10.4.21.0/24               # Ansible role linux-defaults ISF SSH (default)
80/tcp                     ALLOW       Anywhere                   # Ansible role apache2 HTTP
443/tcp                    ALLOW       Anywhere                   # Ansible role apache2 HTTPS
3389                       ALLOW       10.4.21.0/24               # Ansible role linux-defaults RDP (ubuntu-vm-1)
3389                       ALLOW       10.4.245.0/25              # Ansible role linux-defaults RDP (ubuntu-vm-1)
3389                       ALLOW       193.171.195.0/25           # Ansible role linux-defaults RDP (ubuntu-vm-1)
1022                       ALLOW       Anywhere
8000                       ALLOW       Anywhere
5173                       ALLOW       Anywhere
2049                       ALLOW       Anywhere
111                        ALLOW       Anywhere
8080                       ALLOW       Anywhere
8081                       ALLOW       Anywhere
1433                       ALLOW       Anywhere
80/tcp (v6)                ALLOW       Anywhere (v6)              # Ansible role apache2 HTTP
443/tcp (v6)               ALLOW       Anywhere (v6)              # Ansible role apache2 HTTPS
1022 (v6)                  ALLOW       Anywhere (v6)
8000 (v6)                  ALLOW       Anywhere (v6)
5173 (v6)                  ALLOW       Anywhere (v6)
2049 (v6)                  ALLOW       Anywhere (v6)
111 (v6)                   ALLOW       Anywhere (v6)
8080 (v6)                  ALLOW       Anywhere (v6)
8081 (v6)                  ALLOW       Anywhere (v6)
1433 (v6)                  ALLOW       Anywhere (v6)


a
a
a
a
a
a
a
