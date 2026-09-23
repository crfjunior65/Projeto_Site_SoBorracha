# Proposta de Modernização Visual — Home (Só Borracha)

**Data:** 2026-09-22
**Autor:** Junior Fernandes / CloudFix
**Arquivo alvo:** `site/public/index.php` + CSS
**Status:** Proposta para aprovação (nada aplicado ao site ainda)

> Documento complementar: abra o **`mockup-home.html`** (nesta mesma pasta) no
> navegador para ver a proposta renderizada com as cores e fontes reais da marca.

---

## 1. Objetivo

Deixar a página inicial mais **moderna, com mais apelo visual e melhor conversão**
(visitante → contato via WhatsApp/formulário), **sem quebrar** a arquitetura atual
(PHP puro + JSON, sem banco) e **reaproveitando** o design system que já existe
(`style.css`): vermelho da marca `#dc2626`, fonte Inter, tokens de spacing/shadow/radius.

Princípio: **evolução, não reconstrução.** O conteúdo e as seções continuam os mesmos;
muda a apresentação.

---

## 2. Identidade visual (mantida do que já existe)

| Token | Valor atual | Uso na modernização |
|-------|-------------|---------------------|
| Cor primária | `#dc2626` (vermelho) | Hero, CTAs, detalhes, números |
| Primária escura | `#b91c1c` | Gradientes, hover |
| Secundária | `#1f2937` (grafite) | Textos fortes, seção escura |
| Acento | `#f59e0b` (âmbar) | Selos/badges de destaque |
| Fonte | Inter | Mantida; títulos mais pesados (700/800) |

Nada de cores novas fora da paleta — só uso mais intencional do que já há.

**Logo da marca:** usa os arquivos reais já no projeto:
- `images/LogoSB-Photoroom.png` (468×314, transparente) — header e áreas claras.
- `images/logo-darkSite (SemFundo).png` (512×170, transparente) — áreas escuras (CTA final).

O admin já gerencia o `LogoSB-Photoroom.png`, então trocas futuras continuam pelo painel.

---

## 3. Seção a seção — o que muda

### 3.1 Hero (topo)
**Hoje:** título em duas linhas, subtítulo, 3 features em linha, 2 botões, imagem ao lado.

**Proposta:**
- Fundo com **gradiente vermelho diagonal** + textura sutil, em vez de imagem chapada.
- Título maior e mais impactante (peso 800), com a palavra-chave em destaque.
- **Selo de confiança** acima do título ("Há mais de 25 anos em Campo Grande - MS").
- Botão de WhatsApp como **ação principal** (destaque) e "Ver Produtos" como secundário.
- **Faixa de credibilidade** logo abaixo do hero: 25+ anos · 50+ marcas · atacado e varejo.
- Imagem com moldura/sombra suave e cantos arredondados (aspecto mais premium).

**Por quê:** o hero é o que mais influencia a primeira impressão e a decisão de continuar.

---

### 3.2 Serviços (Varejo / Atacado / Instalação)
**Hoje:** 3 cards simples com ícone, título e texto.

**Proposta:**
- Cards com **borda superior colorida** e **elevação no hover** (sobe + sombra).
- Ícone dentro de um círculo com fundo vermelho-claro (`--primary-light`).
- Micro-link "Saiba mais →" em cada card.

**Por quê:** dá sensação de interface moderna e convida ao clique, sem poluir.

---

### 3.3 Principais Produtos
**Hoje:** grade de **3 produtos** em destaque; overlay "Ver Detalhes"; CTA final.

**Proposta:**
- Mostrar **6 destaques** (2 linhas × 3 colunas) — alinha com a página de produtos.
- Cards com imagem em proporção fixa 4:3, badge de categoria e tags de features.
- **Faixa de reforço** "milhares de opções / todas as marcas — consulte pelo WhatsApp".

**Por quê:** 6 itens preenchem melhor a seção e passam ideia de variedade; é o item
"Home mostrar 6 destaques" que já estava previsto.

> Impacto técnico: trocar `array_slice(..., 3)` por `6` no `index.php`. Simples.

---

### 3.4 Sobre + Números (stats)
**Hoje:** texto + 3 números (25+ anos, 1000+ clientes, 50+ marcas) + imagem.

**Proposta:**
- Números com **destaque tipográfico** (grande, vermelho) e rótulo discreto.
- Opcional: **contagem animada** (0 → valor) quando a seção entra na tela (JS leve).
- Imagem da fachada com moldura e um "selo" flutuante ("Loja física em Campo Grande").

**Por quê:** prova social/credibilidade é um dos maiores gatilhos de conversão.

---

### 3.5 Nova seção: Diferenciais / "Por que a Só Borracha"
**Hoje:** não existe.

**Proposta (opcional):** faixa com 4 diferenciais em ícones:
- Multimarcas (todas as montadoras)
- Atacado e varejo
- Instalação profissional
- Atendimento rápido no WhatsApp

**Por quê:** responde à pergunta "por que comprar aqui?" antes do visitante sair.

---

### 3.6 CTA de contato (rodapé da página)
**Hoje:** bloco com endereço/telefone/horário + botões WhatsApp e Formulário.

**Proposta:**
- Fundo **escuro (grafite)** ou vermelho sólido para criar contraste e "fechar" a página.
- CTA de WhatsApp em destaque máximo.
- Mini-mapa/endereço com ícone e link para o Google Maps.

**Por quê:** última chamada para ação, precisa ser inconfundível.

---

## 4. Estratégia de contato e redes sociais

Decisão: **concentrar o WhatsApp no botão flutuante** (sempre visível em todas as
páginas) e **liberar o header e o hero** para outros usos, evitando repetição de
botões de WhatsApp espalhados.

- **Header (linha do menu):** ícones de **redes sociais** (Instagram, Facebook, YouTube)
  no lugar do antigo botão "WhatsApp".
- **Hero:** botões "Ver Produtos" (principal) e "Conheça a Loja" — sem WhatsApp aqui.
- **WhatsApp flutuante:** mantido no canto, com animação de pulso, como canal principal.
- **CTA final:** botão "Formulário de Contato" + ícones de redes sociais (o WhatsApp
  fica no flutuante).

> Links de redes: definir os URLs reais (Instagram já estava pendente de link real;
> hoje está como placeholder `#`). Passe os links e eu preencho.

---

## 5. Micro-interações (toque de modernidade)

- **Reveal on scroll:** seções aparecem com fade/slide suave ao rolar (IntersectionObserver, ~30 linhas de JS, sem biblioteca).
- **Hover nos cards:** elevação + sombra (só CSS).
- **Botão WhatsApp flutuante** fixo no canto (opcional) — presente em todas as páginas.
- Respeitar `prefers-reduced-motion` (acessibilidade).

---

## 5. Responsividade e acessibilidade

- Mobile-first: hero empilha, grades viram 1 coluna, botões full-width.
- Contraste AA garantido (texto sobre vermelho = branco).
- `alt` em todas as imagens, foco visível, navegação por teclado.
- Sem impacto de performance: continua CSS/JS puro, imagens com `loading="lazy"`.

---

## 6. Como seria implementado (plano técnico)

1. Criar **`css/home-moderna.css`** (carregado por último na `index.php`) com os novos estilos — **não reescreve** os CSS atuais, apenas sobrepõe/estende.
2. Ajustes pontuais de marcação na `index.php` (selo no hero, faixa de credibilidade, 6 destaques, seção de diferenciais).
3. Criar **`js/home-anim.js`** leve para o reveal-on-scroll e a contagem de números.
4. Validar via Docker (lint + servidor embutido), como no fluxo atual.
5. Fazer em branch separada e revisar antes de mandar para produção.

**Reversível:** se não gostar, basta remover o `<link>`/`<script>` novos — o site volta ao estado atual.

---

## 7. Níveis de ambição (você escolhe)

| Nível | O que inclui | Esforço |
|-------|--------------|---------|
| **A — Mínimo** | 6 destaques + hover nos cards + refino do hero | Baixo |
| **B — Recomendado** | Nível A + faixa de credibilidade + números destacados + reveal-on-scroll + CTA escuro | Médio |
| **C — Completo** | Nível B + seção de diferenciais + contagem animada + botão WhatsApp flutuante | Médio-alto |

Sugestão: começar pelo **Nível B** (melhor relação impacto/esforço) e decidir depois se sobe para o C.

---

## 8. Próximo passo

1. Você abre o `mockup-home.html` e vê a proposta renderizada.
2. Me diz o que curtiu / o que tirar / qual nível (A, B ou C).
3. Eu implemento em branch separada, valido e te mostro antes de subir.
