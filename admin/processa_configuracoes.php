<?php
session_start();
require_once 'protecao.php';
require_once '../conexao.php'; // Verifique se o caminho da conexão está correto

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hero_titulo = $_POST['hero_titulo'];
    $hero_subtitulo = $_POST['hero_subtitulo'];
    $cor_produtos = $_POST['cor_produtos'];
    $cor_contato = $_POST['cor_contato'];
    $cor_rodape = $_POST['cor_rodape'];

    // Lógica para upload de nova imagem (caso enviada)
    $nova_imagem_nome = null;
    if (isset($_FILES['hero_imagem']) && $_FILES['hero_imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['hero_imagem']['name'], PATHINFO_EXTENSION));
        // Gera um nome único para não sobrescrever imagens antigas por acidente
        $nova_imagem_nome = uniqid("hero_") . "." . $extensao;
        
        // Caminho de destino (Ajuste a pasta conforme a estrutura do seu projeto)
        $caminho_destino = "../assets/images/" . $nova_imagem_nome;
        
        if (!move_uploaded_file($_FILES['hero_imagem']['tmp_name'], $caminho_destino)) {
            die("Erro ao salvar a imagem.");
        }
    }

    // Prepara a query de atualização
    if ($nova_imagem_nome) {
        // Se enviou imagem, atualiza o campo de imagem também
        $sql = "UPDATE configuracoes SET 
                hero_titulo = :titulo, 
                hero_subtitulo = :subtitulo, 
                hero_imagem = :imagem, 
                cor_produtos = :cor_produtos, 
                cor_contato = :cor_contato, 
                cor_rodape = :cor_rodape 
                WHERE id = 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':imagem', $nova_imagem_nome);
    } else {
        // Se não enviou imagem, atualiza apenas os textos e cores
        $sql = "UPDATE configuracoes SET 
                hero_titulo = :titulo, 
                hero_subtitulo = :subtitulo, 
                cor_produtos = :cor_produtos, 
                cor_contato = :cor_contato, 
                cor_rodape = :cor_rodape 
                WHERE id = 1";
                
        $stmt = $pdo->prepare($sql);
    }

    // Executa a atualização
    $stmt->bindValue(':titulo', $hero_titulo);
    $stmt->bindValue(':subtitulo', $hero_subtitulo);
    $stmt->bindValue(':cor_produtos', $cor_produtos);
    $stmt->bindValue(':cor_contato', $cor_contato);
    $stmt->bindValue(':cor_rodape', $cor_rodape);
    
    $stmt->execute();

    // Redireciona de volta ao painel
    header("Location: dashboard.php?msg=sucesso");
    exit;
}