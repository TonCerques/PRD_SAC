# PRD_SAC — Automação de Atendimento ao Cliente e Extração de Dados

Processo de dados e automação de processos (RPA) para triagem, alteração de estado e processamento em lote de chamados de atendimento ao cliente no sistema **PRD_SAC**. O script automatiza o ciclo completo de atendimento, contornando redirecionamentos assíncronos do backend PHP e estruturando relatórios operacionais em DataFrames do `pandas`.

---

## Arquitetura do Projeto

O fluxo de automação integra o robô em Python com a aplicação web (PHP/MySQL) controlando a navegação, preenchimento de formulários e sincronização de estado do DOM.



```

+------------------+         1. Extrai tabela de atendimentos e status         +--------------------------+
|                  | ------------------------------------------------> |                          |
|                  |                                                   |   PRD_SAC Backend        |
|                  | <------------------------------------------------ |  (lista_atendimentos.php)|
|                  |         2. Sincroniza dados com Pandas            +--------------------------+
|   Selenium Bot   |                                                                |
|  (Python/Pandas) | -- 3. Simula entrada de novos clientes via Web Scraping -------> [ Inserção no BD ]
|                  |                                                                |
|                  | -- 4. Processa abertura, assumir e chat automático ------------> [ chat.php / acoes.php ]
|                  |                                                                |
|                  | <-- 5. Finaliza interação, atualiza status e limpa DOM --------+
+------------------+

```

---

## Demonstração das Automações

O ecossistema é dividido em três rotinas independentes operadas via Python e Selenium:

### 1. Disparar Clientes
Insere dinamicamente novos fluxos e solicitações de clientes simulando o tráfego de entrada no sistema.

<p align="center">
  <video src="https://github.com/TonCerques/PRD_SAC/raw/main/Documenta%C3%A7%C3%A3o%20de%20Estudo/disparar_clientes.mp4" width="100%" controls autoplay loop muted></video>
</p>

### 2. Processar Atendimentos
O robô assume os chamados na fila, interage com as rotas de chat e altera os status de forma automatizada.

<p align="center">
  <video src="https://github.com/TonCerques/PRD_SAC/raw/main/Documenta%C3%A7%C3%A3o%20de%20Estudo/processar_atendimento.mp4" width="100%" controls autoplay loop muted></video>
</p>

### 3. Extrair Lista de Atendimentos
Extrai os dados da tabela em tempo real e consolida as informações em estruturas tabulares para análise.

<p align="center">
  <video src="https://github.com/TonCerques/PRD_SAC/raw/main/Documenta%C3%A7%C3%A3o%20de%20Estudo/extrair_lista_atendimentos.mp4" width="100%" controls autoplay loop muted></video>
</p>

---

## Desafios enfrentados

### 1. Resiliência ao `StaleElementReferenceException`

* **Problema:** A cada ciclo de atendimento, a navegação para a rota de Chat e o retorno pra tela inicial, recarrega a página ou perde a sincronia do Render, invalidando instâncias prévias do Selenium (`WebElement`).
* **Solução:** O script re-instancia em um While os elementos do DOM a cada iteração acessando as linhas da tabela dinamicamente por índice (`//table/tbody/tr[{i}]`).

### 2. Estruturação dos Dados

* **Problema:** Os dados de atendimento poderiam ser extraídos através do Banco, mas simulando situações de colaboradores que utilizam sites externos de empresas terceirizadas, a opção mais viável é extrair diretamente do próprio site.
* **Solução:** Consolidação automática de todos os metadados dos chamados (Protocolo, Serviço, Validade, Status e Feedback) em estruturas `pandas.DataFrame` para posterior exportação ou análise de dados.

---

## Tech Stack

* **Linguagem:** Python, PHP
* **Automação Web:** Selenium WebDriver
* **Manipulação e Modelagem de Dados:** Pandas, PostgreSQL, brmodelo 
* **Ambiente do Sistema Alvo:** PHP, MySQL, Apache (XAMPP)

---

## Instalação do Projeto

1. **Clone o repositório:**
```bash
git clone [https://github.com/TonCerques/PRD_SAC.git](https://github.com/TonCerques/PRD_SAC.git)
cd PRD_SAC 

```

2. **Crie e ative um ambiente virtual:**

```bash
python -m venv venv
# Windows:
venv\Scripts\activate
# Linux/Mac:
source venv/bin/activate

```

3. **Instale as dependências:**

```bash
pip install -r requirements.txt

```

---

## Como rodar

Para executar cada uma das etapas automatizadas, rode o script correspondente via terminal:

```bash
python extrair_lista_webscrapping.py

```

```
