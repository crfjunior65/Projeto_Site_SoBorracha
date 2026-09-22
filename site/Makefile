# Makefile para Só Borracha Ltda - Docker
# Comandos simplificados para gerenciar o ambiente

.PHONY: help build up down restart logs clean install backup restore test

# Cores para output
GREEN=\033[0;32m
YELLOW=\033[1;33m
RED=\033[0;31m
NC=\033[0m # No Color

# Comando padrão
help: ## Mostrar esta ajuda
	@echo "$(GREEN)Só Borracha Ltda - Comandos Docker$(NC)"
	@echo ""
	@echo "$(YELLOW)Comandos disponíveis:$(NC)"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  $(GREEN)%-15s$(NC) %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Instalar e configurar o ambiente completo
	@echo "$(GREEN)🚀 Instalando ambiente Docker...$(NC)"
	@./docker/init.sh

build: ## Construir as imagens Docker
	@echo "$(GREEN)🔨 Construindo imagens...$(NC)"
	@docker-compose build --no-cache

up: ## Iniciar os containers
	@echo "$(GREEN)⬆️  Iniciando containers...$(NC)"
	@docker-compose up -d
	@echo "$(GREEN)✅ Containers iniciados!$(NC)"
	@echo "$(YELLOW)Site: http://localhost:8080$(NC)"
	@echo "$(YELLOW)Admin: http://localhost:8080/admin$(NC)"
	@echo "$(YELLOW)phpMyAdmin: http://localhost:8081$(NC)"

down: ## Parar os containers
	@echo "$(RED)⬇️  Parando containers...$(NC)"
	@docker-compose down

restart: ## Reiniciar os containers
	@echo "$(YELLOW)🔄 Reiniciando containers...$(NC)"
	@docker-compose restart
	@echo "$(GREEN)✅ Containers reiniciados!$(NC)"

logs: ## Ver logs dos containers
	@echo "$(YELLOW)📋 Logs dos containers:$(NC)"
	@docker-compose logs -f

logs-web: ## Ver logs apenas do container web
	@echo "$(YELLOW)📋 Logs do container web:$(NC)"
	@docker-compose logs -f web

logs-db: ## Ver logs apenas do container de banco
	@echo "$(YELLOW)📋 Logs do container de banco:$(NC)"
	@docker-compose logs -f db

status: ## Ver status dos containers
	@echo "$(YELLOW)📊 Status dos containers:$(NC)"
	@docker-compose ps

shell-web: ## Acessar shell do container web
	@echo "$(GREEN)🐚 Acessando container web...$(NC)"
	@docker-compose exec web bash

shell-db: ## Acessar MySQL do container de banco
	@echo "$(GREEN)🗄️  Acessando MySQL...$(NC)"
	@docker-compose exec db mysql -u soborracha_user -psoborracha_pass soborracha_db

clean: ## Limpar containers, imagens e volumes
	@echo "$(RED)🧹 Limpando ambiente Docker...$(NC)"
	@read -p "Tem certeza? Isso removerá TODOS os dados (y/N): " confirm && \
	if [ "$$confirm" = "y" ] || [ "$$confirm" = "Y" ]; then \
		docker-compose down -v --rmi all --remove-orphans; \
		docker system prune -f; \
		echo "$(GREEN)✅ Limpeza concluída!$(NC)"; \
	else \
		echo "$(YELLOW)Operação cancelada.$(NC)"; \
	fi

backup: ## Fazer backup do banco de dados
	@echo "$(GREEN)💾 Fazendo backup do banco...$(NC)"
	@mkdir -p backups
	@docker-compose exec -T db mysqldump -u soborracha_user -psoborracha_pass soborracha_db > backups/backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)✅ Backup salvo em backups/$(NC)"

restore: ## Restaurar backup do banco (especificar arquivo com FILE=nome.sql)
	@if [ -z "$(FILE)" ]; then \
		echo "$(RED)❌ Especifique o arquivo: make restore FILE=backup.sql$(NC)"; \
		exit 1; \
	fi
	@if [ ! -f "backups/$(FILE)" ]; then \
		echo "$(RED)❌ Arquivo backups/$(FILE) não encontrado$(NC)"; \
		exit 1; \
	fi
	@echo "$(YELLOW)🔄 Restaurando backup $(FILE)...$(NC)"
	@docker-compose exec -T db mysql -u soborracha_user -psoborracha_pass soborracha_db < backups/$(FILE)
	@echo "$(GREEN)✅ Backup restaurado!$(NC)"

test: ## Testar se o ambiente está funcionando
	@echo "$(GREEN)🧪 Testando ambiente...$(NC)"
	@echo "$(YELLOW)Testando containers...$(NC)"
	@docker-compose ps | grep -q "Up" && echo "$(GREEN)✅ Containers rodando$(NC)" || echo "$(RED)❌ Containers não estão rodando$(NC)"
	@echo "$(YELLOW)Testando site...$(NC)"
	@curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 | grep -q "200" && echo "$(GREEN)✅ Site respondendo$(NC)" || echo "$(RED)❌ Site não responde$(NC)"
	@echo "$(YELLOW)Testando admin...$(NC)"
	@curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/admin | grep -q "200" && echo "$(GREEN)✅ Admin respondendo$(NC)" || echo "$(RED)❌ Admin não responde$(NC)"
	@echo "$(YELLOW)Testando phpMyAdmin...$(NC)"
	@curl -s -o /dev/null -w "%{http_code}" http://localhost:8081 | grep -q "200" && echo "$(GREEN)✅ phpMyAdmin respondendo$(NC)" || echo "$(RED)❌ phpMyAdmin não responde$(NC)"

update: ## Atualizar containers e reconstruir
	@echo "$(GREEN)🔄 Atualizando ambiente...$(NC)"
	@docker-compose down
	@docker-compose build --no-cache
	@docker-compose up -d
	@echo "$(GREEN)✅ Ambiente atualizado!$(NC)"

dev: ## Modo desenvolvimento (com logs em tempo real)
	@echo "$(GREEN)👨‍💻 Iniciando modo desenvolvimento...$(NC)"
	@docker-compose up

prod: ## Configurar para produção
	@echo "$(YELLOW)🏭 Configurando para produção...$(NC)"
	@echo "$(RED)⚠️  Lembre-se de:$(NC)"
	@echo "  - Alterar senhas padrão"
	@echo "  - Configurar HTTPS"
	@echo "  - Configurar backup automático"
	@echo "  - Revisar configurações de segurança"

# Comandos de desenvolvimento
composer-install: ## Instalar dependências PHP (se houver composer.json)
	@if [ -f "composer.json" ]; then \
		echo "$(GREEN)📦 Instalando dependências PHP...$(NC)"; \
		docker-compose exec web composer install; \
	else \
		echo "$(YELLOW)⚠️  Arquivo composer.json não encontrado$(NC)"; \
	fi

permissions: ## Corrigir permissões de arquivos
	@echo "$(GREEN)🔐 Corrigindo permissões...$(NC)"
	@docker-compose exec web chown -R www-data:www-data /var/www/html/admin/uploads
	@docker-compose exec web chmod -R 755 /var/www/html/admin/uploads
	@docker-compose exec web chown -R www-data:www-data /var/www/html/logs
	@docker-compose exec web chmod -R 755 /var/www/html/logs
	@echo "$(GREEN)✅ Permissões corrigidas!$(NC)"

# Comandos de monitoramento
monitor: ## Monitorar recursos dos containers
	@echo "$(GREEN)📊 Monitorando recursos...$(NC)"
	@docker stats

# Comando para desenvolvimento rápido
dev-quick: build up logs ## Construir, iniciar e mostrar logs (desenvolvimento rápido)
