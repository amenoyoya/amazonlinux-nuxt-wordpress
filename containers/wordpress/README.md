# 日本語版WordPress Docker

## Environment

- OS: Ubuntu 18.04
- Docker: 19.03.5
    - DockerCompose: 1.24.0

***

## Docker

### Structure
```bash
./
|_ docker/ # jupyterコンテナビルド設定
|   |_ db/ # dbコンテナ
|   |   |_ initdb.d/  # このディレクトリに配置したSQLファイルが初期データとして流し込まれる
|   |   |_ Dockerfile # dbコンテナビルド設定
|   |   |_ my.cnf     # MySQLデータベースの設定ファイル
|   |
|   |_ web/ # webコンテナ
|       |_ Dockerfile       # webコンテナビルド設定
|       |_ 000-default.conf # Apache設定ファイル
|       |_ php.ini          # PHP設定ファイル
|
|_ web-data/ # 作業ディレクトリ => docker://web:/var/www/html/ にマウントされる
|   |_ .htaccess # リダイレクト設定: ./ => ./wordpress/ にリダイレクト
|   |_ setup_wordpress.sh # WordPressをセットアップするシェルスクリプト
|
|_ docker-compose.yml # webコンテナ: wordpress:latest | http://localhost:8000
                      # dbコンテナ: mysql:5.7
                      ## ┗ db-dataボリュームコンテナ
                      # pmaコンテナ: phpmyadmin/phpmyadmin:latest | http://localhost:8001
```

### Environment
`docker-compose.yml` で設定できる環境変数は以下の通り

- **web.environment**
    - `WORDPRESS_DB_HOST`:
        - 接続先データベースホスト名（デフォルト: `localhost`）
        - dbコンテナを指定する（`db:3306`）
    - `WORDPRESS_DB_USER`:
        - 接続先データベースユーザ名（デフォルト: `root`）
        - dbコンテナの `MYSQL_USER` 環境変数を指定する
    - `WORDPRESS_DB_PASSWORD`:
        - 接続先データベースパスワード（デフォルト: `root`）
        - dbコンテナの `MYSQL_PASSWORD` 環境変数を指定する
    - `WORDPRESS_DB_NAME`:
        - 接続先データベース名（デフォルト: `wordpress`）
        - dbコンテナの `MYSQL_DATABASE` 環境変数を指定する
    - `WORDPRESS_DB_CHARSET`:
        - 接続先データベースの文字コード（デフォルト: `utf8mb4`）
    - `WORDPRESS_DB_COLLATE`:
        - 接続先データベースの照合順序（デフォルト: 空）
    - `WORDPRESS_DEBUG`:
        - WordPressをデバッグモードにするかどうか（デフォルト: `false`）
        - 開発時は `true` にしておくと開発しやすい
- **db.environment**
    - `MYSQL_ROOT_PASSWORD`:
        - データベースのrootユーザパスワード
    - `MYSQL_USER`:
        - rootユーザ以外のユーザでデータベースを使う場合に指定
    - `MYSQL_PASSWORD`:
        - `MYSQL_USER` で指定したユーザのパスワード
    - `MYSQL_DATABASE`:
        - 使用するデータベース名

### Setup
```bash
# webコンテナ内の作業ユーザ（www-data）とDocker実行ユーザのUIDを合わせてコンテナビルド
$ export UID && docker-compose build

# コンテナをバックグラウンドで起動
$ export UID && docker-compose up -d
```

コンテナが起動したら http://localhost:8000 にアクセスするとWordPressが使える
