# Ruby on Rails 環境


## 構成
```bash
./
 |_ app/ # プロジェクトディレクトリ
 |_ db/  # MySQLデータベースコンテナのビルド設定
 |_ web/ # Nginxサーバーコンテナのビルド設定
 |_ Dockerfile # Ruby on Rails コンテナの Dockerfile
 |_ docker-compose.yml # 全Dockerコンテナの構成ファイル
```

### Docker Containers
- **webコンテナ**
    - Nginxサーバー: `1.17`
    - http://localhost:5555 で動作（http://rails:3000 にポートフォワーディングする）
        - `nginx-proxy`コンテナを使用している場合は、vhost: http://rails.localhost で動作
    - サーバーログは [./web/logs/](./web/logs/) ディレクトリ内のファイルとリンク
- **railsコンテナ**
    - Ruby on Rails
        - Ruby: `2.4`
    - [./app/](./app/) ディレクトリが `/var/www/app/`(アプリケーションルート) とリンク
        - 作業はこのディレクトリで行う
- **dbコンテナ**
    - MySQL: `5.7`
        - user: `root`
        - password: `root`
        - database: `development`
    - ダンプファイルがある場合は [./db/initdb.d/](./db/initdb.d/) ディレクトリ内に配置すれば、ビルド時に読み込む
- **pmaコンテナ**
    - phpMyadminを使ってdbコンテナにアクセスするためのコンテナ
    - http://localhost:5556 で動作
        - `nginx-proxy`コンテナを使用している場合は、vhost: http://pma.rails.localhost で動作
- **mailhogコンテナ**
    - ローカル環境でメール送受信を行うためのコンテナ
    - http://localhost:5557 で動作
        - `nginx-proxy`コンテナを使用している場合は、vhost: http://mail.rails.localhost で動作
    - SMTPサーバーは http://mailhog:1025

***

## Setup

### 設定ファイル書き換え
- 開発用にSMTPサーバーの設定を行う
- **./app/config/environments/development.rb**
    ```ruby
    # 以下の設定を追加
    config.action_mailer.smtp_settings = {
      address: 'mailhog', # mailhogコンテナを指定
      port:     1025      # docker://mailhog:1025 がSMTPサーバー
    }
    ```

### dockerコンテナ起動
```bash
$ docker-compose build
$ docker-compose start
```
