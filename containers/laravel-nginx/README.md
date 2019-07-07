参考: https://qiita.com/mejileben/items/0e4d9c9c9470b080b4f8

```bash
# エラーがないか見るためにフォアグラウンドで一度ビルド
$ docker-compose up
## => 問題なくビルドできたら Ctrl+C で一度終了

# バックグラウンドでコンテナ起動
$ docker-compose start

# laravelインストール
## webコンテナでcomposerコマンドを叩く
## webコンテナのカレントディレクトリ（/var/www/laravel/ => ./laravel/）にインストール
docker-compose exec phpfpm composer create-project laravel/laravel .
```
