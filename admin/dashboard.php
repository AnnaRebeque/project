<?php
session_start();
// Se não estiver logado, manda de volta para o login
require_once 'protecao.php';
require_once '../conexao.php'; // Usa '..' para voltar uma pasta e achar a conexão

// Lógica para Adicionar/Editar/Excluir vai aqui (veremos depois)

// Busca todos os produtos para listar
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca configurações atuais para preencher o formulário
$stmt_config = $pdo->query("SELECT * FROM configuracoes WHERE id = 1");
$config = $stmt_config->fetch(PDO::FETCH_ASSOC);

// Busca as imagens do carrossel
$stmt_carrossel = $pdo->query("SELECT * FROM carrossel ORDER BY id DESC");
$imagens_carrossel = $stmt_carrossel->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Confeitaria</title>
    <!-- 1. Cria as variáveis dinâmicas lendo do banco de dados -->
    <style>
        :root {
            --cor-fundo: <?php echo $config['cor_fundo'] ?? '#fffbeb'; ?>;
            --cor-primaria: <?php echo $config['cor_primaria'] ?? '#f59e0b'; ?>;
            --cor-titulo: <?php echo $config['cor_titulo'] ?? '#6b21a8'; ?>;
            --cor-texto: <?php echo $config['cor_texto'] ?? '#4b5563'; ?>;
        }
    </style>

    <!-- 2. Importa o Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- 3. Ensina o Tailwind a usar as suas variáveis -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'tema-fundo': 'var(--cor-fundo)',
                        'tema-primaria': 'var(--cor-primaria)',
                        'tema-titulo': 'var(--cor-titulo)',
                        'tema-texto': 'var(--cor-texto)',
                    }
                }
            }
        }
    </script>
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
                    <input type="text" name="nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-tema-titulo" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Preço (ex: 25.50)</label>
                    <input type="text" name="preco" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Descrição Curta (Vitrine)</label>
                <input type="text" name="descricao" class="w-full border rounded py-2 px-3" maxlength="100" placeholder="Ex: Saboroso e macio..." required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Descrição Detalhada (Modal)</label>
                <textarea name="descricao_detalhada" rows="4" class="w-full border rounded py-2 px-3" placeholder="Conte todos os ingredientes e detalhes aqui..."></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Imagem do Produto</label>
                <input type="file" name="imagem" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Fotos Extras (Galeria do Modal)</label>
                <input type="file" name="fotos_extras[]" multiple accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <p class="text-sm text-gray-500 mt-1">Você pode selecionar várias fotos segurando o botão Ctrl.</p>
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

    <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-bold mb-4">Configurações do Layout do Site</h2>
            <form action="processa_configuracoes.php" method="POST" enctype="multipart/form-data">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Título Principal (Hero)</label>
                        <input type="text" name="hero_titulo" value="<?php echo htmlspecialchars($config['hero_titulo']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-tema-titulo" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Subtítulo (Hero)</label>
                        <input type="text" name="hero_subtitulo" value="<?php echo htmlspecialchars($config['hero_subtitulo']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cor Fundo Produtos</label>
                        <input type="color" name="cor_produtos" value="<?php echo $config['cor_produtos']; ?>" class="w-full h-10 border rounded cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cor Fundo Contato</label>
                        <input type="color" name="cor_contato" value="<?php echo $config['cor_contato']; ?>" class="w-full h-10 border rounded cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cor do Rodapé</label>
                        <input type="color" name="cor_rodape" value="<?php echo $config['cor_rodape']; ?>" class="w-full h-10 border rounded cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cor dos Títulos (Letras Maiores)</label>
                        <input type="color" name="cor_titulo" value="<?php echo @$config['cor_titulo'] ?: '#6b21a8'; ?>" class="w-full h-10 border rounded cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cor dos Textos (Descrições)</label>
                        <input type="color" name="cor_texto" value="<?php echo @$config['cor_texto'] ?: '#4b5563'; ?>" class="w-full h-10 border rounded cursor-pointer">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cor Primária (Botões e Destaques)</label>
                        <input type="color" name="cor_primaria" value="<?php echo @$config['cor_primaria'] ?: '#f59e0b'; ?>" class="w-full h-10 border rounded cursor-pointer">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Imagem de Fundo (Deixe em branco para manter a atual)</label>
                    <input type="file" name="hero_imagem" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    <p class="text-sm text-gray-500 mt-1">Imagem atual: <?php echo $config['hero_imagem']; ?></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Número do WhatsApp (Apenas números, com DDI e DDD. Ex: 5543998253698)</label>
                    <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($config['whatsapp'] ?? ''); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
                
                <button type="submit" class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded">
                    Salvar Layout
                </button>
            </form>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Imagens do Carrossel</h2>
        </div>

        <form action="processa_carrossel.php" method="POST" enctype="multipart/form-data" class="mb-6 flex gap-4 items-end">
            <input type="hidden" name="acao" value="adicionar">
            <div class="flex-grow">
                <label class="block text-gray-700 text-sm font-bold mb-2">Adicionar Nova Imagem</label>
                <input type="file" name="imagem" accept="image/*" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded h-10">
                Fazer Upload
            </button>
        </form>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($imagens_carrossel as $img): ?>
                <div class="border rounded p-2 relative group">
                    <img src="../assets/images/carrossel/<?php echo $img['imagem']; ?>" class="w-full h-32 object-cover rounded">
                    
                    <a href="processa_carrossel.php?acao=excluir&id=<?php echo $img['id']; ?>&arquivo=<?php echo $img['imagem']; ?>" 
                    onclick="return confirm('Excluir esta imagem do carrossel?')"
                    class="absolute top-4 right-4 bg-red-500 text-white p-2 rounded-full shadow-lg hover:bg-red-700">
                        🗑️
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>