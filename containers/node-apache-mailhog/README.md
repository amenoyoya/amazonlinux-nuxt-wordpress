# node-apache-mailhog

## Execute

```bash
# ホストのユーザーIDとApache実行ユーザー（www-data）のIDを揃えるために UIDをエクスポートしながら起動する
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
