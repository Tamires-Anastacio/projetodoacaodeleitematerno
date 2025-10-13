-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Out-2025 às 13:41
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
-- Banco de dados: `usuario`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastro`
--

CREATE TABLE `cadastro` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `datanascimento` date DEFAULT NULL,
  `senha` varchar(30) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `telefone` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cadastro`
--

INSERT INTO `cadastro` (`id`, `nome`, `cpf`, `email`, `datanascimento`, `senha`, `uf`, `telefone`) VALUES
(1, 'Lorena silva', '111.111.111', 'cristinabruna877@gmail.com', '1999-11-11', '111', 'DF', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cadastro`
--
ALTER TABLE `cadastro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadastro`
--
ALTER TABLE `cadastro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
