INSERT INTO `usuario` (`id_user`, `cpf`, `nome_completo`, `cidade`, `data_nascimento`, `telefone`, `email`, `senha_hash`, `uf`, `tipo_user`, `data_cadastro`, `latitude`, `longitude`) VALUES
(1, '84364785664', 'Tamires Anastacio ', 'Antônio Prado', '2006-06-30', '11837484332', 'yuhuu@gmail.com', '$2y$10$Wx7nrtYfm93UDc67cWFZquymH2jFxuhbxE9e7nFf5vocD47eXiXEe', 'RS', 'user', '2025-11-24 07:46:54', NULL, NULL),
(2, '63536346754', 'Ana Carolina', 'Baião', '2020-12-15', '11927836442', 'tudodebom@gmail.com', '$2y$10$hPdOjeTxqZLw0Qc5ZE/8Wuwdye2b/v29c3KOAGXlMTOWfwbQr1i0a', 'PA', 'user', '2025-11-24 07:49:03', NULL, NULL),
(3, '63352363474', 'bobbie', 'Álvaro de Carvalho', '2020-12-07', '11928736455', 'goods@gmail.com', '$2y$10$LwUeQukPLmTszYDVF5jLzegV.5rez6kg6Yk8fadSpgVl4OEzzVoJS', 'SP', 'adm', '2025-11-24 08:14:46', NULL, NULL);


# 🍼 Projeto de Doação de Leite Materno  
Sistema completo para conectar doadoras, receptores e instituições de coleta.

<p align="center">
  <img src="./assets/banner.png" width="700">
</p>

---

## 📚 Sumário
- [Sobre o Projeto](#sobre-o-projeto)
- [Funcionalidades](#funcionalidades)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Como Executar](#como-executar)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [API / Backend](#api--backend)
- [Interface / Frontend](#interface--frontend)
- [Futuras Implementações](#futuras-implementações)
- [Contribuição](#contribuição)
- [Licença](#licença)

---

## 🧸 Sobre o Projeto
O sistema de **Doação de Leite Materno** foi criado para facilitar a comunicação entre:

- 👩‍🍼 **Doadoras**  
- 🧒 **Famílias receptoras**  
- 🏥 **Instituições e bancos de leite**

O objetivo é **agilizar o processo de cadastro, aprovação, solicitação e retirada**, garantindo segurança, controle e informação de qualidade.

---

## ✨ Funcionalidades

### 🧑‍🍼 Para Usuários
- Cadastro de mães doadoras e receptores  
- Autenticação (login/senha)  
- Atualização de dados pessoais  
- Acompanhamento de solicitações

### 🏥 Para Instituições
- Dashboard de doações e retiradas  
- Gerenciamento de usuários  
- Aprovação e recusa de cadastros  
- Registro de estoque de leite  
- Abertura e encerramento de solicitações

### ⚙️ Sistema
- Validação de CPF, telefone e endereço  
- Máscaras automáticas para campos sensíveis  
- Busca de instituições  
- Logs de ações importantes

---

## 🛠 Tecnologias Utilizadas

- **Frontend:** HTML, CSS, JavaScript, Bootstrap  
- **Backend:** PHP (com sessões, validações e rotas)  
- **Banco de Dados:** MySQL / MariaDB  
- **Infra:** AWS (serviços básicos), Apache ou Nginx  
- **Extras:** AJAX, JSON, Fetch API  

---

## 🚀 Como Executar

### 🔧 Requisitos
- PHP 8+
- MySQL ou MariaDB
- Composer (opcional)
- Servidor local (XAMPP, WAMP, Laragon)

### ▶️ Passo a passo
```bash
git clone https://github.com/seu-usuario/projeto-doacao-leite-materno
cd projeto-doacao-leite-materno

---
## Caso de Uso e funcionalidades ##
https://docs.google.com/document/d/1HGOclJQjvIkPw20wCeOSK7nX2VjGyYlrEm7LjCNQ8_I/edit?usp=sharing
