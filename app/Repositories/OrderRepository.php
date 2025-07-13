<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAll(): Collection
    {
        return Order::with('items.product')->get();
    }

    public function findById(string $orderId): ?Order
    {
        return Order::with('items.product')->where('order_id', $orderId)->first();
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order->fresh();
    }

    public function findByStatus(string $status): Collection
    {
        return Order::with('items.product')->where('status', $status)->get();
    }
} 