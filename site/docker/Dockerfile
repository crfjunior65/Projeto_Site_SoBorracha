FROM php:8.1-apache

# Instalar extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        intl \
        mbstring \
        xml \
        opcache \
        exif

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilitar módulos do Apache
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod ssl
RUN a2enmod expires
RUN a2enmod deflate

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos de configuração
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/php.ini

# Definir permissões
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Criar diretórios necessários
RUN mkdir -p /var/www/html/admin/uploads/suppliers \
    && mkdir -p /var/www/html/admin/uploads/products \
    && mkdir -p /var/www/html/admin/uploads/site \
    && mkdir -p /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/admin/uploads \
    && chown -R www-data:www-data /var/www/html/logs \
    && chmod -R 755 /var/www/html/admin/uploads \
    && chmod -R 755 /var/www/html/logs

# Expor porta 80
EXPOSE 80

# Comando padrão
CMD ["apache2-foreground"]
