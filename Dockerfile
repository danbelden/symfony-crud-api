# docker build -t danbelden/symfony-crud-api .
FROM danbelden/ubuntu-php70-fpm-nginx

RUN apt-get update
RUN apt-get install -y php7.0-mysql
RUN apt-get install -y php7.0-xml

WORKDIR /var/www/html
