# Docker環境構築

開発環境は基本的に以下のような構成とする

- OS:
    - Ubuntu 18.04
    - Windows 10: Ubuntu on WSL
        - Web開発は、基本的に Ubuntu 等のLinux環境で開発する方が楽
- エディタ:
    - VSCode
        - Web開発に便利なプラグインが多くあり、使いやすいため採用
- パッケージマネージャ:
    - Linuxbrew
        - Mac用パッケージマネージャ Homebrew のLinux版
        - 最新パッケージをインストールしやすい
        - root権限不要で使用可能
        - 環境を汚さずパッケージをインストール可能
- コマンドラインツール:
    - Git
        - 基本的にコードの管理はGitで行う
    - Node.js
        - 最近のフロントエンド開発に必須
    - Docker
        - Web開発はDockerコンテナを使うことで環境の違いを吸収できる
        - 必要な環境を自動的に構築することも可能


## Windows 10 環境構築

Read [Windows10.md](./Windows10.md).

***

## Ubuntu 18.04 環境構築

### Linuxbrew導入
gitやビルドツール等の必須パッケージをインストールし、パッケージマネージャとして Linuxbrew を導入する

```bash
# 各種パッケージをアップデート
## sudo: root権限でコマンド実行
## apt: Ubuntu 標準のパッケージマネージャ
$ sudo apt update && sudo apt upgrade -y
[sudo] password: # <= 設定したパスワードを入力してインストール

# git, ビルドツール等の必須パッケージをインストール
$ sudo apt install -y build-essential git curl vim ruby

# 不要なパッケージを自動削除
$ sudo apt autoremove -y

# Linuxbrew導入
$ sh -c "$(curl -fsSL https://raw.githubusercontent.com/Linuxbrew/install/master/install.sh)"
## PATHを通す
$ echo 'export PATH="/home/linuxbrew/.linuxbrew/bin:$PATH"' >> ~/.bashrc
$ source ~/.bashrc

# Linuxbrewバージョン確認
$ brew --version
Homebrew 2.2.0
```

なお、Linuxbrewでインストールしたパッケージが多くなりすぎて環境をリセットしたいときなどは、Linuxbrewごと削除してしまえば良い

Linuxbrewの削除は以下のようにして行う

```bash
# アンインストールスクリプトの実行
$ ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/uninstall)"

# ディレクトリが残ることがあるため、その場合は手動で削除する
$ sudo rm -rf /home/linuxbrew
```

### VSCodeのインストール
Vagrant環境の場合は、Windows側にVSCodeがインストールしてあるはずなので、この項は飛ばして良い

Microsoft公式ページから debパッケージをダウンロードしてインストールする

```bash
# curl で debパッケージをダウンロード
$ curl -L https://go.microsoft.com/fwlink/?LinkID=760868 -o vscode.deb

# debパッケージからインストール
$ sudo dpkg -i vscode.deb

# いくつか足りない依存パッケージがあるはずなので、それらを含めて fix install
$ sudo apt install -yf
```

### Docker導入

#### Dockerインストール
```bash
# Dockerインストール
$ sudo apt install -y apt-transport-https ca-certificates software-properties-common
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
$ sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable test edge"
$ sudo apt update && sudo apt install -y docker-ce

# dockerデーモンをスタートアップ登録
$ sudo systemctl enable docker

# dockerデーモン起動
$ sudo service docker start

# dockerバージョン確認
$ docker -v
Docker version 19.03.5, build 633a0ea838

# DockerCompose導入
$ sudo curl -L https://github.com/docker/compose/releases/download/1.24.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose

# DockerComposeバージョン確認
$ docker-compose -v
docker-compose version 1.24.0, build 0aa59064

# dockerをsudoなしで実行可能に
## カレントユーザーをdockerグループに所属させれば良い
$ sudo gpasswd -a $USER docker
$ sudo chgrp docker /var/run/docker.sock # docker.sockへのグループ書き込み権限を付与
$ sudo service docker restart
```

#### マシン再起動
Dockerを sudo 権限なしで実行する設定を反映するためには、一度マシンを再起動する必要がある

Vagrant環境で実行している場合は、PowerShell で以下のコマンドを実行する

```powershell
# Vagrantfile のあるディレクトリで以下のコマンドを実行し、Vagrantマシン再起動
> vagrant reload
```

#### Docker動作確認
`docker run` コマンドでコンテナイメージの取得・実行を行う

ここでは、動作確認のための hello-world コンテナを利用する

コンテナとはアプリやインフラなどを入れた状態のスナップショットのようなものである

このコンテナを使うことで、常に同一の環境を再現することが可能となる

```bash
# hello-world コンテナを実行
## 初回実行時はコンテナイメージがローカルにないため、ダウンロードが同時に行われる
$ docker run hello-world

## => いろいろ表示されるが、以下のような文章が出力されればOK
### Hello from Docker!
### This message shows that your installation appears to be working correctly.
```

### Node.js のインストール
Linuxbrewを用いて Node.js をインストールする

```bash
# brew は root権限（sudo）なしでパッケージインストール可能
$ brew install node

# Node.js のパッケージマネージャとして yarn を導入しておくことを推奨
$ npm i -g yarn

# Node.js のバージョン確認
$ node -v
v13.2.0

# yarn のバージョン確認
$ yarn -v
1.21.0
```
