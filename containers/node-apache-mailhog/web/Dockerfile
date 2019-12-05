FROM php:7.2-apache

WORKDIR /var/www/html

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    : 'Install dependent packages' && \
    apt-get install -y curl git libicu-dev mailutils unzip vim && \
    : 'Install external PHP modules' && \
    docker-php-ext-install intl pdo pdo_mysql && \
    : 'Install nodejs' && \
    curl -sL https://deb.nodesource.com/setup_10.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn && \
    : 'Enable rewrite engine' && \
    a2enmod rewrite && \
    : 'Exchange: sendmail -> mhsendmail' && \
    curl -sSLO https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 && \
    chmod +x mhsendmail_linux_amd64 && \
    mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail
