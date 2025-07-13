<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'message',
        'estimated_delivery',
        'total_amount',
    ];

    protected $casts = [
        'estimated_delivery' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'R$ ' . number_format($this->total_amount, 2, ',', '.');
    }

    public function getFormattedEstimatedDeliveryAttribute(): string
    {
        return $this->estimated_delivery?->format('d/m/Y H:i') ?? 'Não informado';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'Processando';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Concluído';
    }
} 