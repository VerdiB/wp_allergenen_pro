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
if [ "$WP_VERSION" == 'latest' ]; then
  WP_VERSION=$(curl -s https://api.github.com/repos/WordPress/WordPress/tags | jq -r '.[0].name')
fi

# Maak de map aan
mkdir -p /tmp/wordpress/

# Download WordPress met controle op de juiste URL
curl -L -o /tmp/wordpress/wordpress.tar.gz https://wordpress.org/wordpress-$WP_VERSION.tar.gz

# Controleer of het gedownloade bestand echt een gzip-bestand is
if file /tmp/wordpress/wordpress.tar.gz | grep -q gzip; then
  tar -xzf /tmp/wordpress/wordpress.tar.gz -C /tmp/wordpress/
else
  echo "Error: Downloaded file is not in gzip format. Exiting."
  exit 1
fi

# Controleer of mysqladmin beschikbaar is, anders installeer het
if ! command -v mysqladmin &> /dev/null; then
  apt-get update && apt-get install -y mysql-client
fi

# Create database
mysqladmin create "$DB_NAME" --user="$DB_USER" --host="$DB_HOST" --password="$DB_PASS"

# Install WordPress
cp /tmp/wordpress/wordpress/wp-config-sample.php /tmp/wordpress/wordpress/wp-config.php
sed -i "s/database_name_here/$DB_NAME/" /tmp/wordpress/wordpress/wp-config.php
sed -i "s/username_here/$DB_USER/" /tmp/wordpress/wordpress/wp-config.php
sed -i "s/password_here/$DB_PASS/" /tmp/wordpress/wordpress/wp-config.php
sed -i "s/localhost/$DB_HOST/" /tmp/wordpress/wordpress/wp-config.php