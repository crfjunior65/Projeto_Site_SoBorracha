# Só Borracha — Site Institucional

Site institucional da **Só Borracha**, loja especializada em borrachas automotivas
(varejo e atacado) em Campo Grande - MS.

Desenvolvido em **PHP puro (8.2) + HTML/CSS/JS**, com persistência em **arquivos JSON**
(sem banco de dados), o que simplifica o deploy e a segurança em cPanel.

---

## Funcionalidades

- **Página inicial** com visual moderno (header sticky com glassmorphism, hero,
  faixa de credibilidade, serviços, catálogo dinâmico, diferenciais e CTA).
- **Produtos** — vitrine dinâmica lida de `data/produtos.json` (a home exibe até 6 destaques).
- **Parceiros** — rede de distribuidores/revendedores/oficinas (lida de `data/parceiros.json`).
- **Sobre** e **Contato** — formulário com envio por SMTP.
- **Formulário de contato** com envio real (SMTP), deduplicação anti-duplo-envio,
  rate limiting por IP e anti-spam (honeypot + time-trap).
- **Painel administrativo** (login por `.env`, sem banco): CRUD de produtos e parceiros,
  upload de imagens e troca de imagens do site.
- **Design responsivo** e acessível (respeita `prefers-reduced-motion`).
- **WhatsApp flutuante** e redes sociais no cabeçalho.

---

## Design System (modernização)

- **Tipografia:** Outfit (títulos) + Plus Jakarta Sans (texto).
- **Estilo:** cantos arredondados (2xl/3xl), sombras suaves, glassmorphism leve.
- **Cor da marca:** vermelho `#dc2626` sobre grafite `#0f172a`.
- **Animações leves:** header compacto ao rolar, reveal on scroll e contagem de números
  (`js/home-anim.js`, sem dependências).
- CSS da modernização em `css/home-moderna.css` (carregado por último, sobrepõe o base
  sem reescrevê-lo). As seções da home usam classes `.hm-*`.

---

## Informações de Contato

- **Endereço:** Av. Calogeras, 1300 — Campo Grande - MS
- **Telefone / WhatsApp:** (67) 99918-0553
- **E-mail:** ronaldo@soborracha.com.br
- **Atendimento:** Seg-Sex 7:30 às 17:30 · Sábado 8:00 às 12:00

---

## Estrutura do Projeto

```
site/
└── public/                 # Webroot (vai para public_html/ no cPanel)
    ├── index.php           # Home (dinâmica: destaques do JSON)
    ├── produtos.php        # Vitrine de produtos
    ├── parceiros.php       # Rede de parceiros
    ├── sobre.php
    ├── contato.php         # Formulário (fetch -> send_mail_final.php)
    ├── send_mail_final.php # Envio via SMTP + dedup + rate limit + anti-spam
    ├── css/                # style, components, refinamentos, home-moderna
    ├── js/                 # main, contact, home-anim
    ├── includes/           # header, footer, ProductStore, PartnerStore, mailer, env
    ├── data/               # produtos.json, parceiros.json (bloqueados via .htaccess)
    ├── admin/              # Painel administrativo (login por .env)
    └── images/             # imagens do site e uploads
```

---

## Como Executar (ambiente de desenvolvimento)

O host de desenvolvimento não precisa ter PHP instalado — usa-se **Docker**.

### Servidor local rápido (PHP embutido)

```bash
docker run --rm -p 8890:80 \
  -v "$(pwd)/site/public:/var/www/html" \
  php:8.2-apache
# acesse http://localhost:8890
```

### Lint / validação de sintaxe

```bash
docker run --rm -v "$(pwd)/site/public":/pub -w /pub php:8.2-cli php -l index.php
```

---

## Deploy (cPanel)

- Subir o conteúdo de `site/public/` para `public_html/`.
- Garantir escrita em `data/`, `images/produtos/` e `uploads/parceiros/`.
- Conferir que `.env` e `data/*.json` retornam **403** (protegidos por `.htaccess`).
- O `.env` do servidor deve conter as variáveis `SMTP_*` e `ADMIN_*`.

---

Desenvolvido por **Junior Fernandes / CloudFix** — https://www.cloudfix.net.br
