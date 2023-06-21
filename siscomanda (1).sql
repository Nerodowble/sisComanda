-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Jun-2023 às 03:55
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `siscomanda`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `mesa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `mesa_id`) VALUES
(36, 'kçljjgui', 13),
(38, 'Meu Cu', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `cargo`, `username`, `password`) VALUES
(9, '', '', 'Willian Vidal Lima', '$2y$10$ZsTzH.k0jnOJFY8MyoYCQO00nQ2NNBfEOrM6ZmdsRB6uyHuAgxi.y');

-- --------------------------------------------------------

--
-- Estrutura da tabela `garcons`
--

CREATE TABLE `garcons` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `garcons`
--

INSERT INTO `garcons` (`id`, `nome`, `ativo`) VALUES
(1, 'Beto', 1),
(4, 'João', 0),
(5, 'Marilda', 0),
(6, 'Sonic', 0),
(10, 'Pimentel', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` varchar(250) NOT NULL,
  `valor` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `itens`
--

INSERT INTO `itens` (`id`, `nome`, `descricao`, `valor`) VALUES
(3, 'Pizza Marguerita', 'Sabores', 30.00),
(4, 'Coca-Cola', 'Coca lata 350ml', 5.00),
(5, 'Coca-Cola', '2 litros', 12.00),
(8, 'Pastel de Queijo', 'Pastel de queijo mussarela, cheddar ou requeijão cremoso', 12.00),
(10, 'Pastel de Carne', 'Pastel com Carne', 9.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `status` enum('livre','ocupada') NOT NULL DEFAULT 'livre',
  `garcom_id` int(11) DEFAULT NULL,
  `nome_atendido` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mesas`
--

INSERT INTO `mesas` (`id`, `numero`, `nome`, `status`, `garcom_id`, `nome_atendido`) VALUES
(1, 0, NULL, '', NULL, NULL),
(2, 0, NULL, 'livre', NULL, NULL),
(3, 0, NULL, 'livre', NULL, NULL),
(4, 0, NULL, 'livre', NULL, NULL),
(5, 0, NULL, 'livre', NULL, NULL),
(6, 0, NULL, 'livre', NULL, NULL),
(7, 0, NULL, 'livre', NULL, NULL),
(8, 0, NULL, 'livre', NULL, NULL),
(9, 0, NULL, 'livre', NULL, NULL),
(10, 0, NULL, 'livre', NULL, NULL),
(11, 0, NULL, 'livre', NULL, NULL),
(12, 0, NULL, 'livre', NULL, NULL),
(13, 0, NULL, '', NULL, NULL),
(14, 0, NULL, 'livre', NULL, NULL),
(15, 0, NULL, 'livre', NULL, NULL),
(16, 0, NULL, 'livre', NULL, NULL),
(17, 0, NULL, 'livre', NULL, NULL),
(18, 0, NULL, 'livre', NULL, NULL),
(19, 0, NULL, 'livre', NULL, NULL),
(20, 0, NULL, 'livre', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mesa_itens`
--

CREATE TABLE `mesa_itens` (
  `id` int(11) NOT NULL,
  `mesa_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `mesa_numero` int(11) DEFAULT NULL,
  `status` enum('pendente','atendido') NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mesa_itens`
--

INSERT INTO `mesa_itens` (`id`, `mesa_id`, `item_id`, `mesa_numero`, `status`) VALUES
(4, NULL, 3, 0, 'pendente'),
(167, 1, 3, NULL, 'pendente'),
(168, 1, 4, NULL, 'pendente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mesa_id` (`mesa_id`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `garcons`
--
ALTER TABLE `garcons`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garcom_id` (`garcom_id`);

--
-- Índices para tabela `mesa_itens`
--
ALTER TABLE `mesa_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `garcons`
--
ALTER TABLE `garcons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `mesa_itens`
--
ALTER TABLE `mesa_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_mesa_id` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `mesas`
--
ALTER TABLE `mesas`
  ADD CONSTRAINT `mesas_ibfk_1` FOREIGN KEY (`garcom_id`) REFERENCES `garcons` (`id`);

--
-- Limitadores para a tabela `mesa_itens`
--
ALTER TABLE `mesa_itens`
  ADD CONSTRAINT `mesa_itens_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `mesa_itens_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `itens` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
