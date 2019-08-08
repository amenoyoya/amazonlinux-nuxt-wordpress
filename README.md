# Docker Collection

## What's this?

以下のTipsを集めたもの

- Dockerに関する備忘録
- 仕事や趣味で使ったコンテナの設定

***

## Projects

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
    - VirtualHost名で他のコンテナに上手いことリクエストを振り分けてくれるプロキシコンテナ
    - 起動すると ポート80, 443 を使用する

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
