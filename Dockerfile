FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/html
COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf
RUN rm /etc/nginx/sites-enabled/default
EXPOSE 80

CMD ["sh", "-c", "php-fpm -F & nginx -g 'daemon off;'"]

