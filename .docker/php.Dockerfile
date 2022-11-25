FROM php:apache-buster

COPY user.php.ini /usr/local/etc/php/conf.d/user.php.ini

RUN apt-get update && \
    apt-get install -y git zip postgresql libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql

RUN mkdir -p /var/www/html/public

COPY dev-site.conf /etc/apache2/sites-available/dev-site.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf &&\
    a2enmod rewrite &&\
    a2dissite 000-default &&\
    a2ensite dev-site &&\
    service apache2 restart

WORKDIR /var/www/html

# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
#RUN composer install \
# --no-dev
