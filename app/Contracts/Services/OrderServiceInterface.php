<?php

namespace App\Contracts\Services;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderServiceInterface
{
    /**
     * Cria um novo pedido
     */
    public function createOrder(array $items): Order;

    /**
     * Busca todos os pedidos
     */
    public function getAllOrders(): Collection;

    /**
     * Busca um pedido específico por ID
     */
    public function getOrderById(string $orderId): ?Order;

    /**
     * Envia pedido para a API externa
     */
    public function sendOrderToExternalApi(Order $order): array;
} 