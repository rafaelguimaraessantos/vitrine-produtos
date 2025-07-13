

<?php $__env->startSection('title', 'Pedido Realizado'); ?>

<?php $__env->startSection('content'); ?>
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
                    <span class="font-semibold"><?php echo e($order->order_id); ?></span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-semibold text-blue-600"><?php echo e($order->status); ?></span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-semibold text-green-600"><?php echo e($order->formatted_total); ?></span>
                </div>
                
                <?php if($order->estimated_delivery): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Entrega Estimada:</span>
                        <span class="font-semibold"><?php echo e($order->formatted_estimated_delivery); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Itens do Pedido</h3>
            
            <div class="space-y-3">
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div class="flex items-center space-x-3">
                            <img src="<?php echo e($item->product->image_url); ?>" alt="<?php echo e($item->product->name); ?>" 
                                 class="w-12 h-12 object-cover rounded">
                            <div>
                                <h4 class="font-semibold text-gray-800"><?php echo e($item->product->name); ?></h4>
                                <p class="text-sm text-gray-600"><?php echo e($item->product->brand); ?></p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Qtd: <?php echo e($item->quantity); ?></p>
                            <p class="font-semibold text-green-600"><?php echo e($item->formatted_total_price); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="p-6">
            <?php if($order->status === 'Pendente'): ?>
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                        <p class="text-yellow-800">
                            Seu pedido foi criado localmente. Para processá-lo, envie-o para nossa API externa.
                        </p>
                    </div>
                </div>
                
                <form action="<?php echo e(route('vitrine.send-order-external', $order->order_id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Pedido para Processamento
                    </button>
                </form>
            <?php else: ?>
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <p class="text-green-800">
                            Pedido enviado com sucesso! Status: <?php echo e($order->status); ?>

                        </p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="mt-4 text-center">
                <a href="<?php echo e(route('vitrine.index')); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                    <i class="fas fa-home mr-2"></i>Voltar à Vitrine
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/vitrine/order-success.blade.php ENDPATH**/ ?>