# syntax=docker/dockerfile:1

# Use an customized image of PHP 8.2 with Nginx
# https://github.com/webdevops/Dockerfile/blob/master/docker/php-nginx/8.2-alpine/Dockerfile
FROM webdevops/php-nginx:8.2-alpine

# Set the working directory to the website files
WORKDIR /app

# Copy only files required to install dependencies
COPY composer*.json ./

# Install all dependencies
# Use cache mount to speed up installation of existing dependencies
RUN --mount=type=cache,target=/app/.composer \
	composer install

# Apply a workaround to the GmodStore library
RUN sed -i "s/ObjectSerializer::deserialize(\$content, '\Everyday\GmodStore\Sdk\Model\DownloadProductVersionResponse', \[])/json_decode(\$content, true)/g" /app/vendor/everyday/gmodstore-sdk/lib/Api/ProductVersionsApi.php

# Copy the remaining files AFTER installing dependencies
COPY . .