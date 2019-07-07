# Docker on Windows

Vagarantの挙動が不安定で開発が進まないときは、**Docker on Windows**を使うと良いかも知れない

- 必要要件:
    - Windows 10 Pro以上
        - Hyper/Vが動作する環境が必要

***

## インストール

***

## トラブルシューティング

### nginx-proxyの開始に失敗する
`jwilder/nginx-proxy`のリバーシプロキシ機能を使ってvhosts環境で開発を行っている場合、Windows起動直後は、vhostsドライバ周りの問題で起動に失敗しやすい

その場合、Docker on Windows を再起動してから、もう一度 `docker-compose start` を実行する

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
