<?php
// Atualizar quantidade de produto no carrinho
require 'auth.php';
session_start();

if(!isset($_SESSION["user"])){
    header("Location: ../views/login.php");
    exit();
}

require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['produtoId']) && isset($_POST['quantidade'])){
    $produto_id = intval($_POST['produtoId']);
    $quantidade = intval($_POST['quantidade']);
    $user_id = $_SESSION['user']['id'];
    
    if($quantidade > 0) {
        // Atualiza a quantidade se for maior que 0
        $sql = $con->prepare("UPDATE Carrinho SET quantidade = ? WHERE produtoId = ? AND userId = ?");
        $sql->bind_param("iii", $quantidade, $produto_id, $user_id);
        $sql->execute();
    } else {
        // Remove o item se quantidade for 0 ou menor
        $sql = $con->prepare("DELETE FROM Carrinho WHERE produtoId = ? AND userId = ?");
        $sql->bind_param("ii", $produto_id, $user_id);
        $sql->execute();
    }
}

header("Location: ../views/cart.php");
exit();
?>

