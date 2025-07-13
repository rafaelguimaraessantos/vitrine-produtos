<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\ProductServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductServiceInterface $productService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $products = $this->productService->getAllProducts();
            
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar produtos'
            ], 500);
        }
    }

    public function show(string $productId): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produto não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar produto'
            ], 500);
        }
    }

    public function sync(): JsonResponse
    {
        try {
            $this->productService->syncProducts();
            
            return response()->json([
                'success' => true,
                'message' => 'Produtos sincronizados com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao sincronizar produtos'
            ], 500);
        }
    }
} 