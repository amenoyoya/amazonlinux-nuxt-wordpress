FROM php:7.1-apache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update -y && \
    apt-get install -y curl git libicu-dev mailutils unzip vim zlib1g-dev && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    docker-php-ext-install pdo_mysql mysqli mbstring zip && \
    a2enmod rewrite && \
    : 'Exchange: sendmail -> mhsendmail' && \
    curl -sSLO https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 && \
    chmod +x mhsendmail_linux_amd64 && \
    mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

WORKDIR /var/www/web-data/
