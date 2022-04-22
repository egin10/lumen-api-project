FROM php:7.3-fpm
ENV ACCEPT_EULA=Y

# Install nginx
RUN apt-get update -y \
    && apt-get install -y nginx

# PHP_CPPFLAGS are used by the docker-php-ext-* scripts
# ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

# Install PHP ext
RUN docker-php-ext-install mbstring pdo pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install opcache \
    && apt-get install libicu-dev -y \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && apt-get remove libicu-dev icu-devtools -y
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/php-opocache-cfg.ini

# Install Composer && MongoDB
RUN apt-get update && apt-get install -y git zip unzip \
    && apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && docker-php-ext-install opcache \
    && pecl install mongodb apcu && docker-php-ext-enable mongodb apcu opcache

COPY nginx-site.conf /etc/nginx/sites-enabled/default
COPY run-app.sh /etc/run-app.sh
RUN chmod +x /etc/run-app.sh

COPY --chown=www-data:www-data ./src /var/www/app

WORKDIR /var/www/app

# Install Redis
RUN pecl install redis && docker-php-ext-enable redis.so

RUN rm -rf /vendor && rm composer.lock
RUN composer update

EXPOSE 80 443

ENTRYPOINT ["/etc/run-app.sh"]