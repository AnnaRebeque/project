<footer class="rodape py-8">
    <div class="container mx-auto px-4 text-center">
        <div class="flex items-center justify-center space-x-2 mb-4">
            <div class="text-3xl">🧁</div>
            <h3 class="text-tema-titulo font-bold text-white-600">Cake Up Doces</h3>
        </div>
        <p class="text-tema-texto mb-4">Criando momentos doces</p>
        <div class="flex justify-center items-center space-x-6">
            <img src="assets/images/insta.jpg" alt="Logo insta" class="h-5 w-auto">
            <a href="https://www.instagram.com/cakeupdoces/" target="_blank" class="text-white-600 hover:text-white transition-colors">Instagram</a>
            
            <img src="assets/images/whats.jpg" alt="Logo whats" class="h-5 w-auto">
            <a href="https://wa.me/<?php echo $config['whatsapp'] ?? '5543998253698'; ?>" target="_blank" class="text-white-600 hover:text-white transition-colors">WhatsApp</a>
            
            <a href="admin/index.php" class="bg-tema-primaria hover:brightness-90 transition-all text-white py-2 px-2 rounded">Área restrita</a>
        </div>
    </div>
</footer>

<div class="floating-cart">
    <button onclick="toggleCart()" class="bg-tema-primaria text-white p-4 rounded-full shadow-lg hover:brightness-90 transition-all relative">        🛒
        <span id="floating-cart-count" class="cart-badge">0</span>
    </button>
</div>

<div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-md w-full max-h-96 overflow-hidden">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">Seu Carrinho</h3>
                    <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700">✕</button>
                </div>
            </div>

            <div id="cart-items" class="p-6 max-h-64 overflow-y-auto">
                <p class="text-gray-500 text-center">Seu carrinho está vazio</p>
            </div>

            <div class="p-6 border-t">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-bold">Total:</span>
                    <span id="cart-total" class="font-bold text-xl text-amber-600">R$ 0,00</span>
                </div>
                <button onclick="checkout()" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                    Finalizar Pedido
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modal-produto" class="fixed inset-0 bg-black bg-opacity-80 z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row relative max-h-[90vh]">
        
        <button onclick="fecharModalProduto()" class="absolute top-2 right-2 z-10 bg-white rounded-full w-8 h-8 flex items-center justify-center text-red-600 font-bold hover:bg-gray-200 shadow-md">✕</button>
        
        <div class="w-full md:w-1/2 bg-gray-100 relative">
             <div class="swiper swiper-modal w-full h-64 md:h-full min-h-[300px]">
                  <div class="swiper-wrapper" id="wrapper-fotos-modal">
                      </div>
                  <div class="swiper-button-next" style="color: #f59e0b;"></div>
                  <div class="swiper-button-prev" style="color: #f59e0b;"></div>
                  <div class="swiper-pagination"></div>
             </div>
        </div>
        
        <div class="w-full md:w-1/2 p-8 flex flex-col overflow-y-auto">
             <div>
                 <h3 id="modal-nome" class="text-3xl font-bold text-tema-titulo mb-4">Nome do Produto</h3>
                 <p id="modal-preco" class="text-3xl font-bold text-amber-600 mb-6">R$ 0,00</p>
                 
                 <h4 class="font-bold text-tema-texto mb-2">Sobre este produto:</h4>
                 <p id="modal-descricao" class="text-gray-600 leading-relaxed">Descrição vai aqui...</p>
             </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // --- LÓGICA DO CARROSSEL (SWIPER) ---
    const swiper = new Swiper('.meu-carrossel', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 30,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });

    // --- LÓGICA DO CARRINHO DE COMPRAS ---
    let cart = [];
    let cartTotal = 0;

    function incrementQuantity(index) {
        const input = document.getElementById('quantity-' + index);
        let currentValue = parseInt(input.value);
        input.value = currentValue + 1;
    }

    function decrementQuantity(index) {
        const input = document.getElementById('quantity-' + index);
        let currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }

    // Adicionamos 'quantityOverride' como um parâmetro opcional (valor padrão null)
    function addToCart(index, name, price, quantityOverride = null) {
        const quantityInput = document.getElementById('quantity-' + index);
        
        // Se passarmos a quantidade (pelo modal), usamos ela. 
        // Se não, pegamos o valor do input da vitrina.
        const quantity = quantityOverride ? parseInt(quantityOverride) : parseInt(quantityInput.value);

        if (isNaN(quantity) || quantity <= 0) {
            alert("A quantidade deve ser pelo menos 1.");
            return;
        }

        const existingItem = cart.find(item => item.name === name);

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({
                name,
                price,
                quantity: quantity
            });
        }

        updateCartDisplay();
        showNotification(`${quantity}x ${name} adicionado(s) ao carrinho!`);
        
        // Reseta o input da vitrina para 1
        quantityInput.value = 1;
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartDisplay();
    }

    function updateCartDisplay() {
        const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        document.getElementById('cart-count').textContent = cartCount;
        document.getElementById('floating-cart-count').textContent = cartCount;
        document.getElementById('cart-total').textContent = `R$ ${cartTotal.toFixed(2).replace('.', ',')}`;

        const cartItems = document.getElementById('cart-items');

        if (cart.length === 0) {
            cartItems.innerHTML = '<p class="text-gray-500 text-center">Seu carrinho está vazio</p>';
        } else {
            cartItems.innerHTML = cart.map((item, index) => `
                <div class="flex items-center justify-between py-2 border-b">
                    <div>
                        <p class="font-semibold">${item.name}</p>
                        <p class="text-sm text-gray-600">Qtd: ${item.quantity} | R$ ${item.price.toFixed(2).replace('.', ',')}</p>
                    </div>
                    <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700">🗑️</button>
                </div>
            `).join('');
        }
    }

    function toggleCart() {
        const modal = document.getElementById('cart-modal');
        modal.classList.toggle('hidden');
    }

    function checkout() {
        if (cart.length === 0) {
            alert('Seu carrinho está vazio!');
            return;
        }

        const orderSummary = cart.map(item =>
            `${item.quantity}x ${item.name} - R$ ${(item.price * item.quantity).toFixed(2).replace('.', ',')}`
        ).join('\n');

        const message = `Olá, Cake UP! Gostaria de fazer o seguinte pedido:\n\n${orderSummary}\n\nTotal: R$ ${cartTotal.toFixed(2).replace('.', ',')}\n\nObrigado!`;
        const whatsappUrl = `https://wa.me/${numeroWhatsAppAdmin}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');

        cart = [];
        updateCartDisplay();
        toggleCart();
    }

    function enviarContato(event) {
        event.preventDefault(); // Impede a página de recarregar
        
        // Pega os valores digitados (ajuste os IDs para os que você usou no seu HTML)
        const nome = document.getElementById('nome_contato').value;
        const mensagem = document.getElementById('mensagem_contato').value;
        
        // Monta o texto bonitinho
        const texto = `Olá! Meu nome é ${nome}.\n\nVim pelo site e gostaria de saber: ${mensagem}`;
        
        // Cria o link do WhatsApp com o número do painel admin
        const whatsappUrl = `https://wa.me/${numeroWhatsAppAdmin}?text=${encodeURIComponent(texto)}`;
        
        // Abre o WhatsApp
        window.open(whatsappUrl, '_blank');
        
        // Limpa o formulário depois de enviar
        event.target.reset();
    }

    function scrollToProducts() {
        document.getElementById('produtos').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function submitOrder(event) {
        event.preventDefault();
        showNotification('Pedido enviado com sucesso! Entraremos em contato em breve.');
        event.target.reset();
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Initialize cart display on page load
    updateCartDisplay();

    // Pega as fotos extras que trouxemos do PHP
    const fotosExtras = <?php echo $json_fotos_extras; ?>;
    let swiperModal; 

    function abrirModalProduto(elemento_clicado) {
        // 1. Lê os dados escondidos no HTML
        const idProduto = elemento_clicado.getAttribute('data-id');
        const nome = elemento_clicado.getAttribute('data-nome');
        const preco = parseFloat(elemento_clicado.getAttribute('data-preco'));
        const fotoCapa = elemento_clicado.getAttribute('data-foto');
        const descricaoDetalhada = elemento_clicado.getAttribute('data-desc-detalhada');

        // 2. Preenche os textos
        document.getElementById('modal-nome').textContent = nome;
        
        // Usamos innerHTML para as quebras de linha do Admin (Enters) aparecerem aqui
        document.getElementById('modal-descricao').innerHTML = descricaoDetalhada.replace(/\n/g, '<br>');
        
        document.getElementById('modal-preco').textContent = 'R$ ' + preco.toFixed(2).replace('.', ',');

        // 3. Monta o carrossel de fotos (A lógica continua igualzinha)
        const wrapper = document.getElementById('wrapper-fotos-modal');
        let htmlFotos = `<div class="swiper-slide"><img src="assets/images/produtos/${fotoCapa}" class="w-full h-full object-cover"></div>`;

        if (fotosExtras && fotosExtras[idProduto]) {
            fotosExtras[idProduto].forEach(foto => {
                htmlFotos += `<div class="swiper-slide"><img src="assets/images/produtos/${foto}" class="w-full h-full object-cover"></div>`;
            });
        }
        
        wrapper.innerHTML = htmlFotos;
        document.getElementById('modal-produto').classList.remove('hidden');

        if (swiperModal) {
            swiperModal.destroy(); 
        }
        swiperModal = new Swiper('.swiper-modal', {
            loop: true,
            pagination: { el: '.swiper-modal .swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-modal .swiper-button-next', prevEl: '.swiper-modal .swiper-button-prev' }
        });
    }

    function fecharModalProduto() {
        document.getElementById('modal-produto').classList.add('hidden');
    }
</script>

</body>
</html>