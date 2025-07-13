<?php

namespace App\Http\Controllers;

use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VitrineController extends Controller
{
    public function __construct(
        private ProductServiceInterface $productService,
        private OrderServiceInterface $orderService
    ) {}

    public function index(Request $request): View
    {
        $products = $this->productService->getAllProducts();
        
        // Busca categorias únicas para o filtro
        $categories = $products->pluck('category')->unique()->sort();
        
        // Busca marcas únicas para o filtro
        $brands = $products->pluck('brand')->unique()->sort();
        
        // Filtra por pesquisa se fornecida
        $search = $request->input('search', '');
        if ($search) {
            $products = $products->filter(function ($product) use ($search) {
                return stripos($product->name, $search) !== false ||
                       stripos($product->brand, $search) !== false ||
                       stripos($product->category, $search) !== false ||
                       stripos($product->description, $search) !== false;
            });
        }
        
        // Filtra por categoria se selecionada
        $selectedCategory = $request->input('category', '');
        if ($selectedCategory) {
            $products = $products->where('category', $selectedCategory);
        }
        
        // Filtra por marca se selecionada
        $selectedBrand = $request->input('brand', '');
        if ($selectedBrand) {
            $products = $products->where('brand', $selectedBrand);
        }
        
        return view('vitrine.index', compact('products', 'categories', 'brands', 'selectedCategory', 'selectedBrand', 'search'));
    }

    public function checkout(Request $request): View
    {
        $cartItems = $request->session()->get('cart', []);
        $products = collect();
        $total = 0;

        foreach ($cartItems as $productId => $quantity) {
            $product = \App\Models\Product::where('product_id', $productId)->first();
            if ($product) {
                $product->cart_quantity = $quantity;
                $products->push($product);
                $total += $product->price * $quantity;
            }
        }

        return view('vitrine.checkout', compact('products', 'total'));
    }

    public function addToCart(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Busca o produto para verificar estoque
        $product = \App\Models\Product::where('product_id', $productId)->first();
        
        if (!$product) {
            return redirect()->back()->with('error', 'Produto não encontrado!');
        }

        // Verifica se há estoque suficiente
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Quantidade solicitada não disponível em estoque!');
        }

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId] + $quantity;
            
            // Verifica se a quantidade total não excede o estoque
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Quantidade total excede o estoque disponível!');
            }
            
            $cart[$productId] = $newQuantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $request->session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function removeFromCart(Request $request, string $productId): \Illuminate\Http\RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$productId]);
        $request->session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto removido do carrinho!');
    }

    public function clearCart(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->session()->forget('cart');
        return redirect()->route('vitrine.index')->with('success', 'Carrinho limpo!');
    }

    public function placeOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        $cartItems = $request->session()->get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('vitrine.checkout')->with('error', 'Carrinho vazio!');
        }

        $items = [];
        foreach ($cartItems as $productId => $quantity) {
            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        try {
            $order = $this->orderService->createOrder($items);
            
            // Limpa o carrinho após criar o pedido
            $request->session()->forget('cart');
            
            return redirect()->route('vitrine.order.success', $order->order_id)
                ->with('success', 'Pedido criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('vitrine.checkout')
                ->with('error', 'Erro ao criar pedido. Tente novamente.');
        }
    }

    public function orderSuccess(string $orderId): View
    {
        $order = $this->orderService->getOrderById($orderId);
        
        if (!$order) {
            abort(404);
        }

        return view('vitrine.order-success', compact('order'));
    }

    public function sendOrderToExternalApi(string $orderId): \Illuminate\Http\RedirectResponse
    {
        $order = $this->orderService->getOrderById($orderId);
        
        if (!$order) {
            return redirect()->back()->with('error', 'Pedido não encontrado!');
        }

        $result = $this->orderService->sendOrderToExternalApi($order);
        
        if ($result['success']) {
            return redirect()->route('vitrine.order.success', $orderId)
                ->with('success', 'Pedido enviado com sucesso!');
        } else {
            return redirect()->route('vitrine.order.success', $orderId)
                ->with('error', $result['message']);
        }
    }
} 