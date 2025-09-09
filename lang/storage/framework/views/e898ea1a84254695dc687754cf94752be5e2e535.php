<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
    id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
    <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
        <div class=" menu-active-bg">
            <div class="menu-item">
                <a class="menu-link <?php echo e(isActiveRoute('home')); ?>" href="<?php echo e(route('home')); ?>">
                    <span class="menu-bullet">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" style="width:25px;height:25px">
                    </span>
                    <span class="menu-title">
                        <?php echo app('translator')->get('dashboard.home'); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <hr style="background:#fff">

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])); ?>"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/roles.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.roles'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('roles.index')); ?>" class="menu-link py-3  <?php echo e(isActiveRoute('roles.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.roles')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('roles.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('roles.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.role')]); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admins.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['admins.index', 'admins.create', 'admins.edit'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['admins.index', 'admins.create', 'admins.edit'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/admins.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.admins'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('admins.index')); ?>" class="menu-link py-3  <?php echo e(isActiveRoute('admins.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.admins')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admins.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('admins.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('admins.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.admin')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if(isSuperAdmin() || Gate::allows('customers.index')): ?>
        <!--begin::Menu item-->

        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['customers.index', 'customers.create', 'customers.edit','notices.index','notice-types.index'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#"
               class="menu-link py-3 <?php echo e(areActiveRoutes(['customers.index', 'customers.create', 'customers.edit','notices.index','notice-types.index'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/customers.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.customers'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('customers.index')); ?>" class="menu-link py-3  <?php echo e(isActiveRoute('customers.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.customers')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('customers.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('customers.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.customers')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <!--end::Menu item-->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notices.index')): ?>
                    <div class="menu-item">
                        <a href="<?php echo e(route('notices.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('notices.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.notices'); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notice-types.index')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('notice-types.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('notice-types.index')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.notice_types'); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('surveys.create')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['surveys.create', 'surveys.answer', 'surveys.settings', 'surveys.results', 'surveys.statistics'])); ?>" data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['surveys.create', 'surveys.answer', 'surveys.results', 'surveys.settings', 'surveys.statistics'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/tasks.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.surveys'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('surveys.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('surveys.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('surveys.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.create_survey'); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('surveys.results', 1)); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('surveys.results')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.survey_results'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('surveys.statistics',1)); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('surveys.statistics')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.survey_statistics'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('surveys.settings')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('surveys.settings')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.survey_settings'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['stocks.index', 'stocks.show', 'stocks.create', 'stocks.edit', 'stock-quantities.show'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['stocks.index', 'stocks.create', 'stocks.edit'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/stocks.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.stocks'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('stocks.index')); ?>" class="menu-link py-3  <?php echo e(isActiveRoute('stocks.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.stocks')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('stocks.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('stocks.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.stocks')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('addons.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['addons.index', 'addons.show', 'addons.create', 'addons.edit', 'stock-quantities.show'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['addons.index', 'addons.create', 'addons.edit'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/addons.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.addons'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('addons.index')); ?>" class="menu-link py-3  <?php echo e(isActiveRoute('addons.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.addons')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('addons.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('addons.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('addons.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.addons')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>

    <?php if(Gate::allows('services.index') || Gate::allows('camp-types.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['services.index', 'services.create', 'services.edit'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['services.index', 'services.create', 'services.edit'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/services.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.services'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('services.index')); ?>" class="menu-link py-3  <?php echo e(isActiveRoute('services.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.services')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if(Gate::allows('services.create') || Gate::allows('camp-types.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('services.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('services.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.service')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['orders.index', 'orders.reports', 'payments.show', 'expenses.show', 'orders.orders-by-status', 'orders.create', 'orders.edit', 'orders.show' , 'orders.registeration-forms'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['orders.index', 'orders.orders-by-status'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/orders.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.orders'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('orders.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('orders.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.orders')]); ?></span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="<?php echo e(route('orders.registeration-forms')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('orders.registeration-forms')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.registration_forms'); ?></span>
                    </a>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('orders.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('orders.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.orders')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <!--end::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('orders.rate')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('orders.rate')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.Questionnaires', ['page_title' => __('dashboard.orders')]); ?></span>
                    </a>
                </div>


                <div class="menu-item">
                    <a class="menu-link <?php echo e(isActiveURLSegment('pending', 2)); ?>" title="<?php echo app('translator')->get('dashboard.pending_desc'); ?>"
                       href="<?php echo e(route('orders.index', ['status' => 'pending'])); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('apis.pending_orders'); ?></span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="<?php echo e(route('orders.index', ['status' => 'approved'])); ?>" title="<?php echo app('translator')->get('dashboard.approved_desc'); ?>"
                       class="menu-link py-3 <?php echo e(isActiveURLSegment('approved', 2)); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.delegate_accept_orders'); ?></span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="<?php echo e(route('orders.index', ['status' => 'canceled'])); ?>" title="<?php echo app('translator')->get('dashboard.canceled_desc'); ?>"
                       class="menu-link py-3 <?php echo e(isActiveURLSegment('canceled', 2)); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('apis.cancelled_orders'); ?></span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="<?php echo e(route('orders.index', ['status' => 'delayed'])); ?>" title="<?php echo app('translator')->get('dashboard.delayed_desc'); ?>"
                       class="menu-link py-3 <?php echo e(isActiveURLSegment('delayed', 2)); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.delayed_orders'); ?></span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="<?php echo e(route('orders.index', ['status' => 'completed'])); ?>"
                       title="<?php echo app('translator')->get('dashboard.completed_desc'); ?>"
                       class="menu-link py-3 <?php echo e(isActiveURLSegment('completed', 2)); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('apis.done_orders'); ?></span>
                    </a>
                </div>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>

    <?php if(Gate::allows('bank-accounts.index') || Gate::allows('expenses.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['bank-accounts.index', 'expenses.index'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['bank-accounts.index', 'expenses.index'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/payments.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.financial_system'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-accounts.index')): ?>
                        <!--begin::Menu item-->
                        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['bank-accounts.index', 'bank-accounts.create', 'payments.create', 'payments.edit', 'transactions.index', 'bank-accounts.edit', 'bank-accounts.show'])); ?>"
                             data-kt-menu-trigger="click">
                            <!--begin::Menu link-->
                            <a href="#"
                               class="menu-link py-3 <?php echo e(areActiveRoutes(['bank-accounts.index', 'bank-accounts.create', 'bank-accounts.edit'])); ?>">
                                <span class="menu-icon">
                                    <img src="<?php echo e(asset('images/bank-accounts.png')); ?>" style="width:25px;height:25px">
                                </span>
                                <span class="menu-title"><?php echo app('translator')->get('dashboard.bank-accounts'); ?></span>
                                <span class="menu-arrow"></span>
                            </a>
                            <!--end::Menu link-->
                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="<?php echo e(route('bank-accounts.index')); ?>"
                                       class="menu-link py-3  <?php echo e(isActiveRoute('bank-accounts.index')); ?>">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span
                                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.bank-accounts')]); ?></span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-accounts.create')): ?>
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="<?php echo e(route('bank-accounts.create')); ?>"
                                           class="menu-link py-3 <?php echo e(isActiveRoute('bank-accounts.create')); ?>">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span
                                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.bank-accounts')]); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.create')): ?>
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="<?php echo e(route('payments.transfer')); ?>"
                                            class="menu-link py-3 <?php echo e(isActiveRoute('payments.create')); ?>">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title"><?php echo app('translator')->get('dashboard.switch_accounts'); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <!--end::Menu item-->
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-accounts.index')): ?>
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="<?php echo e(route('transactions.index')); ?>"
                                           class="menu-link py-3 <?php echo e(isActiveRoute('transactions.index')); ?>">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span
                                                class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.transactions')]); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!--end::Menu sub-->
                        </div>
                        <!--end::Menu item-->
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expenses.index')): ?>
                        <!--begin::Menu item-->
                        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['expenses.index', 'expenses.edit', 'expenses.show', 'expenses.create'])); ?>"
                             data-kt-menu-trigger="click">
                            <!--begin::Menu link-->
                            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['expenses.index'])); ?>">
                                <span class="menu-icon">
                                    <img src="<?php echo e(asset('images/expenses.png')); ?>" style="width:25px;height:25px">
                                </span>
                                <span class="menu-title"><?php echo app('translator')->get('dashboard.expenses'); ?></span>
                                <span class="menu-arrow"></span>
                            </a>
                            <!--end::Menu link-->

                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="<?php echo e(route('expenses.index')); ?>"
                                       class="menu-link py-3 <?php echo e(isActiveRoute('expenses.index')); ?>">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span
                                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.expenses')]); ?></span>
                                    </a>
                                </div>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expenses.create')): ?>
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="<?php echo e(route('expenses.create')); ?>"
                                           class="menu-link py-3 <?php echo e(isActiveRoute('expenses.create')); ?>">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span
                                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.expenses')]); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!--end::Menu sub-->
                        </div>
                        <!--end::Menu item-->
                    <?php endif; ?>

                </div>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense-items.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['expense-items.index', 'expense-items.create', 'expense-items.edit', 'expense-items.show'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['expense-items.index'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/expense-items.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.expense-items'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('expense-items.index')); ?>"
                       class="menu-link py-3 <?php echo e(isActiveRoute('expense-items.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.expense-items')]); ?></span>
                    </a>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense-items.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('expense-items.create')); ?>"
                           class="menu-link py-3 <?php echo e(isActiveRoute('expense-items.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.expense-items')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>


    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  <?php echo e(areActiveRoutes(['payments.index', 'payments.create', 'payments.edit', 'payments.show', 'general_payments.index', 'general_payments.create'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['payments.index', 'general_payments.index'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/payments.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.payments'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('payments.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('payments.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.reservation_revenue'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('general_payments.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('general_payments.index')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.general_payments'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('payments.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('payments.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.payment')]); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion <?php echo e(areActiveRoutes(['tasks.index', 'tasks.create', 'tasks.edit', 'tasks.reports','employee.tasks', 'task-types.index', 'task-types.create', 'task-types.edit', 'task-types.show'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['tasks.index', 'tasks.create', 'tasks.edit', 'tasks.reports', 'task-types.index', 'task-types.create', 'task-types.edit', 'task-types.show'])); ?>">
            <span class="menu-icon">
                <img src="<?php echo e(asset('images/tasks.png')); ?>" style="width:25px;height:25px">
            </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.tasks'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('tasks.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('tasks.index')); ?>">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.tasks')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('tasks.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('tasks.create')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.add_title', ['page_title' => __('dashboard.tasks')]); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks.reports')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('tasks.reports')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('tasks.reports')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.task_reports'); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>

                <!-- Task Types -->
                <div class="menu-item">
                    <a href="<?php echo e(route('task-types.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('task-types.index')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.task_types'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks.index')): ?>
                    <!-- Employee Tasks -->
                    <div class="menu-item">
                        <a href="<?php echo e(route('employee.tasks')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('employee.tasks')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.my_tasks'); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('daily-reports.index')): ?>
        <div class="menu-item menu-sub-indention menu-accordion <?php echo e(areActiveRoutes(['daily-reports.index', 'daily-reports.create', 'daily-reports.edit', 'daily-reports.export'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['daily-reports.index', 'daily-reports.create', 'daily-reports.edit'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/daily-reports.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.daily_reports'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('daily-reports.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('daily-reports.index')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_daily_reports'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('daily-reports.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('daily-reports.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('daily-reports.create')); ?>">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.create_daily_report'); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
    <?php endif; ?>
    <?php if(Gate::allows('equipment.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion <?php echo e(areActiveRoutes([
        'equipment-directories.index',
        'equipment-directories.create',
        'equipment-directories.edit',
        'equipment-directories.items.index',
        'equipment-directories.items.create',
        'equipment-directories.items.edit',
    ])); ?>" data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes([
            'equipment-directories.index',
            'equipment-directories.create',
            'equipment-directories.edit',
            'equipment-directories.items.index',
            'equipment-directories.items.create',
            'equipment-directories.items.edit',
        ])); ?>">
            <span class="menu-icon">
                <img src="<?php echo e(asset('images/equipments.png')); ?>" style="width:25px;height:25px">
            </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.equipment_directories'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('equipment-directories.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('equipment-directories.index')); ?>">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_directories'); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if(Gate::allows('equipment.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('equipment-directories.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('equipment-directories.create')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.create_directory'); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('camp-reports.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion <?php echo e(areActiveRoutes(['camp-reports.index', 'camp-reports.create', 'camp-reports.edit', 'camp-reports.show'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['camp-reports.index', 'camp-reports.create', 'camp-reports.edit', 'camp-reports.show'])); ?>">
            <span class="menu-icon">
                <img src="<?php echo e(asset('images/camps.png')); ?>" style="width:25px;height:25px">
            </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.camp_reports'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('camp-reports.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('camp-reports.index')); ?>">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.camp_reports')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('camp-reports.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('camp-reports.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('camp-reports.create')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.camp_report')]); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('meetings.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion <?php echo e(areActiveRoutes(['meetings.index', 'meetings.create', 'meetings.edit', 'meetings.show','meeting-locations.index','meeting-locations.create','meeting-locations.edit'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['meetings.index', 'meetings.create', 'meetings.edit','meeting-locations.index','meeting-locations.create','meeting-locations.edit'])); ?>">
            <span class="menu-icon">
                <img src="<?php echo e(asset('images/meetings.png')); ?>" style="width:25px;height:25px">
            </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.meetings'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('meeting-locations.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('meeting-locations.index')); ?>">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.meeting_locations')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('meetings.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('meetings.index')); ?>">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.meetings')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('meetings.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('meetings.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('meetings.create')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.meeting')]); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('violations.index')): ?>
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion <?php echo e(areActiveRoutes(['violations.index', 'violations.create', 'violations.edit','violation-types.index', 'violation-types.create', 'violation-types.edit'])); ?>"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 <?php echo e(areActiveRoutes(['violations.index', 'violations.create', 'violations.edit','violation-types.index', 'violation-types.create', 'violation-types.edit'])); ?>">
            <span class="menu-icon">
                <img src="<?php echo e(asset('images/violations.png')); ?>" style="width:25px;height:25px">
            </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.violations'); ?></span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('violation-types.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('violation-types.index')); ?>">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.violation_types')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="<?php echo e(route('violations.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('violations.index')); ?>">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title"><?php echo app('translator')->get('dashboard.all_title', ['page_title' => __('dashboard.violations')]); ?></span>
                    </a>
                </div>
                <!--end::Menu item-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('violations.create')): ?>
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="<?php echo e(route('violations.create')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('violations.create')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title"><?php echo app('translator')->get('dashboard.add_title', ['page_title' => __('dashboard.violation')]); ?></span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                <?php endif; ?>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reprots')): ?>
        <div class="menu-item">
            <!--begin::Menu link-->
            <a href="<?php echo e(route('reprots')); ?>" class="menu-link py-3 <?php echo e(areActiveRoutes(['reprots'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/reprots.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.financial_reports'); ?></span>
            </a>
            <!--end::Menu link-->
        </div>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('statistics.index')): ?>
        <div class="menu-item">
            <a href="<?php echo e(route('statistics.index')); ?>" class="menu-link py-3" title="<?php echo app('translator')->get('dashboard.statistics'); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/roles.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.statistics'); ?></span>
            </a>
        </div>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('scheduling.index')): ?>
        <div class="menu-item">
            <!--begin::Menu link-->
            <a href="<?php echo e(route('calender')); ?>" class="menu-link py-3 <?php echo e(areActiveRoutes(['calender'])); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/calender.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.calender'); ?></span>
            </a>
            <!--end::Menu link-->
        </div>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('terms-settings.index')): ?>
        <div class="menu-item">
            <!--begin::Menu link-->
            <a href="<?php echo e(route('terms_sittngs.index')); ?>" class="menu-link py-3 <?php echo e(isActiveRoute('terms_sittngs.index')); ?>">
                <span class="menu-icon">
                    <img src="<?php echo e(asset('images/rules.png')); ?>" style="width:25px;height:25px">
                </span>
                <span class="menu-title"><?php echo app('translator')->get('dashboard.terms_setting'); ?></span>
            </a>
            <!--end::Menu link-->
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/layouts/menu.blade.php ENDPATH**/ ?>