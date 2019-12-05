# Vagrant + CoreOS + Docker 上で Vue CLI 3 の開発環境を構築

-----

## Vagrant + CoreOS + DockerCompose インストール

`000-共通設定.md`を参考に準備する

-----

## Vue CLI 3 環境構築

### Windowsから共有フォルダでシンボリックリンクを張れるように設定
Windowsのシンボリックリンク設定がされていないと、yarnのインストール時にエラーが起こる

- Vagrantfileで以下の設定がされているか確認
  ```ruby
  config.vm.provider :virtualbox do |vb|
      :
    # VirtualBoxから共有フォルダでシンボリックリンクを張れるよう設定
    vb.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/.","1"]
    config.ignition.config_obj = vb
  end
  ```
- `Win + X` > `Shift + A` で管理者権限PowerShellを起動
  ```sh
  # シンボリックリンクを有効化
  > fsutil behavior set SymlinkEvaluation L2L:1 R2R:1 L2R:1 R2L:1

  # 確認
  > fsutil behavior query symlinkevaluation
  ローカルからローカルへのシンボリック リンクは有効です。
  ローカルからリモートへのシンボリック リンクは有効です。
  リモートからローカルへのシンボリック リンクは有効です。
  リモートからリモートへのシンボリック リンクは有効です。
  ```

### DockerComposeファイル構成
`./Container/vue-cli`フォルダを参考に構成

### Vueコンテナ構築
```sh
$ cd ~/share/docker-vue/
$ docker-compose -d --build
```

### Vueコンテナ内でプロジェクト作成
```sh
$ docker-compose exec web /bin/ash

/app ＃ vue create vue-project
/app ＃ cd vue-project
/app/vue-project ＃ npm run serve
```