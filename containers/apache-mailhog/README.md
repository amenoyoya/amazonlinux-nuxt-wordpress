# node-apache-mailhog

## Environment

- OS:
    - Ubuntu 18.04
    - Windows 10
- Docker: 19.03.5
    - docker-compose: 1.24.0

### Structure
```bash
./
|_ docker/ # Dockerコンテナ設定
|   |_ certs/ # SSL証明書格納ディレクトリ
|   |_ db/    # dbコンテナ
|   |   |_ dump/            # => docker://db:/var/dump/ にマウントされる
|   |   |_ initdb.d/        # 初期流し込み用SQLファイル格納ディレクトリ
|   |   |_ Dockerfile       # ビルド設定
|   |   |_ my.cnf           # MySQL設定ファイル
|   |
|   |_ web/   # webコンテナ
|   |   |_ Dockerfile       # ビルド設定
|   |   |_ 000-default.conf # Apacheデフォルト設定ファイル
|   |   |_ php.ini          # PHP設定ファイル
|   |
|   |_ docker-compose.handlebars # docker-compose.yml のテンプレートファイル
|
|_ web-data/
|   |_ index.php      # DocumentRoot
|
|_ docker-compose.yml # Docker構成ファイル
|                     ## webコンテナ: php:7.3-apache | https://web.local/ => docker://web:80
|                     ## dbコンテナ: mysql:5.7
|                     ## pmaコンテナ: phpmyadmin:latest | https://pma.web.local/ => docker://pma:80
|                     ## mailhogコンテナ: mailhog/mailhog | https://mail.web.local/ => docker://mailhog:8025
|                     ## nginx-proxyコンテナ: jwilder/nginx-proxy | vhostルーティング用プロキシ
|                     ## letsencryptコンテナ: jrcs/letsencrypt-nginx-proxy-companion | 無料SSL発行用
|_ handledocker.js    # 環境に合わせて docker-compose.yml を生成するスクリプト
```

![apache-mailhog.png](https://github.com/amenoyoya/docker-collection/blob/master/img/apache-mailhog.png?raw=true)

### コンテナ起動
```bash
# Docker実行ユーザIDを合わせてDockerコンテナビルド
$ export UID && docker-compose build

# コンテナ起動
$ export UID && docker-compose up -d

## => https://web.local/ でサーバ稼働
```

### 本番公開時
```bash
# -- user@server

# masterブランチ pull
$ pull origin master

# docker-compose.yml の変更を無視
$ git update-index --assume-unchanged docker-compose.yml

# 本番公開用の docker-compose.yml 作成
## --host <ドメイン名>: 公開ドメイン名
## --email <メールアドレス>: Let's Encrypt 申請用メールアドレス（省略時: admin@<ドメイン名>）
## --dbpass <データベースパスワード>: 省略時は `root`
## +noproxy: 複数のDockerComposeで運用していて nginx-proxy, letsencrypt コンテナが別に定義されている場合に指定
$ node handledocker.js --host yourdomain.com --email yourmail@yourdomain.com +noproxy

# Docker実行ユーザIDを合わせてDockerコンテナビルド
$ export UID && docker-compose build

# コンテナ起動
$ export UID && docker-compose up -d
```

***

## 共有ディレクトリのパーミッション問題

- 参考: [README.md](../../README.md)
- 本プロジェクトは `./wwww/html` と `docker://web///var/www/html` が共有ディレクトリとなっている
- アップロードファイル保存のため webコンテナ内のApache実行ユーザー（`www-data`）が共有ディレクトリに書き込みできる必要がある
- 普通にDockerを起動してしまうとパーミッションの問題で www-data ユーザーが共有ディレクトリにファイルを書き込みできない

### 解決策
- 試したこと
    1. `/etc/passwd` と `/etc/group` を READ-ONLY でマウント
        ```yaml
        web:
            volumes:
                - ./www/html:/var/www/html # ドキュメントルート
                - ./web/000-default.conf:/etc/apache2/sites-available/000-default.conf
                - ./web/php.ini:/usr/local/etc/php/php.ini
                - /etc/passwd:/etc/passwd:ro # read_only(ro)で passwd を共有
                - /etc/group:/etc/group:ro # read_only(ro)で group を共有
        ```
        - ホストに `www-data` ユーザーがいないと、webコンテナを起動できないためダメ
    2. webコンテナ起動時に wwww-data ユーザーのIDを Docker実行ユーザーのIDに変更する
        ```yaml
        web:
            command: bash -c 'usermod -o -u ${UID} www-data; groupmod -o -g ${UID} www-data; apachectl -D FOREGROUND'
        ```
        - 今回の場合、この方法でうまく行った
        - Dockerコンテナ起動時、UIDを環境変数にexportしなければならない
            ```bash
            $ export UID && docker-compose up -d
            ```
