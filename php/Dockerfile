FROM php:8.1.15-apache

RUN apt update \
    && apt install -y \ 
        zip \
        cron \
        ghostscript

ENV APACHE_DOCUMENT_ROOT /var/www/html/web

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN touch /var/log/cron.log
COPY cron-daemon /etc/cron.d/cron-daemon
RUN chmod 0644 /etc/cron.d/cron-daemon
RUN crontab /etc/cron.d/cron-daemon

WORKDIR /var/www/html

CMD cron && php composer.phar install && apache2-foreground
