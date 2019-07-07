# 超軽量Linux AlpineベースのPythonイメージを使用
FROM python:3.5.2-alpine

# 作業ディレクトリを変数`project_dir`に格納
ARG project_dir=/web/app/

# 必要なファイルをコンテナ内にコピー
COPY requirements.txt $project_dir
#COPY index.py $project_dir

# requirements.txtに記載されたパッケージをインストール
WORKDIR $project_dir
RUN pip install --upgrade pip && \
    pip install -r requirements.txt
