<?php
        include 'includes/header.php';
        require_once 'conexao.php';

        $stmt_config = $pdo->query("SELECT * FROM configuracoes WHERE id = 1");
        $config = $stmt_config->fetch(PDO::FETCH_ASSOC);

        // Busca as fotos do carrossel
        $stmt_carrossel = $pdo->query("SELECT * FROM carrossel WHERE ativo = 1 ORDER BY id DESC");
        $slides = $stmt_carrossel->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <?php
 
        include 'includes/hero.php';
        include 'includes/galeria.php';
        include 'includes/produtos.php'; 
        include 'includes/sobre.php';
        include 'includes/contato.php';
    ?>
</main>

<?php
    include 'includes/footer.php';
?>