

<?php $__env->startSection('content'); ?>


<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1"></h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Secondary button-->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.create')): ?>
                <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-sm btn-light">
                    <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.customers')]); ?>
                </a>
                <?php endif; ?>
                <!--end::Secondary button-->
                <!--begin::Primary button-->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.create')): ?>
                <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-sm btn-primary">
                     <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.orders')]); ?>
                </a>
                <!--end::Primary button-->
                <?php endif; ?>
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">


            <div class="row">
                <!-- Customers Count -->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.index')): ?>
                <div class="col-md-4">
                    <a href="<?php echo e(route('customers.index')); ?>">
                        <div class="card">
                            <div class="card-body">
                                <h3><?php echo e(__('dashboard.total_customers')); ?></h3>
                                <h5><?php echo e($totalCustomersCount); ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.index')): ?>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('orders.index')); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h3><?php echo e(__('dashboard.total_orders')); ?></h3>
                                    <h5><?php echo e($totalOrdersCount); ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php $__currentLoopData = $ordersCountByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <a href="<?php echo e(route('orders.index', ['status' => $status])); ?>">
                                <div class="card">
                                    <div class="card-body">
                                        <h3><?php echo e(__('dashboard.order_status.' . $status)); ?></h3>
                                        <h5><?php echo e($count); ?></h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

            </div>

            <div class="row mt-10">


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.index')): ?>
                <div class="col-6 mb-xl-10">
                    <!--begin::List widget for Orders-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800"> <?php echo app('translator')->get('dashboard.orders'); ?></span>
                            </h3>
                            <!--end::Title-->
                            <!--begin::Toolbar-->
                            <div class="card-toolbar">
                                <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-sm btn-light">
                                    <?php echo app('translator')->get('dashboard.orders'); ?>
                                </a>
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-4 px-0">
                            <!--begin::Tab Content-->
                            <div class="tab-content px-9 hover-scroll-overlay-y pe-7 me-3 mb-2" style="height: 454px">
                                <!--begin::Tab pane-->
                                <div class="tab-pane fade show active" id="kt_list_widget_16_tab_1">
                                    <?php $__empty_1 = true; $__currentLoopData = $latestOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <!--begin::Order Item-->
                                        <div class="m-0">
                                            <!--begin::Timeline-->
                                            <div class="timeline ms-n1">
                                                <!--begin::Timeline item-->
                                                <div class="timeline-item align-items-center mb-4">
                                                    <div class="timeline-line w-20px mt-9 mb-n14"></div>
                                                    <div class="timeline-icon pt-1">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </div>
                                                    <!--begin::Timeline content-->
                                                    <div class="timeline-content m-0">
                                                        <span class="fs-8 fw-boldest text-success text-uppercase">
                                                            <a href="<?php echo e(route('orders.show', $order->id)); ?>">
                                                                <?php echo e($order->customer->name); ?> - <?php echo e($order->status); ?>

                                                            </a>
                                                        </span>
                                                        <span class="fw-bold text-gray-400 d-block"> <?php echo app('translator')->get('dashboard.date'); ?>: <?php echo e($order->date); ?></span>
                                                    </div>
                                                    <!--end::Timeline content-->
                                                </div>
                                                <!--end::Timeline item-->
                                            </div>
                                            <!--end::Timeline-->
                                        </div>
                                        <!--end::Order Item-->
                                        <div class="separator separator-dashed mt-5 mb-4"></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="alert alert-info text-center"> <?php echo app('translator')->get('dashboard.no orders'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <!--end::Tab pane-->
                            </div>
                            <!--end::Tab Content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::List widget for Orders-->
                </div>
                <!--end::Col-->
                <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.index')): ?>
                <div class="col-6 mb-xl-10">
                    <!--begin::List widget for Payments-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800"><?php echo app('translator')->get('dashboard.latest_payments'); ?></span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-4 px-0">
                            <div class="tab-content px-9 hover-scroll-overlay-y pe-7 me-3 mb-2" style="height: 454px">
                                <div class="tab-pane fade show active">
                                    <?php $__empty_1 = true; $__currentLoopData = $latestPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="m-0">
                                            <div class="timeline ms-n1">
                                                <div class="timeline-item align-items-center mb-4">
                                                    <div class="timeline-line w-20px mt-9 mb-n14"></div>
                                                    <div class="timeline-icon pt-1">
                                                        <i class="fa fa-credit-card"></i>
                                                    </div>
                                                    <div class="timeline-content m-0">
                                                        <span class="fs-8 fw-boldest text-success text-uppercase">
                                                            <?php echo app('translator')->get('dashboard.order'); ?> #<?php echo e($payment->order->id); ?>

                                                        </span>
                                                        <span class="fw-bold text-gray-400 d-block"><?php echo app('translator')->get('dashboard.amount'); ?>: <?php echo e($payment->price); ?></span>
                                                        <span class="fw-bold text-gray-400 d-block"><?php echo app('translator')->get('dashboard.method'); ?>: <?php echo e(__('dashboard.'. $payment->payment_method)); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed mt-5 mb-4"></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="alert alert-info text-center"><?php echo app('translator')->get('dashboard.no_payments'); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::List widget for Payments-->
                </div>
                <?php endif; ?>

            </div>

        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    </div>
<!--end::Content-->


<?php $__env->stopSection(); ?>
<?php $__env->startPush('css'); ?>
    <style>
        #kt_content_container .card {
           margin-top:10px
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/home.blade.php ENDPATH**/ ?>