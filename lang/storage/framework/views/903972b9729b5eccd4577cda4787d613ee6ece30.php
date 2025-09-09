
<?php $__env->startSection('pageTitle' , __('dashboard.payments')); ?>

<?php $__env->startSection('content'); ?>



<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

        <?php echo $__env->make('dashboard.orders.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!--begin::Category-->
        <div class="card card-flush">
            <!-- customer information -->
                <div class="pt-5 px-9 gap-2 gap-md-5">
                    <div class="row g-3 small">
                        <div class="col-md-1 text-center">
                            <div class="fw-semibold text-muted"><?php echo e(__('dashboard.order_id')); ?></div>
                            <div class="fw-bold"><?php echo e($order->id); ?></div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="fw-semibold text-muted"><?php echo e(__('dashboard.customer_name')); ?></div>
                            <div class="fw-bold"><?php echo e($order->customer->name); ?></div>
                        </div>
                    </div>
                </div>
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
                        <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="<?php echo app('translator')->get('dashboard.search_title', ['page_title' => __('dashboard.payments')]); ?>" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->


                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Add customer-->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.create')): ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewCount">
                            <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.payments')]); ?>
                    </button>
                    <?php endif; ?>
                    <!--end::Add customer-->
                    <span class="w-5px h-2px"></span>

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
                    <th class=""><?php echo e(__('dashboard.statement')); ?></th>
                    <th><?php echo e(__('dashboard.price')); ?></th>
                    <th class=""><?php echo e(__('dashboard.payment_method')); ?></th>
                    <th class=""><?php echo e(__('dashboard.bank_account')); ?></th>
                    <th class=""><?php echo e(__('dashboard.verified')); ?></th>
                    <th class=""><?php echo e(__('dashboard.notes')); ?></th>
                    <th class=""><?php echo e(__('dashboard.created_at')); ?></th>
                    <th class="text-end min-w-70px"><?php echo app('translator')->get('dashboard.actions'); ?></th>
                </tr>
                <!--end::Table row-->
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody class="fw-bold text-gray-600">
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!--begin::Table row-->
                    <tr data-id="<?php echo e($payment->id); ?>">
                        <!--begin::Checkbox-->
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input checkSingle" type="checkbox" value="1" id="<?php echo e($payment->id); ?>"/>
                            </div>
                        </td>
                        <!--begin::Category=-->
                        <td><?php echo e(__('dashboard.'. $payment->statement )); ?></td>
                        <td>
                            <div class="d-flex">
                                <!--end::Thumbnail-->
                                <div class="ms-5">
                                    <!--begin::Title-->
                                    <a href="#"
                                    data-kt-ecommerce-category-filter="search"
                                     class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1"
                                   ><?php echo e($payment->price); ?></a>
                                    <!--end::Title-->
                                </div>
                            </div>
                        </td>

                        <td><?php echo e(__('dashboard.'. $payment->payment_method )); ?></td>
                        <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($payment->account_id == $bankAccount->id): ?>
                            <td><?php echo e(__($bankAccount->name )); ?></td>
    
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <td>
                            <?php echo e($payment->verified ? __('dashboard.yes') : __('dashboard.no')); ?> <br>
                              <?php if($payment->verified): ?>
                                    <a href="<?php echo e(route('order.verified' , [$payment->id , 'payment'])); ?>" class="btn btn-sm btn-danger" ><?php echo e(__('dashboard.mark')); ?> <?php echo e(__('dashboard.unverifyed')); ?></a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('order.verified' , [$payment->id , 'payment'])); ?>" class="btn btn-sm btn-success"><?php echo e(__('dashboard.mark')); ?> <?php echo e(__('dashboard.verified')); ?></a>
                                <?php endif; ?>
                        </td>
                        <td  data-kt-ecommerce-category-filter="category_name" >
                            <?php echo e($payment->notes); ?>

                        </td>
                        <td>
                           <?php echo e($payment->created_at->diffForHumans()); ?>

                        </td>
                        <!--end::Category=-->
                        <!--begin::Action=-->
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <?php echo e(__('dashboard.actions')); ?>

                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                            <span class="svg-icon svg-icon-5 m-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon--></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="#"
                                       class="menu-link px-3 payment-receipt-link"
                                       data-verified="<?php echo e($payment->verified ? '1' : '0'); ?>"
                                       data-url="<?php echo e(route('payments.receipt', ['order' => $order->id, 'payment' => $payment->id])); ?>">
                                        <?php echo e(__('dashboard.receipt')); ?>

                                    </a>
                                </div>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.edit')): ?>
                                <div class="menu-item px-3">
                                    <a href="#" type="button" data-toggle="modal" data-target="#editCount-<?php echo e($payment->id); ?>" class="menu-link px-3"><?php echo e(__('actions.edit')); ?></a>
                                </div>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.destroy')): ?>
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-url="<?php echo e(route('payments.destroy', $payment->id)); ?>" data-id="<?php echo e($payment->id); ?>"> <?php echo app('translator')->get('dashboard.delete'); ?></a>
                                </div>
                                <?php endif; ?>
                            <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </td>
                        <!--end::Action=-->
                    </tr>
                    <!--end::Table row-->

                    <!-- Modal -->
                    <div class="modal fade" id="editCount-<?php echo e($payment->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editCount-<?php echo e($payment->id); ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addNewCoutLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?php echo e(route('payments.update',$payment->id)); ?>" id="editCountForm<?php echo e($payment->id); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="mb-5 fv-row col-md-12">
                                    <label class="required form-label"><?php echo e(__('dashboard.statement')); ?></label>
                                    <select name="statement" id="" class="form-select" required>
                                        <?php $__currentLoopData = statements(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e($payment->statement == $statement ? 'selected' : ''); ?> value="<?php echo e($statement); ?>"><?php echo e(__('dashboard.'. $statement )); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                
                                <!--begin::Card body-->
                                 <input type="hidden" value="reservation_payments" name="source">

                                <div class="mb-5 fv-row col-md-12">
                                    <label class="required form-label"><?php echo e(__('dashboard.price')); ?></label>
                                    <input type="number" name="price" id="price" value="<?php echo e($payment->price); ?>"
                                        class="form-control mb-2" required
                                        value="" />
                                </div>
                                <div class="mb-5 fv-row col-md-12">
                                    <label class="required form-label"><?php echo e(__('dashboard.payment_method')); ?></label>
                                    <select name="payment_method" id="" class="form-select" required>
                                        <?php $__currentLoopData = paymentMethod(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentSelect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e($payment->payment_method == $paymentSelect ? 'selected' : ''); ?> value="<?php echo e($paymentSelect); ?>"><?php echo e(__('dashboard.'. $paymentSelect )); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="mb-5 fv-row col-md-12">
                                    <label class="required form-label"><?php echo e(__('dashboard.bank_account')); ?></label>
                                    <select name="account_id" id="" class="form-select" required>
                                        <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e($payment->account_id == $bankAccount->id ? 'selected' : ''); ?> value="<?php echo e($bankAccount->id); ?>"><?php echo e($bankAccount->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <!--begin::Input group-->
                                <div class="mb-5 fv-row col-md-12">
                                    <label class="form-label"><?php echo e(__('dashboard.notes')); ?></label>
                                    <textarea name="notes" id="" class="form-control mb-2"><?php echo e($payment->notes); ?></textarea>
                                </div>
                                <!--end::Input group-->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" form="editCountForm<?php echo e($payment->id); ?>" class="btn btn-primary"><?php echo app('translator')->get('dashboard.save_changes'); ?></button>
                        </div>
                        </div>
                    </div>
                    </div>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <!--end::Table body-->
        </table>

        <!-- Modal -->
<div class="modal fade" id="addNewCount" tabindex="-1" role="dialog" aria-labelledby="addNewCountLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewCountLabel"> <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.payments')]); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo e(route('payments.store')); ?>" id="saveCountDetails" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <!--begin::Input group-->
            <input type="hidden" value="<?php echo e($order->id); ?>" name="order_id">
            <input type="hidden" value="reservation_payments" name="source">
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label"><?php echo e(__('dashboard.statement')); ?></label>
                <select name="statement" id="" class="form-select" required>
                    <?php $__currentLoopData = statements(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($statement); ?>"><?php echo e(__('dashboard.'. $statement )); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label"><?php echo e(__('dashboard.price')); ?></label>
                <input type="number" name="price" id="price" value="<?php echo e(old('price')); ?>"
                    class="form-control mb-2" required
                    value="" />
            </div>
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label"><?php echo e(__('dashboard.payment_method')); ?></label>
                <select name="payment_method" id="" class="form-select" required>
                    <?php $__currentLoopData = paymentMethod(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentSelect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($paymentSelect); ?>"><?php echo e(__('dashboard.'. $paymentSelect )); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label"><?php echo e(__('dashboard.bank_account')); ?></label>
                <select name="account_id" id="" class="form-select" required>
                    <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php echo e(old('account_id') == $bankAccount->id ? 'selected' : ''); ?> value="<?php echo e($bankAccount->id); ?>"><?php echo e($bankAccount->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!--begin::Input group-->
            <div class="mb-5 fv-row col-md-12">
                <label class=" form-label"><?php echo e(__('dashboard.notes')); ?></label>
                <textarea name="notes" id="" class="form-control mb-2"><?php echo e(old('notes')); ?></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="saveCountDetails" class="btn btn-primary"><?php echo app('translator')->get('dashboard.save_changes'); ?></button>
      </div>
    </div>
  </div>
</div>


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
        $(document).on('click', '.payment-receipt-link', function(e) {
            e.preventDefault();

            if ($(this).data('verified') == '1') {
                window.open($(this).data('url'), '_blank');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo e(__("dashboard.error")); ?>',
                    text: '<?php echo e(__("dashboard.payment_not_verified_receipt_error")); ?>',
                    confirmButtonText: '<?php echo e(__("dashboard.ok")); ?>'
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/payments/show.blade.php ENDPATH**/ ?>