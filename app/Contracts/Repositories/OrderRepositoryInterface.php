<?php

namespace App\Contracts\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    /**
     * Busca todos os pedidos
     */
    public function getAll(): Collection;

    /**
     * Busca pedido por ID
     */
    public function findById(string $orderId): ?Order;

    /**
     * Cria um novo pedido
     */
    public function create(array $data): Order;

    /**
     * Atualiza um pedido
     */
    public function update(Order $order, array $data): Order;

    /**
     * Busca pedidos por status
     */
    public function findByStatus(string $status): Collection;
} 