# vue-express

Vue + Webpack + Express サーバ Docker 構成

## Environment

- OS:
    - Ubuntu 18.04
    - Windows 10
- Node.js: 12.14.1
    - Yarn package manager: 1.21.1
- Docker: 19.03.5
    - docker-compose: 1.24.0

### Structure
```bash
./
|_ app/ # 作業ディレクトリ => docker://express:/home/node/app/
|   |_ api/     # API定義スクリプト格納ディレクトリ
|   |   |_ index.js   # /api/*
|   |
|   |_ lib/
|   |   |_ puppet.js  # puppeteerラッパーライブラリ
|   |
|   |_ public/  # 静的ホスティングディレクトリ
|   |   |_ js/
|   |   |   |_ (index.js) # Webpackバンドル後のJavaScriptファイル
|   |   |
|   |   |_ index.html # ドキュメントルート
|   |
|   |_ app.js   # Expressサーバ | http://localhost:3333
|
|_ docker/ # Dockerコンテナ設定
|   |_ certs/   # SSL証明書格納ディレクトリ
|   |
|   |_ docker-compose.handlebars # docker-compose.yml のテンプレートファイル
|
|_ src/    # Webpackソーススクリプト格納ディレクトリ
|   |_ App.vue  # Appコンポーネント
|   |_ index.js # Webpackソーススクリプト（エントリーポイント）
|
|_ Dockerfile         # expressコンテナ生成スクリプト
|_ docker-compose.yml # Docker構成ファイル
|                     ## expressコンテナ: node:12-alpine3.11 | https://web.local/ => docker://express:3333
|                     ## nginx-proxyコンテナ: jwilder/nginx-proxy | vhostルーティング用プロキシ
|                     ## letsencryptコンテナ: jrcs/letsencrypt-nginx-proxy-companion | 無料SSL発行用
|_ handledocker.js    # 環境に合わせて docker-compose.yml を生成するスクリプト
|_ package.json       # 必要な node_modules 設定
|_ webpack.config.js  # Webpackバンドル設定
```

![vue-express.png](https://github.com/amenoyoya/docker-collection/blob/master/img/vue-express.png?raw=true)

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
## +noproxy: 複数のDockerComposeで運用していて nginx-proxy, letsencrypt コンテナが別に定義されている場合に指定
$ node handledocker.js --host yourdomain.com --email yourmail@yourdomain.com +noproxy

# Docker実行ユーザIDを合わせてDockerコンテナビルド
$ export UID && docker-compose build

# コンテナ起動
$ export UID && docker-compose up -d
```

***

## Express Server + Webpack 開発

ローカル開発時は Docker を使わず、Node.js でそのままローカルサーバを起動して開発する

```bash
# install node_modules from package.json
$ yarn install

# npm scripts: `start`
## concurrently 並列実行: `webpack --watch --watch-poll` & `node app/app.js`
$ yarn start

## => http://localhost:3333
```
