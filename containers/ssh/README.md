# SSH接続可能なDockerコンテナ

## Environment
- OS: Windows10
- Docker: `18.09.2`
    - DockerCompose: `1.23.2`

***

## 構成

```conf
./
|- key/ # ssh-keygen -t rsa -b 2048
|  |- id_rsa     # 秘密鍵: これを使ってコンテナにSSH接続する
|  `- id_rsa.pub # 公開鍵: authorized_keys としてコンテナ内にコピーされる
|- docker-compose.yml
`- Dockerfile
```

### コンテナ構成
- `web`コンテナ:
    - AlpineLinuxベース
    - OpenSSH
    - ポート: `2222` -> `22`
    - SSH接続用ユーザー: `ssh-user`
        - ユーザーパスワード: `passwd`

***

## Setup

```bash
# docker-compose build up
$ docker-compose up -d

# after build up, connect to `web` container by ssh
$ ssh -i ./key/id_rsa ssh-user@localhost -p 2222
## => password: passwd
```
