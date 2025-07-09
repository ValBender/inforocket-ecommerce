<?php
require '../api/auth.php';

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo Premium - Loja Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            --border-radius: 15px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Header Premium */
        .admin-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .admin-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .admin-header .container {
            position: relative;
            z-index: 2;
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.2);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Cards Premium */
        .premium-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .card-header-premium {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }

        .card-title-premium {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        /* Botões Premium */
        .btn-premium {
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-premium:hover::before {
            left: 100%;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-success-premium {
            background: var(--success-gradient);
        }

        .btn-success-premium:hover {
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        .btn-warning-premium {
            background: var(--warning-gradient);
        }

        .btn-warning-premium:hover {
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.4);
        }

        .btn-danger-premium {
            background: var(--danger-gradient);
        }

        .btn-danger-premium:hover {
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
        }

        /* Tabela Premium */
        .table-premium {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            background: white;
        }

        .table-premium thead th {
            background: var(--dark-gradient);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .table-premium tbody tr {
            transition: all 0.3s ease;
        }

        .table-premium tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: scale(1.01);
        }

        .table-premium tbody td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle;
        }

        /* Modal Premium */
        .modal-premium .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .modal-premium .modal-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .modal-premium .modal-title {
            font-weight: 600;
            font-size: 1.3rem;
        }

        .modal-premium .btn-close {
            filter: invert(1);
        }

        /* Form Premium */
        .form-control-premium {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control-premium:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        .form-label-premium {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        /* Loading Premium */
        .loading-premium {
            display: none;
            text-align: center;
            padding: 3rem;
        }

        .spinner-premium {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--success-gradient);
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        /* Action Buttons */
        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin: 0 2px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-edit {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-delete {
            background: var(--danger-gradient);
            color: white;
        }

        /* Alerts Premium */
        .alert-premium {
            border-radius: var(--border-radius);
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .alert-success-premium {
            background: var(--success-gradient);
            color: white;
        }

        .alert-danger-premium {
            background: var(--danger-gradient);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }
            
            .user-info {
                margin-top: 1rem;
                text-align: center;
            }
            
            .stats-card {
                margin-bottom: 1rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <!-- Header Premium -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="header-title">
                        <i class="fas fa-crown me-3"></i>Painel Administrativo Premium
                    </h1>
                    <p class="header-subtitle">Gestão Avançada de Produtos da Loja Online</p>
                </div>
                <div class="col-lg-4">
                    <div class="user-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1">👋 Bem-vindo!</h6>
                                <strong><?php echo htmlspecialchars($_SESSION["user"]["username"]); ?></strong>
                            </div>
                            <div>
                                <a href="../index.php" class="btn btn-light btn-sm me-2">
                                    <i class="fas fa-home"></i>
                                </a>
                                <a href="logout.php" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 fade-in-up stagger-1">
                <div class="stats-card">
                    <div class="stats-number" id="totalProdutos">0</div>
                    <div class="stats-label">Total de Produtos</div>
                </div>
            </div>
            <div class="col-md-3 fade-in-up stagger-2">
                <div class="stats-card">
                    <div class="stats-number" id="produtosHoje">0</div>
                    <div class="stats-label">Criados Hoje</div>
                </div>
            </div>
            <div class="col-md-3 fade-in-up stagger-3">
                <div class="stats-card">
                    <div class="stats-number" id="valorTotal">€0</div>
                    <div class="stats-label">Valor Total</div>
                </div>
            </div>
            <div class="col-md-3 fade-in-up stagger-4">
                <div class="stats-card">
                    <div class="stats-number" id="precoMedio">€0</div>
                    <div class="stats-label">Preço Médio</div>
                </div>
            </div>
        </div>

        <!-- Produtos Section -->
        <div class="premium-card fade-in-up">
            <div class="card-header-premium d-flex justify-content-between align-items-center">
                <h3 class="card-title-premium">
                    <i class="fas fa-box-open me-2"></i>Gestão de Produtos Premium
                </h3>
                <button type="button" class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#produtoModal" onclick="abrirModalCriar()">
                    <i class="fas fa-plus me-2"></i>Novo Produto
                </button>
            </div>
            <div class="card-body p-0">
                <!-- Loading Premium -->
                <div class="loading-premium">
                    <div class="spinner-premium"></div>
                    <h5>Carregando produtos...</h5>
                    <p class="text-muted">Aguarde enquanto buscamos os dados</p>
                </div>

                <!-- Tabela Premium -->
                <div class="table-responsive">
                    <table class="table table-premium mb-0" id="produtosTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                <th><i class="fas fa-tag me-1"></i>Nome</th>
                                <th><i class="fas fa-align-left me-1"></i>Descrição</th>
                                <th><i class="fas fa-euro-sign me-1"></i>Preço</th>
                                <th><i class="fas fa-calendar me-1"></i>Data</th>
                                <th><i class="fas fa-cogs me-1"></i>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="produtosTableBody">
                            <!-- Produtos serão carregados aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Premium para Criar/Editar Produto -->
    <div class="modal fade modal-premium" id="produtoModal" tabindex="-1" aria-labelledby="produtoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="produtoModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Novo Produto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="produtoForm">
                    <div class="modal-body p-4">
                        <input type="hidden" id="produtoId" name="id">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="produtoNome" class="form-label form-label-premium">
                                        <i class="fas fa-tag me-1"></i>Nome do Produto *
                                    </label>
                                    <input type="text" class="form-control form-control-premium" id="produtoNome" name="nome" required placeholder="Digite o nome do produto">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="produtoPreco" class="form-label form-label-premium">
                                        <i class="fas fa-euro-sign me-1"></i>Preço (€) *
                                    </label>
                                    <input type="number" class="form-control form-control-premium" id="produtoPreco" name="preco" step="0.01" min="0" required placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="produtoDescricao" class="form-label form-label-premium">
                                <i class="fas fa-align-left me-1"></i>Descrição *
                            </label>
                            <textarea class="form-control form-control-premium" id="produtoDescricao" name="descricao" rows="4" required placeholder="Descreva o produto detalhadamente"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="produtoImagem" class="form-label form-label-premium">
                                <i class="fas fa-image me-1"></i>Imagem do Produto
                            </label>
                            <input type="file" class="form-control form-control-premium" id="produtoImagem" name="imagem" accept="image/*">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Formatos aceitos: JPG, PNG, GIF (máx. 5MB)
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-premium" id="salvarProdutoBtn">
                            <i class="fas fa-save me-1"></i>Salvar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Premium de Confirmação -->
    <div class="modal fade modal-premium" id="confirmarEliminacaoModal" tabindex="-1" aria-labelledby="confirmarEliminacaoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarEliminacaoModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminação
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <h5>Tem certeza que deseja eliminar este produto?</h5>
                        <p class="lead"><strong id="produtoNomeEliminacao"></strong></p>
                        <p class="text-muted">Esta ação não pode ser desfeita.</p>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger-premium" id="confirmarEliminacaoBtn">
                        <i class="fas fa-trash me-1"></i>Eliminar Definitivamente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let produtoIdParaEliminar = null;
        let modoEdicao = false;
        let produtos = [];

        // Carregar produtos ao inicializar
        document.addEventListener('DOMContentLoaded', function() {
            carregarProdutos();
        });

        // Função para carregar produtos
        function carregarProdutos() {
            mostrarLoading(true);
            
            fetch('../api/produtos.php')
                .then(response => response.json())
                .then(data => {
                    produtos = data;
                    mostrarLoading(false);
                    preencherTabelaProdutos(data);
                    atualizarEstatisticas(data);
                })
                .catch(error => {
                    mostrarLoading(false);
                    console.error('Erro ao carregar produtos:', error);
                    mostrarAlerta('Erro ao carregar produtos', 'danger');
                });
        }

        // Função para preencher tabela
        function preencherTabelaProdutos(produtos) {
            const tbody = document.getElementById('produtosTableBody');
            tbody.innerHTML = '';

            if (produtos.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum produto encontrado</h5>
                            <p class="text-muted">Comece criando seu primeiro produto!</p>
                        </td>
                    </tr>
                `;
                return;
            }

            produtos.forEach((produto, index) => {
                const row = document.createElement('tr');
                row.style.animationDelay = `${index * 0.1}s`;
                row.className = 'fade-in-up';
                
                row.innerHTML = `
                    <td><span class="badge bg-primary">#${produto.id}</span></td>
                    <td>
                        <strong>${produto.nome}</strong>
                    </td>
                    <td>
                        <span class="text-muted">
                            ${produto.descricao.length > 60 ? produto.descricao.substring(0, 60) + '...' : produto.descricao}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success fs-6">€${parseFloat(produto.preco).toFixed(2)}</span>
                    </td>
                    <td>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            ${new Date(produto.created_at).toLocaleDateString('pt-BR')}
                        </small>
                    </td>
                    <td>
                        <button class="action-btn btn-edit" onclick="editarProduto(${produto.id})" title="Editar Produto">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-delete" onclick="confirmarEliminacao(${produto.id}, '${produto.nome}')" title="Eliminar Produto">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Função para atualizar estatísticas
        function atualizarEstatisticas(produtos) {
            const total = produtos.length;
            const hoje = new Date().toDateString();
            const produtosHoje = produtos.filter(p => new Date(p.created_at).toDateString() === hoje).length;
            const valorTotal = produtos.reduce((sum, p) => sum + parseFloat(p.preco), 0);
            const precoMedio = total > 0 ? valorTotal / total : 0;

            // Animação dos números
            animarNumero('totalProdutos', total);
            animarNumero('produtosHoje', produtosHoje);
            animarNumero('valorTotal', valorTotal, '€');
            animarNumero('precoMedio', precoMedio, '€');
        }

        // Função para animar números
        function animarNumero(elementId, valor, prefixo = '') {
            const elemento = document.getElementById(elementId);
            const valorFinal = typeof valor === 'number' ? valor : parseFloat(valor);
            let valorAtual = 0;
            const incremento = valorFinal / 50;
            
            const timer = setInterval(() => {
                valorAtual += incremento;
                if (valorAtual >= valorFinal) {
                    valorAtual = valorFinal;
                    clearInterval(timer);
                }
                
                if (prefixo === '€') {
                    elemento.textContent = prefixo + valorAtual.toFixed(2);
                } else {
                    elemento.textContent = Math.floor(valorAtual);
                }
            }, 20);
        }

        // Função para mostrar loading
        function mostrarLoading(mostrar) {
            const loading = document.querySelector('.loading-premium');
            const table = document.getElementById('produtosTable');
            
            if (mostrar) {
                loading.style.display = 'block';
                table.style.display = 'none';
            } else {
                loading.style.display = 'none';
                table.style.display = 'table';
            }
        }

        // Função para abrir modal de criação
        function abrirModalCriar() {
            modoEdicao = false;
            document.getElementById('produtoModalLabel').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Novo Produto';
            document.getElementById('produtoForm').reset();
            document.getElementById('produtoId').value = '';
        }

        // Função para editar produto
        function editarProduto(id) {
            modoEdicao = true;
            document.getElementById('produtoModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Produto';
            
            fetch(`../api/produtos.php?id=${id}`)
                .then(response => response.json())
                .then(produto => {
                    document.getElementById('produtoId').value = produto.id;
                    document.getElementById('produtoNome').value = produto.nome;
                    document.getElementById('produtoDescricao').value = produto.descricao;
                    document.getElementById('produtoPreco').value = produto.preco;
                    
                    const modal = new bootstrap.Modal(document.getElementById('produtoModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Erro ao carregar produto:', error);
                    mostrarAlerta('Erro ao carregar dados do produto', 'danger');
                });
        }

        // Função para confirmar eliminação
        function confirmarEliminacao(id, nome) {
            produtoIdParaEliminar = id;
            document.getElementById('produtoNomeEliminacao').textContent = nome;
            
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacaoModal'));
            modal.show();
        }

        // Event listener para formulário
        document.getElementById('produtoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const produtoData = {
                nome: formData.get('nome'),
                descricao: formData.get('descricao'),
                preco: parseFloat(formData.get('preco'))
            };

            if (modoEdicao) {
                produtoData.id = parseInt(formData.get('id'));
            }

            const url = '../api/produtos.php';
            const method = modoEdicao ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(produtoData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta(data.message, 'success');
                    carregarProdutos();
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('produtoModal'));
                    modal.hide();
                } else {
                    mostrarAlerta(data.error || 'Erro ao salvar produto', 'danger');
                }
            })
            .catch(error => {
                console.error('Erro ao salvar produto:', error);
                mostrarAlerta('Erro ao salvar produto', 'danger');
            });
        });

        // Event listener para eliminação
        document.getElementById('confirmarEliminacaoBtn').addEventListener('click', function() {
            if (produtoIdParaEliminar) {
                fetch(`../api/produtos.php?id=${produtoIdParaEliminar}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta(data.message, 'success');
                        carregarProdutos();
                    } else {
                        mostrarAlerta(data.error || 'Erro ao eliminar produto', 'danger');
                    }
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmarEliminacaoModal'));
                    modal.hide();
                    produtoIdParaEliminar = null;
                })
                .catch(error => {
                    console.error('Erro ao eliminar produto:', error);
                    mostrarAlerta('Erro ao eliminar produto', 'danger');
                });
            }
        });

        // Função para mostrar alertas premium
        function mostrarAlerta(mensagem, tipo) {
            const alertaDiv = document.createElement('div');
            alertaDiv.className = `alert-premium alert-${tipo}-premium fade-in-up`;
            alertaDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    <span>${mensagem}</span>
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertaDiv, container.firstChild);
            
            setTimeout(() => {
                if (alertaDiv.parentNode) {
                    alertaDiv.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>

