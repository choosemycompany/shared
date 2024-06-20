FROM php:8.1-cli-alpine

WORKDIR /srv/app

RUN docker-php-ext-install bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=0
ENV COMPOSER_HOME=/tmp

CMD ["sleep", "infinity"]
