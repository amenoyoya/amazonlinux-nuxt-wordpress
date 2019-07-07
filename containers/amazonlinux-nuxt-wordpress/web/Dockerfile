FROM amazonlinux:2

WORKDIR /var/www/html

# nodejsインストール
RUN curl -sL https://rpm.nodesource.com/setup_10.x | bash -
RUN yum install -y nodejs

# yarn導入
RUN yum install -y wget \
    && wget https://dl.yarnpkg.com/rpm/yarn.repo -O /etc/yum.repos.d/yarn.repo \
    && curl --silent --location https://rpm.nodesource.com/setup_6.x | bash - \
    && yum install -y yarn

# PHP72インストール
RUN wget https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm -O /etc/yum.repos.d/epel-release-latest-7.noarch.rpm \
    && rpm -ivh /etc/yum.repos.d/epel-release-latest-7.noarch.rpm \
    && yum install -y yum-utils http://rpms.famillecollet.com/enterprise/remi-release-7.rpm \
    && yum-config-manager --enable remi-php72 \
    && yum install -y php72 php72-php php72-php-mbstring php72-php-mysqlnd php72-php-pdo

# Apache実行
EXPOSE 80
EXPOSE 3000
COPY ./httpd.conf /etc/httpd/conf/
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
