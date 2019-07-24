FROM alpine:latest

# openssh等をインストール
RUN set -x && apk update && apk upgrade && apk add --no-cache openssh openrc

RUN rc-update add sshd && rc-status
RUN mkdir -p /run/openrc/ && touch /run/openrc/softlevel

# SSH接続用ユーザー作成
RUN adduser -D ssh-user
## パスワード設定 -> "passwd"
### ※パスワードがないと接続できない
RUN echo 'ssh-user:passwd' | chpasswd
## sudo権限付与
RUN apk add --no-cache sudo
RUN echo "ssh-user ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

# 鍵ファイル設定
USER ssh-user
RUN mkdir -p ~/.ssh
ADD ./key/id_rsa.pub ~/.ssh/authorized_keys
RUN chmod -R 700 ~/.ssh

# sshd起動のためrootユーザーに戻しておく
USER root
