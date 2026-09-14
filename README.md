# PRD_SAC — Automação de Atendimento ao Cliente e Extração de Dados

Processo de dados e automação de processos (RPA) para triagem, alteração de estado e processamento em lote de chamados de atendimento ao cliente no sistema **PRD_SAC**.
O script automatiza o ciclo completo de atendimento, contornando redirecionamentos assíncronos do backend PHP e estruturando relatórios operacionais em DataFrames do `pandas`.

---

## Arquitetura do Projeto

O fluxo de automação integra o robô em Python com a aplicação web (PHP/MySQL) controlando a navegação, preenchimento de formulários e sincronização de estado do DOM.

```
+------------------+         1. Captura a tabela de chamados           +--------------------------+
|                  | ------------------------------------------------> |                          |
|                  |                                                   |   PRD_SAC Backend        |
|                  | <------------------------------------------------ |   (lista_atendimentos)   |
|                  |       2. Mapeia registros com Feedbacks nulos     +--------------------------+
|                  |                                                                |
|                  | -- 3. Acessa o formulário de atendimento ---------> [ chat.php?id=X ]
|   Selenium Bot   |                                                                |
|   (Python/Pandas)| -- 4. Dispara opções de atendimento no <select> -+             |
|                  |    (Itera sobre opções de formulário)             |            |
|                  |                                                   v            |
|                  | -- 5. Submete resposta / Registra ação ------------------------> [ acoes.php ]
|                  |                                                                |
|                  | <-- 6. Processa no banco e redireciona (HTTP 302) -------------+
|                  |
|                  | -- 7. Retorna à lista, sincroniza DOM e avança --> [ lista_atendimentos.php ]
+------------------+

```

---

## Desafios enfrentados

### 1. Resiliência ao `StaleElementReferenceException`

* **Problema:** A cada ciclo de atendimento, a navegação para rotas como `acoes.php` recarrega a página ou limpa a árvore do DOM, invalidando instâncias prévias do Selenium (`WebElement`).
* **Solução:** O script re-instancia os elementos do DOM a cada iteração acessando as linhas da tabela dinamicamente por índice (`//table/tbody/tr[{i}]`).

### 2. Tratamento de Redirecionamentos Assíncronos

* **Problema:** A aplicação backend processa a transição de estados via `acoes.php` e aplica um redirecionamento imediato para a visualização principal, interrompendo chamadas diretas de elementos.
* **Solução:** Implementação de barreiras de sincronização explícitas com `WebDriverWait` e sincronização de estado garantindo o recarregamento total da lista antes do próximo ciclo.

### 3. Estruturação dos Dados

* **Solução:** Consolidação automática de todos os metadados dos chamados (Protocolo, Serviço, Validade, Status e Feedback) em estruturas `pandas.DataFrame` para posterior exportação ou análise de dados.

---

## Tech Stack

* **Linguagem:** Python 3.10+, PHP
* **Automação Web:** Selenium WebDriver
* **Manipulação e Modelgagem de Dados:** Pandas, PostegreSQL, brmodelo 
* **Ambiente do Sistema Alvo:** PHP, MySQL, Apache (XAMPP)

---

## Instalação do Projeto

1. **Clone o repositório:**
```bash
git clone https://github.com/seu-usuario/prd_sac-automation.git
cd prd_sac-automation

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
pip install requirements.txt

```

4. **Certifique-se de que a aplicação base está rodando:**
* Certifique-se de que o servidor local (XAMPP/Apache/MySQL) esteja ativo em `http://localhost/prd_sac/`.



---

## How to Run

Execute a rotina do robô iniciando o script principal:

```bash
python main.py

```

### Exemplo de Output do Console

```text
Loop iniciado
[Navegação] Acessando chamado pendente...
Atendimento finalizado
[Redirecionamento] Retornando para lista_atendimentos.php...

   protocolo         servico    validade      status feedback
0        140  Suporte Técnico  2026-10-01   Concluído        -
1        139  Financeiro       2026-10-02   Concluído        -

```