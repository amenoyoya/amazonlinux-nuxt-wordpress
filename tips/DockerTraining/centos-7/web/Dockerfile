FROM centos:centos7

WORKDIR /var/www/html/

# Install yum packages
RUN yum update && yum upgrade -y && \
    yum install -y vim git zip unzip && \
    : 'Install Apache' && \
    rpm --import http://vault.centos.org/RPM-GPG-KEY-CentOS-7 && \
    rpm --import http://rpms.famillecollet.com/RPM-GPG-KEY-remi && \
    yum -y install httpd && \
    : 'Install PHP 7.2 + 拡張モジュール' && \
    yum install -y epel-release && \
    rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum install -y --enablerepo=epel,remi,remi-php72 php php-mbstring php-pdo php-mysqlnd php-xml php-intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
