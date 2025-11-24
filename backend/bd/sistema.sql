-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24-Nov-2025 às 12:16
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `doacao`
--

CREATE TABLE `doacao` (
  `id_doacao` int(11) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `status` enum('pendente','aceita','recusada','finalizada') DEFAULT 'pendente',
  `id_doadora` int(11) NOT NULL,
  `id_instituicao` int(11) NOT NULL,
  `data_doacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `instituicao`
--

CREATE TABLE `instituicao` (
  `id_instituicao` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `certificacao` text DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) NOT NULL,
  `uf` char(2) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `num` int(11) NOT NULL,
  `telefone` char(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tipo_user` enum('adm','inst','user') NOT NULL DEFAULT 'inst',
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `senha_hash` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `instituicao`
--

INSERT INTO `instituicao` (`id_instituicao`, `nome`, `cnpj`, `certificacao`, `especialidade`, `cidade`, `uf`, `rua`, `num`, `telefone`, `email`, `tipo_user`, `data_cadastro`, `senha_hash`) VALUES
(1, 'nome', '11.111.111/1111-11', 'certificado', 'banco_de_leite', 'Cardoso Moreira', 'RJ', 'rua', 2, '(11) 11111-1111', 'email@gmail.com', 'inst', '2025-11-24 08:06:17', '$2y$10$tYs1cSuw85gj6EzXhWU7Y.IIx3M6PX8qQ3V3buZ6QicA5xiqDsexi');

-- --------------------------------------------------------

--
-- Estrutura da tabela `logins`
--

CREATE TABLE `logins` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagem`
--

CREATE TABLE `mensagem` (
  `id_mensagem` int(11) NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `conteudo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `possui`
--

CREATE TABLE `possui` (
  `id_tipo` int(11) NOT NULL,
  `id_doacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `recebe`
--

CREATE TABLE `recebe` (
  `id_instituicao` int(11) NOT NULL,
  `id_doacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitacao`
--

CREATE TABLE `solicitacao` (
  `id_solicitacao` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_instituicao` int(11) NOT NULL,
  `tipo_solicitacao` enum('doar','receber') NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `data_solicitacao` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','aceita','recusada') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_leite`
--

CREATE TABLE `tipo_leite` (
  `id_tipo` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_user` int(11) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` char(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `uf` char(2) NOT NULL,
  `tipo_user` enum('adm','inst','user') NOT NULL DEFAULT 'user',
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id_user`, `cpf`, `nome_completo`, `cidade`, `data_nascimento`, `telefone`, `email`, `senha_hash`, `uf`, `tipo_user`, `data_cadastro`, `latitude`, `longitude`) VALUES
(1, '84364785664', 'Tamires Anastacio ', 'Antônio Prado', '2006-06-30', '11837484332', 'yuhuu@gmail.com', '$2y$10$Wx7nrtYfm93UDc67cWFZquymH2jFxuhbxE9e7nFf5vocD47eXiXEe', 'RS', 'user', '2025-11-24 07:46:54', NULL, NULL),
(2, '63536346754', 'Ana Carolina', 'Baião', '2020-12-15', '11927836442', 'tudodebom@gmail.com', '$2y$10$hPdOjeTxqZLw0Qc5ZE/8Wuwdye2b/v29c3KOAGXlMTOWfwbQr1i0a', 'PA', 'user', '2025-11-24 07:49:03', NULL, NULL),
(3, '63352363474', 'bobbie', 'Álvaro de Carvalho', '2020-12-07', '11928736455', 'goods@gmail.com', '$2y$10$LwUeQukPLmTszYDVF5jLzegV.5rez6kg6Yk8fadSpgVl4OEzzVoJS', 'SP', 'adm', '2025-11-24 08:14:46', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `doacao`
--
ALTER TABLE `doacao`
  ADD PRIMARY KEY (`id_doacao`),
  ADD KEY `id_doadora` (`id_doadora`),
  ADD KEY `id_instituicao` (`id_instituicao`);

--
-- Índices para tabela `instituicao`
--
ALTER TABLE `instituicao`
  ADD PRIMARY KEY (`id_instituicao`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices para tabela `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Índices para tabela `mensagem`
--
ALTER TABLE `mensagem`
  ADD PRIMARY KEY (`id_mensagem`),
  ADD KEY `id_remetente` (`id_remetente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Índices para tabela `possui`
--
ALTER TABLE `possui`
  ADD PRIMARY KEY (`id_tipo`,`id_doacao`),
  ADD KEY `id_doacao` (`id_doacao`);

--
-- Índices para tabela `recebe`
--
ALTER TABLE `recebe`
  ADD PRIMARY KEY (`id_instituicao`,`id_doacao`),
  ADD KEY `id_doacao` (`id_doacao`);

--
-- Índices para tabela `solicitacao`
--
ALTER TABLE `solicitacao`
  ADD PRIMARY KEY (`id_solicitacao`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_instituicao` (`id_instituicao`);

--
-- Índices para tabela `tipo_leite`
--
ALTER TABLE `tipo_leite`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `doacao`
--
ALTER TABLE `doacao`
  MODIFY `id_doacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `instituicao`
--
ALTER TABLE `instituicao`
  MODIFY `id_instituicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagem`
--
ALTER TABLE `mensagem`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `solicitacao`
--
ALTER TABLE `solicitacao`
  MODIFY `id_solicitacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_leite`
--
ALTER TABLE `tipo_leite`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `doacao`
--
ALTER TABLE `doacao`
  ADD CONSTRAINT `doacao_ibfk_1` FOREIGN KEY (`id_doadora`) REFERENCES `usuario` (`id_user`),
  ADD CONSTRAINT `doacao_ibfk_2` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicao` (`id_instituicao`);

--
-- Limitadores para a tabela `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id_user`);

--
-- Limitadores para a tabela `mensagem`
--
ALTER TABLE `mensagem`
  ADD CONSTRAINT `mensagem_ibfk_1` FOREIGN KEY (`id_remetente`) REFERENCES `usuario` (`id_user`),
  ADD CONSTRAINT `mensagem_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `usuario` (`id_user`);

--
-- Limitadores para a tabela `possui`
--
ALTER TABLE `possui`
  ADD CONSTRAINT `possui_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_leite` (`id_tipo`),
  ADD CONSTRAINT `possui_ibfk_2` FOREIGN KEY (`id_doacao`) REFERENCES `doacao` (`id_doacao`);

--
-- Limitadores para a tabela `recebe`
--
ALTER TABLE `recebe`
  ADD CONSTRAINT `recebe_ibfk_1` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicao` (`id_instituicao`),
  ADD CONSTRAINT `recebe_ibfk_2` FOREIGN KEY (`id_doacao`) REFERENCES `doacao` (`id_doacao`);

--
-- Limitadores para a tabela `solicitacao`
--
ALTER TABLE `solicitacao`
  ADD CONSTRAINT `solicitacao_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id_user`),
  ADD CONSTRAINT `solicitacao_ibfk_2` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicao` (`id_instituicao`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
