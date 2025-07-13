<?php

namespace App\Console\Commands;

use App\Contracts\Services\ProductServiceInterface;
use Illuminate\Console\Command;

class SyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza produtos da API externa da Luvinco';

    /**
     * Execute the console command.
     */
    public function handle(ProductServiceInterface $productService): int
    {
        $this->info('Iniciando sincronização de produtos...');
        $this->info('ℹ️  Observação: A API externa atualiza preços apenas uma vez por dia à meia-noite.');
        
        try {
            $productService->syncProducts();
            $this->info('✅ Produtos sincronizados com sucesso!');
            $this->info('📊 Os preços foram atualizados conforme disponível na API externa.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao sincronizar produtos: ' . $e->getMessage());
            return 1;
        }
    }
} 