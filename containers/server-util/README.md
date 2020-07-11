# サーバユーティリティ Docker環境

## Environment

- OS: Ubuntu 20.04
- Docker: 19.03.12
    - DockerCompose: 1.24.0

***

## Docker

### Structure
```bash
./ # カレントディレクトリ = 作業ディレクトリ
|  ## docker://app コンテナの /work/ ディレクトリにマウントされる
|_ docker-compose.yml # docker://app <= httpd:2.4-alpine
```

![server-util.png](../../img/server-util.png)

### Usage
基本的に Dockerコンテナを以下のようにコマンドとして使うことを想定している

```bash
# 利用時にコンテナ起動＆コマンド実行 => コマンド完了後コンテナ削除（--rm オプション）
## ※初回起動時のみコンテナイメージのダウンロード＆ビルドに時間がかかる

# 例: htpasswd コマンドを使って user/password のBasic認証ファイルを .htpasswd に保存
$ docker-compose run --rm app htpasswd -b -c -m .hpasswd user password
```
