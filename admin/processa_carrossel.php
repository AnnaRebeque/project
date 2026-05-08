<?php
session_start();
require_once 'protecao.php';
require_once '../conexao.php'; // Verifique o caminho da conexão

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if ($acao == 'adicionar') {
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        // Gera um nome único para a imagem
        $novo_nome = uniqid("slide_") . "." . $extensao;
        $pasta_destino = "../assets/images/carrossel/";

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta_destino . $novo_nome)) {
            $stmt = $pdo->prepare("INSERT INTO carrossel (imagem) VALUES (:imagem)");
            $stmt->bindValue(':imagem', $novo_nome);
            $stmt->execute();
        }
    }
} 
elseif ($acao == 'excluir') {
    $id = $_GET['id'];
    $arquivo = $_GET['arquivo'];
    $caminho_arquivo = "../assets/images/carrossel/" . $arquivo;

    // Apaga o arquivo físico da pasta
    if (file_exists($caminho_arquivo)) {
        unlink($caminho_arquivo);
    }

    // Apaga do banco de dados
    $stmt = $pdo->prepare("DELETE FROM carrossel WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

header("Location: dashboard.php?msg=carrossel_atualizado");
exit;