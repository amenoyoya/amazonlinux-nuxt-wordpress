#!/bin/bash
# -- sudo www-data@docker://web/

# 環境変数 UID が与えられていれば www-data ユーザIDを $UID に合わせる
if [ "$UID" != "" ]; then
    # uid = $UID のダミーユーザ作成
    # if [ "$(getent passwd $UID)" = "" ]; then
    #     useradd -u $UID -g $UID myuser
    # fi
    # www-data ユーザIDを変更
    usermod -u $UID www-data
    # www-data のホームディレクトリのパーミッション修正
    chown -R www-data:www-data /var/www/
fi

# ~/.msmtprc のパーミッション修正
chown www-data:www-data /var/www/.msmtprc
chmod 600 /var/www/.msmtprc

# プロジェクト準備: setup.sh を編集することで任意のテンプレートを準備可能
if [ ! -d './app' ]; then
    source ./setup.sh
fi

# Apache をフォアグランドで起動
a2enmod rewrite
a2enmod headers
a2enmod ssl
apachectl -D FOREGROUND
