  <!-- Products Section -->
<?php
// Inclui o arquivo de conexão
require_once __DIR__ . '/../conexao.php';

// Prepara e executa a consulta para buscar todos os produtos que estão ativos
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE ativo = 1 ORDER BY id DESC");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt_extras = $pdo->query("SELECT * FROM produto_imagens");
$todas_fotos_extras = $stmt_extras->fetchAll(PDO::FETCH_ASSOC);

// Agrupa as fotos pelo ID do produto para facilitar a vida do JavaScript
$fotos_por_produto = [];
foreach ($todas_fotos_extras as $foto) {
    $fotos_por_produto[$foto['produto_id']][] = $foto['imagem'];
}
// Converte para JSON (a linguagem que o JavaScript entende)
$json_fotos_extras = json_encode($fotos_por_produto);
?>

<section id="produtos" class="fundo-produtos-personalizado py-16">
   <div class="container mx-auto px-2 max-w-4xl"> 
      <h2 class="text-4xl font-bold text-center text-purple-800 mb-12">Nossos Produtos</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

         <?php foreach ($produtos as $index => $produto): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover group">
               
                <div class="aspect-[4/3] overflow-hidden cursor-pointer relative" 
                    onclick="abrirModalProduto(this)"
                    data-id="<?php echo $produto['id']; ?>"
                    data-nome="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES); ?>"
                    data-preco="<?php echo $produto['preco']; ?>"
                    data-foto="<?php echo htmlspecialchars($produto['imagem'], ENT_QUOTES); ?>"
                    data-desc-detalhada="<?php echo htmlspecialchars($produto['descricao_detalhada'] ?? '', ENT_QUOTES); ?>">
                    
                    <img src="assets/images/produtos/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                        
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                        <span class="text-white opacity-0 group-hover:opacity-100 font-bold drop-shadow-md">Ver Detalhes 🔍</span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-amber-600">
                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </span>

                        <div class="flex items-center">
                            <button onclick="decrementQuantity(<?php echo $index; ?>)" 
                                    class="bg-amber-200 text-amber-800 hover:bg-amber-300 w-8 h-8 rounded-l-lg font-bold transition-colors">
                                -
                            </button>
                            <input id="quantity-<?php echo $index; ?>" type="number" value="1" min="1" 
                                   class="w-10 h-8 text-center border-y border-amber-300 font-semibold text-gray-700">
                            <button onclick="incrementQuantity(<?php echo $index; ?>)" 
                                    class="bg-amber-200 text-amber-800 hover:bg-amber-300 w-8 h-8 rounded-r-lg font-bold transition-colors">
                                +
                            </button>
                        </div>
                    </div>

                    <button onclick="addToCart(<?php echo $index; ?>, '<?php echo htmlspecialchars($produto['nome']); ?>', <?php echo $produto['preco']; ?>)" 
                            class="w-full mt-4 btn-primary text-white py-2 rounded-lg font-semibold">
                        Adicionar ao Carrinho
                    </button>
                </div>
            </div>
         <?php endforeach; ?>

      </div>
   </div>
</section>