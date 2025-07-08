-- Adicionar tabela de produtos ao banco de dados 24198_Loja

USE `24198_Loja`;

-- Estrutura da tabela `Produto`
CREATE TABLE `Produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` longblob,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserir alguns produtos de exemplo
INSERT INTO `Produto` (`nome`, `descricao`, `preco`) VALUES
('Produto Exemplo 1', 'Descrição do produto exemplo 1', 29.99),
('Produto Exemplo 2', 'Descrição do produto exemplo 2', 49.99),
('Produto Exemplo 3', 'Descrição do produto exemplo 3', 19.99);

