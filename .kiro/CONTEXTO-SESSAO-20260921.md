# Contexto do Projeto — Só Borracha Ltda (Site Institucional)

**Última atualização:** 2026-09-21
**Diretório local:** `/home/junior/Dados/WebSites/soborracha.com.br`
**Webroot da aplicação:** `site/public/` (é o que vai para `public_html/` no cPanel)
**Produção:** cPanel, home `/home1/soborrachacom/`, domínio `soborracha.com.br`
**Desenvolvido por:** Junior Fernandes / CloudFix (https://www.cloudfix.net.br)

---

## 1. Visão geral

Site institucional em **PHP puro (8.2) + HTML/CSS/JS**, **sem banco de dados**.
Empresa: loja de borrachas automotivas (varejo e atacado) em Campo Grande - MS.
Contato: (67) 99918-0553 · ronaldo@soborracha.com.br · Av. Calogeras, 1300.

Decisão de arquitetura: **persistência em arquivos JSON** (não usa MySQL), pois o
volume é pequeno e simplifica deploy/segurança no cPanel.

Ambiente de dev NÃO tem PHP nem Composer instalados no host. Toda validação/lint/testes
foram feitos via **Docker** (`php:8.2-cli`, `node:20-alpine`) com servidor embutido + curl.

---

## 2. Estrutura atual (site/public/)

```
site/public/
├── index.php            # Home (dinâmica: destaques do JSON)
├── produtos.php         # Vitrine (6 produtos do JSON, grade 3 colunas)
├── parceiros.php        # Rede de parceiros (ex-fornecedores)
├── fornecedores.php     # REDIRECT 301 -> parceiros.php
├── sobre.php
├── contato.php          # Formulário de contato (fetch -> send_mail_final.php)
├── send_mail_final.php  # Processa envio via SoBorrachaMailer + dedup + rate limit
├── .env                 # SEGREDOS (SMTP + admin). NÃO versionado. Protegido por .htaccess
├── .env.example         # Template sem segredos
├── .htaccess            # Bloqueia .env, .env.*, composer.*, /includes/, -Indexes
├── css/
│   ├── style.css        # base (existente)
│   ├── components.css   # NOVO: notificações, badges, utilitários
│   ├── refinamentos.css # NOVO: proporção de imagens, header sticky, contraste de botões, grade 3 col
│   ├── products.css, contact.css, about.css, suppliers.css
├── js/
│   ├── main.js          # menu mobile, scroll (header NÃO some mais), form fallback (guard __contactFormHandled)
│   ├── contact.js       # handler principal do formulário (fetch real) + FAQ accordion + máscara tel
│   ├── suppliers.js, products.js, about.js
├── includes/
│   ├── header.php        # menu do site (Parceiros no lugar de Fornecedores)
│   ├── footer.php        # rodapé + logo CloudFix (link nova aba)
│   ├── env.php           # loader de .env (getenv/$_ENV, prioriza ambiente do servidor)
│   ├── SoBorrachaMailer.php  # SMTP socket nativo + fallback mail(); lê creds do .env
│   ├── ProductStore.php  # CRUD de produtos no data/produtos.json
│   └── PartnerStore.php  # CRUD de parceiros no data/parceiros.json
├── data/
│   ├── .htaccess         # bloqueia acesso web
│   ├── produtos.json     # 6 produtos
│   └── parceiros.json    # 3 parceiros
├── admin/                # Painel administrativo (login por .env)
│   ├── _header.php       # topbar/menu: Produtos, Parceiros, Imagens do site
│   ├── index.php login.php logout.php
│   ├── produtos.php produto_form.php produto_salvar.php produto_excluir.php
│   ├── parceiros.php parceiro_form.php parceiro_salvar.php parceiro_excluir.php
│   ├── imagens.php       # troca hero-borrachas.jpg, loja-fachada.jpg, LogoSB-Photoroom.png
│   └── assets/admin.css
├── images/               # imagens do site + images/produtos/ (uploads) 
└── uploads/parceiros/    # logos de parceiros (criada no 1º upload)
```

Backups locais (NÃO subir): `site/_backup_limpeza_*`, `site/_backup_fornecedores_*`.

---

## 3. O que foi feito nesta sessão (2026-09-21)

### 3.1 Correção do site público
- **Formulário de contato**: estava "mockado" no main.js (setTimeout, nunca enviava).
  Corrigido: contact.js faz fetch() real para send_mail_final.php; main.js só faz fallback
  com guard `window.__contactFormHandled`. contato.php carrega contact.js ANTES de main.js.
- **CSS ausente**: criado css/components.css (index.php referenciava e dava 404).
- **Imagens quebradas**: criadas várias imagens que faltavam (favicon, borracha-*, team-*, etc.).
- **FAQ accordion**: já funcional (contact.js initFAQ + contact.css) após corrigir carregamento.
- **Ícones**: header.php/footer.php migrados de classes icon-* (emojis) para Font Awesome.
- **Limpeza**: send_mail_* redundantes, about.php e cookies.txt movidos para backup.

### 3.2 E-mail (SMTP)
- send_mail_final.php passou de mail() para **SoBorrachaMailer** (SMTP com fallback).
- Remetente/login: **webmaster@soborracha.com.br** · Destino: **ronaldo@soborracha.com.br**.
- Senha SMTP movida para .env (SMTP_PASSWORD). FUNCIONOU em produção.
- **require robusto**: send_mail_final.php procura o mailer em includes/ ao lado, ../includes, DOCUMENT_ROOT.
- **Dedup anti-duplo-envio**: bloqueia e-mails idênticos (email+assunto+mensagem) numa janela de 120s.
  Resolveu problema de 2-3 e-mails por envio (causado por JS antigo no servidor).
- Rate limit: 5 req / 5 min por IP (sys_get_temp_dir).

### 3.3 Segurança / .env
- includes/env.php: loader de .env sem dependências; variáveis do servidor têm prioridade.
- .env com SMTP_* e ADMIN_*; .env.example versionável; .htaccess protege.
- .gitignore atualizado (ignora .env, cookies.txt, _backup_*).

### 3.4 Módulo Admin (sem banco, JSON)
- Login: AdminAuth (usuário/hash no .env), sessão 2h, CSRF.
  - **ADMIN_USER=admin · senha inicial: SoBorracha@2026** (TROCAR).
  - Hash atual no .env: `$2y$10$5rlBogTj.uKS54K/My7dOuO.lZQbefm8vqDY7eq3.FHjL5d/agN0S`
- CRUD de **Produtos**: criar/editar/excluir + upload (finfo, max 4MB, jpg/png/webp -> images/produtos/).
  Também edita textos da vitrine (título, subtítulo, aviso "milhares de opções").
- CRUD de **Parceiros** (ex-fornecedores): campo tipo (distribuidor/revendedor/loja/oficina) + upload logo (uploads/parceiros/).
- **Imagens do site**: troca hero/fachada/logo sobrescrevendo o nome (com backup automático).

### 3.5 Site público dinâmico
- index.php e produtos.php leem do data/produtos.json.
- Vitrine passa a ideia de "milhares de opções / multimarcas" com CTA WhatsApp
  (cliente deve consultar peça pelo WhatsApp). São só 4-6 exemplos exibidos.
- **6 produtos** em 2 linhas x 3 colunas (grade fixa em 3 col no desktop).

### 3.6 Fornecedores -> Parceiros (mudança de conceito de negócio)
- Motivo: não expor cadeia de suprimentos; virar ferramenta de EXPANSÃO de rede
  (distribuidores/revendedores/oficinas credenciadas/afiliados).
- parceiros.php novo (narrativa de rede + filtro por tipo + CTA "Quero ser parceiro").
- fornecedores.php -> redirect 301. Menu do site e do admin atualizados.

### 3.7 Ajustes visuais
- css/refinamentos.css (carregado por último em todas as páginas):
  - Imagens com aspect-ratio 4:3 + object-fit cover (corrige "estouro"/desproporção).
  - Header sticky que NÃO some mais no scroll (removido translateY(-100%) do main.js).
  - Cards elegantes, grade 3 colunas, tags/badges estilizados, títulos de seção com linha vermelha.
  - **Contraste de botões**: .btn-outline sobre fundo vermelho (hero, CTAs) agora é branco/legível.
    Distinção: section.products-cta (produtos, fundo vermelho) vs .products-section .products-cta (home, fundo claro).

### 3.8 Rodapé
- footer.php: adicionado logo **ClodFix-Icone.png** ao lado do copyright,
  com link https://www.cloudfix.net.br em nova aba (target=_blank rel=noopener). 24px altura.

---

## 4. Deploy no cPanel (public_html/)

- Subir conteúdo de `site/public/` para `public_html/` (ativar "mostrar ocultos" p/ .env e .htaccess).
- Criar pastas com escrita: `images/produtos/`, `uploads/parceiros/`, e `data/` gravável.
- APAGAR do servidor (antigos): about.php, send_mail.php, send_mail_smtp.php.
- Conferir no navegador: `/.env` e `/data/produtos.json` devem dar 403.
- .env do servidor precisa ter SMTP_* E ADMIN_USER/ADMIN_PASSWORD_HASH.

## 5. Credenciais (guardar com segurança / trocar)

- SMTP: webmaster@soborracha.com.br / senha ry)KJ.e1xg%A (no .env — TROCAR e atualizar .env)
- Admin: admin / SoBorracha@2026 (hash no .env — TROCAR)
- Ambas trafegaram em texto plano no chat; recomendável rotacionar.

## 6. Pendências / próximos passos sugeridos

- [ ] Trocar senhas (SMTP e admin) e atualizar o .env no servidor.
- [ ] Anti-spam do formulário: adicionar honeypot (muitos bots no error_log).
- [ ] Link real do Instagram (hoje aponta para instagram.com genérico).
- [ ] Commit organizado no Git (com .env ignorado) — nada foi commitado ainda nesta sessão.
- [ ] Modernização visual mais ampla (redesign da home) — se desejado.
- [ ] Home mostrar 6 destaques (hoje mostra 3) — se desejado.

## 7. Como validar localmente (sem PHP no host)

```bash
# lint
docker run --rm -v "$PWD":/pub -w /pub php:8.2-cli php -l arquivo.php
# servidor + teste
docker run --rm -v "$PWD":/pub -w /pub php:8.2-cli sh -c 'php -S 127.0.0.1:8899 & sleep 2; curl ...'
```
Login admin no teste: user=admin, password=SoBorracha@2026.
