FROM php:7.2.0-fpm

MAINTAINER Puraskar Sapkota <@ems>

# Git & Zip necessary for composer install
RUN apt-get update
RUN apt-get install git zip unzip -y

# SSL necessary for pecl install
RUN apt-get install autoconf g++ make openssl libssl-dev libcurl4-openssl-dev pkg-config libsasl2-dev libpcre3-dev -y

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone (very important - all dates are instantiated in utc)
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/UTC /etc/localtime
RUN "date"

# PDO / MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Symfony cache, logs, session directories

RUN mkdir -p /symfony_tmp
RUN rm /symfony_tmp/* -Rf

RUN mkdir /symfony_tmp/cache
RUN chmod 777 /symfony_tmp/cache

RUN mkdir /symfony_tmp/logs
RUN chmod 777 /symfony_tmp/logs

RUN mkdir /symfony_tmp/sessions
RUN chmod 777 /symfony_tmp/sessions

# WORKDIR

WORKDIR /srv

# PHP INSTALLATION
# DO COPY IN PRODUCTION
#COPY srv /srv
#RUN composer install