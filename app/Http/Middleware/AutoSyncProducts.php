<?php

namespace App\Http\Middleware;

use App\Jobs\SyncProductsJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AutoSyncProducts
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se é a página da vitrine
        if ($request->routeIs('vitrine.index')) {
            $this->checkAndSyncProducts();
        }

        return $next($request);
    }

    /**
     * Verifica se precisa sincronizar produtos
     */
    private function checkAndSyncProducts(): void
    {
        $lastSyncKey = 'last_products_sync';
        $syncInterval = 86400; // 24 horas em segundos (uma vez por dia)

        $lastSync = Cache::get($lastSyncKey);
        $now = now();

        // Se nunca sincronizou ou passou do tempo limite (24 horas)
        if (!$lastSync || $now->diffInSeconds($lastSync) >= $syncInterval) {
            try {
                // Dispara o job de sincronização
                SyncProductsJob::dispatch();
                
                // Atualiza timestamp da última sincronização
                Cache::put($lastSyncKey, $now, now()->addDays(2)); // Cache por 2 dias
                
                \Log::info('Job de sincronização diária de produtos disparado');
            } catch (\Exception $e) {
                \Log::error('Erro ao disparar job de sincronização: ' . $e->getMessage());
            }
        }
    }
} 