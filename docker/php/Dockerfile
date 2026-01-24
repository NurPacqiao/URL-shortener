FROM php:8.4-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    librabbitmq-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_pgsql intl \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Install Composer (The PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
