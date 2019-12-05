# https対応 localhost 開発

## https-portal

Dockerで構築したサーバを自動でhttps化してくれるコンテナ

NginxとLet’s Encryptを内包している

DNS登録されているドメインなら実際に証明書を取得し、ローカルの場合はオレオレ証明書（自己証明書）に切り替えて、https対応のコンテナを簡単に作成することができる

### 構成
最も単純な PHP + Apache の構成は以下のようになる

```bash
./
|_ html/ # Web公開ディレクトリ
|   |_ index.php
|
|_ web/  # webコンテナ
|   |_ 000-default.conf # Apacheデフォルト設定
|   |_ php.ini          # PHP設定
|   |_ Dockerfile       # webコンテナビルドファイル
|
|_ docker-compose.yml # Docker構成
                      # https-portalコンテナ: SSL化＋リバースプロキシ
                      # webコンテナ: php:7.2-apache | https://web.local
```

### vhostsの設定
環境ごとに仮想ドメイン（vhosts）を設定する

#### Windows
管理者権限のメモ帳などで `C:\Windows\System32\drivers\etc\hosts` を編集

```ruby
# vhosts: web.local => 127.0.0.1
127.0.0.1  web.local
```

編集したら コマンドプロンプト or Powershell で以下を実行

```powershell
# DNSキャッシュをクリア
> ipconfig /flushdns
```

#### Ubuntu
```bash
# /vi/hosts を編集
$ sudo vi /etc/hosts

---
# vhosts: web.local => 127.0.0.1
127.0.0.1  web.local
---
```

### ビルド
```bash
$ docker-compose build
$ docker-compose up -d
```

Dockerコンテナが起動したら https://web.local/ にアクセスして動作確認する
