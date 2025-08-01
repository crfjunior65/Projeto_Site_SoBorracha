# Usa a imagem oficial do Apache com PHP
FROM php:8.2-apache

# Atualiza os pacotes e instala extensões PHP comumente necessárias
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        && \
    docker-php-ext-install zip pdo pdo_mysql && \
    a2enmod rewrite

# Copia o arquivo de configuração personalizado do Apache (opcional)
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Define o diretório de trabalho
WORKDIR /var/www/html

# Expõe a porta 80 (HTTP)
EXPOSE 80

# Inicia o Apache em primeiro plano
CMD ["apache2-foreground"]