#!/bin/bash
# scripts/fix-perms.sh
#
# This script set's the permissions for the www-data user using ACLs
# This can then be run via the ansible role on initial deployment, but
# also on post-merge after running composer install.
#
# jw:note This is currently just for testing.

# Get the directory where this script is located
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Define the laravel path relative to the script directory
# Since scripts/ is next to laravel/, we go up one level then into laravel/
PROJECT_PATH="$SCRIPT_DIR/../laravel"
WWW_USER="www-data"

echo "Applying ACLs to $PROJECT_PATH..."

# 1. Base permissions for the whole project (Read/Execute for folders)
# We use -P to ensure we don't follow symlinks unless intended
setfacl -R -m u:$WWW_USER:rX "$PROJECT_PATH"

# 2. Force 'rx' on the vendor folder (Lowercase x for stubborn files)
if [ -d "$PROJECT_PATH/vendor" ]; then
    setfacl -R -m u:$WWW_USER:rx "$PROJECT_PATH/vendor"
fi

# 3. Write access for storage and cache
if [ -d "$PROJECT_PATH/storage" ]; then
    setfacl -R -m u:$WWW_USER:rwx "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache"
    setfacl -Rd -m u:$WWW_USER:rwx "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache"
fi

echo "Permissions fixed successfully."
