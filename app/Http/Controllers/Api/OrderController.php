<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\OrderServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderServiceInterface $orderService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $orders = $this->orderService->getAllOrders();
            
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar pedidos'
            ], 500);
        }
    }

    public function show(string $orderId): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar pedido'
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $order = $this->orderService->createOrder($request->input('items'));
            
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Pedido criado com sucesso'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pedido'
            ], 500);
        }
    }

    public function sendToExternalApi(string $orderId): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido não encontrado'
                ], 404);
            }

            $result = $this->orderService->sendOrderToExternalApi($order);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data'],
                    'message' => 'Pedido enviado com sucesso'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar pedido'
            ], 500);
        }
    }
} 