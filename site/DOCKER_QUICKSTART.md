# 🚀 Guia Rápido - Docker

## ⚡ Instalação em 3 Passos

### 1️⃣ Executar Script de Instalação
```bash
./docker/init.sh
```

### 2️⃣ Aguardar Conclusão
O script vai:
- ✅ Construir as imagens Docker
- ✅ Iniciar os containers
- ✅ Configurar o banco de dados
- ✅ Inserir dados de exemplo
- ✅ Configurar permissões

### 3️⃣ Acessar o Site
- **Site**: http://localhost:8080
- **Admin**: http://localhost:8080/admin (admin/admin123)
- **phpMyAdmin**: http://localhost:8081

---

## 🎯 Comandos Essenciais

```bash
# ⬆️ Iniciar ambiente
make up

# ⬇️ Parar ambiente  
make down

# 🔄 Reiniciar
make restart

# 📋 Ver logs
make logs

# 🧪 Testar se está funcionando
make test

# 🧹 Limpar tudo (cuidado!)
make clean
```

---

## 🆘 Problemas Comuns

### ❌ "Port already in use"
```bash
# Verificar o que está usando a porta
sudo lsof -i :8080
# Parar o processo ou alterar porta no docker-compose.yml
```

### ❌ "Containers not starting"
```bash
# Ver logs detalhados
make logs

# Limpar e reinstalar
make clean
make install
```

### ❌ "Database connection failed"
```bash
# Aguardar mais tempo
sleep 30

# Verificar se MySQL está pronto
make shell-db
```

### ❌ "Permission denied"
```bash
# Corrigir permissões
make permissions
```

---

## 📱 URLs Importantes

| Serviço | URL | Login |
|---------|-----|-------|
| **Site** | http://localhost:8080 | - |
| **Fornecedores** | http://localhost:8080/fornecedores.php | - |
| **Admin** | http://localhost:8080/admin | admin/admin123 |
| **phpMyAdmin** | http://localhost:8081 | soborracha_user/soborracha_pass |

---

## 🔧 Desenvolvimento

### Editar Código
Os arquivos são sincronizados automaticamente. Basta editar e recarregar a página.

### Ver Logs em Tempo Real
```bash
make logs
```

### Acessar Container
```bash
# Shell do container web
make shell-web

# MySQL
make shell-db
```

### Backup do Banco
```bash
# Fazer backup
make backup

# Restaurar backup
make restore FILE=backup_20231201.sql
```

---

## 🎉 Pronto!

Seu ambiente Docker está configurado e funcionando!

**Próximos passos:**
1. Acesse http://localhost:8080/admin
2. Faça login (admin/admin123)
3. **Altere a senha padrão**
4. Cadastre seus fornecedores
5. Personalize o site

---

**Precisa de ajuda?** Consulte o arquivo `DOCKER.md` para documentação completa.
