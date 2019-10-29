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
```

### on Ubuntu
```bash
$ sudo apt install -y virtualbox
$ sudo apt install -y virtualbox-ext-pack
$ sudo apt install -y vagrant

# vagrantプラグイン インストール
$ vagrant plugin install vagrant-vbguest # Vagrantのマウント（共有フォルダ）周りのエラーを解決するプラグイン
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
  config.vm.box = "alpine/alpine64"
  # config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.synced_folder "./data/", "/data/"
end
```

### 仮想マシン起動
```bash
# ビルド＆起動
## 初回起動時はboxイメージのダウンロードに時間がかかる
$ vagrant up

# おそらく以下のようなエラーが起こるはず
## mount mounting /dev/loop0 on /mnt failed invalid argument
## これは vagrant-vbguestプラグインがVirtulBoxのバージョンに合わせてゲストOSのバージョンアップを行うため起こるエラーである
## （なお、vagrant-vbguestプラグインを入れていないと、VirtualBoxとゲストOSのバージョンが合わず別のエラーが出る）
## OSバージョンとカーネルバージョンが合っていないために起こるエラーなので、カーネルをバージョンアップすれば解決する

# AlpineLinux仮想マシンにSSHアタッチする
$ vagrant ssh
## => Password を聞かれた場合は vagrant

---

# リポジトリが古いため、新しいリポジトリに変更する
## 好きなエディタで /etc/apk/repositories を編集すれば良い
$ sudo vi /etc/apk/repositories
---
# 以下のリポジトリのみ有効化されている状態にする
http://dl-cdn.alpinelinux.org/alpine/edge/main
http://dl-cdn.alpinelinux.org/alpine/edge/community
http://dl-cdn.alpinelinux.org/alpine/edge/testing
---

# カーネルアップデート
$ sudo apk update && sudo apk upgrade

# Dockerインストール
$ sudo apk update && sudo apk add docker docker-compose

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
