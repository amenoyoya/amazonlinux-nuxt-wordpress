# Go言語 Docker環境

## Environment

- OS: Ubuntu 18.04
- Docker: 19.03.5
    - DockerCompose: 1.24.0

***

## Docker

### Structure
```bash
./ # カレントディレクトリ = 作業ディレクトリ（appコンテナの /go/ ディレクトリにマウントされる）
|_ Dockerfile # appコンテナビルド設定
|_ docker-compose.yml # appコンテナ: golang:alpine
```

### Usage
基本的に Dockerコンテナを以下のようにコマンドとして使うことを想定している

```bash
# 利用時にコンテナ起動＆コマンド実行 => コマンド完了後コンテナ削除（--rm オプション）
## ※初回起動時のみコンテナイメージのダウンロード＆ビルドに時間がかかる

# 例: test/main.go をスクリプトとして実行
## go run test/main.go
$ docker-compose run --rm go run test/main.go

# 例: test/main.go を main 実行ファイルにコンパイル
## go build -o test/main test/main.go
$ docker-compose run --rm go build -o test/main test/main.go
```
