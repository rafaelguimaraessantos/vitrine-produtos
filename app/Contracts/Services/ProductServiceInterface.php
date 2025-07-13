<?php

namespace App\Contracts\Services;

use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    /**
     * Busca todos os produtos da API externa
     */
    public function getAllProducts(): Collection;

    /**
     * Busca um produto específico por ID
     */
    public function getProductById(string $productId): ?array;

    /**
     * Sincroniza produtos da API externa com o banco local
     */
    public function syncProducts(): void;
} 