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

### VSCode インストール
[公式ページ](https://azure.microsoft.com/ja-jp/products/visual-studio-code/) からダウンロードしてインストールする

インストール時は、以下の項目にチェックを入れておくことを推奨

- [x] エクスプローラーのファイルコンテキストメニューに【Codeで開く】アクションを追加する
- [x] エクスプローラーのディレクトリコンテキストメニューに【Codeで開く】アクションを追加する
- [x] サポートされているファイルの種類のエディターとして、Codeを登録する
- [x] PATHへの追加（再起動後に使用可能）

プラグインのインストールや設定については後述

### Ubuntu 18.04 on WSL 環境構築
基本的に Ubuntu 18.04 環境で開発を行いたいため、Windows Subsystem for Linux (WSL) に Ubuntu をインストールして使用する

`Win + X` |> `A` キー => 管理者権限PowerShell 起動

```powershell
# Windows Subsystem Linux を有効化する
> Enable-WindowsOptionalFeature -Online -FeatureName Microsoft-Windows-Subsystem-Linux
この操作を完了するために、今すぐコンピューターを再起動しますか?
[Y] Yes  [N] No  [?] ヘルプ (既定値は "Y"): # そのままENTERして再起動

# 再起動したら Ubuntu 18.04 ディストロパッケージをダウンロード
## 「ダウンロード」ディレクトリに ubuntu1804.appx というファイル名でダウンロード
> Invoke-WebRequest -Uri https://aka.ms/wsl-ubuntu-1804 -OutFile ~\Downloads\ubuntu1804.appx -UseBasicParsing

# ダウンロードしたディストロパッケージをWSLに追加
> Add-AppxPackage ~\Downloads\ubuntu1804.appx
```

スタートメニューに「Ubuntu 18.04」が追加されるため、起動する

```bash
# 初回起動時は初期設定が必要
Installing, this may take a few minutes...
Please create a default UNIX user account. The username does not need to match your Windows username.
For more information visit: https://aka.ms/wslusers
Enter new UNIX username: # ログインユーザ名を設定
Enter new UNIX password: # ログインパスワードを設定
Retype new UNIX password: # パスワードをもう一度入力
```

以降の操作は **Ubuntu 18.04** の項を参照

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

# Linuxbrew導入
$ sh -c "$(curl -fsSL https://raw.githubusercontent.com/Linuxbrew/install/master/install.sh)"
## PATHを通す
$ echo 'export PATH="/home/linuxbrew:$PATH"' >> ~/.bashrc
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

### Docker導入

#### Dockerインストール
```bash
# Dockerインストール
$ sudo apt install -y apt-transport-https ca-certificates software-properties-common
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
$ sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable test edge"
$ sudo apt update && sudo apt install -y docker-ce

# dockerデーモン起動
$ sudo service docker start

# dockerバージョン確認
$ docker -v
Docker version 19.03.5, build 633a0ea838

# Dockerをsudoなしで実行可能に
## カレントユーザーをdockerグループに所属させれば良い
$ sudo gpasswd -a $USER docker
$ sudo chgrp docker /var/run/docker.sock # docker.sockへのグループ書き込み権限を付与
$ sudo service docker restart

## 一度ログアウトしないと反映されないため、マシン再起動
### WSL環境の場合は exit でターミナルを閉じる
$ sudo reboot
```

#### Dockerデーモンのスタートアップ登録
現状では、再起動の度（WSLターミナルを開く度）に以下のコマンドでDockerデーモンを開始する必要がある

```bash
$ sudo service docker start
```

これが面倒な場合は、デーモンをスタートアップに登録しておくと良い

Ubuntu環境であれば `sudo systemctl enable docker` コマンド一発だが、WSL環境の場合はWindowsのタスクスケジューラを使わなければならないため少し面倒である

これについては別に解説する

#### Docker動作確認
`docker run` コマンドでコンテナイメージの取得・実行を行う

ここでは、動作確認のための hello-world コンテナを利用する

コンテナとはアプリやインフラなどを入れた、Linuxマシンの状態のスナップショットのようなものである

このコンテナを使うことで、常に同一の環境を再現することが可能となる

```bash
# hello-world コンテナを実行
## 初回実行時はコンテナイメージがローカルにないため、ダウンロードが同時に行われる
$ docker run hello-world
```

### VSCodeのインストール
WSL環境の場合は、Windows側にVSCodeがインストールしてあるはずなので、この項は飛ばして良い

Microsoft公式ページから debパッケージをダウンロードしてインストールする

```bash
# curl で debパッケージをダウンロード
$ curl -L https://go.microsoft.com/fwlink/?LinkID=760868 -o vscode.deb

# debパッケージからインストール
$ sudo dpkg -i vscode.deb

# いくつか足りない依存パッケージがあるはずなので、それらを含めて fix install
$ sudo apt install -yf
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
