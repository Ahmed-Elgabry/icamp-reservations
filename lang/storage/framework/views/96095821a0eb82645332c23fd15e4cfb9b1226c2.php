


 <!--begin::Card header-->
 <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
            <!--begin:::Tab item-->
        <li class="nav-item">
                <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="nav-link bg-transparent border-0 text-active-primary pb-4 <?php echo e(isActiveRoute('orders.edit')); ?>">
                    <i class="fa fa-home text-primary me-1"></i>
                    <?php echo e(__('dashboard.Reservation_information')); ?> 
                </a>
        </li>
        <?php if(isset($order)): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.addons')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.addons',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.addons')); ?>">
                     <?php echo e(__('dashboard.addons')); ?> <span class="badge badge-primary"><?php echo e($order->addons->count()); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.show')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('payments.show',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('payments.show')); ?>">
                     <?php echo e(__('dashboard.payments')); ?> <span class="badge badge-primary" id="payment-amount"><?php echo e($order->payments->sum('price')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- روابط الدفع -->
            <li class="nav-item">
                <a href="<?php echo e(route('payment-links.create')); ?>?order_id=<?php echo e($order->id); ?>"
                class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('payment-links.*')); ?>">
                 <?php echo e(__('dashboard.payment-links')); ?> <span class="badge badge-success">+</span>
                </a>
            </li>

            <!-- عرض روابط الدفع المرتبطة بالطلب -->
            <li class="nav-item">
                <a href="<?php echo e(route('payment-links.index')); ?>?order_id=<?php echo e($order->id); ?>"
                class="nav-link text-active-primary pb-4">
                 <?php echo e(__('dashboard.view_payment_links')); ?> <span class="badge badge-info"><?php echo e($order->paymentLinks->count() ?? 0); ?></span>
                </a>
            </li>

            <li class="nav-item">
                    <a href="<?php echo e(route('warehouse_sales.show',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('warehouse_sales.show')); ?>">
                     <?php echo e(__('dashboard.warehouse_sales')); ?> <span class="badge badge-primary"><?php echo e($order->items->count()); ?></span>
                    </a>
                </li>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expenses.show')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('expenses.show',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('expenses.show')); ?>">
                     <?php echo e(__('dashboard.expenses')); ?> <span class="badge badge-primary " id="expense-amount"><?php echo e($order->expenses->sum('price')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.insurance')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.insurance',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.insurance')); ?>">
                     <?php echo e(__('dashboard.Insurance forfeiture refund')); ?>

                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.reports')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.reports',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.reports')); ?>">
                        <?php echo e(__('dashboard.reports')); ?>

                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.signin')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.signin',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.signin')); ?>">
                        <?php echo e(__('dashboard.signin')); ?>

                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.logout')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.logout',$order->id)); ?>"
                    class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.logout')); ?>">
                        <?php echo e(__('dashboard.logout')); ?>

                    </a>
                </li>
            <?php endif; ?>

            <?php if($order->status == 'approved'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.client-pdf', $order->id)); ?>"
                       class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.client-pdf')); ?>"
                       target="_blank">
                        <?php echo e(__('dashboard.client_pdf')); ?>

                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="<?php echo e(route('orders.quote',$order->id)); ?>"
                class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.quote')); ?>" target="_blank">
                    <?php echo e(__('dashboard.Offer Price')); ?>

                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo e(route('orders.invoice',$order->id)); ?>"
                class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.invoice')); ?>" target="_blank">
                    <?php echo e(__('dashboard.invoice')); ?>

                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo e(route('orders.accept_terms',$order->id)); ?>"
                class="nav-link text-active-primary pb-4 <?php echo e(isActiveRoute('orders.accept_terms')); ?>">
                    <?php echo e(__('dashboard.accept_terms')); ?>

                </a>
            </li>


        <?php endif; ?>
    <!--end:::Tab item-->
    </ul>
    <hr>
<?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/orders/nav.blade.php ENDPATH**/ ?>