<?php
// Inclui o arquivo de autenticação
require '../api/auth.php';

// Verifica se o utilizador está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com a base de dados
require '../api/db.php';

// Obtém o ID do utilizador da sessão
$user_id = $_SESSION['user_id'];

// Prepara a query para obter os produtos no carrinho do utilizador
$sql = $con->prepare("
    SELECT c.id, c.quantidade, p.id as produto_id, p.nome, p.descricao, p.preco, p.imagem 
    FROM Carrinho c 
    JOIN Produto p ON c.produto_id = p.id 
    WHERE c.utilizador_id = ?
");

// Vincula o parâmetro e executa a query
$sql->bind_param("i", $user_id);

// Executa a query e obtém o resultado
$sql->execute();
$result = $sql->get_result();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrinho de compras | InfoRocket</title>
    <!-- Importa o Bootstrap e os ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            margin-bottom: 2rem;
            padding: 2rem;
        }
        
        .cart-title {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            margin-bottom: 2rem;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .btn-update {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-remove {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .btn-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
            color: white;
        }
        
        .total-badge {
            background: linear-gradient(45deg, #00b894, #00cec9);
            border: none;
            font-size: 1.2rem;
            padding: 10px 25px;
            border-radius: 25px;
        }
        
        .btn-checkout {
            background: linear-gradient(45deg, #00b894, #00cec9);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            padding: 15px 40px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 184, 148, 0.4);
            color: white;
        }
        
        .search-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 25px;
            padding: 10px 20px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .search-input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            padding: 5px 10px;
        }
        
        .search-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 20px;
            color: white;
            padding: 8px 15px;
        }
        
        .empty-cart {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-cart i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-rocket me-2"></i>InfoRocket
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home me-1"></i>Início
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="cart-title">
            <i class="fas fa-shopping-cart me-3"></i>Carrinho de Compras
        </h1>

        <!-- Barra de busca no carrinho -->
        <div class="search-container d-flex">
            <input type="text" class="search-input" placeholder="Buscar produtos no carrinho..." id="searchInput">
            <button class="search-btn" onclick="searchCart()">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <?php
        $total = 0;
        $hasProducts = false;

        if ($result->num_rows > 0) {
            $hasProducts = true;
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="product-card" data-product-name="<?php echo strtolower($row['nome']); ?>">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <?php if ($row["imagem"]): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($row["imagem"]); ?>" 
                                     alt="<?php echo htmlspecialchars($row["nome"]); ?>" 
                                     class="product-image">
                            <?php else: ?>
                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <h5 class="mb-1"><?php echo htmlspecialchars($row["nome"]); ?></h5>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($row["descricao"]); ?></p>
                            <strong class="text-success">€<?php echo number_format($row["preco"], 2, ',', '.'); ?></strong>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Quantidade:</span>
                                <form method="POST" action="../api/update_cart.php" class="d-flex align-items-center">
                                    <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                                    <input type="number" name="quantidade" value="<?php echo $row['quantidade']; ?>" 
                                           min="1" max="99" class="form-control me-2" style="width: 70px;">
                                    <button type="submit" class="btn btn-update btn-sm">
                                        <i class="fas fa-sync-alt me-1"></i>Atualizar
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-secondary fs-6">
                                Subtotal: €<?php echo number_format($row["quantidade"] * $row["preco"], 2, ',', '.'); ?>
                            </span>
                        </div>
                        <div class="col-md-1">
                            <form method="POST" action="../api/delete_cart.php">
                                <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-remove btn-sm" 
                                        onclick="return confirm('Tem certeza que deseja remover este produto?')">
                                    <i class="fas fa-trash me-1"></i>Remover
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                $total += $row["quantidade"] * $row["preco"];
            }
        }
        ?>

        <?php if ($hasProducts): ?>
            <div class="d-flex justify-content-end mt-4">
                <h4>Total do Pedido: <span class="badge total-badge">€<?php echo number_format($total, 2, ',', '.'); ?></span></h4>
            </div>
            
            <!-- Botão de finalizar compra -->
            <div class="d-flex justify-content-center mt-4">
                <a href="finish.php" class="btn btn-checkout">
                    <i class="fas fa-check-circle me-2"></i>Finalizar Compra
                </a>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Seu carrinho está vazio</h3>
                <p>Adicione alguns produtos incríveis da nossa loja!</p>
                <a href="../index.php" class="btn btn-checkout">
                    <i class="fas fa-shopping-bag me-2"></i>Continuar Comprando
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function searchCart() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');
            
            productCards.forEach(card => {
                const productName = card.getAttribute('data-product-name');
                if (productName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Busca em tempo real
        document.getElementById('searchInput').addEventListener('input', searchCart);
    </script>
</body>
</html>

