FROM php:8.2-cli

WORKDIR /app

# install dependency
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy project
COPY . .

# install laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# expose port
EXPOSE 8080

# start laravel
CMD php -S 0.0.0.0:$PORT -t public