FROM catatnight/postfix

# local postfix SMTP 設定
## local network 内のコンテナ (今回の場合 web コンテナ) から接続する場合に利用
## - maildomain: smtp サーバ名
## - smtp_user: <接続ユーザ名>:<接続パスワード>
ENV maildomain=smtp.postfix.localhost
ENV smtp_user=root:localpostfix

# SMTPリレー設定用変数
## local postfix から更に外部の SMTP サーバに接続する場合に利用
ARG RELAY_SMTP_HOST
ARG RELAY_SMTP_PORT
ARG RELAY_SMTP_USER
ARG RELAY_SMTP_PASSWORD

# SMTPリレーを行う場合に使う設定ファイルをコンテナ内に追加
COPY ./relay_smtp.cf /tmp/relay_smtp.cf
COPY ./sasl_passwd /tmp/sasl_passwd

RUN : 'smtp relay settings' && \
    echo "$RELAY_SMTP_HOST" && \
    if [ "$RELAY_SMTP_HOST" != "" -a "$RELAY_SMTP_PORT" != "" -a "$RELAY_SMTP_USER" != "" -a "$RELAY_SMTP_PASSWORD" != "" ]; then \
        sed -i '/^relayhost/d' /etc/postfix/main.cf && \
        printf "cat <<++EOS\n`cat /tmp/relay_smtp.cf`\n++EOS\n" | sh | tee -a /etc/postfix/main.cf && \
        printf "cat <<++EOS\n`cat /tmp/sasl_passwd`\n++EOS\n" | sh | tee /etc/postfix/sasl_passwd && \
        postmap /etc/postfix/sasl_passwd; \
    fi
