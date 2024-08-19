# Gebruik een specifieke Debian-basisimage
FROM debian:latest

# Installeer noodzakelijke tools en dependencies
RUN apt-get update && apt-get install -y curl php-cli unzip git

# Installeer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installeer PHPUnit via Composer
RUN composer global require "phpunit/phpunit=11.3.0" --ignore-platform-req=ext-dom \
--ignore-platform-req=ext-mbstring --ignore-platform-req=ext-xml --ignore-platform-req=ext-xmlwriter \
&& ln -s /root/.composer/vendor/bin/phpunit /usr/local/bin/phpunit

# Zorg ervoor dat de doelmap bestaat
RUN mkdir -p /var/www/html/wordpress-tests/bin

# Kopieer bestanden en tests van lokaal naar de container
COPY ../tests/wp-tests-config.php /var/www/html/wordpress-tests/wp-tests-config.php
COPY ../tests/bin/install-wp-tests.sh /var/www/html/wordpress-tests/bin/install-wp-tests.sh
COPY ../tests/test-sample.php /var/www/html/wordpress-tests/test-sample.php
RUN chmod +x /var/www/html/wordpress-tests/bin/install-wp-tests.sh