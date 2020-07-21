# Based on AlpineLinux with Apache 2.4
FROM httpd:2.4-alpine

# Go言語用環境変数
ENV GOPATH /root/go
ENV GOBIN $GOROOT/bin
ENV GO111MODULE on
ENV PATH $GOPATH/bin:/usr/local/go/bin:$PATH

# lego (Go製の Let's Encrypt クライアント) 用環境変数
ENV PATH /usr/local/lego:$PATH

RUN : 'install golang' && \
    apk add --update --no-cache vim git make musl-dev go curl && \
    mkdir -p ${GOPATH}/src ${GOPATH}/bin && \
    : 'install lego (letsencrypt client)' && \
    mkdir -p /usr/local/lego && \
    wget -O - https://github.com/go-acme/lego/releases/download/v3.8.0/lego_v3.8.0_linux_amd64.tar.gz | tar -xzvf - -C /usr/local/lego/

# 作業ディレクトリ: host:./ => docker://app:/work/
WORKDIR "/work/"
