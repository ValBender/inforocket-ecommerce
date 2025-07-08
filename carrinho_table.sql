-- Criar tabela Carrinho para sistema de compras
CREATE TABLE IF NOT EXISTS Carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produtoId INT NOT NULL,
    userId INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produtoId) REFERENCES Produto(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES Utilizador(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (userId, produtoId)
);

-- Inserir alguns itens de exemplo no carrinho (opcional)
-- INSERT INTO Carrinho (produtoId, userId, quantidade) VALUES 
-- (1, 1, 2),
-- (2, 1, 1);

