FROM python:3.7-alpine

# 作業ディレクトリを変数`project_dir`に格納
ARG project_dir=/var/www/app/

# Docker実行ユーザID取得
## Dockerコンテナ起動時 `export UID` で渡されたユーザIDが格納される
ARG UID

# 必要なファイルをコンテナ内にコピー
COPY requirements.txt $project_dir

# 作業ディレクトリに移動
WORKDIR $project_dir

RUN : 'uWSGIビルド用ツール導入' && \
    apk add gcc build-base linux-headers && \
    : 'requirements.txtに記載されたパッケージをインストール' && \
    pip install --upgrade pip && \
    pip install -r requirements.txt && \
    : 'www-dataユーザ追加（ユーザIDをDocker実行ユーザIDと合わせる）' && \
    adduser -u $UID -S www-data && \
    : 'www-dataユーザをsudoersに追加' && \
    echo 'www-data ALL=(ALL) NOPASSWORD:ALL' >> /etc/sudoers && \
    : 'www-dataユーザのパスワードを`password`に変更' && \
    echo 'www-data:password' | chpasswd && \
    : '作業ディレクトリのパーミッション変更' && \
    chown -R www-data $project_dir

# 作業者: www-dataユーザ
USER www-data

# uWSGI Emperor を使って複数のFlaskサーバーを起動できるようにする
CMD ["uwsgi", "--emperor", "/var/www/app/vassals/*.ini"]
