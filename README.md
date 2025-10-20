# Conecta Ação — Plataforma de Doações (ONGs & Doadores)

> **MVP Web**: HTML, CSS, JavaScript (frontend) + PHP (API) + MySQL (phpMyAdmin).  
> **Objetivo**: conectar doadores e ONGs, tornando visível o que é necessário, o que já foi doado e o que ainda falta.

---

## 🎯 Sobre o Projeto
O **Conecta Ação** permite que **ONGs** publiquem pedidos de doação (com meta/quantidade) e que **doadores** encontrem, filtrem e registrem intenções de doação. O sistema mostra progresso por pedido, um **kanban** de intenções para a ONG (Pendentes/Concluídas) e, para o doador, quanto ele **já doou** e sua **participação (%)** em cada meta.

**Principais features**
- **Doadores**: listar/filtrar pedidos, enviar intenção (nome, contato, mensagem, quantidade), ver “Já doei X (Y%)” e “Restante”.
- **ONGs**: criar/editar/excluir pedidos; visualizar intenções em **kanban**; **concluir** intenções; pedido é marcado como **atendido** quando a meta é alcançada.
- **Autenticação** separada para doador e ONG; senhas com `password_hash`.
- **Stack**: executa localmente com **XAMPP** (Apache + PHP + MySQL).

---

## 👥 Equipe
| Nome | RA | Papel |
|---|---|---|
| Preencher | Preencher | Representante / Dev Back |
| Preencher | Preencher | Dev Front |
| Preencher | Preencher | QA / Testes |
| Preencher | Preencher | DevOps / Métricas |
| Preencher | Preencher | Design / Docs |

> **Representante**: Preencher (contato com o professor)  
> **Grupos**: 3–5 integrantes. Cadastro até **19 de setembro**.

---

## 🗂 Estrutura do Repositório
```
conecta-acao/
├─ index.html
├─ login_doador.html
├─ login_ong.html
├─ painel_doador.html
├─ painel_ong.html
├─ assets/
│  ├─ css/style.css
│  └─ js/
│     ├─ main.js
│     ├─ painel_doador.js
│     └─ painel_ong.js
├─ php/
│  ├─ includes/
│  │  ├─ config.php
│  │  └─ db.php
│  └─ actions/
│     ├─ auth.php (login/cadastro + logout.php)
│     ├─ listar_pedidos.php
│     ├─ detalhar_pedido.php
│     ├─ salvar_pedido.php
│     ├─ excluir_pedido.php
│     ├─ meus_pedidos.php
│     ├─ marcar_atendido.php
│     ├─ registrar_doacao.php
│     ├─ alterar_status_doacao.php
│     ├─ minhas_intencoes.php
│     └─ intencoes_por_pedido.php
├─ migrations/
│  └─ 001_init.sql
├─ docs/
│  ├─ prints/
│  └─ pitch/
└─ tests/
   ├─ postman/
   ├─ selenium/
   └─ manual/
```

---

## 🚀 Execução Local

### Requisitos
- **XAMPP** com Apache + PHP 8+ + MySQL
- **phpMyAdmin**
- Navegador moderno

### Passo a passo
1. **Clonar** o repositório para o webroot (ex.: `htdocs/conecta-acao`).
2. **Banco de dados** (duas opções):
   - **A)** *Importar tudo* (estrutura + dados): phpMyAdmin → Importar um dump `.sql` exportado de outra máquina.
   - **B)** *Criar schema “limpo”*: phpMyAdmin → criar DB `conecta_acao` → Importar `migrations/001_init.sql`.
3. **Configurar** `php/includes/config.php` com as credenciais:
   ```php
   define('DB_HOST','localhost');
   define('DB_PORT','3306');
   define('DB_NAME','conecta_acao');
   define('DB_USER','root');
   define('DB_PASS',''); // ajuste conforme seu ambiente
   ```
4. Iniciar **Apache** e **MySQL** no XAMPP.
5. Acessar `http://localhost/conecta-acao/index.html`.

> **Dica**: para migrar entre máquinas sem refazer o DB, use **Exportar/Importar** do phpMyAdmin.

---

## 🧪 Testes (Plano Manual)

> Mínimo de **5 casos**. Registre evidências em `/tests/manual/` (MD/CSV + prints em `docs/prints/`).

| ID | Caso | Pré-condição | Passos | Resultado Esperado |
|---|---|---|---|---|
| CT01 | Cadastro ONG | Sem ONG com email X | Login ONG → “Criar conta” → preencher → enviar | ONG criada; senha com hash no DB; redireciona p/ login |
| CT02 | Login ONG | ONG existente | Login ONG com credenciais | Sessão iniciada; acesso ao painel |
| CT03 | Criar Pedido (ONG) | ONG logada | Painel ONG → “Criar pedido” → salvar | Pedido “ativo” listado; aparece em listagem pública |
| CT04 | Intenção (Doador) | Doador logado + pedido ativo | Painel Doador → “Ver/Doar” → enviar intenção | ONG vê a intenção no kanban; card do doador mostra “Já doou X (Y%)” |
| CT05 | Concluir Intenção (ONG) | Intenção pendente | Kanban → “Marcar concluída” | Move para “Concluídas”; “Restante” reduz para doadores |

---

## 🤖 Testes Automatizados

### Ferramentas
- **Postman + Newman** (API PHP/JSON)
- **Selenium** (E2E: login, criar pedido, doar, concluir)
- (Opcional) **PHPUnit** para helpers em PHP

### Como rodar (Newman)
1. Exportar a coleção **Postman** para `tests/postman/conecta-acao.postman_collection.json`.  
2. (Opcional) Environment `tests/postman/local.postman_environment.json` com `baseUrl = http://localhost/conecta-acao`.  
3. Executar:
   ```bash
   npm i -g newman
   newman run tests/postman/conecta-acao.postman_collection.json \
     -e tests/postman/local.postman_environment.json
   ```

### Sugestão de 5 testes de API
- `auth.php`: cadastro doador (201) e login (200).
- `salvar_pedido.php`: criar pedido (ONG logada).
- `listar_pedidos.php`: retorna metas/restante.
- `registrar_doacao.php`: criar intenção.
- `alterar_status_doacao.php`: marcar “concluida”; validar `detalhar_pedido.php` (restante).

### Selenium (exemplo)
- Script Python/JS em `tests/selenium/` cobrindo:  
  - login ONG → criar pedido  
  - login doador → doar  
  - login ONG → concluir → conferir atualização no doador

---

## 📊 Métricas e Estimativas

### Métricas (mínimo 2)
- **Cobertura de testes** (relatórios Newman + logs E2E).  
- **Qualidade estática**:
  - **ESLint** para `assets/js`
  - **Stylelint** para `assets/css`
  - **PHP CodeSniffer** (PSR-12) para `php/`

> (Opcional) **SonarQube** para visão global (bugs, code smells, duplicação).

### Estimativas (mínimo 1)
- **Planning Poker** nas histórias HU01–HU05.  
- (Alternativas) **T-Shirt sizing** por módulo, **Three-Point (PERT)** para tarefas críticas (E2E + ajustes DB).

Registre os resultados (prints/relatórios) em `docs/`.

---

## 🔍 Revisão Técnica / Qualidade
- **Revisão por pares** (PRs críticos): auth, SQL, segurança, JS.  
- **Linters**: ESLint/Stylelint/PHPCS (pre-commit com Husky – opcional).  
- **Resultados esperados**:
  - PHPCS: aderência PSR-12 > 90%
  - ESLint/Stylelint: 0 erros
  - Sonar (se usado): 0 *bugs* críticos, cobertura básica nos endpoints

---

## 🔧 Versionamento (Git)

**Branches**
- `main`: estável
- `dev`: integração
- `feature/<nome>`: por funcionalidade (ex.: `feature/kanban-intencoes`)
- `hotfix/<nome>`: correções urgentes

**PRs & Commits**
- Commits pequenos e descritivos (`feat(ong): kanban pendente/concluida`)
- PR com descrição clara e revisão por 1+ integrante
- Vincular issues quando possível

> Adicione links de PRs relevantes aqui:
- PR Kanban: …
- PR Métricas: …
- PR Testes: …

---

## 🌐 GitHub Pages (Landing Page)
A landing (estática) deve conter:
- Nome e objetivo do projeto  
- Pitch (vídeo embed ou link)  
- Tecnologias  
- Capturas de tela (usar `docs/prints/`)  
- Link para o repositório  
- Equipe (nomes + RAs)

**Publicação**: `https://seuusuario.github.io/conecta-acao/`  
(Use HTML simples/Jekyll/Markdown ou o site builder do GitHub.)

---

## 🗣 Pitch (5 min) — Roteiro sugerido
1. **Problema** (visibilidade & atrito nas doações)  
2. **Solução** (Conecta Ação — metas claras, intenção rápida, progresso visível)  
3. **Demonstração** (criar pedido, doar, kanban, conclusão, restante caindo, “já doou X%”)  
4. **Resultados/Impacto** (agilidade, transparência)  
5. **Roadmap** (e-mail/WhatsApp, geolocalização, relatórios, reputação)  

---

## 🧑‍🏫 Apresentação Técnica (10–15 min)
- Estrutura do **repositório** (pastas, branches, PRs)  
- **Execução local** (XAMPP, config, DB)  
- **Demonstração** do sistema  
- **Testes** (Newman + Selenium)  
- **Métricas & Estimativas** (linters, cobertura, planning poker)  
- **Lições aprendidas** e próximos passos  
> **Todos os membros participam** na fala.

---

## ✅ Checklist
- [ ] README.md (este) completo  
- [ ] **Landing Page (GitHub Pages)** com prints, pitch e links  
- [ ] **5+ casos de teste** documentados  
- [ ] **5+ testes automatizados** (Postman/Selenium) no `/tests`  
- [ ] **2+ métricas** + **1 estimativa** com evidências em `/docs`  
- [ ] Linters/PHPCS configurados (ou relatórios em `/docs`)  
- [ ] Repositório público, com branches/PRs organizados  

---

## 🔒 Segurança & Boas Práticas
- Hash de senhas (`password_hash/verify`)  
- PDO + prepared statements  
- Validação server-side de inputs  
- Regeneração de ID de sessão no login  
- (Futuro) CSRF tokens; rate limiting em auth; logs em produção

---

## 📎 Links
- **Repositório**: _preencher_  
- **Landing Page (GitHub Pages)**: _preencher_  

---

**Conecta Ação** — aproximando quem quer **doar** de quem mais **precisa**. 💚