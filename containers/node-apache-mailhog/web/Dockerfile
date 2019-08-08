FROM php:7.2-apache

RUN apt-get update
RUN apt-get install -y \
    curl \
    git \
    libicu-dev \
    mailutils \
    unzip \
    vim

RUN docker-php-ext-install intl pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

# sendmail -> mhsendmail 置き換え
RUN curl -sSLO https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail_linux_amd64 \
    && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail
