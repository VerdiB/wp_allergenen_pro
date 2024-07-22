#!/bin/bash

# tests/bin/install-wp-tests.sh

if [ $# -lt 1 ]; then
  echo "usage: $0 <db-name> [db-user] [db-pass] [db-host] [wp-version]"
  exit 1
fi

DB_NAME=$1
DB_USER=${2:-root}
DB_PASS=${3:-root}
DB_HOST=${4:-localhost}
WP_VERSION=${5:-latest}

# Download WordPress
if [ $WP_VERSION == 'latest' ]; then
  WP_VERSION=$(curl -s https://api.github.com/repos/WordPress/WordPress/tags | jq -r '.[0].name')
fi

mkdir -p /tmp/wordpress/
curl -o /tmp/wordpress/wordpress.tar.gz https://wordpress.org/wordpress-$WP_VERSION.tar.gz
tar -xzf /tmp/wordpress/wordpress.tar.gz -C /tmp/wordpress/

# Create database
mysqladmin create $DB_NAME --user="$DB_USER" --host="$DB_HOST"

# Install WordPress
cp /tmp/wordpress/wordpress/wp-config-sample.php /tmp/wordpress/wordpress/wp-config.php
sed -i "s/database_name_here/$DB_NAME/" /tmp/wordpress/wordpress/wp-config.php
sed -i "s/username_here/$DB_USER/" /tmp/wordpress/wordpress/wp-config.php
sed -i "s/password_here/$DB_PASS/" /tmp/wordpress/wordpress/wp-config.php
sed -i "s/localhost/$DB_HOST/" /tmp/wordpress/wordpress/wp-config.php
