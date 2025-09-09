
<?php $__env->startSection('pageTitle' , __('dashboard.expense-items')); ?>

<?php $__env->startSection('content'); ?>


<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Category-->
        <div class="card card-flush">
           
            
          
               
           
                    <!--begin::Category-->
         <div class="card card-flush mt-10">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="<?php echo app('translator')->get('dashboard.search_title', ['page_title' => __('dashboard.expense-items')]); ?>" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense-items.create')): ?>
                    <!--begin::Add customer-->
                    <a href="<?php echo e(route('expense-items.create')); ?>" class="btn btn-success">
                        <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.expense-items')]); ?>
                        <i class="fa fa-plus"></i>
                    </a>
                    <!--end::Add customer-->
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
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3" >
                                    <input class="form-check-input" id="checkedAll"  type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class=""><?php echo app('translator')->get('dashboard.name'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.number_of_times'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.notes'); ?></th>
                            <th class="text-end min-w-70px"><?php echo app('translator')->get('dashboard.actions'); ?></th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        <?php $__currentLoopData = $expenseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--begin::Table row-->
                            <tr data-id="<?php echo e($item->id); ?>">
                                <!--begin::Checkbox-->
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input checkSingle" type="checkbox" value="1" id="<?php echo e($item->id); ?>"/>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <span ><?php echo e($item->name); ?></a>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($item->expenses->count()); ?> </td>
                                <td><?php echo e($item->description); ?> </td>
                           
                                <!--begin::Action=-->
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"><?php echo app('translator')->get('dashboard.actions'); ?>
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon--></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense-items.show')): ?>
                                        <div class="menu-item px-3">
                                            <a href="<?php echo e(route('expense-items.show', $item->id)); ?>" class="menu-link px-3"><?php echo app('translator')->get('dashboard.show'); ?></a>
                                        </div>
                                        <?php endif; ?> 
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense-items.edit')): ?>
                                        <div class="menu-item px-3">
                                            <a href="<?php echo e(route('expense-items.edit', $item->id)); ?>" class="menu-link px-3"><?php echo app('translator')->get('dashboard.edit'); ?></a>
                                        </div>
                                        <?php endif; ?> 
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense-items.destroy')): ?>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-url="<?php echo e(route('expense-items.destroy', $item->id)); ?>" data-id="<?php echo e($item->id); ?>"><?php echo app('translator')->get('dashboard.delete'); ?></a>
                                        </div>
                                        <?php endif; ?> 
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                                <!--end::Action=-->
                            </tr>
                            <!--end::Table row-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->

            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
					
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css'); ?>
<style>
    .total-price-h {
        padding: 20px;
    background: #fffcfc;
    width: 30%;
    text-align: center;
    border-radius: 5%;
    border: 1px solid #000;
    }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/expense_items/index.blade.php ENDPATH**/ ?>