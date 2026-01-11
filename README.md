---

# 🧠 Teste Técnico — PHP + SQLite

## 🎯 Objetivo do Projeto

Este projeto consiste no desenvolvimento de um **CRUD simples em PHP puro**, sem uso de frameworks, com o objetivo de gerenciar:

* 👤 Usuários
* 🎨 Cores
* 🔗 Relacionamento **N:N** entre usuários e cores

O sistema permite criar, editar, listar e excluir usuários e cores, além de **vincular e desvincular múltiplas cores a cada usuário**, respeitando regras de negócio específicas.

---

## 🛠️ Tecnologias Utilizadas

* **PHP 8.4**
* **SQLite**
* **PDO** para acesso ao banco de dados
* **Bootstrap** para estilização da interface
* HTML + CSS
* JavaScript mínimo (apenas para pequenos comportamentos de interface)

---

## 🗄️ Banco de Dados

O banco de dados utilizado é **SQLite**, conforme requisito do teste.

* Arquivo:

  ```
  database/db.sqlite
  ```
* Contém:

  * Estrutura das tabelas
  * Relacionamento entre usuários e cores
  * Registros iniciais para teste

A conexão com o banco é feita através de um **arquivo único**, utilizando **POO** e **PDO**, garantindo maior segurança e reutilização.

---

## 📁 Estrutura do Projeto

A organização do projeto foi feita por **responsabilidade funcional**, separando usuários, cores e relatórios em diretórios distintos:

```
prova-php-entrevista-master/
├── index.php
├── cores/
│   ├── cores-screen.php
│   ├── cores-create.php
│   └── outros arquivos relacionados a cores
├── usuario/
│   ├── usuario-screen.php
│   ├── usuario-create.php
│   └── outros arquivos relacionados a usuários
├── ultimos/
│   └── ultimos_vinculados.php
└── outros arquivos do projeto
```

Essa abordagem facilita a leitura do código e a manutenção, mesmo sem o uso de frameworks.

---

## ⚙️ Regras de Negócio Implementadas

✔️ Um usuário **não pode ter cores repetidas**
✔️ Uma cor **não pode ser excluída** se estiver vinculada a algum usuário
✔️ Na listagem de usuários é exibida a **quantidade de cores vinculadas**
✔️ Na listagem de cores é exibida a **quantidade de usuários vinculados**
✔️ Cores **sem associação** com usuários podem ser facilmente identificadas na listagem

---

##  Funcionalidades Extras Implementadas

### 🗓️ Relatório por Período

Foi implementado um **relatório de cores vinculadas por período**, permitindo filtrar associações com base em datas, atendendo a um dos requisitos opcionais do teste.

---

## 🖥️ Interface e Usabilidade

* Interface simples e funcional
* Utilização do **Bootstrap** para layout e responsividade
* Navegação intuitiva
* Uso mínimo de JavaScript, priorizando formulários tradicionais em PHP

---

## ▶️ Execução do Projeto

1. Certifique-se de ter o **PHP 8.4** instalado com a extensão **SQLite habilitada**
2. No diretório do projeto, execute:

```bash
php -S 0.0.0.0:7070
```

3. Acesse no navegador:

👉 [http://localhost:7070](http://localhost:7070)

---

## 🧱 Decisões de Arquitetura

* Utilização de **PHP puro**, conforme exigido no teste
* Separação de responsabilidades por diretórios (usuário, cores, relatórios)
* Conexão com banco centralizada em um único arquivo utilizando **POO**
* Uso de **PDO e prepared statements** para maior segurança contra SQL Injection
* SQLite escolhido por ser um banco leve, simples de configurar e adequado ao escopo do projeto

---

## 📜 Regras Implementadas

* Controle de duplicidade de cores por usuário
* Validação de exclusão de cores vinculadas
* Contadores de relacionamentos nas listagens
* Relatório com filtragem por data

---

## ⚠️ Dificuldades Enfrentadas

* **Organização do projeto sem framework**, exigindo mais código manual para funcionalidades simples
* Implementação de **filtros por período utilizando SQLite**, tecnologia que ainda não havia sido utilizada anteriormente
* Primeira experiência com **SQLite e sua conexão via PDO**, exigindo estudo da documentação

Esses desafios contribuíram para um melhor entendimento do funcionamento interno do PHP e do banco de dados.

---

## 🍀 Considerações Finais

O projeto cumpre todos os requisitos obrigatórios do teste e implementa funcionalidades extras.
O foco foi entregar um código funcional, organizado e de fácil entendimento, demonstrando domínio dos conceitos fundamentais de **PHP, banco de dados e regras de negócio**.

🚀 Obrigado pela oportunidade!

---

Se quiser, posso:

* Ajustar o **tom** (mais formal ou mais direto)
* Adaptar para **README em inglês**
* Revisar como se fosse um **avaliador técnico** e sugerir melhorias
