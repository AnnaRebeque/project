<section id="galeria" class="py-16 bg-amber-50">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-tema-titulo mb-12">Nossa galeria</h2>
            
            <div class="swiper meu-carrossel">
                <div class="swiper-wrapper">
                    
                    <?php foreach ($slides as $slide): ?>
                        <div class="swiper-slide">
                            <img src="assets/images/carrossel/<?php echo $slide['imagem']; ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if(empty($slides)): ?>
                        <div class="swiper-slide">
                            <img src="assets/images/fundo_hero.jpeg" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                </div>
    
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        

    </div>
</section>