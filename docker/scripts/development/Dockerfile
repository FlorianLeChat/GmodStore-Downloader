# syntax=docker/dockerfile:1

# Use an customized image of PHP
# https://hub.docker.com/_/php
FROM php:apache

# Set the working directory to the website files
WORKDIR /var/www/html

# Use the default development PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Change current user to www-data
USER www-data