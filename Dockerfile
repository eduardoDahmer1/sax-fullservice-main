FROM php:8.1-fpm

# Copia o composer.lock e composer.json
COPY composer.lock composer.json /var/www/

# Defini o diretório de trabalho ( Seu projeto )
WORKDIR /var/www/sax-fullservice

# Install packges derivados do php
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
        build-essential \
        libpng-dev \
        libwebp-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        zip \
        jpegoptim optipng pngquant gifsicle \
        vim \
        unzip \
        git \
        curl \
        libzip-dev

# Instala as extensões do php
RUN docker-php-ext-install pdo_mysql zip exif pcntl \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install -j$(nproc) gd

# Limpeza do cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Adicionar usuário para aplicação Laravel
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Copia o workspace ( Seu projeto Clonado )
COPY . /var/www/sax-fullservice

# Copiar permissões de diretório de aplicativos existentes
COPY --chown=www:www . /var/www/sax-fullservice

# Phpmyadmin Instalado na (Porta 80 )
USER root
RUN apt-get update \
    && apt-get install -y wget unzip \
    && wget https://files.phpmyadmin.net/phpMyAdmin/5.1.1/phpMyAdmin-5.1.1-all-languages.zip -O /tmp/phpmyadmin.zip \
    && unzip /tmp/phpmyadmin.zip -d /var/www/html/ \
    && mv /var/www/html/phpMyAdmin-5.1.1-all-languages /var/www/html/phpmyadmin \
    && chown -R www:www /var/www/html/phpmyadmin

# Exponha a porta 9000 e inicia o servidor Php-fpm
EXPOSE 9000
CMD ["php-fpm"]
