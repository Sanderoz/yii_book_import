FROM php:8.2-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

#USER 1000

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libmcrypt-dev \
    default-mysql-client \
    supervisor \
    librabbitmq-dev \
    git \
    curl \
    libfreetype6-dev  \
    libjpeg62-turbo-dev  \
    libpng-dev  \
    libmagickwand-dev

RUN docker-php-ext-install pdo pdo_mysql sockets
RUN pecl install amqp imagick && docker-php-ext-enable amqp imagick

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

#COPY ./settings/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY ./settings/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY ./app /var/www/html/

WORKDIR /var/www/html

#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN composer install

RUN groupadd -g 1000 mygroup && \
    useradd -u 1000 -g mygroup -m myuser
USER myuser

#RUN chmod -R 777 /var/www/html/common/uploads
#RUN chown -R www-data:www-data ./frontend/web ./backend/web

#EXPOSE 9000

#CMD ["supervisord", "-n"]
