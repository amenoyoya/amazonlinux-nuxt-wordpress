FROM amazonlinux:2

WORKDIR /var/www/html

# nodejsインストール
RUN curl -sL https://rpm.nodesource.com/setup_10.x | bash -
RUN yum install -y nodejs

# yarn導入
RUN yum install -y wget
RUN wget https://dl.yarnpkg.com/rpm/yarn.repo -O /etc/yum.repos.d/yarn.repo
RUN curl --silent --location https://rpm.nodesource.com/setup_6.x | bash -
RUN yum install -y yarn

# PHP72インストール
RUN wget https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm -O /etc/yum.repos.d/epel-release-latest-7.noarch.rpm
RUN rpm -ivh /etc/yum.repos.d/epel-release-latest-7.noarch.rpm
RUN yum install -y yum-utils http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum-config-manager --enable remi-php72
RUN yum install -y php72 php72-php

# nuxt.jsプロジェクト作成
# RUN yarn create nuxt-app app

# yarn development server
# WORKDIR /var/www/html/app
# RUN yarn dev

CMD /bin/bash
