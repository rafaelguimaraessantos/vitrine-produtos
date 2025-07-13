

<?php $__env->startSection('title', 'Vitrine de Produtos'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-8">
    <div class="text-center mb-8">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Vitrine de Produtos</h2>
        <p class="text-gray-600 text-lg mb-6">Descubra produtos incríveis com os melhores preços</p>
        
        <?php
            $cartItems = session('cart', []);
            $totalItems = array_sum($cartItems);
        ?>
        <?php if($totalItems > 0): ?>
            <div class="mt-4 inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full">
                <i class="fas fa-shopping-cart mr-2"></i>
                <span class="font-semibold"><?php echo e($totalItems); ?> <?php echo e($totalItems == 1 ? 'item' : 'itens'); ?> no carrinho</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Filtros e Ações -->
    <div class="mb-8">
        <!-- Filtros de Categoria e Marca - Centralizados -->
        <div class="flex flex-col items-center mb-6">
            <form action="<?php echo e(route('vitrine.index')); ?>" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                <?php if($search): ?>
                    <input type="hidden" name="search" value="<?php echo e($search); ?>">
                <?php endif; ?>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <select name="category" id="category" onchange="this.form.submit()" 
                            class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Categorias</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category); ?>" <?php echo e($selectedCategory == $category ? 'selected' : ''); ?>>
                                <?php echo e($category); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                    <select name="brand" id="brand" onchange="this.form.submit()" 
                            class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Marcas</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand); ?>" <?php echo e($selectedBrand == $brand ? 'selected' : ''); ?>>
                                <?php echo e($brand); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </form>
        </div>
        
        <!-- Resultados do Filtro -->
        <?php if($selectedCategory || $selectedBrand || $search): ?>
            <div class="text-center mb-4">
                <div class="inline-flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full">
                    <i class="fas fa-filter mr-2"></i>
                    <span class="font-semibold">
                        <?php if($search && $selectedCategory && $selectedBrand): ?>
                            Pesquisa: "<?php echo e($search); ?>" | Categoria: <?php echo e($selectedCategory); ?> | Marca: <?php echo e($selectedBrand); ?>

                        <?php elseif($search && $selectedCategory): ?>
                            Pesquisa: "<?php echo e($search); ?>" | Categoria: <?php echo e($selectedCategory); ?>

                        <?php elseif($search && $selectedBrand): ?>
                            Pesquisa: "<?php echo e($search); ?>" | Marca: <?php echo e($selectedBrand); ?>

                        <?php elseif($search): ?>
                            Pesquisa: "<?php echo e($search); ?>"
                        <?php elseif($selectedCategory && $selectedBrand): ?>
                            Filtrado por: <?php echo e($selectedCategory); ?> e <?php echo e($selectedBrand); ?>

                        <?php elseif($selectedCategory): ?>
                            Filtrado por: <?php echo e($selectedCategory); ?>

                        <?php elseif($selectedBrand): ?>
                            Filtrado por: <?php echo e($selectedBrand); ?>

                        <?php endif; ?>
                    </span>
                    <a href="<?php echo e(route('vitrine.index')); ?>" class="ml-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Contagem de Produtos -->
    <div class="text-center mb-6">
        <p class="text-gray-600">
            <?php if($search && $selectedCategory && $selectedBrand): ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto encontrado' : 'produtos encontrados'); ?> para "<?php echo e($search); ?>" em "<?php echo e($selectedCategory); ?>" e "<?php echo e($selectedBrand); ?>"
            <?php elseif($search && $selectedCategory): ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto encontrado' : 'produtos encontrados'); ?> para "<?php echo e($search); ?>" em "<?php echo e($selectedCategory); ?>"
            <?php elseif($search && $selectedBrand): ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto encontrado' : 'produtos encontrados'); ?> para "<?php echo e($search); ?>" em "<?php echo e($selectedBrand); ?>"
            <?php elseif($search): ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto encontrado' : 'produtos encontrados'); ?> para "<?php echo e($search); ?>"
            <?php elseif($selectedCategory && $selectedBrand): ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto encontrado' : 'produtos encontrados'); ?> em "<?php echo e($selectedCategory); ?>" e "<?php echo e($selectedBrand); ?>"
            <?php elseif($selectedCategory): ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto encontrado' : 'produtos encontrados'); ?> em "<?php echo e($selectedCategory); ?>"
            <?php elseif($selectedBrand): ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto encontrado' : 'produtos encontrados'); ?> em "<?php echo e($selectedBrand); ?>"
            <?php else: ?>
                <?php echo e($products->count()); ?> <?php echo e($products->count() == 1 ? 'produto disponível' : 'produtos disponíveis'); ?>

            <?php endif; ?>
        </p>
    </div>

    <!-- Products Grid -->
    <?php if($products->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-md card-hover overflow-hidden flex flex-col h-full">
                    <div class="relative">
                        <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" 
                             class="w-full h-48 object-cover">
                        <?php if(!$product->isInStock()): ?>
                            <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                Sem Estoque
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800 line-clamp-2"><?php echo e($product->name); ?></h3>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2 flex-grow"><?php echo e($product->description); ?></p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-green-600"><?php echo e($product->formatted_price); ?></span>
                            <span class="text-sm text-gray-500"><?php echo e($product->brand); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500"><?php echo e($product->category); ?></span>
                            <span class="text-sm text-gray-500 <?php echo e($product->stock <= 5 ? 'text-red-500 font-semibold' : ''); ?>">
                                Estoque: <?php echo e($product->stock); ?>

                            </span>
                        </div>
                        
                        <div class="mt-auto">
                            <?php if($product->isInStock()): ?>
                                <form action="<?php echo e(route('vitrine.add-to-cart')); ?>" method="POST" class="space-y-3">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($product->product_id); ?>">
                                    
                                    <div class="flex items-center space-x-2">
                                        <label for="quantity-<?php echo e($product->id); ?>" class="text-sm font-medium text-gray-700">Qtd:</label>
                                        <input type="number" name="quantity" id="quantity-<?php echo e($product->id); ?>" 
                                               value="1" min="1" max="<?php echo e($product->stock); ?>"
                                               class="w-16 px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                    
                                    <button type="submit" 
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                        <i class="fas fa-cart-plus mr-2"></i>Adicionar ao Carrinho
                                    </button>
                                </form>
                            <?php else: ?>
                                <button disabled class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                    Produto Indisponível
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-600 mb-2">Nenhum produto disponível</h3>
            <p class="text-gray-500">Os produtos são sincronizados automaticamente. Tente novamente em alguns minutos.</p>
        </div>
    <?php endif; ?>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/vitrine/index.blade.php ENDPATH**/ ?>