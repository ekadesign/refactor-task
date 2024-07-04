FROM php:8.0.11-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apk -v --update add \
    zip \
    mc \
    unzip \
    git \
    curl \
    sqlite

# Clear cache
RUN rm -rf /var/lib/apt/lists/*

# Install extensions
RUN apk add zlib-dev libzip-dev freetype-dev libxml2-dev \
    && docker-php-ext-install pdo zip


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc

RUN addgroup -S www && adduser -S www -G www

# Change current user to www
USER www

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
