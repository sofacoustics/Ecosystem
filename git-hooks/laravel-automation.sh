#!/bin/sh

echo "Running 'laravel-automation.sh'"

# Get the current branch name
branch_name=$(git branch | grep "*" | sed "s/\* //")
echo "branch: $branch_name"

# we should be in the root folder of the git repository
cd laravel
echo "pwd: $(pwd)"

echo "<?php return ['string' => '$(git describe --tags)'];" > config/version.php

echo "Running 'composer install'"
composer install

# Fix storage/cache ACLs (www-data + sonicom) BEFORE anything below writes to
# storage/framework/cache: migrations, static seeders (spatie permission cache)
# and 'artisan optimize' (laravel-data structure cache). Otherwise cache
# subdirectories created by www-data without sonicom ACLs make these steps fail
# with "file_put_contents(...): No such file or directory".
# Files created later (public/build, storage link) inherit the default ACLs set
# by the script, so a second run at the end is not needed.
if [ -f /usr/local/bin/laravel-fix-perms ] ; then
	echo "Setting www-data ACLs with /usr/local/bin/laravel-fix-perms"
	echo "Running 'sudo /usr/local/bin/laravel-fix-perms $(realpath ../laravel)'"
	sudo /usr/local/bin/laravel-fix-perms $(realpath ../laravel)
fi

changed_files="$(git diff --name-only HEAD@{1} HEAD)"
echo "changed_files: $changed_files"

# Check for new migration files
if echo "$changed_files" | grep -qE '^laravel/database/migrations/.*\.php'; then
	echo "New migrations found. Running database migrations..."
	php artisan migrate --force
else
	echo "No new migrations found. Skipping migration step."
fi
# Check for static seeder modifications
if echo "$changed_files" | grep -qE '^laravel/database/seeders/Static(/.*)?\.php'; then
	echo "Modified static seeders found. Running database static seeders..."
	php artisan db:seed --force --class StaticSeeder
else
	echo "No static seeder updates found. Skipping seeder step."
fi

# Always clear and rebuild the Laravel caches (config, events, routes, views).
# Changes outside config/, routes/ etc. also affect the caches, e.g. package
# updates via composer.lock (stale Livewire route -> 404 for livewire.js) or
# config/version.php, which is regenerated on every run.
echo "Clearing and rebuilding Laravel caches..."
php artisan optimize:clear
php artisan optimize

echo "Running ./artisan storage:unlink && ./artisan storage:link"
./artisan storage:unlink && ./artisan storage:link

echo "Running 'npm ci and npm run build'"
npm ci && npm run build

echo "Restarting all queues with 'sudo supervisorctl restart all'"
sudo supervisorctl restart all

cd ..
