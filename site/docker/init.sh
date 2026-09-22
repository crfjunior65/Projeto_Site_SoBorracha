#!/bin/bash

# Script de inicialização para Docker
# Só Borracha Ltda

echo "🚀 Iniciando configuração do ambiente Docker..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para imprimir mensagens coloridas
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    print_error "Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

print_message "Docker e Docker Compose encontrados!"

# Verificar se estamos no diretório correto
if [ ! -f "docker-compose.yml" ]; then
    print_error "Arquivo docker-compose.yml não encontrado. Execute este script no diretório raiz do projeto."
    exit 1
fi

print_step "1. Parando containers existentes (se houver)..."
docker-compose down

print_step "2. Removendo volumes antigos (opcional)..."
read -p "Deseja remover os volumes existentes? Isso apagará todos os dados do banco. (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose down -v
    print_warning "Volumes removidos. Todos os dados foram apagados."
fi

print_step "3. Construindo imagens Docker..."
docker-compose build --no-cache

print_step "4. Iniciando containers..."
docker-compose up -d

print_step "5. Aguardando containers ficarem prontos..."
sleep 10

# Verificar se os containers estão rodando
print_step "6. Verificando status dos containers..."
if docker-compose ps | grep -q "Up"; then
    print_message "Containers iniciados com sucesso!"
else
    print_error "Erro ao iniciar containers. Verificando logs..."
    docker-compose logs
    exit 1
fi

print_step "7. Aguardando banco de dados ficar pronto..."
sleep 15

# Verificar conexão com o banco
print_step "8. Testando conexão com o banco de dados..."
max_attempts=30
attempt=1

while [ $attempt -le $max_attempts ]; do
    if docker-compose exec -T db mysql -u soborracha_user -psoborracha_pass -e "SELECT 1;" soborracha_db &> /dev/null; then
        print_message "Conexão com banco de dados estabelecida!"
        break
    else
        print_warning "Tentativa $attempt/$max_attempts - Aguardando banco de dados..."
        sleep 2
        ((attempt++))
    fi
done

if [ $attempt -gt $max_attempts ]; then
    print_error "Não foi possível conectar ao banco de dados após $max_attempts tentativas."
    print_error "Verificando logs do banco..."
    docker-compose logs db
    exit 1
fi

print_step "9. Configurando permissões de arquivos..."
docker-compose exec web chown -R www-data:www-data /var/www/html/admin/uploads
docker-compose exec web chmod -R 755 /var/www/html/admin/uploads

print_step "10. Copiando configuração Docker para produção..."
if [ -f "config/database.docker.php" ]; then
    cp config/database.docker.php config/database.php
    print_message "Configuração Docker aplicada!"
fi

print_message "✅ Configuração concluída com sucesso!"
echo
echo "🌐 URLs de acesso:"
echo "   Site principal: http://localhost:8080"
echo "   Página de fornecedores: http://localhost:8080/fornecedores.php"
echo "   Administração: http://localhost:8080/admin"
echo "   phpMyAdmin: http://localhost:8081"
echo
echo "🔐 Credenciais padrão:"
echo "   Admin: usuário 'admin', senha 'admin123'"
echo "   MySQL: usuário 'soborracha_user', senha 'soborracha_pass'"
echo "   MySQL Root: senha 'root_password'"
echo
echo "📋 Comandos úteis:"
echo "   Parar containers: docker-compose down"
echo "   Ver logs: docker-compose logs"
echo "   Reiniciar: docker-compose restart"
echo "   Acessar container web: docker-compose exec web bash"
echo "   Acessar MySQL: docker-compose exec db mysql -u root -p"
echo
print_warning "⚠️  IMPORTANTE: Altere as senhas padrão antes de usar em produção!"
echo
