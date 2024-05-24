#!/bin/bash

# Define variables
REMOTE_USER="hag"   
REMOTE_HOST="192.168.1.101" 
REMOTE_DIR="/Users/user/Downloads/school/"   # Remove extra space
FILE_PATH="/var/www/html/all_tables_data.txt"  

# Use scp to copy the file to the remote server
scp "$FILE_PATH" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_DIR"

