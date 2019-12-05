# --- GCCビルド用Dockerコンテナ ---
# ※ビルドに結構時間かかる
FROM alpine:3.8

WORKDIR /
ENV GCC_VERSION=9.1.0

# GCCコンパイル用ビルドツール＆nodejsインストール
RUN apk update && \
    apk add --no-cache \
    build-base \
    dejagnu \
    isl-dev \
    make \
    mpc1-dev \
    mpfr-dev \
    texinfo \
    zlib-dev \
    nodejs \
    nodejs-npm

# yarnインストール
RUN npm install -g npm && \
    npm install -g yarn

# 指定バージョンのGCCビルド
RUN wget -q https://ftp.gnu.org/gnu/gcc/gcc-${GCC_VERSION}/gcc-${GCC_VERSION}.tar.gz && \
    tar -xzf gcc-${GCC_VERSION}.tar.gz && \
    rm -f gcc-${GCC_VERSION}.tar.gz

WORKDIR /gcc-${GCC_VERSION}

RUN ./configure \
    --prefix=/usr/local \
    --build=$(uname -m)-alpine-linux-musl \
    --host=$(uname -m)-alpine-linux-musl \
    --target=$(uname -m)-alpine-linux-musl \
    --with-pkgversion="Alpine ${GCC_VERSION}" \
    --enable-checking=release \
    --disable-fixed-point \
    --disable-libmpx \
    --disable-libmudflap \
    --disable-libsanitizer \
    --disable-libssp \
    --disable-libstdcxx-pch \
    --disable-multilib \
    --disable-nls \
    --disable-symvers \
    --disable-werror \
    --enable-__cxa_atexit \
    --enable-default-pie \
    --enable-languages=c,c++ \
    --enable-shared \
    --enable-threads \
    --enable-tls \
    --with-linker-hash-style=gnu \
    --with-system-zlib
RUN make -j $(nproc)
RUN make -j $(nproc) install-strip

# GCCバージョン確認
RUN gcc -v


# --- AlpineLinux + GCC 9.1.0 + nodejs ---
FROM alpine:3.8

# ビルドツール＆nodejsインストール
RUN apk update && \
    apk add --no-cache \
    autoconf \
    automake \
    binutils \
    cmake \
    file \
    git \
    gmp \
    isl \
    libc-dev \
    libtool \
    make \
    mpc1 \
    mpfr3 \
    musl-dev \
    pkgconf \
    zlib-dev \
    nodejs \
    nodejs-npm

# yarnインストール
RUN npm install -g npm && \
    npm install -g yarn

# builderコンテナからビルド済みGCC取得
COPY --from=builder /usr/local/ /usr/
RUN ln -s /usr/bin/gcc /usr/bin/cc

# コンテナを作成したらコンテナイメージを再利用できるように保存
# $ docker commit this-container alpine-node-gcc:9.1.0

# コンテナイメージを30MBに分割しながらエクスポート
# $ docker save alpine-node-gcc | split -b 30M - alpine-node-gcc.9.1.0.tar_

# エクスポートしたコンテナイメージをロード
# $ docker load <(cat alpine-node-gcc.9.1.0.tar_*)
# => Dockerfile: FROM alpine-node-gcc:9.1.0 が使えるようになる
