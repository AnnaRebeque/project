<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. VERIFICA SE ESTÁ LOGADO
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}

// 2. CONFIGURA O TEMPO DE EXPIRAÇÃO (em segundos)
// Exemplo: 600 segundos = 10 minutos.
// Se quiser muito rigoroso, coloque 300 (5 minutos).
$tempo_limite = 300; 

// 3. VERIFICA INATIVIDADE
if (isset($_SESSION['ultimo_acesso'])) {
    $tempo_transcorrido = time() - $_SESSION['ultimo_acesso'];

    // Se passou do tempo limite, destrói tudo e chuta pro login
    if ($tempo_transcorrido > $tempo_limite) {
        session_unset();
        session_destroy();
        header("Location: index.php?erro=sessao_expirada");
        exit;
    }
}

// 4. ATUALIZA O TEMPO DO ÚLTIMO ACESSO
// Toda vez que ele carregar uma página protegida, o cronômetro zera.
$_SESSION['ultimo_acesso'] = time();
?>