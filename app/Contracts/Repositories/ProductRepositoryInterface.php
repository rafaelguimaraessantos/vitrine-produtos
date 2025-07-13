<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * Busca todos os produtos
     */
    public function getAll(): Collection;

    /**
     * Busca produto por ID
     */
    public function findById(string $productId): ?Product;

    /**
     * Cria ou atualiza um produto
     */
    public function createOrUpdate(array $data): Product;

    /**
     * Busca produtos por categoria
     */
    public function findByCategory(string $category): Collection;

    /**
     * Busca produtos por marca
     */
    public function findByBrand(string $brand): Collection;
} 