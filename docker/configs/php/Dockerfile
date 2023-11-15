FROM php:8.2-fpm
ARG WORKDIR
WORKDIR ${WORKDIR}

RUN apt-get -y update && apt-get -y install git unzip rsync

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug;
COPY ./docker/configs/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Add a simple script that emulates the sendmail command and writes to a unique file each time
RUN echo '#!/bin/sh\nFILE=/var/www/mail/$(date +"%Y%m%d%H%M%S").txt\ncat > $FILE' > /usr/sbin/sendmail && \
    chmod +x /usr/sbin/sendmail


ENV TERM xterm-256color

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer


CMD php-fpm
EXPOSE 9000