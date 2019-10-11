FROM php:7.3-fpm-alpine

WORKDIR /var/www/html

RUN apk update && \
    apk add --no-cache libzip-dev bash && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip && \
    docker-php-ext-install mysqli pdo pdo_mysql

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./ /var/www/html/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-scripts

ENV PATH $PATH:/var/www/html/vendor/bin

ENTRYPOINT ["./entrypoint-dev"]
