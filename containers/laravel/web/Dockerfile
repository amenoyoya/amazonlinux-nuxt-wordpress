# php7とApacheがパックされたイメージを使う
## php:7-apacheイメージはDebianベースのため、設定はDebian系の設定方法に従う
FROM php:7-apache

# set working directory
WORKDIR /var/www/laravel

# -- php settings --
# php で必要なものを入れておく
RUN apt-get update && apt-get install -y unzip git && \
    docker-php-ext-install pdo_mysql mysqli mbstring

# -- Composer settings --
# composerインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Composerをrootユーザーで実行可能にする
ENV COMPOSER_ALLOW_SUPERUSER 1
# Composerのグローバルパッケージのインストール先を変更
ENV COMPOSER_HOME /composer
# ComposerのグローバルパッケージへのPATHを通す
ENV PATH $PATH:/composer/vendor/bin

# -- apache settings --
# mod_rewrite有効化
RUN a2enmod rewrite

# ※Apacheは、COPYやRUNの後に起動するようなので再起動は不要
# Apache再起動
#RUN service apache2 restart
