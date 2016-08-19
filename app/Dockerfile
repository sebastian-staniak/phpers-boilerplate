FROM php:7.0-fpm

RUN apt-get update && \
    apt-get install -y git curl && \
    docker-php-ext-install -j$(nproc) bcmath && \
    docker-php-ext-install -j$(nproc) mbstring && \
    pecl install redis \
    &&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["bash"]
