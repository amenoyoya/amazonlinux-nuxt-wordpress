FROM wordpress:latest

# Docker実行ユーザID取得
ARG UID

RUN apt-get update && \
    : '必要なパッケージをインストール' && \
    apt-get install -y wget tar && \
    : 'Rewriteエンジン有効化' && \
    a2enmod rewrite && \
    : 'www-data ユーザの UID をDocker実行ユーザのものに合わせる' && \
    usermod -o -u $UID www-data && groupmod -o -g $UID www-data && \
    : 'www-data ユーザで sudo 実行可能に' && \
    apt-get install sudo && \
    echo 'www-data ALL=NOPASSWD: ALL' >> '/etc/sudoers' && \
    : 'ドキュメントルートを www-data ユーザ所有に変更' && \
    chown -R www-data:www-data /var/www/

# 作業者: www-data
USER www-data

# 作業ディレクトリ
WORKDIR /var/www/html/
    
# WordPressをセットアップし Apache 起動
ENTRYPOINT ["bash", "setup_wordpress.sh"]
