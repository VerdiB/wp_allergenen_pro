# phpunit/Dockerfile

FROM wordpress:cli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php --install-dir=/usr/local/bin --filename=composer

# Install PHPUnit
RUN composer global require "phpunit/phpunit=^9.5" && ln -s /root/.composer/vendor/bin/phpunit /usr/local/bin/phpunit

# Zorg ervoor dat de doelmap bestaat
RUN mkdir -p /var/www/html/wordpress-tests/bin

# Kopieer bestanden naar de container
COPY ../tests/wp-tests-config.php /var/www/html/wordpress-tests/wp-tests-config.php
COPY ../tests/bin/install-wp-tests.sh /var/www/html/wordpress-tests/bin/install-wp-tests.sh
RUN chmod +x /var/www/html/wordpress-tests/bin/install-wp-tests.sh