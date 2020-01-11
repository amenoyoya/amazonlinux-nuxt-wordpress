FROM php:7.3-apache

# 作業ディレクトリ: /var/www/html/
## = コンテナ内作業のカレントディレクトリ
WORKDIR /var/www/html/

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    : 'Install apt packages' && \
    apt-get install -y curl git libicu-dev unzip && \
    : 'Enable rewrite engine' && \
    a2enmod rewrite
