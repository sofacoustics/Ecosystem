#!/bin/bash
# scripts/fix-perms.sh
#
# Set the laravel directories permissios for the www-data user using ACLs
#
# This is deployed via ansible to /usr/local/bin/laravel-fix-perms
# where it can only be called by the sonicom user using 'sudo' with the 
# path to the local laravel repo 
# 
# E.g. sudo /usr/local/bin/laravel-fix-perms /var/www/isf-sonicom-laravel/laravel
#
# jw:note just testing at the moment

TARGET_PATH="${1}"

# Safety: Ensure an argument was actually passed
if [ -z "$TARGET_PATH" ]; then
    echo "Usage: $0 <path>"
    exit 1
fi

PROJECT_PATH=$(realpath "$TARGET_PATH")
WWW_USER="www-data"
DEPLOY_USER="sonicom"

echo "Converging permissions at: $PROJECT_PATH"

# Apply permissions (Generic commands)
setfacl -R -m u:$WWW_USER:rX "$PROJECT_PATH"
if [ -d "$PROJECT_PATH/vendor" ]; then
    setfacl -R -m u:$WWW_USER:rx "$PROJECT_PATH/vendor"
fi
if [ -d "$PROJECT_PATH/storage" ]; then
    setfacl -R -m u:$WWW_USER:rwx,u:$DEPLOY_USER:rwx "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache"
    setfacl -Rd -m u:$WWW_USER:rwx,u:$DEPLOY_USER:rwx "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache"
fi
