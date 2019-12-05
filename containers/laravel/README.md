# Laravel入門

## Laravel + PHP7.3 + MySQL5.7の環境構築

### 環境
- `_common_docker-compose`によるリバースプロキシが稼働している
- vhostsとして `127.0.0.1  local-host.dev` が定義されている

---

### 構成
```conf
./
 |- db/
 |   `- initdb.d/ # DB初期構成用のSQLファイルを配置
 |- laravel/ # laravelインストールディレクトリ（共有）
 |- web/
 |   |- apache2/ # apache設定ディレクトリ
 |   |   |- sites-available
 |   |   |   `- 000-default.conf
 |   |   `- ports.conf
 |   |- Dockerfile
 |   `- php.ini
 `- docker-compose.yml
```

---

### 構築
```bash
# エラーがないか見るためにフォアグラウンドで一度ビルド
$ docker-compose up
## => 問題なくビルドできたら Ctrl+C で一度終了

# バックグラウンドでコンテナ起動
$ docker-compose start

# laravelインストール
## webコンテナでcomposerコマンドを叩く
## webコンテナのカレントディレクトリ（/var/www/laravel/ => ./laravel/）にインストール
docker-compose exec web composer create-project laravel/laravel .
```
