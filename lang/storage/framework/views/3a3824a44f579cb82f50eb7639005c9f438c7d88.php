<?php $__env->startSection('pageTitle', __('dashboard.payments')); ?>


<?php $__env->startSection('content'); ?>
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Category-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="col-4 mt-3">

                    <a href="<?php echo e(route('general_payments.create')); ?>"
                        class="btn btn-primary"><?php echo e(__('dashboard.add')); ?></a>
                </div>
                <!--begin::Card title-->
                <div class="card-title">
                    <form action="" method="GET" class="row g-3 align-items-end">

                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">
                                <?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.customer')); ?>

                            </label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value=""><?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.customer')); ?></option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->id); ?>" <?php echo e(request('customer_id') == $customer->id ? 'selected' : ''); ?>>
                                        <?php echo e($customer->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="order_id" class="form-label">
                                <?php echo e(__('dashboard.orders')); ?>

                            </label>
                            <select name="order_id" id="order_id" class="form-select">
                                <option value=""><?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.orders')); ?></option>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($order->id); ?>" <?php echo e(request('order_id') == $order->id ? 'selected' : ''); ?>>
                                        #<?php echo e($order->id); ?> [<?php echo e($order->customer->name); ?>]
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="date_from" class="form-label"><?php echo e(__('dashboard.date_from')); ?></label>
                            <input type="date" name="date_from" id="date_from" class="form-control"
                                value="<?php echo e(request('date_from')); ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="date_to" class="form-label"><?php echo e(__('dashboard.date_to')); ?></label>
                            <input type="date" name="date_to" id="date_to" class="form-control"
                                value="<?php echo e(request('date_to')); ?>">
                        </div>

                        <div class="col-md-4 d-flex">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-1"></i> <?php echo e(__('dashboard.search')); ?>

                            </button>
                        </div>

                    </form>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <?php if(count(request()->all())): ?>
                        <a href="?">
                            <i class="fa fa-list"></i> <?php echo e(__('dashboard.showall')); ?>

                        </a>
                    <?php endif; ?>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-nowrap"><?php echo e(__('dashboard.orders')); ?></th>
                            <th class="text-nowrap"><?php echo e(__('dashboard.Insurance')); ?></th>
                            <th class="text-nowrap"><?php echo e(__('dashboard.addons')); ?></th>
                            <th class="text-nowrap"><?php echo e(__('dashboard.warehouse_sales')); ?></th>
                            <th class="text-nowrap"><?php echo e(__('dashboard.total')); ?></th>
                            <th class="text-nowrap"><?php echo e(__('dashboard.created_at')); ?></th>
                        </tr>
                    </thead>

                    <tbody class="fw-semibold text-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $orderSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr data-order-id="<?php echo e($summary->order->id); ?>">

                            
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="<?php echo e(route('general_payments.show', $summary->order->id)); ?>" class="fw-bold text-hover-primary">
                                        #<?php echo e($summary->order->id); ?>

                                    </a>
                                    <span class="text-muted small"><?php echo e($summary->customer->name); ?></span>
                                </div>
                            </td>

                            
                            <td>
                                <?php if($summary->insurance_count): ?>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold"><?php echo e(number_format($summary->insurance_total, 2)); ?></span>
                                        <span class="badge badge-light-primary"><?php echo e($summary->insurance_count); ?> <?php echo e(__('dashboard.Insurance')); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>


                            
                            <td>
                                <?php if($summary->addons_count): ?>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold"><?php echo e(number_format($summary->addons_total, 2)); ?></span>
                                        <span class="badge badge-light-primary"><?php echo e($summary->addons_count); ?> <?php echo e(__('dashboard.addons')); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <?php if($summary->warehouse_count): ?>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold"><?php echo e(number_format($summary->warehouse_total, 2)); ?></span>
                                        <span class="badge badge-light-info"><?php echo e($summary->warehouse_count); ?> <?php echo e(__('dashboard.sales')); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>


                            
                            <td>
                                <span class="fw-bold text-success fs-6"><?php echo e(number_format($summary->grand_total, 2)); ?></span>
                            </td>

                            
                            <td class="text-muted">
                                <?php echo e($summary->latest_date ? \Carbon\Carbon::parse($summary->latest_date)->diffForHumans() : '—'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-muted py-10">— <?php echo e(__('dashboard.no_results')); ?> —</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script>
        $("#customer_id,#order_id").select2();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/general_payments/index.blade.php ENDPATH**/ ?>