FROM php:8.1-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

ENV APACHE_LOG_DIR /var/log/apache2

RUN ln -f -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

ENV APACHE_RUN_USER nobody
ENV APACHE_RUN_GROUP www-data
