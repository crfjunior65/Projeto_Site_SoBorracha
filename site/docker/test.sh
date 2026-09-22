#!/bin/bash

# Script de teste para verificar se o ambiente Docker está funcionando
# Só Borracha Ltda

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Contadores
TESTS_PASSED=0
TESTS_FAILED=0
TOTAL_TESTS=0

# Função para imprimir resultados
print_test_result() {
    local test_name="$1"
    local result="$2"
    local message="$3"
    
    ((TOTAL_TESTS++))
    
    if [ "$result" = "PASS" ]; then
        echo -e "${GREEN}✅ PASS${NC} - $test_name"
        ((TESTS_PASSED++))
    else
        echo -e "${RED}❌ FAIL${NC} - $test_name: $message"
        ((TESTS_FAILED++))
    fi
}

print_header() {
    echo -e "${BLUE}===========================================${NC}"
    echo -e "${BLUE}🧪 TESTE DO AMBIENTE DOCKER${NC}"
    echo -e "${BLUE}Só Borracha Ltda${NC}"
    echo -e "${BLUE}===========================================${NC}"
    echo
}

print_summary() {
    echo
    echo -e "${BLUE}===========================================${NC}"
    echo -e "${BLUE}📊 RESUMO DOS TESTES${NC}"
    echo -e "${BLUE}===========================================${NC}"
    echo -e "Total de testes: ${YELLOW}$TOTAL_TESTS${NC}"
    echo -e "Testes aprovados: ${GREEN}$TESTS_PASSED${NC}"
    echo -e "Testes falharam: ${RED}$TESTS_FAILED${NC}"
    
    if [ $TESTS_FAILED -eq 0 ]; then
        echo -e "${GREEN}🎉 TODOS OS TESTES PASSARAM!${NC}"
        echo -e "${GREEN}O ambiente está funcionando perfeitamente.${NC}"
        return 0
    else
        echo -e "${RED}⚠️  ALGUNS TESTES FALHARAM!${NC}"
        echo -e "${RED}Verifique os erros acima e corrija antes de continuar.${NC}"
        return 1
    fi
}

# Iniciar testes
print_header

echo -e "${YELLOW}🔍 Iniciando testes do ambiente...${NC}"
echo

# Teste 1: Verificar se Docker está instalado
echo -e "${BLUE}[1/12]${NC} Verificando Docker..."
if command -v docker &> /dev/null; then
    print_test_result "Docker instalado" "PASS"
else
    print_test_result "Docker instalado" "FAIL" "Docker não encontrado"
fi

# Teste 2: Verificar se Docker Compose está instalado
echo -e "${BLUE}[2/12]${NC} Verificando Docker Compose..."
if command -v docker-compose &> /dev/null; then
    print_test_result "Docker Compose instalado" "PASS"
else
    print_test_result "Docker Compose instalado" "FAIL" "Docker Compose não encontrado"
fi

# Teste 3: Verificar se containers estão rodando
echo -e "${BLUE}[3/12]${NC} Verificando containers..."
if docker-compose ps | grep -q "Up"; then
    print_test_result "Containers rodando" "PASS"
else
    print_test_result "Containers rodando" "FAIL" "Containers não estão rodando"
fi

# Teste 4: Verificar container web
echo -e "${BLUE}[4/12]${NC} Verificando container web..."
if docker-compose ps web | grep -q "Up"; then
    print_test_result "Container web ativo" "PASS"
else
    print_test_result "Container web ativo" "FAIL" "Container web não está rodando"
fi

# Teste 5: Verificar container database
echo -e "${BLUE}[5/12]${NC} Verificando container database..."
if docker-compose ps db | grep -q "Up"; then
    print_test_result "Container database ativo" "PASS"
else
    print_test_result "Container database ativo" "FAIL" "Container database não está rodando"
fi

# Teste 6: Verificar conexão com MySQL
echo -e "${BLUE}[6/12]${NC} Testando conexão MySQL..."
if docker-compose exec -T db mysql -u soborracha_user -psoborracha_pass -e "SELECT 1;" soborracha_db &> /dev/null; then
    print_test_result "Conexão MySQL" "PASS"
else
    print_test_result "Conexão MySQL" "FAIL" "Não foi possível conectar ao MySQL"
fi

# Teste 7: Verificar se site principal responde
echo -e "${BLUE}[7/12]${NC} Testando site principal..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 | grep -q "200"; then
    print_test_result "Site principal (HTTP 200)" "PASS"
else
    print_test_result "Site principal (HTTP 200)" "FAIL" "Site não responde ou erro HTTP"
fi

# Teste 8: Verificar se página de fornecedores responde
echo -e "${BLUE}[8/12]${NC} Testando página de fornecedores..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/fornecedores.php | grep -q "200"; then
    print_test_result "Página de fornecedores (HTTP 200)" "PASS"
else
    print_test_result "Página de fornecedores (HTTP 200)" "FAIL" "Página não responde ou erro HTTP"
fi

# Teste 9: Verificar se admin responde
echo -e "${BLUE}[9/12]${NC} Testando painel admin..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/admin | grep -q "200"; then
    print_test_result "Painel admin (HTTP 200)" "PASS"
else
    print_test_result "Painel admin (HTTP 200)" "FAIL" "Admin não responde ou erro HTTP"
fi

# Teste 10: Verificar se phpMyAdmin responde
echo -e "${BLUE}[10/12]${NC} Testando phpMyAdmin..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8081 | grep -q "200"; then
    print_test_result "phpMyAdmin (HTTP 200)" "PASS"
else
    print_test_result "phpMyAdmin (HTTP 200)" "FAIL" "phpMyAdmin não responde ou erro HTTP"
fi

# Teste 11: Verificar se tabelas do banco existem
echo -e "${BLUE}[11/12]${NC} Verificando estrutura do banco..."
if docker-compose exec -T db mysql -u soborracha_user -psoborracha_pass -e "SHOW TABLES;" soborracha_db | grep -q "suppliers"; then
    print_test_result "Tabelas do banco criadas" "PASS"
else
    print_test_result "Tabelas do banco criadas" "FAIL" "Tabelas não encontradas"
fi

# Teste 12: Verificar se há dados de exemplo
echo -e "${BLUE}[12/12]${NC} Verificando dados de exemplo..."
supplier_count=$(docker-compose exec -T db mysql -u soborracha_user -psoborracha_pass -e "SELECT COUNT(*) FROM suppliers;" soborracha_db 2>/dev/null | tail -n 1)
if [ "$supplier_count" -gt 0 ] 2>/dev/null; then
    print_test_result "Dados de exemplo carregados" "PASS"
else
    print_test_result "Dados de exemplo carregados" "FAIL" "Nenhum fornecedor encontrado"
fi

# Testes adicionais de funcionalidade
echo
echo -e "${YELLOW}🔧 Testes adicionais de funcionalidade...${NC}"

# Teste de permissões de upload
echo -e "${BLUE}[EXTRA]${NC} Verificando permissões de upload..."
if docker-compose exec web test -w /var/www/html/admin/uploads/suppliers; then
    print_test_result "Permissões de upload" "PASS"
else
    print_test_result "Permissões de upload" "FAIL" "Diretório não tem permissão de escrita"
fi

# Teste de configuração PHP
echo -e "${BLUE}[EXTRA]${NC} Verificando configuração PHP..."
if docker-compose exec web php -m | grep -q "pdo_mysql"; then
    print_test_result "Extensão PDO MySQL" "PASS"
else
    print_test_result "Extensão PDO MySQL" "FAIL" "Extensão não encontrada"
fi

# Teste de mod_rewrite do Apache
echo -e "${BLUE}[EXTRA]${NC} Verificando mod_rewrite..."
if docker-compose exec web apache2ctl -M | grep -q "rewrite_module"; then
    print_test_result "Apache mod_rewrite" "PASS"
else
    print_test_result "Apache mod_rewrite" "FAIL" "Módulo não carregado"
fi

# Mostrar informações úteis
echo
echo -e "${YELLOW}📋 Informações do ambiente:${NC}"
echo -e "Docker version: $(docker --version 2>/dev/null || echo 'N/A')"
echo -e "Docker Compose version: $(docker-compose --version 2>/dev/null || echo 'N/A')"
echo -e "Containers ativos: $(docker-compose ps --services --filter status=running | wc -l)"
echo -e "Uso de memória: $(docker stats --no-stream --format 'table {{.Container}}\t{{.MemUsage}}' | tail -n +2 | head -3)"

# Mostrar URLs importantes
echo
echo -e "${YELLOW}🌐 URLs de acesso:${NC}"
echo -e "Site principal: ${GREEN}http://localhost:8080${NC}"
echo -e "Página de fornecedores: ${GREEN}http://localhost:8080/fornecedores.php${NC}"
echo -e "Painel admin: ${GREEN}http://localhost:8080/admin${NC}"
echo -e "phpMyAdmin: ${GREEN}http://localhost:8081${NC}"

# Mostrar credenciais
echo
echo -e "${YELLOW}🔐 Credenciais padrão:${NC}"
echo -e "Admin: usuário '${GREEN}admin${NC}', senha '${GREEN}admin123${NC}'"
echo -e "MySQL: usuário '${GREEN}soborracha_user${NC}', senha '${GREEN}soborracha_pass${NC}'"

# Resumo final
print_summary
exit_code=$?

# Sugestões baseadas nos resultados
if [ $exit_code -ne 0 ]; then
    echo
    echo -e "${YELLOW}💡 Sugestões para resolver problemas:${NC}"
    echo -e "1. Verificar se as portas 8080, 8081 e 3306 estão livres"
    echo -e "2. Executar: ${GREEN}docker-compose down && docker-compose up -d${NC}"
    echo -e "3. Aguardar mais tempo para inicialização: ${GREEN}sleep 30${NC}"
    echo -e "4. Verificar logs: ${GREEN}docker-compose logs${NC}"
    echo -e "5. Reinstalar: ${GREEN}make clean && make install${NC}"
fi

exit $exit_code
