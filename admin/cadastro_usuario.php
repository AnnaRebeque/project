<?php
session_start();

// --- TRAVA DE SEGURANÇA ---
// Se não estiver logado, manda pro login na hora!
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}
// --------------------------
require_once '../conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        // Verifica se usuário já existe
        $check = $pdo->prepare("SELECT idlogin FROM login WHERE usuario = :usuario");
        $check->bindValue(':usuario', $usuario);
        $check->execute();

        if ($check->rowCount() > 0) {
            $mensagem = "Erro: O usuário '$usuario' já existe!";
            $tipo_msg = "erro"; // Para pintar de vermelho
        } else {
            // CRIA O HASH SEGURO
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO login (usuario, senha) VALUES (:usuario, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':usuario', $usuario);
            $stmt->bindValue(':senha', $senha_hash);
            $stmt->execute();

            $mensagem = "Usuário '$usuario' cadastrado com sucesso!";
            $tipo_msg = "sucesso"; // Para pintar de verde
        }

    } catch (PDOException $e) {
        $mensagem = "Erro no banco: " . $e->getMessage();
        $tipo_msg = "erro";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Usuário - Área Restrita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 flex items-center justify-center h-screen">
    <div class="w-full max-w-sm">
        <div class="mb-4 text-white text-sm">
            Logado como: <b><?php echo $_SESSION['usuario_nome'] ?? 'Admin'; ?></b>
        </div>

        <form method="POST" class="bg-white shadow-lg rounded px-8 pt-6 pb-8 mb-4">
            <h1 class="text-center text-xl font-bold mb-6 text-gray-800">Novo Acesso</h1>
            
            <?php if ($mensagem): ?>
                <div class="<?php echo ($tipo_msg == 'sucesso') ? 'bg-green-100 text-green-700 border-green-500' : 'bg-red-100 text-red-700 border-red-500'; ?> border-l-4 p-4 mb-4 text-sm">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nome do Usuário</label>
                <input name="usuario" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
                <input name="senha" type="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between gap-2">
                <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center w-1/2">
                    Voltar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded w-1/2">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</body>
</html>