FROM php:5.6-cli

WORKDIR /usr/src/app/

COPY composer-install.sh /usr/src/app/

RUN apt-get update
RUN apt-get install wget
RUN ./composer-install.sh
