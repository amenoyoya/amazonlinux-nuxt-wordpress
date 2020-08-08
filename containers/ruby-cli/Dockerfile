FROM ruby:2.7

# Docker実行ユーザID取得
ARG UID

# PATH に Linuxbrew 追加
ENV PATH=/home/linuxbrew/bin:$PATH

RUN : 'install bundler' && \
    apt-get update && \
    apt-get install -y build-essential git && \
    gem install bundler && \
    \
    : 'install rails' && \
    gem install rails && \
    : 'install Linuxbrew' && \
    git clone https://github.com/Homebrew/brew /home/linuxbrew && \
    \
    : 'install nodejs' && \
    brew install nodejs && \
    npm i -g yarn && \
    \
    : 'ホスト側カレントユーザと同一IDのユーザが存在しない場合は新規作成' && \
    if [ "$(getent passwd $UID)" = "" ]; then useradd -m -u $UID worker && chown -R worker /home/worker/; fi && \
    \
    : '作業ディレクトリ作成＆パーミッション修正' && \
    mkdir /work/ && chown -R $UID /work/ && \
    chown -R $UID /home/linuxbrew/ && \
    chown -R $UID /usr/local/bundle/ && \
    \
    : 'キャッシュ削除' && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# 作業ディレクトリ: host:./ => service://cli:/work/
WORKDIR /work/

# ホスト側カレントユーザと同一IDのユーザを作業者に指定
## ファイルパーミッション問題の対策
USER $UID
