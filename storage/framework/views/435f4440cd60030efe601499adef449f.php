

<?php $__env->startSection('title', 'Carrinho de Compras'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <div class="text-center mb-8">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Seu Carrinho</h2>
        <p class="text-gray-600">Revise seus itens antes de finalizar a compra</p>
    </div>

    <?php if($products->count() > 0): ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Cart Items -->
            <div class="p-6">
                <div class="space-y-6">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-start gap-6 p-4 border-b border-gray-200 last:border-b-0">
                            <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" 
                                 class="w-24 h-24 object-cover rounded flex-shrink-0">
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 text-lg mb-2 leading-tight break-normal overflow-visible"><?php echo e($product->name); ?></h3>
                                <p class="text-sm text-gray-600 mb-1"><?php echo e($product->brand); ?> - <?php echo e($product->category); ?></p>
                                <p class="text-sm text-gray-500">Quantidade: <?php echo e($product->cart_quantity); ?></p>
                            </div>
                            
                            <div class="text-right flex-shrink-0 ml-4">
                                <p class="font-semibold text-green-600 text-lg mb-1"><?php echo e($product->formatted_price); ?></p>
                                <p class="text-sm text-gray-500 mb-3">Total: R$ <?php echo e(number_format($product->price * $product->cart_quantity, 2, ',', '.')); ?></p>
                                
                                <form action="<?php echo e(route('vitrine.remove-from-cart', $product->product_id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="bg-gray-50 p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold text-gray-800">Total do Pedido:</span>
                    <span class="text-2xl font-bold text-green-600">R$ <?php echo e(number_format($total, 2, ',', '.')); ?></span>
                </div>

                <div class="flex space-x-4">
                    <form action="<?php echo e(route('vitrine.clear-cart')); ?>" method="POST" class="flex-1">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>Limpar Carrinho
                        </button>
                    </form>
                    
                    <form action="<?php echo e(route('vitrine.place-order')); ?>" method="POST" class="flex-1">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            <i class="fas fa-check mr-2"></i>Finalizar Pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Continue Shopping -->
        <div class="text-center mt-8">
            <a href="<?php echo e(route('vitrine.index')); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Continuar Comprando
            </a>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-600 mb-2">Seu carrinho está vazio</h3>
            <p class="text-gray-500 mb-6">Adicione alguns produtos para começar suas compras.</p>
            <a href="<?php echo e(route('vitrine.index')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i>Ver Produtos
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/vitrine/checkout.blade.php ENDPATH**/ ?>