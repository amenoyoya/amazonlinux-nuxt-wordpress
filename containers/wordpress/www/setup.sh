#!/bin/bash

if [ ! -d './html/cms' ]; then
    # WordPressが未インストールであればダウンロード＆インストール
    mkdir -p ./html/cms
    wget -O - https://ja.wordpress.org/wordpress-5.4.2-ja.tar.gz | tar xzvf - --strip-components 1 -C ./html/cms
    # wp-config.php 更新
    cp ./wp-config.php ./html/cms/
fi

# 環境変数を引き継いで Apache起動
sudo -E apachectl -D FOREGROUND
