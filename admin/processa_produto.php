<?php
session_start();
require_once 'protecao.php';
require_once '../conexao.php';

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// --- ADICIONAR PRODUTO ---
if ($acao == 'adicionar') {
    $nome = $_POST['nome'];
    $preco = str_replace(',', '.', $_POST['preco']); // Troca vírgula por ponto para o banco
    $descricao = $_POST['descricao'];
    $nome_foto_capa = "";

    // 1. UPLOAD DA FOTO PRINCIPAL (CAPA)
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $nome_foto_capa = uniqid("capa_") . "." . $extensao;
        $pasta_destino = "../assets/images/produtos/";
        
        move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta_destino . $nome_foto_capa);
    }

    // 2. INSERE O PRODUTO NA TABELA 'produtos'
    $sql = "INSERT INTO produtos (nome, preco, descricao, imagem, ativo) VALUES (:nome, :preco, :descricao, :imagem, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':preco' => $preco,
        ':descricao' => $descricao,
        ':imagem' => $nome_foto_capa
    ]);

    // 3. PEGA O ID DO PRODUTO RECÉM-CRIADO
    $produto_id = $pdo->lastInsertId();

    // 4. UPLOAD DAS FOTOS EXTRAS (Para o Modal)
    if (isset($_FILES['fotos_extras']) && !empty($_FILES['fotos_extras']['name'][0])) {
        $total_fotos = count($_FILES['fotos_extras']['name']);
        
        for ($i = 0; $i < $total_fotos; $i++) {
            $nome_original = $_FILES['fotos_extras']['name'][$i];
            $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
            $nome_extra = uniqid("extra_") . "." . $extensao;
            $pasta_destino = "../assets/images/produtos/";
            
            if (move_uploaded_file($_FILES['fotos_extras']['tmp_name'][$i], $pasta_destino . $nome_extra)) {
                // Insere na tabela nova que criamos
                $stmt_extra = $pdo->prepare("INSERT INTO produto_imagens (produto_id, imagem) VALUES (?, ?)");
                $stmt_extra->execute([$produto_id, $nome_extra]);
            }
        }
    }

    header("Location: dashboard.php?msg=sucesso");
    exit;
}

// --- EDITAR PRODUTO ---
elseif ($acao == 'editar') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $descricao = $_POST['descricao'];

    // 1. ATUALIZA OS DADOS DE TEXTO
    $sql = "UPDATE produtos SET nome = :nome, preco = :preco, descricao = :descricao WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':preco' => $preco,
        ':descricao' => $descricao,
        ':id' => $id
    ]);

    // 2. ATUALIZA A IMAGEM DE CAPA (Apenas se o usuário enviou uma nova)
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $nome_foto_capa = uniqid("capa_") . "." . $extensao;
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], "../assets/images/produtos/" . $nome_foto_capa)) {
            $stmt_img = $pdo->prepare("UPDATE produtos SET imagem = :imagem WHERE id = :id");
            $stmt_img->execute([':imagem' => $nome_foto_capa, ':id' => $id]);
        }
    }

    // 3. ADICIONA NOVAS FOTOS EXTRAS
    if (isset($_FILES['fotos_extras']) && !empty($_FILES['fotos_extras']['name'][0])) {
        $total_fotos = count($_FILES['fotos_extras']['name']);
        
        for ($i = 0; $i < $total_fotos; $i++) {
            $nome_original = $_FILES['fotos_extras']['name'][$i];
            $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
            $nome_extra = uniqid("extra_") . "." . $extensao;
            
            if (move_uploaded_file($_FILES['fotos_extras']['tmp_name'][$i], "../assets/images/produtos/" . $nome_extra)) {
                $stmt_extra = $pdo->prepare("INSERT INTO produto_imagens (produto_id, imagem) VALUES (?, ?)");
                $stmt_extra->execute([$id, $nome_extra]);
            }
        }
    }

    header("Location: dashboard.php?msg=editado");
    exit;
}

// --- EXCLUIR PRODUTO ---
elseif ($acao == 'excluir') {
    $id = $_GET['id'];
    
    // Deleta o produto (graças ao 'ON DELETE CASCADE', as fotos extras sumirão do banco automaticamente)
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    header("Location: dashboard.php?msg=excluido");
    exit;
}
?>