<?php
// Verifica se o utilizador está autenticado
require 'auth.php';
session_start();

if(!isset($_SESSION["user"])){
    // Se o utilizador não estiver autenticado, redireciona para a página de login
    header("Location: ../views/login.php");
    exit();
}

// Recebe o post com o produto_id e quantidade
require 'db.php'; // Inclui o arquivo de conexão com a base de dados

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['produto_id']) && isset($_POST['quantidade'])){
    // Verifica se o produto já está no carrinho e se sim, atualiza a quantidade
    $produto_id = intval($_POST['produto_id']); // Obtém o ID do produto do POST
    $quantidade = intval($_POST['quantidade']); // Obtém a quantidade do POST
    $sql = $con->prepare("SELECT quantidade FROM Carrinho WHERE produtoId = ? AND userId = ?");
    $sql->bind_param("ii", $produto_id, $_SESSION['user']['id']); // Associa os parâmetros
    $sql->execute(); // Executa o query
    $result = $sql->get_result(); // Obtém o resultado da query
    
    if ($result->num_rows > 0) {
        // Produto já existe no carrinho, atualiza a quantidade
        $row = $result->fetch_assoc(); // Busca os dados do produto
        $nova_quantidade = $row['quantidade'] + $quantidade; // Soma a quantidade
        $update_sql = $con->prepare("UPDATE Carrinho SET quantidade = ? WHERE produtoId = ? AND userId = ?");
        $update_sql->bind_param("iii", $nova_quantidade, $produto_id, $_SESSION['user']['id']);
        $update_sql->execute(); // Executa o update
    } else {
        // Produto não existe no carrinho, adiciona novo item
        $insert_sql = $con->prepare("INSERT INTO Carrinho (produtoId, userId, quantidade) VALUES (?, ?, ?)");
        $insert_sql->bind_param("iii", $produto_id, $_SESSION['user']['id'], $quantidade);
        $insert_sql->execute(); // Executa o insert
    }
    
    // Redireciona para a página inicial após adicionar
    header("Location: ../index.php");
    exit();
} else {
    // Se não, redirecionar para a página inicial
    header("Location: ../index.php");
    exit();
}
?>

