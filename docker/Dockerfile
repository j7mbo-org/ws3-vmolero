FROM php:7.1-apache

# Basic installation
RUN apt-get update && apt-get install -y -qq libssl-dev libxml2-dev curl nano git zip unzip libevent-dev sqlite3

# Virtualhost
COPY workshop.app.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod proxy_http headers

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer