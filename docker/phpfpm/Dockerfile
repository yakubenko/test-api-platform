FROM ubuntu:20.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt update

RUN apt install -y lsb-release ca-certificates apt-transport-https software-properties-common \
    supervisor iputils-ping libboost-dev libxml2-dev curl nano wget

RUN add-apt-repository -y ppa:ondrej/php && apt update

RUN apt install -y php8.1-cli php8.1-fpm php8.1-curl php8.1-mbstring \
    php8.1-xml php8.1-intl php8.1-zip php8.1-xdebug php8.1-pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir /run/php

CMD ["supervisord", "-c", "/etc/supervisor/supervisor_nodeamon.conf"]

EXPOSE 9000
