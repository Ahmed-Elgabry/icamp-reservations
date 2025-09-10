

 <div class="row justify-content-center">
     <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                        <form action="?" method="GET" class="form-inline row">
                            <div class="col-md-4 mb-3">
                                <label for="expense_item_id" class="form-label text-right d-block">
                                     <?php echo e(__('dashboard.expense-item')); ?>

                                </label>
                                <select name="expense_item_id" id="expense_item_id" class="form-select select2-search">
                                    <option value=""><?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.expense-items')); ?></option>
                                    <?php if(isset($expenseItems)): ?>
                                        <?php $__currentLoopData = $expenseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expenseItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($expenseItem->id); ?>" <?php echo e(request('expense_item_id') == $expenseItem->id ? 'selected' : ''); ?>>
                                                <?php echo e($expenseItem->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group mb-2 col-md-4">
                                <label for="start_date" class="text-right d-block"><?php echo app('translator')->get('dashboard.date_from'); ?></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="<?php echo app('translator')->get('dashboard.date_from'); ?>" value="<?php echo e(request('start_date')); ?>">
                            </div>
                            <div class="form-group mb-2 col-md-4">
                                <label for="end_date" class="text-right d-block"><?php echo app('translator')->get('dashboard.date_to'); ?></label>
                                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="إلى تاريخ" value="<?php echo e(request('end_date')); ?>">
                            </div>
                            <button type="submit" class="mt-5 btn btn-primary mb-2" style="width:20%">
                            <?php echo app('translator')->get('dashboard.search'); ?> <i class="fa fa-search"></i>
                            </button>
                            <?php if(count(request()->all())): ?>
                                <a href="?"><?php echo app('translator')->get('dashboard.showall'); ?></a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

  <!--begin::Card header-->
  <div class="card-header align-items-center py-5 gap-2 gap-md-5 p-0">



 <!--begin::Card body-->
 <div class="card-body p-0">
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
                            <th class=""><?php echo app('translator')->get('dashboard.date'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.price'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.source'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.payment_method'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.notes'); ?></th>
                            <th class=" min-w-70px"><?php echo app('translator')->get('dashboard.actions'); ?></th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!--begin::Table row-->
                        <tr data-id="<?php echo e($expense->id); ?>">
                            <!--begin::Checkbox-->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input checkSingle" type="checkbox" value="1" id="<?php echo e($expense->id); ?>"/>
                                </div>
                            </td>
                            <td><?php echo e($expense->date); ?> </td>
                            <td><?php echo e($expense->price); ?> </td>
                            <?php if($expense->source === 'reservation_expenses'): ?>
                                <td><?php echo e(__('dashboard.reservations')); ?>-<?php echo e($expense->order->id); ?> </td>
                            <?php elseif($expense->source === 'general_expenses'): ?>
                            <td><?php echo e(__('dashboard.general')); ?></td>
                            <?php endif; ?>
                                <td><?php echo e($expense->payment_method ? __('dashboard.' . $expense->payment_method) : __('dashboard.not_specified')); ?> </td>
                                <td><?php echo e($expense->notes); ?> </td>

                                <!--begin::Action=-->
                                <!--end::Action=-->
                            </tr>
                            <!--end::Table row-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
                <div class="mt-4"><?php echo e($expenses->onEachSide(1)->links('pagination::bootstrap-5')); ?></div>
            </div>

<?php $__env->startPush('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Make pagination next/prev icons smaller */
.pagination .page-link svg { width: 14px; height: 14px; }
.pagination .page-link { padding: .35rem .6rem; }

.select2-container .select2-selection--single {
    height: 38px !important;
    padding: 6px 12px;
    border: 1px solid #d1d3e2;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
}

<?php if(app()->getLocale() == 'ar' || app()->getLocale() == 'ps'): ?>
.select2-container--default .select2-selection--single {
    text-align: right;
    direction: rtl;
}

.select2-dropdown {
    direction: rtl;
}

.select2-results__option {
    text-align: right;
}
<?php endif; ?>
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for expense items dropdown
    $('#expense_item_id').select2({
        placeholder: "<?php echo e(__('dashboard.select')); ?> <?php echo e(__('dashboard.expense-items')); ?>",
        allowClear: true,
        width: '100%',
        language: "<?php echo e(app()->getLocale()); ?>",
        dir: "<?php echo e(app()->getLocale() == 'ar' || app()->getLocale() == 'ps' ? 'rtl' : 'ltr'); ?>"
    });

    // Custom styling for RTL languages
    <?php if(app()->getLocale() == 'ar' || app()->getLocale() == 'ps'): ?>
    $('body').on('select2:open', '#expense_item_id', function() {
        $('.select2-dropdown').css('direction', 'rtl');
        $('.select2-search__field').css('text-align', 'right');
    });
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/expenses/table.blade.php ENDPATH**/ ?>