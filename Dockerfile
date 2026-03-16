FROM php:8.4-fpm

# Argumentos
ARG user=larachat
ARG uid=1000

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libwebp-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        xml \
        opcache

# Redis via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Usuário não-root
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Diretório da aplicação
RUN mkdir -p /var/www && chown -R $user:$user /var/www
WORKDIR /var/www

# Copia arquivos de dependências primeiro (cache de camadas)
COPY --chown=$user:$user composer.json composer.lock ./
COPY --chown=$user:$user package.json ./

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER $user

# Instala dependências PHP (sem scripts para não falhar sem .env)
RUN composer install --no-scripts --no-autoloader --prefer-dist

# Copia o restante da aplicação
COPY --chown=$user:$user . .

# Finaliza autoload
RUN composer dump-autoload --optimize

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
