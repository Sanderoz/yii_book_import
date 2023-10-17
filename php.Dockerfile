FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
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

# Установка GD расширения
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

COPY ./settings/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY ./settings/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY ./app/ /var/www/html/

RUN chmod -R 755 /var/www/html
RUN chmod -R 777 /var/www/html/frontend/web/assets && chmod -R 777 /var/www/html/frontend/runtime

WORKDIR /var/www/html
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN composer install --no-interaction

#RUN chown -R www-data:www-data ./frontend/web ./backend/web

EXPOSE 21080

CMD ["php-fpm"]
# Запуск Supervisor и RabbitMQ при старте контейнера
#CMD ["/usr/local/bin/startup.sh"]


#COPY ./ /app/
#RUN chmod -R 777 /app/backend/web/assets && chmod -R 777 /app/backend/runtime && chmod -R 777 /app/common/uploads

#COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
#RUN chmod +x /usr/local/bin/docker-entrypoint
#ENTRYPOINT ["docker-entrypoint"]

