<?php
session_start();
// Se não estiver logado, manda de volta para o login
require_once 'protecao.php';
require_once '../conexao.php'; // Usa '..' para voltar uma pasta e achar a conexão

// Lógica para Adicionar/Editar/Excluir vai aqui (veremos depois)

// Busca todos os produtos para listar
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Confeitaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold">Gerenciar Produtos</h1>
            <a href="logout.php" class="bg-red-500 text-white py-2 px-4 rounded">Sair</a>
            <a href="cadastro_usuario.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">+ Cadastrar novo usuário</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
    <h2 class="text-2xl font-bold mb-4">Adicionar Novo Produto</h2>
    <form action="processa_produto.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="adicionar">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Nome do Produto</label>
                <input type="text" name="nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Preço (ex: 25.50)</label>
                <input type="text" name="preco" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
            <textarea name="descricao" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Imagem do Produto</label>
            <input type="file" name="imagem" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
        </div>
        
        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Adicionar Produto
        </button>
    </form>
</div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Produtos Cadastrados</h2>
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Nome</th>
                        <th class="px-4 py-2">Preço</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td class="border px-4 py-2">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td class="border px-4 py-2">
                                <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="text-blue-500">Editar</a>
                                <a href="processa_produto.php?acao=excluir&id=<?php echo $produto['id']; ?>" class="text-red-500 ml-4" onclick="return confirm('Tem certeza?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>