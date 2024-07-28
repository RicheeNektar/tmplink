FROM php:8-apache-bookworm

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install cron -y

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

ADD ./ /var/www/html/

RUN mv /var/www/html/config/docker/php/apache2.conf /etc/apache2/sites-available/app.conf \
    && mv /var/www/html/config/docker/php/crontab /etc/cron.d/app

RUN a2dissite 000-default.conf \
    && a2ensite app.conf \
    && apache2ctl restart

RUN service cron reload

ENTRYPOINT ["/var/www/html/scripts/docker/entrypoint.sh"]