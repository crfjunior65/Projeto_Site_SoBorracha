# Só Borracha Ltda - Site Institucional

Este projeto é um site institucional para a loja **Só Borracha Ltda**, especializada em borrachas automotivas para varejo e atacado.

## Funcionalidades

- **Página Inicial:** Apresentação da empresa, produtos e diferenciais.
- **Sobre:** Informações sobre a história e missão da Só Borracha.
- **Contato:** Formulário para contato, endereço, e-mail e horário de funcionamento.
- **Design Responsivo:** Layout moderno e adaptável para dispositivos móveis.
- **Imagens Ilustrativas:** Uso de imagens de borrachas automotivas e carros (livres de direitos).
- **Rodapé:** Informações de copyright e dados de contato.

## Informações de Contato

- **Endereço:** Av. Calogeras, 1300, Campo Grande - MS
- **E-mail:** ronaldo@soborracha.com.br
- **Horário de Atendimento:** 7:30 às 17:30

## Como Executar

1. **Docker:** O projeto possui um `Dockerfile` para facilitar a execução em ambiente PHP + Apache.
2. **Instalação Manual:** Basta copiar os arquivos para um servidor Apache com PHP 8.2+.

## Estrutura de Pastas

- `/public` — Arquivos públicos do site (HTML/PHP, CSS, imagens)
- `/src/includes` — Componentes reutilizáveis (header, footer)
- `/Dockerfile` — Configuração para ambiente Docker

---
/projeto-web/
├── Dockerfile
├── docker-compose.yml (opcional)
└── site/              # Pasta que será mapeada como volume
    ├── index.php
    ├── index.html
    ├── css/
    └── ...
---
# Docker Web Server with Apache + PHP

## 📋 Project Structure

/projeto-web/
├── Dockerfile # Container configuration
├── docker-compose.yml # Service definition (recommended)
├── php.ini # Custom PHP settings (optional)
└── site/ # Website files (mapped volume)
├── index.php
├── index.html
└── css/
    ---

## 🚀 Quick Start

### With Docker Compose (recommended):
```bash
docker-compose up -d

With Docker only:
bash

docker build -t web-server .
docker run -d -p 8080:80 -v $(pwd)/site:/var/www/html web-server

🔧 Configuration Files
Dockerfile
dockerfile

FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip && \
    docker-php-ext-install zip pdo pdo_mysql && \
    a2enmod rewrite

WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]

docker-compose.yml
yaml

version: '3.8'

services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - ./site:/var/www/html
    restart: unless-stopped

🌐 Access

    Web Server: http://localhost:8080

    Files: Edit in ./site/ (changes appear immediately)

🛠️ Customization
Add PHP extensions:
dockerfile

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

Custom php.ini:

    Create php.ini file

    Add to Dockerfile:

dockerfile

COPY php.ini /usr/local/etc/php/conf.d/custom.ini

📜 Useful Commands
Command	Description
docker-compose logs	View service logs
docker exec -it web-server bash	Enter container
docker-compose down	Stop services
🔍 Troubleshooting

    Permission issues:
    bash

sudo chown -R $USER:$USER ./site

Apache not starting:
bash

    docker-compose logs web

📌 Note: For production environments, consider adding:

    SSL certificates

    Reverse proxy (Nginx)

    Proper security hardening

---
docker run -d \
  -p 8080:80 \
  -v "$(pwd)/site:/var/www/html" \
  --name meu-site \
  meu-servidor-web

Desenvolvido por: [Junior Fernandes]