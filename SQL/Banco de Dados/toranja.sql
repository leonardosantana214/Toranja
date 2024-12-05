-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/01/2024 às 15:16
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `toranja`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `produto_nome` varchar(255) DEFAULT NULL,
  `produto_imagem` varchar(255) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`id`, `usuario_id`, `produto_id`, `produto_nome`, `produto_imagem`, `quantidade`, `preco`) VALUES
(41, 15, 7, 'League Of Legends', 'https://i.redd.it/ezpbxq8wwp6c1.gif', 15, 0.00),
(42, 15, 2, 'Elder Ring', 'https://cdna.artstation.com/p/assets/images/images/063/357/982/original/bryan-heemskerk-eldenringfinal.gif?1685365876', 3, 49.99),
(46, 1, 1, 'Dark Souls', 'https://i.pinimg.com/originals/06/a8/5b/06a85b703ccc50fcc2214bac56214f48.gif', 10, 59.99),
(47, 1, 6, 'Harry Potter', 'https://i.pinimg.com/originals/29/9c/57/299c57a4e37ab1153f6abf19f62b2be2.gif', 10, 100.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pontos_desconto`
--

CREATE TABLE `pontos_desconto` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `pontos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pontos_desconto`
--

INSERT INTO `pontos_desconto` (`id`, `usuario_id`, `pontos`) VALUES
(1, 9, 50),
(2, 10, 50),
(3, 6, 50),
(4, 7, 50),
(5, 11, 50),
(6, 1, 50);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL,
  `imagem3` varchar(255) DEFAULT NULL,
  `imagem4` varchar(255) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `imagem`, `imagem1`, `imagem2`, `imagem3`, `imagem4`, `preco`) VALUES
(1, 'Dark Souls', 'Um Mundo Bastante desafiador', 'https://i.pinimg.com/originals/06/a8/5b/06a85b703ccc50fcc2214bac56214f48.gif', 'Paisagens\\Dark-soul\\Dark-Souls.gif', 'Paisagens\\Dark-soul\\Dark-Souls3.gif', 'Paisagens\\Dark-soul\\Dark-Souls4.gif', 'Paisagens\\Dark-soul\\Dark-souls4.webp', 59.99),
(2, 'Elder Ring', 'Um mundo cheio de aventuras', 'https://cdna.artstation.com/p/assets/images/images/063/357/982/original/bryan-heemskerk-eldenringfinal.gif?1685365876', 'Paisagens\\Elden-Ring\\Elden-Ring2.gif', 'Paisagens\\Elden-Ring\\Elden-Ring3.gif', 'Paisagens\\Elden-Ring\\Elden-Ring4.gif', 'Paisagens\\Elden-Ring\\Elden-Ring5.gif', 49.99),
(3, 'Final Fantasy', 'Um mundo único com vários desafios', 'https://i.pinimg.com/originals/58/89/cb/5889cb1227933a14c8f24220fc7cd390.gif', '\nPaisagens\\Final-fantasy\\Final Fantasy1.gif\n', 'Paisagens\\Final-fantasy\\Final Fantasy2.gif', 'Paisagens\\Final-fantasy\\Final Fantasy3.gif', 'Paisagens\\Final-fantasy\\Final Fantasy4.gif', 69.99),
(4, 'skyrim', 'Viva uma historia de um Heroi ', 'https://i.redd.it/l91u0gey2cz91.gif', 'https://i.pinimg.com/originals/e4/97/0b/e4970b0db8da0923c4cc9a88dc1c707a.gif', 'https://i.pinimg.com/originals/83/cf/15/83cf152095bdeb24713093606facc72b.gif', 'https://64.media.tumblr.com/6ea61f6d4be3e6063b10d6fa7787c78c/tumblr_omofu5R4Dz1sakqgbo3_r1_1280.gif', 'https://i.pinimg.com/originals/47/8d/60/478d6057a2d9c2f01db4d8751d926498.gif', 129.00),
(6, 'Harry Potter', 'Se torne um mago poderoso sendo um trouxa', 'https://i.pinimg.com/originals/29/9c/57/299c57a4e37ab1153f6abf19f62b2be2.gif', 'https://art.pixilart.com/c54917a56a375fc.gif', 'https://art.pixilart.com/sr29075589cbdb2.gif', 'https://gifdb.com/images/high/phoenix-pixel-art-19x0p0sly95iicb4.gif', 'https://i.redd.it/fc7o2n2lici41.jpg', 100.00),
(7, 'League Of Legends', 'um mundo de batalhas  unicamentes lendarias ', 'https://i.redd.it/ezpbxq8wwp6c1.gif', 'https://cdnb.artstation.com/p/assets/images/images/056/424/555/original/gustavo-tajima-birthday.gif?1669209185', 'https://pa1.aminoapps.com/5838/f550289222937b68f457a534e56d4eb48751767d_00.gif', 'https://cdna.artstation.com/p/assets/images/images/047/500/012/original/hoon-kim-orrn-smith-3.gif?1647736905', 'https://i.pinimg.com/originals/c2/b7/2c/c2b72cfbbbc93891c51b5c615c10b356.gif', 0.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `image` varchar(255) DEFAULT 'Buriti.gif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `cpf`, `image`) VALUES
(1, 'Toranjas', 'Toranjas@toto.com', '$2y$10$e5oWDdbmaP0.2khmkZEgPeZr2tf/.yDuIUilC8ukHSTbmQLaN2bIC', '100.220.232', 'foto de perfil.gif'),
(6, 'Rau', 'Limas@tu.com', '$2y$10$TYCGQPeHKJj.34.0Ho1N1ur8cJXVOyVNc57pqztSkfXVJMkgNPtx6', '100.220.232', 'Frutas/Enchanted_Golden_Apple_JE2_BE2.gif'),
(7, 'Lua', 'Lulu@lu.com', '$2y$10$/oS23WRIc0NKRlJgm4sYperOJn3r0bCrHwRmkd2VTj/AlW5SxZTtK', '100.220.232', 'foto de perfil.gif'),
(9, 'zelia', '', '$2y$10$2kq4Oy/zMGx5T6ySGb.Xb.Wpejs0fIczFC2KML7YvYTsgAD/1bM2q', '', 'Frutas/Enchanted_Golden_Apple_JE2_BE2.gif'),
(10, 'zelia', 'contatocpae@gmail.com', '$2y$10$Pug5Q90AbMsg9D269oGOvOj4CepMVwuWcNda79hbR.4mlVoFr7xVm', '296.547.898', 'Frutas/Enchanted_Golden_Apple_JE2_BE2.gif'),
(11, 'Rau Pau Pqno', 'raupnq@gmail.com', '$2y$10$04FPMOWeBALN.oo2.LTRZ.mqi8YWwWQkSaqMRMYnpxwxi0NxQu5hO', '100.220.232', 'Frutas/Enchanted_Golden_Apple_JE2_BE2.gif'),
(13, 'Thierry Henry', 'Henry@loli.com', '$2y$10$m/Q4PsZrjxDtTU7FY26rHuVtxpLp4Pia4.M9ERvis/7gYV2YOaR3u', '100.220.232', ''),
(15, 'kkk', 'evilmulack@gmail.com', '$2y$10$P5jzLzXdq6DnEW319i4/heCLgQwrdorDRg79NUtqHrM3.X8PPmO/K', '123.312.312', 'Buriti.gif');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`usuario_id`);

--
-- Índices de tabela `pontos_desconto`
--
ALTER TABLE `pontos_desconto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `pontos_desconto`
--
ALTER TABLE `pontos_desconto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `pontos_desconto`
--
ALTER TABLE `pontos_desconto`
  ADD CONSTRAINT `pontos_desconto_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
