FROM php:7.2-cli

WORKDIR /app

RUN apt-get update \ 
    # && apt-get install -y git zlib1g-dev \
    # && docker-php-ext-install zip \
    # && a2enmod rewrite \
    # && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
    # && mv /var/www/html /var/www/public \
    && curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# COPY ./bin ./bin
# COPY ./config ./config
# COPY ./data ./data
# COPY ./public ./public
# COPY ./src ./src
# COPY ./templates ./templates
# COPY ./test ./test
# COPY ./composer.json ./composer.json

# RUN composer install