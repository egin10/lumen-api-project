FROM php:7.3.25-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql