<?php

namespace App\Jobs;

use App\Contracts\Services\ProductServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ProductServiceInterface $productService): void
    {
        try {
            Log::info('Iniciando sincronização de produtos via job');
            
            $productService->syncProducts();
            
            Log::info('Sincronização de produtos concluída com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro na sincronização de produtos via job: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job de sincronização de produtos falhou: ' . $exception->getMessage());
    }
} 