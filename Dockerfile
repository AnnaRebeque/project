# Usa a imagem oficial do PHP com o servidor Apache embutido
FROM php:8.2-apache

# Instala as extensões do banco de dados (necessário para o PDO funcionar)
RUN docker-php-ext-install pdo pdo_mysql

# Copia todos os arquivos do seu projeto para a pasta pública do servidor dentro do container
COPY . /var/www/html/

# Libera a porta 80 para podermos acessar pelo navegador
EXPOSE 80