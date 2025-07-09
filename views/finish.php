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

// Obtém informações do utilizador
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];

// Limpa o carrinho após a finalização (simulação)
$clear_cart = $con->prepare("DELETE FROM Carrinho WHERE utilizador_id = ?");
$clear_cart->bind_param("i", $user_id);
$clear_cart->execute();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pedido Finalizado | InfoRocket</title>
    <!-- Importa o Bootstrap e os ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
        .success-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-top: 3rem;
            margin-bottom: 3rem;
            padding: 3rem;
            text-align: center;
        }
        
        .success-icon {
            font-size: 5rem;
            color: #00b894;
            margin-bottom: 2rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .success-title {
            background: linear-gradient(45deg, #00b894, #00cec9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .order-details {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        
        .detail-value {
            font-weight: 500;
            color: #333;
        }
        
        .btn-continue {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            padding: 15px 40px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin: 10px;
        }
        
        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-admin {
            background: linear-gradient(45deg, #fd79a8, #e84393);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            padding: 15px 40px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin: 10px;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(253, 121, 168, 0.4);
            color: white;
        }
        
        .thank-you-message {
            color: #666;
            font-size: 1.1rem;
            margin: 2rem 0;
            line-height: 1.6;
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
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="success-title">Obrigado pela sua compra!</h1>
            
            <p class="thank-you-message">
                Seu pedido foi processado com sucesso. Em breve você receberá um email de confirmação 
                com todos os detalhes da sua compra.
            </p>
            
            <div class="order-details">
                <h4 class="mb-3">
                    <i class="fas fa-receipt me-2"></i>Detalhes do Pedido
                </h4>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-user me-2"></i>Cliente:
                    </span>
                    <span class="detail-value"><?php echo htmlspecialchars($user_name); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-envelope me-2"></i>Email:
                    </span>
                    <span class="detail-value"><?php echo htmlspecialchars($user_email); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-calendar me-2"></i>Data do Pedido:
                    </span>
                    <span class="detail-value"><?php echo date('d/m/Y H:i'); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-credit-card me-2"></i>Método de Pagamento:
                    </span>
                    <span class="detail-value">Processamento Online</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-shipping-fast me-2"></i>Status:
                    </span>
                    <span class="detail-value">
                        <span class="badge bg-success">Pedido Confirmado</span>
                    </span>
                </div>
            </div>
            
            <div class="mt-4">
                <h5 class="mb-3">O que acontece agora?</h5>
                <div class="row text-start">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope-open text-primary me-2"></i>
                            <span>Confirmação por email</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-box text-warning me-2"></i>
                            <span>Preparação do pedido</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-truck text-success me-2"></i>
                            <span>Entrega em 24h</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="../index.php" class="btn btn-continue">
                    <i class="fas fa-shopping-bag me-2"></i>Continuar Comprando
                </a>
                
                <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                    <a href="areaadmin_premium.php" class="btn btn-admin">
                        <i class="fas fa-cogs me-2"></i>Área de Administração
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

