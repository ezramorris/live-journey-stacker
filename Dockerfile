FROM php:8.3-apache

ENV DEBUG=true
ENV BASE_PATH=/

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"