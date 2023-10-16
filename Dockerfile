# syntax=docker/dockerfile:1

# Use an customized image of PHP
# https://hub.docker.com/_/php
ARG VERSION=8.2-apache
FROM php:${VERSION}

# Install dependencies
ARG MANAGER=apt
RUN if [ $MANAGER = "apt" ]; then \
        apt update && apt install git zip unzip libzip-dev -y; \
    else \
		echo https://dl-4.alpinelinux.org/alpine/v3.18/community/ >> /etc/apk/repositories && \
		apk update && \
        apk add --no-cache git zip unzip libzip-dev; \
    fi

# Install some PHP extensions
RUN docker-php-ext-install zip

# Install Composer for dependency management
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# Use the PHP production configuration
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Set the working directory to the website files
WORKDIR /var/www/html

# Copy only files required to install dependencies
COPY --chown=www-data:www-data composer*.json ./

# Change current user to www-data
USER www-data

# Install all dependencies
# Use cache mount to speed up installation of existing dependencies
RUN --mount=type=cache,target=.composer \
	composer install --no-dev --optimize-autoloader

# Apply a workaround to the GmodStore library
RUN sed -i "s/ObjectSerializer::deserialize(\$content, '\Everyday\GmodStore\Sdk\Model\DownloadProductVersionResponse', \[])/json_decode(\$content, true)/g" vendor/everyday/gmodstore-sdk/lib/Api/ProductVersionsApi.php

# Copy the remaining files AFTER installing dependencies
COPY --chown=www-data:www-data . .

# Use the PHP custom configuration (if exists)
RUN if [ -f "docker/php.ini" ]; then mv "docker/php.ini" "$PHP_INI_DIR/php.ini"; fi