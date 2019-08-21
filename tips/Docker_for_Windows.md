# Docker for Windows

Vagarantの挙動が不安定で開発が進まないときは、**Docker for Windows**を使うと良いかも知れない

- 必要要件:
    - Windows 10 Pro以上
        - Hyper/Vが動作する環境が必要

***

## インストール

### Hyper/V の有効化
- 前準備として、BIOSで`Virtualization Technology (VTx)`を有効化しておく
    - 大抵はすでに有効化されていると思う
- 管理者権限でPowerShellを起動し、以下のコマンドを実行
    ```powershell
    # Hyper/V 有効化
    > Enable-WindowsOptionalFeature -Online -FeatureName Microsoft-Hyper-V
    この操作を完了するために、今すぐコンピューターを再起動しますか?
    [Y] Yes  [N] No  [?] ヘルプ (既定値は "Y"): # Y を押して再起動する
    ```

#### 「構成レジストリキーを読み取れません」エラーが発生する場合
端末によっては上記の `Enable-WindowsOptionalFeature` コマンドが失敗することがある

基本的には再起動すれば上手くいくが、再起動してもダメな場合は、管理者権限のコマンドプロンプトで以下を実行する

```bash
> SC config wuauserv start= auto
> SC config bits start= auto
> SC config cryptsvc start= auto
> SC config trustedinstaller start= auto

# 再起動
> shutdown /r /t 0
```


### Docker for Windows インストール
- まず、Windows用パッケージマネージャの[chocolatey](https://chocolatey.org/)を入れる
    - 管理者権限のPowerShellで以下のコマンドを実行
        ```powershell
        # chocolateyインストール
        > Set-ExecutionPolicy Bypass -Scope Process -Force; iex ((New-Object System.Net.WebClient).DownloadString('https://chocolatey.org/install.ps1'))

        # バージョン確認
        > choco -v
        0.10.15
        ```
- chocolateyを使って Docker for Windows をインストールする
    - 管理者権限のPowerShellで以下のコマンドを実行
        ```powershell
        > choco install -y docker-desktop
        ```
- インストールしたら起動する
    - Docker for Windows を使うには会員登録が必要

***

## トラブルシューティング

### nginx-proxyの開始に失敗する
`jwilder/nginx-proxy`のリバーシプロキシ機能を使ってvhosts環境で開発を行っている場合、Windows起動直後は、vhostsドライバ周りの問題で起動に失敗しやすい

その場合、Docker for Windows を再起動してから、もう一度 `docker-compose start` を実行する

#### 再起動してもダメな場合
```bash
# ポートの使用状況を調べる
> netstat -nao

## -> ポート80番を使用しているプロセスを PIDから探す（タスクマネージャー使用）
```

大抵の場合、**com.docker.backend.exe**というプロセスがゾンビ化しているため、タスクマネージャーを使って終了させる

その後、もう一度 Docker for Windows を起動する

---

### ローカル開発用にvhostsを設定したい
- 管理者権限でメモ帳起動
- `C:\Windows\System32\drivers\etc\hosts`を開く
    ```conf
    127.0.0.1 設定したいvhosts名
    ```
- OSを再起動するか、コマンドプロンプトでDNSのキャッシュをクリアすれば反映される
    ```bash
    > ipconfig /flushdns
    ```
