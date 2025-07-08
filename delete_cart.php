<?php
// Remover produto do carrinho
require 'auth.php';
session_start();

if(!isset($_SESSION["user"])){
    header("Location: ../views/login.php");
    exit();
}

require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['produtoId'])){
    $produto_id = intval($_POST['produtoId']);
    $user_id = $_SESSION['user']['id'];
    
    // Remove o produto do carrinho
    $sql = $con->prepare("DELETE FROM Carrinho WHERE produtoId = ? AND userId = ?");
    $sql->bind_param("ii", $produto_id, $user_id);
    $sql->execute();
}

header("Location: ../views/cart.php");
exit();
?>

