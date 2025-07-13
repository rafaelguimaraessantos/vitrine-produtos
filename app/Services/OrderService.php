<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function createOrder(array $items): Order
    {
        $order = $this->orderRepository->create([
            'order_id' => Str::uuid(),
            'status' => 'Pendente',
            'message' => 'Pedido criado localmente',
            'total_amount' => 0,
        ]);

        $totalAmount = 0;

        foreach ($items as $item) {
            $product = \App\Models\Product::where('product_id', $item['product_id'])->first();
            
            if ($product) {
                $unitPrice = $product->price;
                $quantity = $item['quantity'];
                $totalPrice = $unitPrice * $quantity;
                $totalAmount += $totalPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }
        }

        $order->update(['total_amount' => $totalAmount]);

        return $order->fresh(['items.product']);
    }

    public function getAllOrders(): Collection
    {
        return $this->orderRepository->getAll();
    }

    public function getOrderById(string $orderId): ?Order
    {
        return $this->orderRepository->findById($orderId);
    }

    public function sendOrderToExternalApi(Order $order): array
    {
        try {
            $items = $order->items->map(function ($item) {
                return [
                    'product_id' => $item->product->product_id,
                    'quantity' => $item->quantity,
                ];
            })->toArray();

            $response = Http::withHeaders([
                'Authorization' => config('services.luvinco.token')
            ])->post(config('services.luvinco.url') . '/orders', [
                'items' => $items
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Atualiza o status do pedido
                $order->update([
                    'status' => $data['status'],
                    'message' => $data['message'],
                    'estimated_delivery' => $data['estimated_delivery'],
                ]);

                // Atualiza o estoque dos produtos
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $newStock = $product->stock - $item->quantity;
                    
                    // Garante que o estoque não fique negativo
                    $product->update([
                        'stock' => max(0, $newStock)
                    ]);
                }

                Log::info('Pedido enviado com sucesso e estoque atualizado', [
                    'order_id' => $order->order_id,
                    'external_order_id' => $data['order_id']
                ]);

                return [
                    'success' => true,
                    'data' => $data
                ];
            } else {
                Log::error('Erro ao enviar pedido', [
                    'order_id' => $order->order_id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => 'Erro temporário no processamento. Tente novamente em alguns segundos.'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar pedido', [
                'order_id' => $order->order_id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erro temporário no processamento. Tente novamente em alguns segundos.'
            ];
        }
    }
} 