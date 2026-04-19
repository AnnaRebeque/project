<?php
// PROJETO/conexao.php

$host = 'host';
$dbname = 'nome_do_banco'; // O nome do banco de dados que você criou
$user = 'usuario'; // Usuário padrão
$pass = ''; // Senha padrão 
 
try {
    // Cria uma nova conexão usando PDO 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Se a conexão falhar, exibe uma mensagem de erro e para a execução
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>