FROM php:7.4-cli

# composer インストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV PATH $PATH:~/.composer/vendor/bin

# 環境変数 UID を取得
ARG UID

# 必要なパッケージ類をインストール
RUN apt-get update && apt-get install -y \
        wget curl git vim libicu-dev \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
        zlib1g-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip intl \
    && : 'ホスト側カレントユーザと同一IDのユーザが存在しない場合は新規作成' \
    && if [ "$(getent passwd $UID)" = "" ]; then useradd -m -u $UID worker && chown -R worker /home/worker/; fi \
    && : 'キャッシュ削除' \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 作業ディレクトリ: host:./ => service://php:/work/
WORKDIR /work/

# ホスト側カレントユーザと同一IDのユーザを作業者に指定
## ファイルパーミッション問題の対策
USER $UID
