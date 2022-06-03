FROM phpdockerio/php:8.1-fpm

ADD . /src

RUN chown -R www-data:www-data /src

WORKDIR /src/src

RUN apt-get update; \
    apt-get -y --no-install-recommends install php8.1-xdebug; \
    apt-get -y --no-install-recommends install php-sqlite3; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
