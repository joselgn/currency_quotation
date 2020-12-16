FROM centos/httpd

MAINTAINER Jose Carlos Fernandes <joselgn@gmail.com>

RUN yum -y update && yum -y install wget gcc-c++ make http://rpms.remirepo.net/enterprise/remi-release-7.rpm && yum clean all

RUN yum-config-manager --enable remi-php72 && yum -y update

RUN yum install -y \
    php php-gd php-intl php-pdo php-mysql php-mysqli php-mbstring php-zip php-zlib php-pecl php-xml php-common php-opcache php-mcrypt \
    php-cli php-curl php-mysqlnd \
 && yum clean all

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && HASH="$(wget -q -O - https://composer.github.io/installer.sig)" \
 && php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/httpd/conf/httpd.conf && sed -i 's!AllowOverride None!AllowOverride All!g' /etc/httpd/conf/httpd.conf \
 && echo "LoadModule rewrite_module modules/mod_rewrite.so" >> /etc/httpd/conf.modules.d/00-base.conf

WORKDIR /var/www/html

EXPOSE 80