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
if echo "$changed_files" | grep -qE '^laravel/database/seeders/Static/.*\.php'; then
	echo "Modified static seeders found. Running database static seeders..."
	php artisan db:seed --class StaticSeeders
else
	echo "No static seeder updates found. Skipping seeder step."
fi

# Check for changes in config files
if echo "$changed_files" | grep -qE '^laravel/config/.*\.php'; then
	echo "Config changes detected. Recaching config..."
	php artisan config:cache
else
	echo "No config changes detected. Skipping config cache."
fi
# Check for changes in route files
if echo "$changed_files" | grep -qE '^laravel/route/.*\.php'; then
	echo "Route changes detected. Recaching route..."
	php artisan route:cache
else
	echo "No route changes detected. Skipping route cache."
fi
# Check for changes in view files
if echo "$changed_files" | grep -qE '^laravel/resources/views/.*\.php'; then
	echo "View changes detected. Recaching views..."
	php artisan view:cache
else
	echo "No view changes detected. Skipping view cache."
fi
# Check if event files changed in the merge
if echo "$changed_files" | grep -qE '^laravel/app/Events/.*\.php'; then
  echo "Event files changed. Caching events..."
  php artisan event:cache
else
  echo "No event file changes detected. Skipping event cache."
fi

echo "Running ./artisan storage:unlink && ./artisan storage:link"
./artisan storage:unlink && ./artisan storage:link

echo "Running 'npm install and npm run build'"
npm install && npm run build

if [ -f /usr/local/bin/laravel-fix-perms ] ; then
	echo "Setting www-data ACLs with /usr/local/bin/laravel-fix-perms"
	echo "Running 'sudo /usr/local/bin/laravel-fix-perms $(realpath ../laravel)'"
	sudo /usr/local/bin/laravel-fix-perms $(realpath ../laravel)
fi

echo "Restarting all queues with 'sudo supervisorctl restart all'"
sudo supervisorctl restart all

cd ..
