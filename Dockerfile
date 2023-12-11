# syntax=docker/dockerfile:1

# Use an customized image of PHP
# https://hub.docker.com/_/php
ARG VERSION=apache
FROM php:${VERSION}

# Install some PHP extensions
RUN curl -sSLf \
		-o /usr/local/bin/install-php-extensions \
		https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
	chmod +x /usr/local/bin/install-php-extensions && \
	install-php-extensions zip opcache

# Install Composer for dependency management
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# Use the PHP production configuration
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Set the working directory to the website files
WORKDIR /var/www/html

# Copy all files to the working directory
COPY --chown=www-data:www-data . .

# Use the PHP custom configuration (if exists)
RUN if [ -f "docker/php.ini" ]; then mv "docker/php.ini" "$PHP_INI_DIR/php.ini"; fi

# Change current user to www-data
USER www-data

# Install all dependencies
# Use cache mount to speed up installation of existing dependencies
RUN --mount=type=cache,target=.composer \
	composer install --no-dev --optimize-autoloader

# Apply a workaround to the GmodStore library
RUN sed -i "s/ObjectSerializer::deserialize(\$content, '\Everyday\GmodStore\Sdk\Model\DownloadProductVersionResponse', \[])/json_decode(\$content, true)/g" vendor/everyday/gmodstore-sdk/lib/Api/ProductVersionsApi.php