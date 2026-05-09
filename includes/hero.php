    <!-- Hero Section -->
    <section id="inicio" class="py-20" style="
        background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('assets/images/<?php echo $config['hero_imagem']; ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: white;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-6"><?php echo htmlspecialchars($config['hero_titulo']); ?></h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                <?php echo htmlspecialchars($config['hero_subtitulo']); ?>
            </p>
            <button onclick="scrollToProducts()" class="btn-primary text-white px-8 py-4 rounded-full text-lg font-semibold shadow-lg">
                Ver nossas delicias
            </button>
        </div>
    </section>