# Docker Collection

## What's this?

以下のTipsを集めたもの

- Dockerに関する備忘録
- 仕事や趣味で使ったコンテナの設定

***

## Projects

- [環境構築](./env/README.md)
    - Windows 10, Ubuntu 18.04 におけるDocker開発環境構築
- [Docker入門](./tips/DockerTraining)
    - Webプログラマに転職して初めてDockerに触ったときの備忘録
    - そのうちちゃんと整理したい
- [Vagrant＋Docker Tips](./tips/Vagrant+Docker)
    - Windows10環境におけるVagarntとDockerを使った開発環境構築のTips
    - Vagrantの不安定さに嫌気が差したので、現在は `Ubuntu 18.04 LTS` or `Windows10 + Docker for Windows` の開発環境に移った
- [コンテナ設定集](./containers)
    - Dockerコンテナの設定集
    - 割と雑多に置いているため、書き方が統一されていない

***

## nginx-proxy

- [nginx-proxy](./nginx-proxy)
    - `nginx-proxy`コンテナ
        - VirtualHost名で他のコンテナに上手いことリクエストを振り分けてくれるプロキシコンテナ
        - 起動すると ポート80, 443 を使用する
    - `letsencrypt`コンテナ
        - nginx-proxy と連携して VirtualHost名を自動的に https化してくれるコンテナ
        - Let's Encrypt で無料SSLを発行する

### 使い方
- `nginx-proxy`コンテナ起動
    ```bash
    $ cd ./nginx-proxy
    $ docker-compose up -d
    $ cd ..
    ```
- 適当なDockerComposeを作成する
    - 例: PHP 7.2 + Apache のコンテナを生成するDockerCompose
    - **php72/docker-compose.yml**
        ```yaml
        version: "3"
        services:
          web:
            image: php:7.2-apache
            volumes:
              # 共有ディレクトリ
              - ./html:/var/www/html # ドキュメントルート
            # nginx-proxy を使っている場合はポート設定不要
            # ports:
              # - 1000:80 # localhost:1000 => container.web:80
            stdin_open: true
            tty: true
            privileged: true
            network_mode: bridge # nginx-proxy に見つけてもらうためにブリッジモードに
            environment:
              # VIRTUAL_HOST設定（nginx-proxy）
              VIRTUAL_HOST: devel.localhost # localhost:1000 の代わりに devel.localhost でアクセス可能に
              VIRTUAL_PORT: 80
              # Let's Encrypt 設定（letsencrypt）
              LETSENCRYPT_HOST: devel.localhost # https://devel.localhost でアクセス可能に
              LETSENCRYPT_EMAIL: admin@example.com # Let's Encrypt 申請時のメールアドレス: 適当でも大丈夫
              # ローカル開発時は letsencryptコンテナがオレオレ証明書として発行する default.cert を利用する
              ## 本番環境では CERT_NAME はコメントアウトする
              CERT_NAME: default
        ```
    - **php72/html/index.php**
        ```php
        <?php
        phpinfo();
        ```
- DockerCompose起動
    ```bash
    $ cd ./php72
    $ docker-compose up -d
    ```
- http://devel.localhost にアクセスして `phpinfo()` の内容が表示されることを確認する

***

## Trouble Shooting

### 共有ディレクトリのパーミッション問題
- DockerコンテナによるWeb開発をする際、ホストとコンテナで一部のディレクトリ（大抵はアプリケーションの本体ディレクトリ）を共有することが多い
- ホストがWindowsなどのパーミッションの関係ないOSの場合は問題にならないが、Linux系OSの場合、ホストファイルシステムとゲスト（コンテナ）ファイルシステムでパーミッションの食い違いが起こる
    - 例えば、ホストの`user`ユーザーの所有で`600`のパーミッションなら、コンテナ内のユーザーでそのファイル（ディレクトリ）に書き込みを行うことはできない

#### 解決策
- この問題は、**ホストでDockerを起動するユーザーのID**と**コンテナ内で共有ディレクトリに対して操作を行うユーザーのID**を一致させれば解決する
    1. ホスト `/etc/passwd` と `/etc/group` を READ-ONLY でコンテナと共有
        - **docker-compose.yml** (抜粋)
            ```yaml
            volumes:
                - ./app:/var/www/app         # プロジェクトディレクトリを共有（例）
                - /etc/passwd:/etc/passwd:ro # read_only(ro)で passwd を共有
                - /etc/group:/etc/group:ro   # read_only(ro)で group を共有
            ```
        - 参考: https://qiita.com/yohm/items/047b2e68d008ebb0f001
        - 利点:
            - 手っ取り早くユーザー情報を共有できる利点がある
            - Docker起動時に特殊な操作等を必要としない
        - 欠点:
            - ホストOSがLinux系OS以外では動かない（`/etc/passwd`, `/etc/group`があるホストOSでしか動かない）
            - ホスト側に存在しないユーザーをコンテナ内で作成して使うことはできない
    2. コンテナ起動時に コンテナ内ユーザーのIDを Docker実行ユーザーのIDに変更する
        - **docker-compose.yml** (抜粋)
            ```yaml
            command: bash -c 'usermod -o -u ${UID} <コンテナ内ユーザー>; groupmod -o -g ${UID} <コンテナ内ユーザー>; <スタートアップ処理...>'
            ```
        - 参考: https://qiita.com/reflet/items/3516400c37c4f5b0cd6d
        - 利点:
            - ホストOSを選ばず汎用的に使うことができる
            - ホスト側に存在しないユーザーをコンテナ内で作成して使うことも可能
        - 欠点:
            - Dockerコンテナ起動時、UIDを環境変数にexportしなければならない
                ```bash
                $ export UID && docker-compose up -d

                # WindowsでDockerDesktopを使っている場合はUIDを合わせる必要はないため普通に起動して良い
                ## ※ UID が空だという警告が出るのが気になる場合は、適当な値をセットして実行すれば良い
                > set UID=1000
                > docker-compose up -d
                ```
