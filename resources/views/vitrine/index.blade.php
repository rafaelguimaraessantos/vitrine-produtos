@extends('layouts.app')

@section('title', 'Vitrine de Produtos')

@section('content')
<div class="mb-8">
    <div class="text-center mb-8">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Vitrine de Produtos</h2>
        <p class="text-gray-600 text-lg mb-6">Descubra produtos incríveis com os melhores preços</p>
        
        @php
            $cartItems = session('cart', []);
            $totalItems = array_sum($cartItems);
        @endphp
        @if($totalItems > 0)
            <div class="mt-4 inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full">
                <i class="fas fa-shopping-cart mr-2"></i>
                <span class="font-semibold">{{ $totalItems }} {{ $totalItems == 1 ? 'item' : 'itens' }} no carrinho</span>
            </div>
        @endif
    </div>

    <!-- Filtros e Ações -->
    <div class="mb-8">
        <!-- Filtros de Categoria e Marca - Centralizados -->
        <div class="flex flex-col items-center mb-6">
            <form action="{{ route('vitrine.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                @if($search)
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <select name="category" id="category" onchange="this.form.submit()" 
                            class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Categorias</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="brand" id="brand" onchange="this.form.submit()" 
                            class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Marcas</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" {{ $selectedBrand == $brand ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        
        <!-- Resultados do Filtro -->
        @if($selectedCategory || $selectedBrand || $search)
            <div class="text-center mb-4">
                <div class="inline-flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full">
                    <i class="fas fa-filter mr-2"></i>
                    <span class="font-semibold">
                        @if($search && $selectedCategory && $selectedBrand)
                            Pesquisa: "{{ $search }}" | Categoria: {{ $selectedCategory }} | Marca: {{ $selectedBrand }}
                        @elseif($search && $selectedCategory)
                            Pesquisa: "{{ $search }}" | Categoria: {{ $selectedCategory }}
                        @elseif($search && $selectedBrand)
                            Pesquisa: "{{ $search }}" | Marca: {{ $selectedBrand }}
                        @elseif($search)
                            Pesquisa: "{{ $search }}"
                        @elseif($selectedCategory && $selectedBrand)
                            Filtrado por: {{ $selectedCategory }} e {{ $selectedBrand }}
                        @elseif($selectedCategory)
                            Filtrado por: {{ $selectedCategory }}
                        @elseif($selectedBrand)
                            Filtrado por: {{ $selectedBrand }}
                        @endif
                    </span>
                    <a href="{{ route('vitrine.index') }}" class="ml-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Contagem de Produtos -->
    <div class="text-center mb-6">
        <p class="text-gray-600">
            @if($search && $selectedCategory && $selectedBrand)
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }} para "{{ $search }}" em "{{ $selectedCategory }}" e "{{ $selectedBrand }}"
            @elseif($search && $selectedCategory)
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }} para "{{ $search }}" em "{{ $selectedCategory }}"
            @elseif($search && $selectedBrand)
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }} para "{{ $search }}" em "{{ $selectedBrand }}"
            @elseif($search)
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }} para "{{ $search }}"
            @elseif($selectedCategory && $selectedBrand)
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }} em "{{ $selectedCategory }}" e "{{ $selectedBrand }}"
            @elseif($selectedCategory)
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }} em "{{ $selectedCategory }}"
            @elseif($selectedBrand)
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }} em "{{ $selectedBrand }}"
            @else
                {{ $products->count() }} {{ $products->count() == 1 ? 'produto disponível' : 'produtos disponíveis' }}
            @endif
        </p>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md card-hover overflow-hidden flex flex-col h-full">
                    <div class="relative">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                        @if(!$product->isInStock())
                            <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                Sem Estoque
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $product->name }}</h3>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2 flex-grow">{{ $product->description }}</p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-green-600">{{ $product->formatted_price }}</span>
                            <span class="text-sm text-gray-500">{{ $product->brand }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">{{ $product->category }}</span>
                            <span class="text-sm text-gray-500 {{ $product->stock <= 5 ? 'text-red-500 font-semibold' : '' }}">
                                Estoque: {{ $product->stock }}
                            </span>
                        </div>
                        
                        <div class="mt-auto">
                            @if($product->isInStock())
                                <form action="{{ route('vitrine.add-to-cart') }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                    
                                    <div class="flex items-center space-x-2">
                                        <label for="quantity-{{ $product->id }}" class="text-sm font-medium text-gray-700">Qtd:</label>
                                        <input type="number" name="quantity" id="quantity-{{ $product->id }}" 
                                               value="1" min="1" max="{{ $product->stock }}"
                                               class="w-16 px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                    
                                    <button type="submit" 
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                        <i class="fas fa-cart-plus mr-2"></i>Adicionar ao Carrinho
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                    Produto Indisponível
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-600 mb-2">Nenhum produto disponível</h3>
            <p class="text-gray-500">Os produtos são sincronizados automaticamente. Tente novamente em alguns minutos.</p>
        </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection 