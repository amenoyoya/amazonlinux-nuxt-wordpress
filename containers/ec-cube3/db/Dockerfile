FROM mysql:5.6

# my.cnf は パーミッション644 じゃないと読んでくれないため以下のように設定
ADD ./my.cnf /etc/mysql/my.cnf
RUN chmod 644 /etc/mysql/my.cnf

WORKDIR /var/lib/mysql
