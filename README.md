# 🧠 Teste de Conhecimentos — PHP + Banco de Dados

## 🎯 Objetivo

Desenvolver um **CRUD simples em PHP puro**, **sem frameworks**, onde seja possível:

* 👤 Criar, editar, excluir e listar **usuários**
* 🎨 Criar, editar, excluir e listar **cores**
* 🎨 Vincular e desvincular **várias cores** a cada usuário

---

## 🗄️ Estrutura de Banco de Dados

O projeto utiliza **SQLite**, com o arquivo de banco localizado em:

```
database/db.sqlite
```

A base já contém:

* Estrutura das tabelas necessárias;
* Alguns registros iniciais;
* Exemplo de conexão.

---

## ⚙️ Regras de Negócio

* 🚫 Um **usuário não pode ter cores repetidas**;
* 🔒 Uma **cor não pode ser excluída** se estiver associada a algum usuário;
* 📋 Na **listagem de usuários**, exibir:

  * Quantidade de cores vinculadas;
* 🎨 Na **listagem de cores**, exibir:

  * Quantidade de usuários vinculados;
* ✨ Cores **sem associação** com usuários devem ser facilmente localizadas na listagem.

---

## 💡 Funcionalidades Opcionais

As funcionalidades abaixo não são obrigatórias, mas contarão pontos extras:

* 🗓️ Relatório de cores vinculadas por período;
* 🔐 Autenticação ou controle de sessão;
* ✅ Cobertura de testes.

---

## 🧩 Critérios de Avaliação

| Critério                 | Descrição                                              |
| ------------------------ | -------------------------------------------------------|
| ⚙️ Funcionalidade        | O CRUD de usuários e cores deve funcionar corretamente |
| 🧱 Organização do código | Estrutura clara e modular                              |
| 🧭 Padrão de projeto     | Aplicação de padrões de projeto                        |
| 🖥️ Usabilidade           | Interface simples, funcional e intuitiva               |

---

## 📦 Requisitos da Entrega

* Projeto **funcional** em **PHP puro** com **SQLite**;
* Ao final deste README, adicione:

  * 🧱 Decisões de arquitetura;
  * 📜 Regras implementadas;
  * ⚠️ Dificuldades enfrentadas.

---

## ▶️ Execução do Projeto

Para iniciar o servidor embutido do PHP, execute:

```bash
php -S 0.0.0.0:7070
```

Depois, acesse no navegador:

👉 [http://localhost:7070](http://localhost:7070)

> **Importante:** Certifique-se de que a extensão **SQLite** está instalada e habilitada no seu PHP.

---

## 🍀 Boa Sorte!

Use seu conhecimento, explore a documentação e busque soluções criativas.
Mostre o melhor do seu **raciocínio lógico e domínio de PHP** 🚀

---
