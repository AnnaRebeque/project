<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CakeUpDoces</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        const numeroWhatsAppAdmin = "<?php echo $config['whatsapp'] ?? '5543998253698'; ?>";

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
    
    <link rel="stylesheet" href="assets/css/style.css"> 
    <style>
        body {
            box-sizing: border-box;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #e2b2f9ff 0%, #d9abffff 100%);
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: var(--cor-primaria); /* Usa a cor do seu banco de dados */
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            /* O filter: brightness(0.9) escurece a cor atual em 10%, 
               funciona perfeitamente seja a cor roxa, azul, verde... */
            filter: brightness(0.9); 
            transform: translateY(-1px);
        }
        
        .floating-cart {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        .hero-com-imagem {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('assets/images/<?php echo $config['hero_imagem']; ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        }
    
        .fundo-produtos-personalizado {
            background-color: <?php echo $config['cor_produtos']; ?> !important;
        }
        
        .fundo-contato {
            background-color: <?php echo $config['cor_contato']; ?> !important;
        }
        
        .rodape {
            background-color: <?php echo $config['cor_rodape']; ?> !important;
        }

        :root {
            /* O @ serve para não dar erro se a cor não estiver definida no banco */
            --cor-fundo: <?php echo @$config['cor_fundo'] ?: '#fffbeb'; ?>;
            --cor-primaria: <?php echo @$config['cor_primaria'] ?: '#f59e0b'; ?>;
            --cor-titulo: <?php echo @$config['cor_titulo'] ?: '#6b21a8'; ?>;
            --cor-texto: <?php echo @$config['cor_texto'] ?: '#4b5563'; ?>;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <a href="index.php">
                    <img src="assets/images/logo.png" alt="Logo da Cake UP Doces" class="h-10 w-auto">
                    </a>
                </div>
                
                <nav class="hidden md:flex space-x-8">
                    <a href="#inicio" class="text-gray-700 hover:text-amber-600 transition-colors">Início</a>
                    <a href="#produtos" class="text-gray-700 hover:text-amber-600 transition-colors">Produtos</a>
                    <a href="#sobre" class="text-gray-700 hover:text-amber-600 transition-colors">Sobre</a>
                    <a href="#contato" class="text-gray-700 hover:text-amber-600 transition-colors">Contato</a>
                </nav>
                
                <button onclick="toggleCart()" class="bg-tema-primaria text-white px-4 py-2 rounded-lg hover:brightness-90 transition-all">
                    🛒 Carrinho (<span id="cart-count">0</span>)
                </button>
            </div>
        </div>
    </header>