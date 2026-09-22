# Instalação e Configuração - Site Só Borracha Ltda

## Visão Geral

Este documento contém as instruções para instalar e configurar o site da Só Borracha Ltda, incluindo o módulo administrativo e a seção de fornecedores.

## Funcionalidades Implementadas

### ✅ Site Público
- **Página de Fornecedores**: Nova seção para exibir parceiros
- **Sistema de Filtros**: Filtrar fornecedores por especialidade
- **Design Responsivo**: Compatível com todos os dispositivos
- **SEO Otimizado**: Meta tags e estrutura otimizada

### ✅ Módulo Administrativo
- **Sistema de Login**: Autenticação segura para administradores
- **Dashboard**: Painel com estatísticas e ações rápidas
- **Gerenciamento de Fornecedores**: CRUD completo
- **Gerenciamento de Produtos**: Sistema para cadastrar produtos
- **Upload de Imagens**: Sistema de upload para logos e fotos
- **Configurações do Site**: Editar conteúdo dinamicamente
- **Logs de Atividade**: Rastreamento de ações dos usuários

## Pré-requisitos

- **PHP**: Versão 7.4 ou superior
- **MySQL**: Versão 5.7 ou superior
- **Apache/Nginx**: Servidor web configurado
- **Extensões PHP**: PDO, PDO_MySQL, GD, JSON

## Instalação

### 1. Configuração do Banco de Dados

```bash
# 1. Criar o banco de dados
mysql -u root -p
CREATE DATABASE soborracha_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit

# 2. Importar a estrutura
mysql -u root -p soborracha_db < database/database.sql
```

### 2. Configuração do PHP

Edite o arquivo `config/database.php` com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'soborracha_db');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

### 3. Configuração de Permissões

```bash
# Dar permissões de escrita para uploads
chmod 755 admin/uploads/
chmod 755 admin/uploads/suppliers/
chmod 755 admin/uploads/products/
chmod 755 admin/uploads/site/

# Permissões para logs (se necessário)
chmod 755 logs/
```

### 4. Configuração do Servidor Web

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^admin/(.*)$ admin/$1 [L]

# Proteger arquivos sensíveis
<Files "config/database.php">
    Order Allow,Deny
    Deny from all
</Files>
```

#### Nginx
```nginx
location /admin {
    try_files $uri $uri/ /admin/index.php?$query_string;
}

location ~ /config/ {
    deny all;
    return 403;
}
```

## Acesso ao Sistema

### Administração
- **URL**: `http://seusite.com/admin/`
- **Usuário padrão**: `admin`
- **Senha padrão**: `admin123`

⚠️ **IMPORTANTE**: Altere a senha padrão imediatamente após o primeiro login!

### Site Público
- **URL**: `http://seusite.com/`
- **Fornecedores**: `http://seusite.com/fornecedores.php`

## Configuração Inicial

### 1. Primeiro Acesso ao Admin

1. Acesse `/admin/login.php`
2. Faça login com as credenciais padrão
3. Vá em "Configurações" → "Conta" e altere a senha
4. Configure as informações da empresa em "Configurações" → "Site"

### 2. Cadastrar Fornecedores

1. No admin, vá em "Fornecedores" → "Novo Fornecedor"
2. Preencha as informações básicas
3. Faça upload do logo (opcional)
4. Defina as especialidades
5. Marque como "Destaque" se necessário

### 3. Configurar Produtos

1. Vá em "Produtos" → "Categorias" e crie as categorias
2. Em "Produtos" → "Novo Produto", cadastre os produtos
3. Associe produtos aos fornecedores

### 4. Personalizar o Site

1. Em "Configurações" → "Site", edite:
   - Título e descrição do site
   - Informações de contato
   - Textos da página inicial
   - Horário de funcionamento

## Estrutura de Arquivos

```
site/
├── admin/                  # Módulo administrativo
│   ├── assets/            # CSS, JS, imagens do admin
│   ├── includes/          # Classes PHP (Auth, Supplier, etc.)
│   ├── pages/             # Páginas do admin
│   ├── uploads/           # Arquivos enviados
│   ├── dashboard.php      # Dashboard principal
│   └── login.php          # Página de login
├── config/                # Configurações
│   └── database.php       # Configuração do banco
├── database/              # Scripts SQL
│   └── database.sql       # Estrutura do banco
├── public/                # Site público
│   ├── css/              # Estilos
│   ├── js/               # JavaScript
│   ├── images/           # Imagens
│   ├── includes/         # Header, footer
│   ├── fornecedores.php  # Página de fornecedores
│   └── index.php         # Página inicial
└── README.md             # Documentação
```

## Funcionalidades do Admin

### Dashboard
- Estatísticas gerais (produtos, fornecedores)
- Ações rápidas
- Atividade recente
- Gráficos por estado

### Fornecedores
- **Listar**: Visualizar todos os fornecedores
- **Criar**: Cadastrar novo fornecedor
- **Editar**: Modificar informações
- **Deletar**: Remover fornecedor (se não tiver produtos)
- **Upload**: Logo da empresa
- **Status**: Ativar/desativar
- **Destaque**: Marcar como parceiro destaque

### Produtos
- **Categorias**: Gerenciar categorias de produtos
- **CRUD**: Criar, editar, deletar produtos
- **Galeria**: Múltiplas imagens por produto
- **Associação**: Vincular a fornecedores
- **SEO**: Meta tags personalizadas

### Configurações
- **Site**: Informações gerais
- **Contato**: Dados de contato
- **SEO**: Configurações de SEO
- **Usuários**: Gerenciar administradores (apenas admin)

## Segurança

### Implementadas
- ✅ Autenticação com hash de senha
- ✅ Sessões seguras
- ✅ Validação de entrada
- ✅ Prepared statements (SQL injection)
- ✅ Upload seguro de arquivos
- ✅ Logs de atividade

### Recomendações Adicionais
- Use HTTPS em produção
- Configure backup automático do banco
- Monitore logs de erro
- Mantenha o PHP atualizado
- Use senhas fortes

## Backup

### Banco de Dados
```bash
# Backup
mysqldump -u usuario -p soborracha_db > backup_$(date +%Y%m%d).sql

# Restaurar
mysql -u usuario -p soborracha_db < backup_20231201.sql
```

### Arquivos
```bash
# Backup completo
tar -czf site_backup_$(date +%Y%m%d).tar.gz site/

# Backup apenas uploads
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz admin/uploads/
```

## Troubleshooting

### Problemas Comuns

**1. Erro de conexão com banco**
- Verifique credenciais em `config/database.php`
- Confirme se o MySQL está rodando
- Teste conexão: `mysql -u usuario -p`

**2. Erro de permissões de upload**
- Verifique permissões: `ls -la admin/uploads/`
- Ajuste permissões: `chmod 755 admin/uploads/`

**3. Página em branco no admin**
- Ative logs de erro no PHP
- Verifique `error_log` do Apache/Nginx
- Confirme se todas as extensões PHP estão instaladas

**4. Fornecedores não aparecem**
- Verifique se estão com status "active"
- Confirme se o banco foi importado corretamente
- Teste query diretamente no MySQL

### Logs

```bash
# Logs do Apache
tail -f /var/log/apache2/error.log

# Logs do PHP
tail -f /var/log/php/error.log

# Logs do sistema (admin)
SELECT * FROM admin_logs ORDER BY created_at DESC LIMIT 50;
```

## Suporte

Para dúvidas ou problemas:

1. Verifique este documento primeiro
2. Consulte os logs de erro
3. Teste em ambiente de desenvolvimento
4. Documente o erro com detalhes

## Próximas Funcionalidades

### Planejadas
- [ ] Sistema de produtos completo
- [ ] Galeria de imagens avançada
- [ ] Relatórios e estatísticas
- [ ] API REST para integração
- [ ] Sistema de backup automático
- [ ] Notificações por email
- [ ] Multi-idioma

### Melhorias
- [ ] Cache de páginas
- [ ] Otimização de imagens
- [ ] PWA (Progressive Web App)
- [ ] Integração com redes sociais
- [ ] Chat online

---

**Desenvolvido para Só Borracha Ltda**  
*Sistema de gerenciamento de fornecedores e produtos automotivos*
