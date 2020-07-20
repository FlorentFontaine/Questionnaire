FROM php:7.4-fpm

# Install mysql extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update
RUN apt-get install -y libzip-dev zip
RUN docker-php-ext-install mysqli pdo pdo_mysql zip


# Install misc components
RUN apt-get install -y git unzip

# Install composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer

# Install npm
RUN apt-get install -y curl software-properties-common
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

WORKDIR /home/www/eval_symfony
