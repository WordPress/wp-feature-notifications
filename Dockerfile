ARG PHP_VERSION=7.4
FROM php:${PHP_VERSION}-cli

WORKDIR /usr/src/app/

COPY composer-install.sh /usr/src/app/

RUN apt-get update
RUN apt-get install wget -y
RUN apt-get install zip -y
RUN apt-get install git -y
RUN apt autoremove -y
RUN ./composer-install.sh
