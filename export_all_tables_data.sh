#!/bin/bash

# Define your MySQL connection parameters
DB_HOST="localhost"
DB_USER="root"
DB_PASSWORD="fan_club"
DB_NAME="site"

# Define the output file
OUTPUT_FILE="all_tables_data.txt"

# Clear the output file before appending new data
> "$OUTPUT_FILE"

# Get list of all tables
TABLES=$(mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" -N -B -e "SHOW TABLES" "$DB_NAME")

# Loop through each table and execute SELECT * query
for TABLE in $TABLES; do
    echo "Processing table: $TABLE"
    echo "Table: $TABLE" >> "$OUTPUT_FILE" # Add table name to output file
    echo "----------------------" >> "$OUTPUT_FILE" # Add separator
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" -N -B -e "SELECT * FROM $TABLE" "$DB_NAME" >> "$OUTPUT_FILE"
    echo "----------------------" >> "$OUTPUT_FILE" # Add separator between tables
done

echo "All tables data has been exported to $OUTPUT_FILE"

