<?php
session_start();
$erro = '';

require_once '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        // 2. Prepara a consulta para buscar o usuário
        // Usamos :usuario como um "placeholder" para segurança
        $stmt = $pdo->prepare("SELECT idlogin, usuario, senha FROM login WHERE usuario = :usuario");
        $stmt->bindValue(':usuario', $_POST['usuario']);
        $stmt->execute();
        
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Verificação
        // Se encontrou o usuário E a senha bate
        if ($user_data && password_verify($_POST['senha'], $user_data['senha'])) {
            
            $_SESSION['logado'] = true;
            $_SESSION['usuario_id'] = $user_data['idlogin']; // Útil para usar depois
            $_SESSION['usuario_nome'] = $user_data['usuario'];
            
            header("Location: dashboard.php");
            exit;
            
        } else {
            $erro = 'Usuário ou senha inválidos!';
        }

    } catch (PDOException $e) {
        // Erro de conexão com o banco
        $erro = "Erro no banco de dados: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-xs">
        <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h1 class="text-center text-2xl font-bold mb-6">Login</h1>
            <?php if ($erro): ?>
                <p class="text-red-500 text-xs italic mb-4"><?php echo $erro; ?></p>
            <?php endif; ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Usuário</label>
                <input name="usuario" class="shadow appearance-none border rounded w-full ...">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
                <input name="senha" type="password" class="shadow appearance-none border rounded w-full ...">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</body>
</html>