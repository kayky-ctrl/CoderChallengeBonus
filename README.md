# Projeto Catálogo de Patos Primordiais (Missões 1, 2 e 3)

## 📜 Descrição

Este projeto é uma aplicação web completa desenvolvida como parte de um desafio de programação, simulando um sistema para catalogar, analisar e planejar a captura de entidades biológicas anômalas chamadas "Patos Primordiais". A aplicação consiste em um backend robusto construído com **Laravel (PHP)** e um frontend interativo utilizando **Vue.js 3** (via CDN).

O projeto é dividido em três missões principais:

1.  **Missão 1: Catalogação:** Coleta de dados de avistamentos de Patos Primordiais via drones de pesquisa, incluindo medidas, localização, status (hibernação, transe, desperto) e superpoderes.
2.  **Missão 2: Análise Estratégica:** Processamento automático dos dados coletados para gerar uma análise de risco, custo operacional, valor científico e prioridade de captura para cada pato.
3.  **Missão 3: Captura e Controle:** Gerenciamento de um novo tipo de drone (Drones de Captura), cadastro de fraquezas, estratégias de ataque e sistemas de defesa. Inclui um sistema de "IA Tática" para gerar planos de ataque e ativar contramedidas defensivas durante missões de captura.

---

## ✨ Funcionalidades Principais

**Missão 1 & 2 (Modo Pesquisa):**

* **Logs de Avistamento:** Visualização de todos os registros de avistamentos.
* **Novo Avistamento:** Formulário completo para registrar novos dados, com:
    * Seleção de Drone de Pesquisa.
    * Entrada de medidas com conversão automática de unidades (pés/libras/jardas para cm/g/m).
    * Entrada de localização e dados GPS.
    * Seleção de status (Desperto, Em Transe, Hibernação Profunda).
    * Campos condicionais para Batimentos Cardíacos (se não Desperto) e Superpoder (se Desperto).
* **Patos Catalogados:** Tabela com todos os patos registrados, exibindo:
    * Status de Hibernação.
    * **Análise Automática (Missão 2):** Nível de Risco e Prioridade de Captura.
    * Modal de "Detalhes" com todos os dados coletados (M1) e a análise estratégica completa (M2).
    * Botão para visualizar Logs específicos do pato.
    * Botão para gerar Plano Tático (funcionalidade da M3).
* **Gerenciar Drones de Pesquisa:** CRUD (Criar, Ler, Atualizar, Excluir) para os drones usados na coleta de dados.
* **Gerenciar Fabricantes:** CRUD para os fabricantes dos drones.

**Missão 3 (Modo Captura):**

* **Central de Missões:**
    * Formulário para lançar novas missões de captura, selecionando um Drone de Captura ocioso e um Pato Alvo.
    * Listagem de todas as missões (ativas e passadas).
    * Modal de "Detalhes da Missão" exibindo status, alvo (com análise), drone (com telemetria) e notas.
    * Botões de ação no modal: Ver Plano Tático, Ativar Defesa (SGDA), Atualizar Telemetria.
* **Gerenciar Drones de Captura:** CRUD para os drones de combate/captura (Designação, Status via Telemetria).
    * Botão para abrir modal e enviar atualizações de Telemetria (Bateria, Combustível, Integridade, Status).
* **Gerenciar Arsenal (Fraquezas):** CRUD para a lista mestre de fraquezas conhecidas dos patos.
* **Gerenciar Arsenal (Estratégias):** CRUD para as táticas de ataque, incluindo a "Lógica de Gatilho" em formato JSON (ex: `{"field": "height_cm", "operator": ">", "value": 100}`).
* **Gerenciar Arsenal (Defesas - SGDA):** CRUD para os sistemas de defesa disponíveis para contra-atacar superpoderes.

---

## 💻 Tecnologias Utilizadas

* **Backend:**
    * PHP (>= 8.1 recomendado)
    * Laravel Framework (v10.x ou superior)
    * SQL Server (configurado para conexão via `sqlsrv`)
* **Frontend:**
    * HTML5
    * CSS3 (estilos básicos inline no HTML)
    * JavaScript (ES6+)
    * Vue.js 3 (via CDN)
* **Banco de Dados:**
    * Microsoft SQL Server (testado com conexão via Somee)
* **Ferramentas:**
    * Composer (Gerenciador de dependências PHP)
    * Git (Controle de versão)
    * Navegador Web Moderno (Chrome, Firefox, Edge)
    * Editor de Código (VS Code recomendado)
    * Terminal/Command Prompt

---

## 🔧 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

1.  **PHP:** Versão 8.1 ou superior. Verifique com `php -v`.
2.  **Composer:** Gerenciador de dependências do PHP. Verifique com `composer --version`. [Download Composer](https://getcomposer.org/)
3.  **Git:** Para clonar o repositório. Verifique com `git --version`. [Download Git](https://git-scm.com/)
4.  **SQL Server:** Uma instância acessível (local ou remota como a do Somee).
5.  **Drivers SQL Server para PHP (`sqlsrv` e `pdo_sqlsrv`):** **ESSENCIAL!** O Laravel precisa desses drivers para se comunicar com o SQL Server.
    * **Instalação:** Siga as instruções oficiais da Microsoft para o seu sistema operacional: [Instalar Drivers Microsoft para PHP para SQL Server](https://learn.microsoft.com/pt-br/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver17)
    * **Configuração:** Após instalar, você **precisa** editar seu arquivo `php.ini` (encontre-o rodando `php --ini`) e adicionar/descomentar as linhas:
        ```ini
        extension=php_pdo_sqlsrv_XX_ts_x64.dll ; (ou .so em Linux/Mac, ajuste XX para sua versão PHP)
        extension=php_sqlsrv_XX_ts_x64.dll ; (ou .so em Linux/Mac, ajuste XX para sua versão PHP)
        ```
     * **Intalação dos drivers ODBC para SQL Server** Após a instalação dos drivers pdo, vc precisa agora executar o arquivo **msodbcsql.msi**: [Instalar Driver ODBC](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server?view=sql-server-ver17)

    * Verifique se os drivers foram carregados rodando `php -m` no terminal. `pdo_sqlsrv` e `sqlsrv` devem aparecer na lista.
6.  **Node.js e npm (Opcional, mas recomendado):** Embora o frontend use Vue via CDN, ter Node/npm é útil para desenvolvimento web em geral. [Download Node.js](https://nodejs.org/)
7.  **Editor de Código:** VS Code, Sublime Text, PhpStorm, etc.
8.  **Navegador Web:** Chrome, Firefox, Edge.

---

## 🚀 Instruções de Instalação e Configuração

**1. Clone o Repositório:**

```bash
git clone https://github.com/kayky-ctrl/CoderChallengeBonus.git
cd CoderChallengeBonus
```
**2. Configure o Backend (Laravel):**

Navegue até a pasta do backend (ex: cd BackEnd).

Instale as dependências do PHP:

```Bash

composer install
Copie o arquivo de configuração de ambiente:
```
```Bash

cp .env.example .env
Gere a chave da aplicação Laravel:
```

```Bash
php artisan key:generate
```
Configure a Conexão com o Banco de Dados (Somee/SQL Server):

Abra o arquivo .env no seu editor.

Localize a seção DB_CONNECTION e configure as variáveis para o seu banco de dados SQL Server (obtido no painel do Somee):

Snippet de código
```
DB_CONNECTION=sqlsrv
DB_HOST=<SEU_SERVIDOR_SOMEE.database.windows.net_ou_similar>
DB_PORT=1433
DB_DATABASE=<NOME_DO_SEU_BANCO>
DB_USERNAME=<SEU_USUARIO_DB>
DB_PASSWORD=<SUA_SENHA_DB>
```
Importante: Certifique-se de que os drivers sqlsrv e pdo_sqlsrv estão instalados e habilitados no seu php.ini (veja Pré-requisitos).

Firewall: Verifique se o firewall do Somee (ou local) permite conexões externas para a porta 1433 a partir do seu IP, se necessário.

Execute as Migrações do Banco de Dados: Este comando criará todas as tabelas necessárias.

```Bash

php artisan migrate:fresh
(Use migrate:fresh para apagar tabelas existentes e recriar tudo - CUIDADO: apaga dados! Use php artisan migrate se quiser apenas aplicar migrações pendentes).
```

(Opcional) Limpe os Caches:
```Bash

php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

**3. Configure o Frontend (Vue.js via CDN):**

Navegue até a pasta do frontend (ex: cd ../FrontEnd).

Abra o arquivo index.html no seu editor.

Localize a variável apiBaseUrl dentro da seção <script>:

```JavaScript

// !!! MUDE AQUI SE NECESSÁRIO !!!
apiBaseUrl: 'http://localhost:8000/api', // Sem barra no final
```
Ajuste o valor para corresponder ao endereço onde seu backend Laravel está rodando (o padrão http://localhost:8000/api geralmente funciona se você rodar php artisan serve).

## ▶️ Executando a Aplicação

**Inicie o Servidor Backend (Laravel):**

No terminal, dentro da pasta do backend (BackEnd), execute:
```Bash

php artisan serve
```
O Laravel iniciará um servidor de desenvolvimento, geralmente em http://127.0.0.1:8000. Mantenha este terminal aberto.

Abra o Frontend no Navegador:

Abra o arquivo FrontEnd/index.html diretamente no seu navegador web.

(Recomendado) Se você usa VS Code, instale a extensão "Live Server" e clique com o botão direito no index.html > "Open with Live Server". Isso fornece recarregamento automático.

Agora você deve conseguir acessar a aplicação no seu navegador e interagir com as funcionalidades!

## 🗺️ Visão Geral da API (Backend)

O backend Laravel expõe uma API RESTful para o frontend consumir. Os principais endpoints estão agrupados por missão:

**Endpoints Base (Missão 1 & Outros):**

* **Fabricantes (Manufacturer):**
    * `GET /api/manufacturer`: Lista fabricantes.
    * `POST /api/manufacturer`: Cria fabricante.
    * `GET /api/manufacturer/{id}`: Mostra fabricante.
    * `PUT /api/manufacturer/{id}`: Atualiza fabricante.
    * `DELETE /api/manufacturer/{id}`: Deleta fabricante.
* **Drones de Pesquisa (SurveyDrone):**
    * `GET /api/survey-drones`: Lista drones de pesquisa.
    * `POST /api/survey-drones`: Cria drone de pesquisa.
    * `GET /api/survey-drones/{survey_drone}`: Mostra drone de pesquisa (usa Route Model Binding).
    * `PUT /api/survey-drones/{survey_drone}`: Atualiza drone de pesquisa (usa Route Model Binding).
    * `DELETE /api/survey-drones/{survey_drone}`: Deleta drone de pesquisa (usa Route Model Binding).
* **Avistamentos e Patos (Sightings & PrimordialDucks):**
    * `POST /api/sightings`: Registra um novo avistamento (ingestão principal da M1).
    * `GET /api/primordial-ducks`: Lista todos os patos catalogados (com análise M2).
    * `GET /api/primordial-ducks/{duck:designation}`: Mostra detalhes de um pato (usa Route Model Binding pela `designation`).
* **Logs de Avistamento (SightingLog):**
    * `GET /api/sighting-logs`: Lista todos os logs de avistamento.
    * `GET /api/primordial-ducks/{duck:designation}/logs`: Lista logs para um pato específico.
    * `GET /api/survey-drones/{surveyDrone:serial_number}/logs`: Lista logs para um drone de pesquisa específico.

**Endpoints da Missão 3 (Captura e Controle):**

* **Drones de Captura (CaptureDrone):**
    * `GET /api/capture-drones`: Lista drones de captura.
    * `POST /api/capture-drones`: Cria drone de captura.
    * `GET /api/capture-drones/{capture_drone}`: Mostra drone de captura (usa Route Model Binding).
    * `PUT /api/capture-drones/{capture_drone}`: Atualiza drone de captura (usa Route Model Binding).
    * `DELETE /api/capture-drones/{capture_drone}`: Deleta drone de captura (usa Route Model Binding).
    * `POST /api/capture-drones/{drone}/telemetry`: Recebe atualização de telemetria (usa Route Model Binding).
* **Arsenal:**
    * `GET /api/weaknesses`: Lista fraquezas. (CRUD completo via `apiResource`).
    * `POST /api/weaknesses`: Cria fraqueza.
    * `GET /api/weaknesses/{weakness}`: Mostra fraqueza.
    * `PUT /api/weaknesses/{weakness}`: Atualiza fraqueza.
    * `DELETE /api/weaknesses/{weakness}`: Deleta fraqueza.
    * `GET /api/attack-strategies`: Lista estratégias. (CRUD completo via `apiResource`).
    * `POST /api/attack-strategies`: Cria estratégia.
    * `GET /api/attack-strategies/{attack_strategy}`: Mostra estratégia.
    * `PUT /api/attack-strategies/{attack_strategy}`: Atualiza estratégia.
    * `DELETE /api/attack-strategies/{attack_strategy}`: Deleta estratégia.
    * `GET /api/defense-systems`: Lista sistemas de defesa. (CRUD completo via `apiResource`).
    * `POST /api/defense-systems`: Cria sistema de defesa.
    * `GET /api/defense-systems/{defense_system}`: Mostra sistema de defesa.
    * `PUT /api/defense-systems/{defense_system}`: Atualiza sistema de defesa.
    * `DELETE /api/defense-systems/{defense_system}`: Deleta sistema de defesa.
* **Missões e IA Tática:**
    * `POST /api/capture-missions`: Inicia uma nova missão de captura.
    * `GET /api/capture-missions`: Lista todas as missões.
    * `GET /api/capture-missions/{mission}`: Mostra detalhes de uma missão (usa Route Model Binding).
    * `GET /api/primordial-ducks/{duck:designation}/tactical-plan`: Gera o plano tático (IA Pró-ativa).
    * `POST /api/capture-missions/{mission}/activate-defense`: Solicita ativação de defesa (IA Reativa).

*(Consulte o arquivo `routes/api.php` para a lista completa e detalhada).*

**Padronização:** A API utiliza **API Resources** do Laravel para padronizar as respostas JSON, especialmente para listagens (geralmente dentro de uma chave `"data"`).

## 🖥️ Visão Geral do Frontend

O frontend é uma **Single Page Application (SPA)** contida em um único arquivo `index.html`.

* **Estrutura:** Utiliza **Vue.js 3** carregado via CDN. Todo o estado da aplicação (`data`), lógica de interface (`methods`, `computed`) e chamadas à API residem dentro da instância Vue criada na tag `<script>` no final do arquivo.
* **Modos:** A interface é dividida em dois modos principais, selecionáveis no topo:
* **Modo Pesquisa:** Focado nas funcionalidades das Missões 1 (Catalogação) e 2 (Análise).
* **Modo Captura:** Focado nas funcionalidades da Missão 3 (Gerenciamento de Arsenal, Missões, IA Tática).
* **Navegação:** Cada modo possui seu próprio conjunto de abas (renderização condicional de `<section>`) para navegar entre as diferentes funcionalidades (Logs, Patos, Drones, Missões, Arsenal, etc.).
* **Interatividade:** Usa a reatividade do Vue (`v-model`, `v-for`, `v-if`, `@click`) para atualizar a interface dinamicamente conforme os dados são carregados ou modificados.
* **Modais:** Formulários de criação/edição e visualizações detalhadas (Detalhes do Pato, Detalhes da Missão, Plano Tático, etc.) são exibidos em janelas modais sobrepostas (controladas por `v-if` na variável `modal`).
* **Comunicação:** Interage exclusivamente com o backend Laravel através da API RESTful. As chamadas são feitas pela função `async apiRequest()` usando a `fetch` API nativa do JavaScript. A URL base da API é configurada na variável `apiBaseUrl`.

## 🏗️ Arquitetura do Backend

O backend Laravel segue padrões comuns de desenvolvimento web:

* **MVC (Model-View-Controller):**
* **Models:** (`app/Models`) Representam as tabelas do banco de dados e definem os relacionamentos (ex: `PrimordialDuck`, `CaptureMission`, `Superpower`).
* **Views:** Mínimas neste projeto, pois o frontend é separado (Vue.js).
* **Controllers:** (`app/Http/Controllers`) Recebem as requisições HTTP, interagem com os Models e Services, e retornam as respostas (geralmente JSON formatado por Resources).
* **Services:** (`app/Services`) Contêm a lógica de negócio principal, desacoplada dos Controllers (ex: `UnitConversionService`, `DuckAnalysisService`, `TacticalService`, `DefenseService`).
* **Observers:** (`app/Observers`) Permitem executar ações automaticamente em resposta a eventos dos Models (ex: `PrimordialDuckObserver` gera a análise M2 sempre que um pato é salvo). Registrados em `AppServiceProvider`.
* **API Resources:** (`app/Http/Resources`) Transformam os Models em respostas JSON padronizadas e controladas para a API.
* **Form Requests:** (`app/Http/Requests`) Lidam com a validação das requisições de entrada (ex: `StoreSightingRequest`, `StoreCaptureMissionRequest`).
* **Routing:** (`routes/api.php`) Define todos os endpoints da API e os conecta aos Controllers correspondentes. Usa `Route::apiResource` para CRUDs padrão e rotas específicas para ações customizadas.
* **Migrations:** (`database/migrations`) Definem a estrutura das tabelas do banco de dados de forma versionável.

## ❓ Solução de Problemas Comuns

* **Erro CORS:** Se o frontend não conseguir conectar à API devido a políticas CORS, verifique o arquivo `config/cors.php` no backend e ajuste `allowed_origins` para permitir o endereço onde o frontend está rodando (ex: `http://127.0.0.1:5500` se usar Live Server). Lembre-se de rodar `php artisan config:clear`.
* **Erro 500 (Internal Server Error):** Geralmente indica um erro no backend. Verifique os logs do Laravel em `storage/logs/laravel.log` para detalhes específicos. Causas comuns incluem:
* Problemas de conexão com o banco de dados (verifique `.env`).
* Falta dos drivers `sqlsrv`/`pdo_sqlsrv`.
* Tabelas não criadas (rode `php artisan migrate` ou `migrate:fresh`).
* Erros de sintaxe ou lógica no código PHP.
* **Erro 404 (Not Found):** A URL da API chamada pelo frontend não existe no `routes/api.php` ou há um erro de digitação no endpoint. Verifique a `apiBaseUrl` no `index.html` e as rotas no backend.
* **Erro 422 (Unprocessable Content):** Erro de validação. A API recebeu dados, mas eles não passaram nas regras definidas nos Form Requests ou no Controller. Verifique a mensagem de erro detalhada retornada pela API (o frontend a exibe) para saber qual campo falhou.
* **Dados "N/A" ou Faltando:** Verifique se:
    * Os relacionamentos corretos estão sendo carregados (`with([...])`) nos Controllers do backend.
    * Os API Resources estão incluindo os dados necessários (e com as chaves corretas).
    * O frontend está acessando os dados usando as chaves JSON corretas.

## 🌱 Possíveis Melhorias Futuras

* **Autenticação:** Implementar autenticação na API (Laravel Sanctum ou Passport) e no frontend.
* **Interface de Ligação (M3):** Criar UI para ligar Patos a Fraquezas e Superpoderes a Defesas (tabelas `duck_weaknesses`, `superpower_counters`).
* **Melhorias na UI/UX:** Refinar o layout, adicionar feedback visual, paginação, filtros/busca.
* **Validação Frontend:** Adicionar validação nos formulários do frontend para feedback mais rápido.
* **Testes Automatizados:** Escrever testes unitários e de feature para o backend Laravel.
* **Atualizações em Tempo Real (Opcional):** Usar WebSockets (Laravel Echo) para atualizar status de missões ou telemetria em tempo real.
* **Build do Frontend:** Usar Vite ou Webpack para compilar o JavaScript/CSS do frontend (em vez de CDN), permitindo componentização e otimizações.
