# Gebruik een specifieke Debian-basisimage
FROM debian:latest


# Installeer noodzakelijke tools en dependencies
RUN apt-get update && apt-get install -y curl \
php-cli \
php-xml \
php-mbstring \
unzip


# Installeer Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
&& php -r "unlink('composer-setup.php');"

# Voeg composer to aan het PATH
ENV PATH="$PATH:/root/.composer/vendor/bin"
# Voeg wp test config aan het PATH
ENV WP_TESTS_CONFIG="$PATH/root/.composer/vendor/bin/wp-tests-config.php"

# Installeer PHPUnit via Composer
RUN composer global require --dev phpunit/phpunit ^9 \
&& ln -s /root/.composer/vendor/bin/phpunit /usr/local/bin/phpunit

#  voeg config aan root toe
COPY ../tests/wp-tests-config.php /root/.composer/vendor/bin/wp-tests-config.php