# 🐳 Docker - Site Só Borracha Ltda

Este documento explica como usar o ambiente Docker para desenvolvimento e teste do site.

## 🚀 Início Rápido

### Pré-requisitos
- [Docker](https://docs.docker.com/get-docker/) instalado
- [Docker Compose](https://docs.docker.com/compose/install/) instalado
- Pelo menos 2GB de RAM disponível
- Portas 8080, 8081 e 3306 livres

### Instalação Automática

```bash
# Executar script de instalação
./docker/init.sh
```

### Instalação Manual

```bash
# 1. Construir e iniciar containers
docker-compose up -d --build

# 2. Aguardar containers ficarem prontos
sleep 15

# 3. Corrigir permissões
docker-compose exec web chown -R www-data:www-data /var/www/html/admin/uploads
docker-compose exec web chmod -R 755 /var/www/html/admin/uploads

# 4. Copiar configuração Docker
cp config/database.docker.php config/database.php
```

## 🌐 URLs de Acesso

| Serviço | URL | Descrição |
|---------|-----|-----------|
| **Site Principal** | http://localhost:8080 | Página inicial do site |
| **Fornecedores** | http://localhost:8080/fornecedores.php | Página de fornecedores |
| **Administração** | http://localhost:8080/admin | Painel administrativo |
| **phpMyAdmin** | http://localhost:8081 | Interface do banco de dados |

## 🔐 Credenciais Padrão

### Administração do Site
- **Usuário**: `admin`
- **Senha**: `admin123`

### Banco de Dados
- **Usuário**: `soborracha_user`
- **Senha**: `soborracha_pass`
- **Banco**: `soborracha_db`

### MySQL Root
- **Usuário**: `root`
- **Senha**: `root_password`

## 📋 Comandos Úteis

### Usando Makefile (Recomendado)

```bash
# Ver todos os comandos disponíveis
make help

# Instalar ambiente completo
make install

# Iniciar containers
make up

# Parar containers
make down

# Ver logs
make logs

# Reiniciar containers
make restart

# Acessar shell do container web
make shell-web

# Acessar MySQL
make shell-db

# Fazer backup do banco
make backup

# Testar se tudo está funcionando
make test

# Limpar tudo (cuidado!)
make clean
```

### Usando Docker Compose Diretamente

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f

# Ver status
docker-compose ps

# Reconstruir imagens
docker-compose build --no-cache

# Acessar container web
docker-compose exec web bash

# Acessar MySQL
docker-compose exec db mysql -u soborracha_user -psoborracha_pass soborracha_db
```

## 🏗️ Estrutura dos Containers

### Container Web (PHP + Apache)
- **Imagem**: PHP 8.1 com Apache
- **Porta**: 8080
- **Extensões**: PDO, MySQL, GD, Zip, Intl, OPcache
- **Diretório**: `/var/www/html`

### Container Database (MySQL)
- **Imagem**: MySQL 8.0
- **Porta**: 3306
- **Charset**: utf8mb4
- **Timezone**: America/Campo_Grande

### Container phpMyAdmin
- **Imagem**: phpMyAdmin latest
- **Porta**: 8081
- **Conecta automaticamente** ao MySQL

### Container Redis (Opcional)
- **Imagem**: Redis 7 Alpine
- **Porta**: 6379
- **Para cache futuro**

## 📁 Volumes Persistentes

```bash
# Ver volumes criados
docker volume ls | grep soborracha

# Volumes principais:
# - soborracha_mysql_data: Dados do MySQL
# - soborracha_redis_data: Dados do Redis
```

## 🔧 Configurações

### PHP (docker/php/php.ini)
- **Memory Limit**: 256M
- **Upload Max**: 10M
- **Max Execution Time**: 300s
- **Display Errors**: On (desenvolvimento)
- **OPcache**: Habilitado

### Apache (docker/apache/000-default.conf)
- **Document Root**: `/var/www/html/public`
- **Alias Admin**: `/admin`
- **Mod Rewrite**: Habilitado
- **GZIP**: Habilitado
- **Cache Headers**: Configurado

### MySQL (docker/mysql/my.cnf)
- **Charset**: utf8mb4
- **InnoDB Buffer Pool**: 256M
- **Max Connections**: 200
- **Slow Query Log**: Habilitado

## 🛠️ Desenvolvimento

### Estrutura de Arquivos
```
site/
├── docker/                 # Configurações Docker
│   ├── Dockerfile          # Imagem PHP + Apache
│   ├── init.sh            # Script de instalação
│   ├── php/php.ini        # Configuração PHP
│   ├── apache/000-default.conf # Configuração Apache
│   └── mysql/my.cnf       # Configuração MySQL
├── docker-compose.yml      # Orquestração dos containers
├── Makefile               # Comandos simplificados
└── config/database.docker.php # Configuração para Docker
```

### Hot Reload
Os arquivos são montados como volume, então **mudanças no código são refletidas imediatamente** sem precisar reconstruir containers.

### Debugging
```bash
# Ver logs em tempo real
make logs

# Ver logs apenas do web
make logs-web

# Ver logs apenas do banco
make logs-db

# Acessar container para debug
make shell-web

# Verificar configuração PHP
docker-compose exec web php -i
```

## 💾 Backup e Restore

### Backup Automático
```bash
# Fazer backup
make backup

# Backup manual com nome específico
docker-compose exec -T db mysqldump -u soborracha_user -psoborracha_pass soborracha_db > backups/meu_backup.sql
```

### Restore
```bash
# Restaurar backup específico
make restore FILE=backup_20231201_143022.sql

# Restore manual
docker-compose exec -T db mysql -u soborracha_user -psoborracha_pass soborracha_db < backups/meu_backup.sql
```

## 🔍 Troubleshooting

### Problemas Comuns

**1. Porta já está em uso**
```bash
# Verificar o que está usando a porta
sudo lsof -i :8080
sudo lsof -i :3306

# Parar processo ou alterar porta no docker-compose.yml
```

**2. Containers não iniciam**
```bash
# Ver logs detalhados
docker-compose logs

# Verificar recursos do sistema
docker system df
docker system prune -f
```

**3. Banco não conecta**
```bash
# Verificar se MySQL está pronto
docker-compose exec db mysql -u root -proot_password -e "SELECT 1;"

# Aguardar mais tempo para inicialização
sleep 30
```

**4. Permissões de arquivo**
```bash
# Corrigir permissões
make permissions

# Ou manualmente
docker-compose exec web chown -R www-data:www-data /var/www/html/admin/uploads
```

**5. Site não carrega**
```bash
# Verificar se Apache está rodando
docker-compose exec web service apache2 status

# Verificar configuração
docker-compose exec web apache2ctl configtest

# Ver logs do Apache
docker-compose exec web tail -f /var/log/apache2/error.log
```

### Logs Importantes
```bash
# Logs do PHP
docker-compose exec web tail -f /var/log/php_errors.log

# Logs do Apache
docker-compose exec web tail -f /var/log/apache2/error.log
docker-compose exec web tail -f /var/log/apache2/access.log

# Logs do MySQL
docker-compose exec db tail -f /var/log/mysql/error.log
```

## 🚀 Deploy para Produção

### Preparação
1. **Alterar senhas** em `docker-compose.yml`
2. **Configurar HTTPS** (certificado SSL)
3. **Desabilitar debug** em `config/database.php`
4. **Configurar backup automático**
5. **Configurar monitoramento**

### Exemplo de Produção
```yaml
# docker-compose.prod.yml
version: '3.8'
services:
  web:
    restart: always
    environment:
      - PHP_ENV=production
  db:
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: senha_super_segura_aqui
      MYSQL_PASSWORD: outra_senha_segura
```

## 📊 Monitoramento

### Recursos dos Containers
```bash
# Ver uso de recursos
make monitor

# Ou diretamente
docker stats
```

### Health Checks
```bash
# Testar se tudo está funcionando
make test

# Verificar individualmente
curl -I http://localhost:8080
curl -I http://localhost:8080/admin
curl -I http://localhost:8081
```

## 🤝 Contribuição

### Adicionando Novos Serviços
1. Editar `docker-compose.yml`
2. Adicionar configurações em `docker/`
3. Atualizar `Makefile` se necessário
4. Documentar no README

### Testando Mudanças
```bash
# Reconstruir após mudanças
make update

# Testar ambiente
make test
```

---

## 📞 Suporte

Se encontrar problemas:

1. **Verificar logs**: `make logs`
2. **Testar ambiente**: `make test`
3. **Limpar e reinstalar**: `make clean && make install`
4. **Verificar documentação** deste arquivo

---

**Desenvolvido para Só Borracha Ltda** 🚗  
*Ambiente Docker para desenvolvimento e teste*
