#!/bin/bash

# Define database connection parameters
DB_HOST="localhost"
DB_USER="hag"
DB_PASS="fan_club"
DB_NAME="site"
BACKUP_DIR="/var/www/html/backup"
DATE=$(date +%Y%m%d%H%M%S)
BACKUP_FILE="$BACKUP_DIR/$DB_NAME-$DATE.sql"

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

# Create tables and import data from create.sql
mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < create.sql
sudo mariadb create < create.sql
echo 'create.sql'
if [ $? -eq 0 ]; then 
    echo ' : success'
else 
    echo ' : failed'
    exit 1
fi
mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > backup.sql
sudo mariadb backup < backup.sql
echo 'backup.sql'
if [ $? -eq 0 ]; then 
    echo ' : success'
else 
    echo ' : failed'
    exit 1
fi
