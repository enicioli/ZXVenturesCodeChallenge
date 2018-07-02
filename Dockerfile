FROM php:7.1-fpm

ENV DEBIAN_FRONTEND=noninteractive APP_PATH=/var/www

COPY . $APP_PATH
WORKDIR $APP_PATH

RUN apt-get update && \
    apt-get install -y supervisor git libpng-dev libxml++2.6-dev libssh2-1-dev libaio1 libpq-dev libcurl4-openssl-dev pkg-config libssl-dev && \
    docker-php-ext-install soap zip gd && \
    pecl install ssh2-1-0 channel://pecl.php.net/ssh2-1.0 && \
    echo extension=ssh2.so > /usr/local/etc/php/php.ini && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    ./bin/composer.phar install --no-scripts && \
    cp ./docker-deps/supervisord.conf /etc/supervisor/conf.d/

EXPOSE 80
CMD ["./docker-deps/start-app.sh"]
