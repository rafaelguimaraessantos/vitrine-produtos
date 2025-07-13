#!/bin/bash

echo "🕐 Configurando sincronização automática de produtos..."

# Verifica se está usando Docker
if [ -f "docker-compose.yml" ]; then
    echo "🐳 Detectado Docker Compose"
    
    echo ""
    echo "📋 Comandos para Docker:"
    echo ""
    echo "🔧 Testar sincronização manual:"
    echo "   docker-compose exec app php artisan products:sync"
    echo ""
    echo "🕐 Testar scheduler:"
    echo "   docker-compose exec app php artisan schedule:run"
    echo ""
    echo "📊 Ver logs:"
    echo "   docker-compose logs -f app"
    echo "   docker-compose exec app tail -f storage/logs/sync-products.log"
    echo ""
    echo "🔄 Reconstruir containers com supervisor:"
    echo "   docker-compose down"
    echo "   docker-compose up -d --build"
    echo ""
    echo "✅ O cron já está configurado no Dockerfile!"
    echo "   O scheduler rodará automaticamente a cada minuto."
    
else
    echo "💻 Configurando para ambiente local..."
    
    # Verifica se o cron está instalado
    if ! command -v crontab &> /dev/null; then
        echo "❌ Cron não está instalado. Instalando..."
        sudo apt-get update && sudo apt-get install -y cron
    fi

    # Cria o arquivo de cron temporário
    CRON_FILE=$(mktemp)

    # Adiciona o comando do Laravel Scheduler
    echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1" > $CRON_FILE

    # Instala o cron job
    crontab $CRON_FILE

    # Remove o arquivo temporário
    rm $CRON_FILE

    echo "✅ Cron job configurado com sucesso!"
    echo ""
    echo "📋 Cron jobs ativos:"
    crontab -l
fi

echo ""
echo "🎯 Opções de sincronização implementadas:"
echo "   1. 🕐 Laravel Scheduler (a cada hora)"
echo "   2. 🌐 Middleware automático (quando acessa a vitrine)"
echo "   3. ⚡ Jobs em background (com retry)"
echo ""
echo "📚 Para mais informações, consulte o README.md" 