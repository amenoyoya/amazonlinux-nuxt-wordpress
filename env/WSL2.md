# Windows Subsystem for Linux 環境構築

※ WSL2環境は軽量で開発しやすいが、現時点ではhttps化されたvhostsの開発ができないため、VirtualBox + Vagrant 環境で行うことを推奨

※ 逆に言えば、https化されたvhostsの開発のような特殊な開発を必要としないのであれば WSL2 の方が開発はしやすい

## Windows Insider Program 登録
基本的に Ubuntu 18.04 環境で開発を行いたいため、Windows Subsystem for Linux (WSL) に Ubuntu をインストールして使用する

ただし、2019年12月時点では、WSL上でDockerを動かすことはできない

2020年春に提供予定の WSL2 で、Docker等を含むすべてのLinux機能を使えるようになる予定であるが、ここでは Windows Insider Program に登録し、一足先に WSL2 を使えるようにする

### Windows Insider Program 参加
**Windows Insider Program 参加の前に必ずバックアップを取っておくこと**（動作が不安定になった場合に、バックアップを取っておかないと元に戻せなくなる）

まず、`Win + X` |> `N` キー => システム設定 起動

- 設定 > 更新とセキュリティ > Windows Insider Program
    - Windows Insider Program に参加する
        - ※ Microsoftアカウントでのログイン必須のため、アカウントを持っていない場合は新規作成する

![windows_insider_program.png](./img/windows_insider_program.png.png)

- どのようなコンテンツの受け取りを希望されますか？
    - => `Windowsのアクティブな開発` を選択
- プレビュービルドを受け取る頻度はどの程度を希望されますか？
    - => `スロー` を選択

### Windows Update 実行
WSL2 を使うためには、ビルドバージョン 18917 以降のWindows10である必要がある

`Win + X` |> `N` キー => システム設定 起動

- 設定 > システム > バージョン情報
    - ここで現在のOSビルドバージョンを確認し、18917以前のバージョンならアップデートを行う
- 設定 > 更新とセキュリティ
    - Windows Update を実行する

***

## Ubuntu 18.04 on WSL2 環境構築

### Ubuntu 18.04 on WSL インストール
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

# 起動が完了したら一旦終了
$ exit
```

### WSL1 => WSL2 にバージョン変更
`Win + X` |> `A` キー => 管理者権限PowerShell 起動

```powershell
# 現在の WSL の状態を確認
## Ubuntu-18.04 の VERSION（WSLバージョン）が 1 になっているはず
> wsl -l -v
  NAME            STATE           VERSION
* Ubuntu-18.04    Stopped         1

# WSL2（仮想プラットフォーム）を有効化する
> Enable-WindowsOptionalFeature -Online -FeatureName VirtualMachinePlatform
この操作を完了するために、今すぐコンピューターを再起動しますか?
[Y] Yes  [N] No  [?] ヘルプ (既定値は "Y"): # そのままENTERして再起動

# 再起動したら、WSL1 => WSL2 にバージョン変更
> wsl --set-version Ubuntu-18.04 2

# 再度 WSL の状態を確認（VERSION = 2 になっていればOK）
> wsl -l -v
  NAME            STATE           VERSION
* Ubuntu-18.04    Stopped         2
```
