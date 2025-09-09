 <!--begin::Card header-->
 <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
             <!--begin:::Tab item-->
            <li class="nav-item">
                <a href="<?php echo e(isset($stock) ? route('stocks.edit',$stock->id) : route('stocks.create')); ?>" class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('stocks.edit')); ?>"> 
                    <?php echo e(isset($stock) ? $stock->name : __('dashboard.create_title', ['page_title' => __('dashboard.stocks')])); ?> 
                    </a>
            </li>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-quantities.show')): ?>
                <?php if(isset($stock)): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('stock-quantities.show',$stock->id)); ?>" 
                        class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('stock-quantities.show')); ?>"> 
                            <?php echo e(__('dashboard.amounts')); ?> 
                            <span class="badge badge-success ms-2"><?php echo e($stock->quantity); ?></span>
                        </a>
                    </li>
                <?php endif; ?> 
            <?php endif; ?> 
        <!--end:::Tab item-->
        </ul>
        <hr><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/stocks/nav.blade.php ENDPATH**/ ?>