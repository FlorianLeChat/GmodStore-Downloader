# syntax=docker/dockerfile:1

# Use an customized image of PHP 8.2 with Nginx
# https://github.com/webdevops/Dockerfile/blob/master/docker/php-nginx/8.2-alpine/Dockerfile
ARG PHP_VERSION
FROM webdevops/php-nginx:${PHP_VERSION}-alpine

# Copy the website files to the container
COPY ./ /app

# Apply a workaround to the GmodStore library
RUN sed -i "s/ObjectSerializer::deserialize(\$content, '\Everyday\GmodStore\Sdk\Model\DownloadProductVersionResponse', \[])/json_decode(\$content, true)/g" /app/vendor/everyday/gmodstore-sdk/lib/Api/ProductVersionsApi.php

# Install Composer and run it to install the dependencies
RUN composer install -d /app