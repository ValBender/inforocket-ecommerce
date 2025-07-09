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
    <title>Área de Administração - Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .loading {
            display: none;
        }
        .produto-imagem {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="fas fa-cogs me-2"></i>Área de Administração</h1>
                    <p class="mb-0">Gestão de Produtos da Loja</p>
                </div>
                <div class="col-md-6 text-end">
                    <span class="me-3">Bem-vindo, <?php echo htmlspecialchars($_SESSION["user"]["username"]); ?>!</span>
                    <a href="../index.php" class="btn btn-light me-2">
                        <i class="fas fa-home me-1"></i>Voltar à Loja
                    </a>
                    <a href="logout.php" class="btn btn-outline-light">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Produtos Section -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-box me-2"></i>Gestão de Produtos</h3>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#produtoModal" onclick="abrirModalCriar()">
                    <i class="fas fa-plus me-1"></i>Novo Produto
                </button>
            </div>
            <div class="card-body">
                <!-- Loading -->
                <div class="loading text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2">Carregando produtos...</p>
                </div>

                <!-- Tabela de Produtos -->
                <div class="table-responsive">
                    <table class="table table-hover" id="produtosTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Data de Criação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="produtosTableBody">
                            <!-- Produtos serão carregados aqui via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Criar/Editar Produto -->
    <div class="modal fade" id="produtoModal" tabindex="-1" aria-labelledby="produtoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="produtoModalLabel">Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="produtoForm">
                    <div class="modal-body">
                        <input type="hidden" id="produtoId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="produtoNome" class="form-label">Nome do Produto *</label>
                                    <input type="text" class="form-control" id="produtoNome" name="nome" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="produtoPreco" class="form-label">Preço (€) *</label>
                                    <input type="number" class="form-control" id="produtoPreco" name="preco" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="produtoDescricao" class="form-label">Descrição *</label>
                            <textarea class="form-control" id="produtoDescricao" name="descricao" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="produtoImagem" class="form-label">Imagem do Produto</label>
                            <input type="file" class="form-control" id="produtoImagem" name="imagem" accept="image/*">
                            <div class="form-text">Formatos aceitos: JPG, PNG, GIF (máx. 5MB)</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="salvarProdutoBtn">
                            <i class="fas fa-save me-1"></i>Salvar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação para Eliminar -->
    <div class="modal fade" id="confirmarEliminacaoModal" tabindex="-1" aria-labelledby="confirmarEliminacaoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarEliminacaoModalLabel">Confirmar Eliminação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja eliminar este produto?</p>
                    <p><strong id="produtoNomeEliminacao"></strong></p>
                    <p class="text-muted">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmarEliminacaoBtn">
                        <i class="fas fa-trash me-1"></i>Eliminar
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

        // Carregar produtos ao inicializar a página
        document.addEventListener('DOMContentLoaded', function() {
            carregarProdutos();
        });

        // Função para carregar todos os produtos
        function carregarProdutos() {
            mostrarLoading(true);
            
            fetch('../api/produtos.php')
                .then(response => response.json())
                .then(data => {
                    mostrarLoading(false);
                    preencherTabelaProdutos(data);
                })
                .catch(error => {
                    mostrarLoading(false);
                    console.error('Erro ao carregar produtos:', error);
                    mostrarAlerta('Erro ao carregar produtos', 'danger');
                });
        }

        // Função para preencher a tabela de produtos
        function preencherTabelaProdutos(produtos) {
            const tbody = document.getElementById('produtosTableBody');
            tbody.innerHTML = '';

            if (produtos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">Nenhum produto encontrado</td></tr>';
                return;
            }

            produtos.forEach(produto => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${produto.id}</td>
                    <td>${produto.nome}</td>
                    <td>${produto.descricao.length > 50 ? produto.descricao.substring(0, 50) + '...' : produto.descricao}</td>
                    <td>€${parseFloat(produto.preco).toFixed(2)}</td>
                    <td>${new Date(produto.created_at).toLocaleDateString('pt-BR')}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editarProduto(${produto.id})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminacao(${produto.id}, '${produto.nome}')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Função para mostrar/ocultar loading
        function mostrarLoading(mostrar) {
            const loading = document.querySelector('.loading');
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
            document.getElementById('produtoModalLabel').textContent = 'Novo Produto';
            document.getElementById('produtoForm').reset();
            document.getElementById('produtoId').value = '';
        }

        // Função para editar produto
        function editarProduto(id) {
            modoEdicao = true;
            document.getElementById('produtoModalLabel').textContent = 'Editar Produto';
            
            fetch(`../api/produtos.php?id=${id}`)
                .then(response => response.json())
                .then(produto => {
                    document.getElementById('produtoId').value = produto.id;
                    document.getElementById('produtoNome').value = produto.nome;
                    document.getElementById('produtoDescricao').value = produto.descricao;
                    document.getElementById('produtoPreco').value = produto.preco;
                    
                    // Abrir modal
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

        // Event listener para o formulário de produto
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
                    
                    // Fechar modal
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

        // Event listener para confirmar eliminação
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
                    
                    // Fechar modal
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

        // Função para mostrar alertas
        function mostrarAlerta(mensagem, tipo) {
            const alertaDiv = document.createElement('div');
            alertaDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
            alertaDiv.innerHTML = `
                ${mensagem}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertaDiv, container.firstChild);
            
            // Remover alerta após 5 segundos
            setTimeout(() => {
                if (alertaDiv.parentNode) {
                    alertaDiv.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>

