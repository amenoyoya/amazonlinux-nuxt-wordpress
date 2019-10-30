# Alpine Linux + Docker

## Vagrant Setup

### on Windows
`Win + X` => `A` |> 管理者権限PowerShell

```powershell
# chocolatey で virtualbox, vagrant インストール
> choco install -y virtualbox
> choco install -y vagrant

# vagrantプラグイン インストール
> vagrant plugin install vagrant-vbguest # Vagrantのマウント（共有フォルダ）周りのエラーを解決するプラグイン
> vagrant plugin install vagrant-winnfsd # WindowsのNTFSマウントで、LinuxのNFSマウントを可能にするプラグイン
> vagrant plugin install vagrant-hostsupdater # Vagrant仮想環境にホスト名を設定するためのプラグイン
> vagrant plugin install vagrant-alpine # Alpine Linux ゲスト用プラグイン

# シンボリックリンクを有効化
> fsutil behavior set SymlinkEvaluation L2L:1 R2R:1 L2R:1 R2L:1
```

### on Ubuntu
```bash
$ sudo apt install -y virtualbox
$ sudo apt install -y virtualbox-ext-pack
$ sudo apt install -y vagrant

# vagrantプラグイン インストール
$ vagrant plugin install vagrant-vbguest # VagrantのゲストOS-カーネル間のバージョン不一致解決用プラグイン
$ vagrant plugin install vagrant-hostsupdater # Vagrant仮想環境にホスト名を設定するためのプラグイン
$ vagrant plugin install vagrant-alpine # Alpine Linux ゲスト用プラグイン
```

***

## Alpine Linux Box インストール

### Vagrantfile作成
プロジェクトディレクトリに`Vagrantfile`作成

```ruby
# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.configure("2") do |config|
  config.vm.box = "generic/alpine38"
  config.vbguest.auto_update = false # host-guest間の差分アップデートを無効化
  # config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.synced_folder "./data/", "/data/"
end
```

### 仮想マシン起動
```bash
# ビルド＆起動
## 初回起動時はboxイメージのダウンロードに時間がかかる
$ vagrant up

# AlpineLinux仮想マシンにSSHアタッチする
$ vagrant ssh
## => Password を聞かれた場合は vagrant

---

# カーネルアップデート
$ sudo apk update && sudo apk upgrade

# Dockerインストール
$ sudo apk add docker

# Docker Compose インストール
$ sudo apk add py-pip python-dev libffi-dev openssl-dev gcc libc-dev make
$ sudo pip install --upgrade pip
$ sudo pip install docker-compose

# dockerデーモンをスタートアップに登録
$ sudo rc-update add docker boot

# dockerデーモン起動
$ sudo service docker start

# sudoなしでdockerを使えるようにする
## カレントユーザを docker グループに追加
$ sudo addgroup docker # dockerインストール時にグループは作られているはずだが念の為
$ sudo adduser $USER docker

# 一旦終了
$ exit

---

# 仮想マシン再起動
$ vagrant reload
```
