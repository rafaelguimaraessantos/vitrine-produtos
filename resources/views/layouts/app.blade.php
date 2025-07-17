<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vitrine de Produtos') - Luvinco</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-2 sm:px-4 pt-0 pb-4 sm:pt-4 sm:pb-4">
            <!-- Desktop Layout -->
            <div class="hidden sm:flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-shopping-bag text-2xl"></i>
                    <h1 class="text-2xl font-bold">Vitrine de Produtos</h1>
                </div>
                
                <!-- Barra de Pesquisa Desktop -->
                <div class="flex-1 max-w-lg">
                    <form action="{{ route('vitrine.index') }}" method="GET" class="relative">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Pesquisar produtos, marcas e muito mais..."
                                   class="w-full px-4 py-2 pl-10 pr-10 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            </div>
                            <button type="submit" 
                                    class="absolute inset-y-0 right-0 px-2 sm:px-3 flex items-center bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <nav class="flex items-center space-x-6">
                    <a href="{{ route('vitrine.index') }}" class="hover:text-gray-200 transition-colors text-sm sm:text-base">
                        <i class="fas fa-home mr-1 sm:mr-2"></i>Início
                    </a>
                    <a href="{{ route('vitrine.checkout') }}" class="hover:text-gray-200 transition-colors relative text-sm sm:text-base">
                        <i class="fas fa-shopping-cart mr-1 sm:mr-2"></i>Carrinho
                        @php
                            $cartItems = session('cart', []);
                            $totalItems = array_sum($cartItems);
                        @endphp
                        @if($totalItems > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                {{ $totalItems }}
                            </span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Mobile Layout -->
            <div class="sm:hidden">
                <!-- Top bar: título à esquerda, navegação à direita -->
                <div class="flex flex-row justify-between items-center">
                    <div class="flex items-center space-x-2 min-w-0">
                        <i class="fas fa-shopping-bag text-lg sm:text-2xl"></i>
                        <h1 class="text-base sm:text-2xl font-bold whitespace-nowrap truncate">Vitrine de Produtos</h1>
                    </div>
                    <nav class="flex items-center space-x-2 sm:space-x-6 whitespace-nowrap">
                        <a href="{{ route('vitrine.index') }}" class="hover:text-gray-200 transition-colors text-sm sm:text-base">
                            <i class="fas fa-home mr-1 sm:mr-2"></i>Início
                        </a>
                        <a href="{{ route('vitrine.checkout') }}" class="hover:text-gray-200 transition-colors relative text-sm sm:text-base">
                            <i class="fas fa-shopping-cart mr-1 sm:mr-2"></i>Carrinho
                            @if($totalItems > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                    {{ $totalItems }}
                                </span>
                            @endif
                        </a>
                    </nav>
                </div>
                <!-- Barra de Pesquisa Mobile -->
                <div class="w-full mt-4">
                    <div class="max-w-xs mx-auto -mt-4">
                        <form action="{{ route('vitrine.index') }}" method="GET" class="relative">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Pesquisar produtos, marcas e muito mais..."
                                       class="w-full px-3 py-1 pl-4 sm:pl-10 pr-10 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                </div>
                                <button type="submit" 
                                        class="absolute inset-y-0 right-0 px-2 flex items-center bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition-colors">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 Vitrine de Produtos - Desafio Técnico Luvinco</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html> 