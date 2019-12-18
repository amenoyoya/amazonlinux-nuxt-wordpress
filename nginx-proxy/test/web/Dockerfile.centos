FROM centos:7

RUN yum -y update
RUN yum -y install vim git zip unzip

# Apache + PHP7 インストール
RUN rpm --import http://vault.centos.org/RPM-GPG-KEY-CentOS-7
RUN rpm --import http://rpms.famillecollet.com/RPM-GPG-KEY-remi
RUN yum -y install httpd
RUN yum -y install epel-release
RUN yum -y install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum -y install --enablerepo=remi,remi-php73 php php-mbstring php-pdo php-mysql php-intl php-dom

# nodejsインストール
RUN curl -sL https://rpm.nodesource.com/setup_10.x | bash -
RUN yum -y install nodejs
RUN npm install -g yarn

# composerインストール
# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
# RUN php composer-setup.php
# RUN php -r "unlink('composer-setup.php');"
# RUN mv composer.phar /usr/local/bin/composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# sendmail -> mhsendmail 置き換え
RUN curl -sSLO https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail_linux_amd64 \
    && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

ENTRYPOINT ["/usr/sbin/httpd", "-DFOREGROUND"]
EXPOSE 80