FROM php:8.1-cli-alpine

WORKDIR /srv/app

# Install dependencies required to compile and run Xdebug
RUN apk add --no-cache \
        $PHPIZE_DEPS \
        libzip-dev \
        libtool \
        autoconf \
        gcc \
        g++ \
        make \
        linux-headers \
        bash \
        curl

# Install PHP extensions
RUN docker-php-ext-install bcmath

# Install Xdebug via PECL and enable it
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=0
ENV COMPOSER_HOME=/tmp

CMD ["sleep", "infinity"]
