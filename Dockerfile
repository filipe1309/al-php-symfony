FROM php:7.4-apache
WORKDIR /var/www/html
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN a2enmod rewrite
# Install dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && \
    apt-get install -y git build-essential curl wget software-properties-common zip unzip
# RUN chmod 0700 entrypoint.sh
# RUN chmod 0700 wait-for-it.sh
# ENTRYPOINT ["./wait-for-it.sh", "db:3306", "--", "bin/console", "--no-interaction", "doctrine:migrations:migrate"]