# Vagrant｜Apache環境 最小構成

## Vagrant + AlpineLinux環境構築

### プラグイン準備
`Win + X` => `A` |> 管理者権限PowerShell

```powershell
# vagrantプラグイン インストール
> vagrant plugin install vagrant-vbguest # Vagrantのマウント（共有フォルダ）周りのエラーを解決するプラグイン
> vagrant plugin install vagrant-winnfsd # WindowsのNTFSマウントで、LinuxのNFSマウントを可能にするプラグイン
> vagrant plugin install vagrant-hostsupdater # Vagrant仮想環境にホスト名を設定するためのプラグイン
> vagrant plugin install vagrant-alpine # Alpine Linux ゲスト用プラグイン

# シンボリックリンクを有効化
> fsutil behavior set SymlinkEvaluation L2L:1 R2R:1 L2R:1 R2L:1
```

### Vagrant＋Apache環境構築
管理者権限PowerShellで、このディレクトリ（`Vagrantfile`があるディレクトリ）に移動し、以下のコマンドを実行

```powershell
# ビルド＆起動
## 初回起動時はboxイメージのダウンロードに時間がかかる
$ vagrant up

# AlpineLinux仮想マシンにSSHアタッチする
$ vagrant ssh

---

# カーネルアップデート
$ sudo apk update && sudo apk upgrade

# Apache + PHP インストール
$ sudo apk add apache2 php-apache2

# Apacheデーモンをスタートアップに登録
$ sudo rc-update add apache2 boot

# 一旦exit
$ exit

---

# 一旦Vagrantも停止
> vagrant halt
```

### ホスト-ゲスト間ディレクトリ同期
ホスト側の htdocs/ ディレクトリとゲスト（AlpineLinux）のDocumentRoot（/var/www/localhost/htdocs/）を同期するように設定する

Vagrantfileに以下の設定を追加する

```ruby
# ホスト ./htdocs/ の内容を ゲスト /var/www/localhost/htdocs/ と同期する
config.vm.synced_folder "./htdocs/", "/var/www/localhost/htdocs/"
```

### VirtualHost設定
ゲストApacheサーバに、任意のホスト名でアクセスできるように設定する

Vagantfileにhostsupdaterの設定を追加する

```ruby
# http://example.local/ でアクセスできるように設定
config.hostsupdater.aliases = [
  "example.local",
]
```

### 動作確認
ここまでの設定をすると、Vagrantfile 全体としては以下のようになる

```ruby
# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "generic/alpine38"
  config.vbguest.auto_update = false # host-guest間の差分アップデートを無効化
  config.ssh.insert_key = false
  
  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "172.17.8.100"

  # ホスト ./htdocs/ の内容を ゲスト /var/www/localhost/htdocs/ と同期する
  config.vm.synced_folder "./htdocs/", "/var/www/localhost/htdocs/"

  # http://example.local/ でアクセスできるように設定
  config.hostsupdater.aliases = [
    "example.local",
  ]
end
```

この状態で、管理者権限PowerShellでVagrantを起動する

```powershell
> vagrant up
```

ホストマシンのブラウザで http://example.local/ にアクセスし、phpinfoが表示されることを確認する
（`./htdocs/index.php` の内容が表示される）
