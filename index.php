<?php
require 'api/auth.php';

session_start();

if(!isset($_SESSION["user"])){
    header("Location: views/login.php");
    exit();
}

require 'api/db.php';

// Busca de produtos
$search = isset($_GET['search']) ? $con->real_escape_string($_GET['search']) : '';

$sql = "SELECT id, nome, descricao, preco FROM Produto";
if ($search !== '') {
    $sql .= " WHERE nome LIKE '%$search%' OR descricao LIKE '%$search%'";
}
$result = $con->query($sql);

$produtos = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}

// Mapear imagens para produtos
$imagemMap = [
    'iPhone 15 Pro Max' => 'assets/images/smartphone.jpg',
    'ASUS ROG Strix Gaming' => 'assets/images/laptop.jpg',
    'iPad Pro 12.9' => 'assets/images/tablet.jpg',
    'Apple Watch Ultra 2' => 'assets/images/smartwatch.jpg',
    'Sony WH-1000XM5' => 'assets/images/fones.jpg',
    'Nikon D7500 DSLR' => 'assets/images/camera.jpg',
    'PlayStation 5' => 'assets/images/console.jpg',
    'Samsung Odyssey G9' => 'assets/images/monitor.jpg'
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfoRocket - Tecnologia de Ponta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --light-gradient: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--light-gradient);
            min-height: 100vh;
        }

        .navbar-custom {
            background: var(--primary-gradient);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
            border: none;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .navbar-brand i {
            margin-right: 10px;
            color: #ffd700;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            margin: 0 5px;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .hero-section {
            background: var(--primary-gradient);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,100 0,80"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 300;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .search-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: -2rem auto 3rem;
            max-width: 800px;
            position: relative;
            z-index: 3;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .search-btn {
            background: var(--primary-gradient);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .product-image {
            height: 250px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-body {
            padding: 1.5rem;
        }

        .product-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 0.8rem;
        }

        .product-description {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--success-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .quantity-input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            width: 80px;
            text-align: center;
            font-weight: 600;
        }

        .add-to-cart-btn {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            flex: 1;
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(240, 147, 251, 0.3);
            color: white;
        }

        .cart-icon {
            position: relative;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .stats-section {
            background: var(--dark-gradient);
            color: white;
            padding: 3rem 0;
            margin: 4rem 0;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #ffd700;
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        .footer {
            background: var(--dark-gradient);
            color: white;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .footer-text {
            opacity: 0.8;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .search-container {
                margin: -1rem 1rem 2rem;
                padding: 1.5rem;
            }
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-rocket"></i>InfoRocket
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#produtos">
                        <i class="fas fa-shopping-bag me-1"></i>Produtos
                    </a>
                </li>
                <?php if(isAdmin()){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="views/areaadmin.php">
                            <i class="fas fa-cogs me-1"></i>Admin Básico
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/areaadmin_premium.php">
                            <i class="fas fa-crown me-1"></i>Admin Premium
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link cart-icon" href="views/cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="views/logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Sair
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="hero-title animate-fade-in">InfoRocket</h1>
                <p class="hero-subtitle animate-fade-in">Tecnologia de Ponta ao Seu Alcance</p>
                <p class="lead animate-fade-in">Descubra os produtos mais inovadores do mercado tecnológico. Qualidade premium, preços competitivos e entrega rápida.</p>
            </div>
        </div>
    </div>
</section>

<!-- Search Container -->
<div class="container">
    <div class="search-container animate-fade-in">
        <form method="get" action="" class="row g-3 align-items-center">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control search-input border-start-0" 
                           name="search" 
                           placeholder="Busque por smartphones, laptops, tablets..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn search-btn w-100">
                    <span class="btn-text">Buscar</span>
                    <div class="loading-spinner"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($produtos); ?></span>
                    <div class="stat-label">Produtos Disponíveis</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <span class="stat-number">24h</span>
                    <div class="stat-label">Entrega Rápida</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <span class="stat-number">100%</span>
                    <div class="stat-label">Garantia</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <span class="stat-number">5★</span>
                    <div class="stat-label">Avaliação</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="produtos" class="container">
    <div class="section-title">
        <h2>Nossos Produtos</h2>
        <p>Tecnologia premium selecionada especialmente para você</p>
    </div>

    <div class="row g-4">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card animate-fade-in">
                    <?php
                    $imagemSrc = isset($imagemMap[$produto['nome']]) ? $imagemMap[$produto['nome']] : 'https://via.placeholder.com/300x250?text=Produto';
                    ?>
                    <img src="<?php echo $imagemSrc; ?>" 
                         class="card-img-top product-image" 
                         alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                         loading="lazy">
                    
                    <div class="card-body product-body">
                        <h5 class="product-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                        <p class="product-description"><?php echo htmlspecialchars(substr($produto['descricao'], 0, 100)) . '...'; ?></p>
                        
                        <div class="product-price">€<?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                        
                        <form method="post" action="api/add_to_cart.php" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                            <input type="number" name="quantidade" value="1" min="1" max="10" class="form-control quantity-input">
                            <button type="submit" class="btn add-to-cart-btn">
                                <i class="fas fa-cart-plus me-1"></i>Adicionar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($produtos)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Nenhum produto encontrado</h4>
            <p class="text-muted">Tente buscar por outros termos ou navegue por nossa seleção completa.</p>
        </div>
    <?php endif; ?>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-brand">
                    <i class="fas fa-rocket me-2"></i>InfoRocket
                </div>
                <p class="footer-text">
                    Sua loja de tecnologia de confiança. Oferecemos os melhores produtos 
                    com qualidade garantida e atendimento excepcional.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="footer-text mb-2">
                    <i class="fas fa-envelope me-2"></i>contato@inforocket.com
                </p>
                <p class="footer-text mb-2">
                    <i class="fas fa-phone me-2"></i>+351 123 456 789
                </p>
                <p class="footer-text">
                    <i class="fas fa-map-marker-alt me-2"></i>Lisboa, Portugal
                </p>
            </div>
        </div>
        <hr class="my-4" style="opacity: 0.3;">
        <div class="text-center">
            <p class="mb-0 opacity-75">
                © 2025 InfoRocket. Todos os direitos reservados. | Desenvolvido com ❤️ para a Tarefa 3
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Animações e interatividade
document.addEventListener('DOMContentLoaded', function() {
    // Animação de loading no botão de busca
    const searchForm = document.querySelector('form[method="get"]');
    const searchBtn = document.querySelector('.search-btn');
    const btnText = searchBtn.querySelector('.btn-text');
    const spinner = searchBtn.querySelector('.loading-spinner');
    
    searchForm.addEventListener('submit', function() {
        btnText.style.display = 'none';
        spinner.style.display = 'inline-block';
    });
    
    // Smooth scroll para seções
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Animação de entrada dos cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observar todos os cards de produto
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Contador animado para estatísticas
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 20);
    }
    
    // Animar contadores quando visíveis
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const number = entry.target.querySelector('.stat-number');
                const value = parseInt(number.textContent);
                if (!isNaN(value)) {
                    animateCounter(number, value);
                }
                statsObserver.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.stat-card').forEach(card => {
        statsObserver.observe(card);
    });
});

// Feedback visual para adicionar ao carrinho
document.querySelectorAll('form[action="api/add_to_cart.php"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        const btn = this.querySelector('.add-to-cart-btn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adicionando...';
        btn.disabled = true;
        
        // Simular delay para feedback visual
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Adicionado!';
            btn.classList.remove('add-to-cart-btn');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.add('add-to-cart-btn');
                btn.classList.remove('btn-success');
            }, 1500);
        }, 800);
    });
});
</script>

</body>
</html>

