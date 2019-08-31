FROM python:3.7-slim-buster

# JupyterNotebook と Julia 1.2 インストール
WORKDIR /usr/src/
RUN apt update && apt upgrade -y && \
    apt install -y wget && \
    pip install --upgrade pip && \
    pip install jupyter && \
    wget -O - https://julialang-s3.julialang.org/bin/linux/x64/1.2/julia-1.2.0-linux-x86_64.tar.gz | tar xvzf - && \
    ln -s /usr/src/julia-1.2.0/bin/julia /usr/local/bin/julia && \
    julia -e 'using Pkg; Pkg.add("IJulia")'

# JupyterNotebook設定
COPY ./jupyter_notebook_config.py /root/.jupyter/

WORKDIR /var/www/app
