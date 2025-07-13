<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function getProductById(string $productId): ?array
    {
        $product = $this->productRepository->findById($productId);
        return $product ? $product->toArray() : null;
    }

    public function syncProducts(): void
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.luvinco.token')
            ])->get(config('services.luvinco.url') . '/products');

            if ($response->successful()) {
                $products = $response->json();
                
                foreach ($products as $productData) {
                    $this->productRepository->createOrUpdate($productData);
                }
                
                Log::info('Produtos sincronizados com sucesso', ['count' => count($products)]);
            } else {
                Log::error('Erro ao sincronizar produtos', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao sincronizar produtos', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
} 