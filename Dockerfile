FROM php:8.3-cli-alpine

WORKDIR /srv/app

RUN apk update && apk add --no-cache bash \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    krb5-dev \
    libxml2-dev \
    libzip-dev \
    libxslt-dev

RUN docker-php-ext-install zip intl bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN printf "alias c='composer'\n" >> ~/.bashrc \
    && source ~/.bashrc
