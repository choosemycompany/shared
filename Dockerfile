FROM php:8.3-apache as base-stage

WORKDIR /srv/app/choosemycompany_shared

RUN apt-get update && apt-get install -y sudo \
    git \
    unzip \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    libicu-dev \
    libonig-dev \
    libkrb5-dev \
    libxml2-dev \
    libzip-dev \
    librabbitmq-dev \
    libxslt-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-install zip intl bcmath mbstring \
    && docker-php-ext-enable intl mbstring

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN printf "alias s='php bin/console'\n\
alias c='composer'" >> ~/.bashrc

RUN apt-get clean
