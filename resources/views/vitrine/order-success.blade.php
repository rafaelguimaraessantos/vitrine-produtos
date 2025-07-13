@extends('layouts.app')

@section('title', 'Pedido Realizado')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-center mb-8">
        <div class="mb-4">
            <i class="fas fa-check-circle text-6xl text-green-500"></i>
        </div>
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Pedido Realizado com Sucesso!</h2>
        <p class="text-gray-600">Seu pedido foi criado e está sendo processado.</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Order Details -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Detalhes do Pedido</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Número do Pedido:</span>
                    <span class="font-semibold">{{ $order->order_id }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-semibold text-blue-600">{{ $order->status }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-semibold text-green-600">{{ $order->formatted_total }}</span>
                </div>
                
                @if($order->estimated_delivery)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Entrega Estimada:</span>
                        <span class="font-semibold">{{ $order->formatted_estimated_delivery }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Itens do Pedido</h3>
            
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                 class="w-12 h-12 object-cover rounded">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->product->brand }}</p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Qtd: {{ $item->quantity }}</p>
                            <p class="font-semibold text-green-600">{{ $item->formatted_total_price }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="p-6">
            @if($order->status === 'Pendente')
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                        <p class="text-yellow-800">
                            Seu pedido foi criado localmente. Para processá-lo, envie-o para nossa API externa.
                        </p>
                    </div>
                </div>
                
                <form action="{{ route('vitrine.send-order-external', $order->order_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Pedido para Processamento
                    </button>
                </form>
            @else
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <p class="text-green-800">
                            Pedido enviado com sucesso! Status: {{ $order->status }}
                        </p>
                    </div>
                </div>
            @endif
            
            <div class="mt-4 text-center">
                <a href="{{ route('vitrine.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    <i class="fas fa-home mr-2"></i>Voltar à Vitrine
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 