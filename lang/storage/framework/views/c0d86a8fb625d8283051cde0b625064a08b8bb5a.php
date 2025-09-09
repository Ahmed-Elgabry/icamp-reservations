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
                <!--begin::Card title-->
                <div class="card-title">
                    <form action="" method="GET" class="row">
                        <div class="form-group col-6 mt-3">
                            <label for=""><?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.customer')); ?></label>
                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value=""><?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.customer')); ?></option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->id); ?>" <?php echo e(request('customer_id') == $customer->id ? 'selected' : ''); ?>><?php echo e($customer->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group col-6 mt-3 ">
                            <label for=""><?php echo e(__('dashboard.orders')); ?></label>
                            <select name="order_id" id="order_id" class="form-control">
                                <option value=""><?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.orders')); ?></option>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($order->id); ?>" <?php echo e(request('order_id') == $order->id ? 'selected' : ''); ?>><?php echo e($order->id); ?> [<?php echo e($order->customer->name); ?>]</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group col-6 mt-3">
                            <label for=""><?php echo e(__('dashboard.date_from')); ?></label>
                            <input type="date" name="date_from" class="form-control" placeholder="<?php echo e(__('dashboard.date_from')); ?>" value="<?php echo e(request('date_from')); ?>">
                        </div>
                        <div class="form-group col-6 mt-3 ">
                            <label for=""><?php echo e(__('dashboard.date_to')); ?></label>
                            <input type="date" name="date_to" class="form-control" placeholder="<?php echo e(__('dashboard.date_to')); ?>" value="<?php echo e(request('date_to')); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3"><?php echo e(__('dashboard.search')); ?> <i class="fa fa-search"></i></button>

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
                    <!--begin::Table head-->
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th><?php echo e(__('dashboard.price')); ?></th>
                            <th class=""><?php echo e(__('dashboard.orders')); ?></th>
                            <th class=""><?php echo e(__('dashboard.statement')); ?></th>
                            <th class=""><?php echo e(__('dashboard.notes')); ?></th>
                            <th class=""><?php echo e(__('dashboard.created_at')); ?></th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-id="<?php echo e($payment->id); ?>">
                                <!--begin::Checkbox-->
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input checkSingle" type="checkbox" value="1" id="<?php echo e($payment->id); ?>"/>
                                    </div>
                                </td>
                                <!--begin::Category=-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="#" data-kt-ecommerce-category-filter="search" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                                <?php echo e($payment->price); ?> <?php echo e(__('dashboard.'. $payment->payment_method )); ?>

                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('payments.show', $payment->order_id)); ?>">
                                        <?php echo e($payment->order_id); ?> <?php if(isset($order)): ?> [<?php echo e($order->customer->name); ?>] <?php endif; ?>
                                    </a>
                                </td>
                                <td><?php echo e(__('dashboard.'. $payment->statement )); ?></td>
                                <td data-kt-ecommerce-category-filter="category_name">
                                    <?php echo e($payment->notes); ?>

                                </td>
                                <td>
                                    <?php echo e($payment->created_at->diffForHumans()); ?>

                                </td>
                                <!--end::Category=-->
                                <!--begin::Action=-->
                                <!-- <td class="text-end">-->
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <!--end::Table body-->
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
        $("#customer_id ,#order_id").select2();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/payments/index.blade.php ENDPATH**/ ?>