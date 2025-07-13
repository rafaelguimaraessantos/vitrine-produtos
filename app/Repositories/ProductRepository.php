<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(): Collection
    {
        return Product::all();
    }

    public function findById(string $productId): ?Product
    {
        return Product::where('product_id', $productId)->first();
    }

    public function createOrUpdate(array $data): Product
    {
        return Product::updateOrCreate(
            ['product_id' => $data['product_id']],
            $data
        );
    }

    public function findByCategory(string $category): Collection
    {
        return Product::where('category', $category)->get();
    }

    public function findByBrand(string $brand): Collection
    {
        return Product::where('brand', $brand)->get();
    }
} 