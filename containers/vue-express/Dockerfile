# Node.js:12 AlpineLinux 版を使用
FROM node:12-alpine3.11

# Docker実行ユーザID取得
ARG UID
# Projectディレクトリ
ARG PRJDIR=/home/node/

# package.json を作業ディレクトリにコピー
COPY package.json $PRJDIR

RUN : 'puppeteer用の各種パッケージ導入' && \
    apk add --no-cache chromium nss freetype freetype-dev harfbuzz ca-certificates ttf-freefont && \
    : 'nodeユーザIDをDocker実行ユーザIDと合わせる' && \
    apk add --no-cache shadow && \
    usermod -u $UID node && \
    : 'nodeユーザをsudoersに追加' && \
    apk add --no-cache sudo && \
    echo 'node ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers && \
    : '作業ディレクトリのパーミッション変更' && \
    chown -R node $PRJDIR

# 作業者: nodeユーザ
USER node

# 作業ディレクトリ: $PRJDIR
## $PRJDIR/app/ => host://./app/
WORKDIR $PRJDIR

RUN : 'install node_modules' && \
    yarn install

# Serve Express Server
EXPOSE 3333
CMD ["node", "app/app.js"]
