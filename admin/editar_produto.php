<?php
session_start();
require_once 'protecao.php';
require_once '../conexao.php';

// Pega o ID do produto que veio pela URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Busca os dados atuais do produto
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Produto - Cake UP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="container mx-auto max-w-2xl">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Editar Produto</h2>
                <a href="dashboard.php" class="text-blue-500 hover:text-blue-700">← Voltar</a>
            </div>

            <form action="processa_produto.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nome do Produto</label>
                        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" class="w-full border rounded py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Preço</label>
                        <input type="text" name="preco" value="<?php echo $produto['preco']; ?>" class="w-full border rounded py-2 px-3" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Descrição</label>
                    <textarea name="descricao" rows="4" class="w-full border rounded py-2 px-3" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                </div>

                <div class="mb-4 border-t pt-4">
                    <label class="block text-gray-700 font-bold mb-2">Atualizar Imagem de Capa (Deixe em branco para manter a atual)</label>
                    <input type="file" name="imagem" accept="image/*" class="w-full border rounded py-2 px-3">
                    <p class="text-sm text-gray-500 mt-2">Imagem atual: <img src="../assets/images/produtos/<?php echo $produto['imagem']; ?>" class="h-16 mt-1 rounded shadow"></p>
                </div>

                <div class="mb-6 border-t pt-4">
                    <label class="block text-gray-700 font-bold mb-2">Adicionar MAIS Fotos Extras (Galeria)</label>
                    <input type="file" name="fotos_extras[]" multiple accept="image/*" class="w-full border rounded py-2 px-3">
                    <p class="text-sm text-gray-500 mt-1">Selecione fotos novas para adicionar à galeria deste produto.</p>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded w-full">
                    Salvar Alterações
                </button>
            </form>
        </div>
    </div>
</body>
</html>