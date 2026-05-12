 <!-- Contact Section -->
    <section id="contato" class="fundo-contato py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-tema-titulo mb-12">Não realizamos entregas</h2>
            <h2 class="text-4xl font-bold text-center text-tema-texto mb-12">Entre em contato</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <div>
                    <h3 class="text-2xl font-bold text-tema-titulo mb-6">Informações de contato</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">📍</div>
                            <div>
                                <p class="font-semibold">Endereço</p>
                                <p class="text-amber-600">Londrina-Pr</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">📞</div>
                            <div>
                                <p class="font-semibold">Telefone</p>
                                <p class="text-amber-600">(43) 99825-3698</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">🕒</div>
                            <div>
                                <p class="font-semibold">Horário de Funcionamento</p>
                                <p class="text-amber-600">Seg-Sex: 8h às 18h | Sáb: 8h às 16h</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-2xl font-bold text-tema-titulo mb-6">Faça seu Pedido</h3>
                    <form onsubmit="enviarContato(event)" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nome Completo</label>
                            <input type="text" id="nome_contato" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tema-primaria">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Mensagem</label>
                            <textarea id="mensagem_contato" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tema-primaria" placeholder="Descreva seu pedido ou dúvida..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">Enviar Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </section>