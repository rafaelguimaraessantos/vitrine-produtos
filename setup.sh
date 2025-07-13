#!/bin/bash

echo "🚀 Iniciando setup da Vitrine de Produtos..."

# Verifica se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

# Verifica se o Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

echo "✅ Docker e Docker Compose encontrados"

# Copia arquivo de ambiente
if [ ! -f .env ]; then
    echo "📝 Copiando arquivo de ambiente..."
    cp env.example .env
    echo "✅ Arquivo .env criado"
else
    echo "✅ Arquivo .env já existe"
fi

# Constrói e inicia os containers
echo "🐳 Construindo e iniciando containers..."
docker-compose up -d --build

# Aguarda os containers estarem prontos
echo "⏳ Aguardando containers estarem prontos..."
sleep 30

# Instala dependências do Composer
echo "📦 Instalando dependências do Composer..."
docker-compose exec app composer install --no-interaction --ignore-platform-reqs

# Gera chave da aplicação
echo "🔑 Gerando chave da aplicação..."
docker-compose exec app php artisan key:generate

# Executa migrations
echo "🗄️ Executando migrations..."
docker-compose exec app php artisan migrate

# Sincroniza produtos
echo "🔄 Sincronizando produtos da API..."
docker-compose exec app php artisan products:sync

echo ""
echo "🎉 Setup concluído com sucesso!"
echo ""
echo "📱 Acesse a aplicação em: http://localhost:8000"
echo "🔧 Para ver logs: docker-compose logs app"
echo "🛑 Para parar: docker-compose down"
echo ""
echo "📚 Consulte o README.md para mais informações" 