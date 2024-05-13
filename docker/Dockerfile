FROM php:8.1.1-apache

RUN apt-get update
RUN apt-get install -y libzip-dev zip cron
RUN docker-php-ext-install mysqli pdo pdo_mysql zip

RUN groupadd -r mysql && useradd -r -g mysql mysql

# Copy crontab
COPY ./docker/crontab /etc/cron.d/crontab
RUN chmod 0644 /etc/cron.d/crontab

# Apply cron job
RUN crontab /etc/cron.d/crontab
RUN touch /var/log/cron.log

COPY  ${project_root}/docker/conf/000-default.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite
RUN a2enmod headers

# INSTALL COMPOSER
COPY --from=composer /usr/bin/composer /usr/bin/composer

CMD cron && /usr/sbin/apache2ctl -D FOREGROUND

CMD ./docker/apache-entrypoint.sh

