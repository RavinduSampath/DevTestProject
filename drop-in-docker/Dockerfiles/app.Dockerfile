FROM php:8.2.5-fpm-alpine3.17

ARG UID

# Add shadow and sudo
RUN apk --update add shadow sudo

# Update user and group IDs for www-data
RUN usermod -u $UID www-data && groupmod -g $UID www-data

# Allow www-data to use sudo without a password
RUN echo "www-data ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

# Install Composer
RUN apk --update add composer

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install additional PHP extensions via apk
RUN apk --no-cache add \
    php-openssl \
    php-simplexml \
    php-mbstring \
    php-dom \
    php-fileinfo \
    php-xmlwriter \
    php-xmlreader \
    php-xml \
    php-tokenizer \
    php-exif \
    php-gd \
    php-session

# Install npm and make
RUN apk add --update npm make
