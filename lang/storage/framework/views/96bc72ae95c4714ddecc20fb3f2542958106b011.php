
<?php $__env->startSection('pageTitle' , __('dashboard.create_title', ['page_title' => __('dashboard.expense-items')])); ?>

<?php $__env->startSection('content'); ?>


    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
       
    
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">

            <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
                            <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#product_details"> بيانات المصاريف </a>
                </li>

            <!--end:::Tab item-->
            </ul>
                <hr>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="product_details" role="tab-panel">
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_product_form" action="<?php echo e(isset($expense) ?  route('expenses.update',$expense->id) : route('expenses.store')); ?>" method="POST"
                        class="form d-flex flex-column flex-lg-row store" data-kt-redirect="<?php echo e(route('expenses.index')); ?>" enctype='multipart/form-data'>
                        <?php echo csrf_field(); ?>

                        <?php if(isset($expense)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::Tab content-->
                            <div class="tab-content">
                                <!--begin::Tab pane-->
                                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_ar" role="tab-panel">
                                    <div class="d-flex flex-column gap-7 gap-lg-10">
                                        <!--begin::General options-->
                                        <div class="card card-flush py-4">

                                            <!--begin::Card body-->
                                            <div class="card-body pt-10 row">

                                                <div class="row">

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="account_id" class="required"><?php echo e(__('dashboard.bank_account')); ?></label>
                                                        <select name="account_id" id="account_id" class="form-control" required>
                                                            <option value=""><?php echo e(__('dashboard.choose_bank_account')); ?></option>
                                                            <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option
                                                                        <?php if(isset($expense) && $expense->account_id): ?>
                                                                            <?php echo e($expense->account_id == $bank->id ? 'selected' : ''); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(old('account_id') == $bank->id ? 'selected' : ''); ?>

                                                                        <?php endif; ?>
                                                                value="<?php echo e($bank->id); ?>"><?php echo e($bank->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="expense_item_id" class="required"><?php echo e(__('dashboard.expense_item')); ?></label>
                                                        <select name="expense_item_id" id="expense_item_id" class="form-control" required>
                                                            <option selected disabled><?php echo e(__('dashboard.select')); ?></option>
                                                            <?php $__currentLoopData = $expenseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expenseItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option
                                                                        <?php if(isset($expense) && $expense->expense_item_id): ?>
                                                                            <?php echo e($expense->expense_item_id == $expenseItem->id ? 'selected' : ''); ?>

                                                                        <?php elseif(request()->has('expenseItem') && request()->get('expenseItem') == $expenseItem->id): ?>
                                                                            selected
                                                                        <?php else: ?>
                                                                            <?php echo e(old('expense_item_id') == $expenseItem->id ? 'selected' : ''); ?>

                                                                        <?php endif; ?>
                                                                value="<?php echo e($expenseItem->id); ?>"><?php echo e($expenseItem->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="payment_method" class="required"><?php echo e(__('dashboard.payment_method')); ?></label>
                                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                                            <option value=""><?php echo e(__('dashboard.choose_payment_method')); ?></option>
                                                            <?php $__currentLoopData = paymentMethod(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentSelect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option
                                                                        <?php if(isset($expense) && $expense->payment_method): ?>
                                                                            <?php echo e($expense->payment_method == $paymentSelect ? 'selected' : ''); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(old('payment_method') == $paymentSelect ? 'selected' : ''); ?>

                                                                        <?php endif; ?>
                                                                value="<?php echo e($paymentSelect); ?>"><?php echo e(__('dashboard.'. $paymentSelect )); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="date" class="required"><?php echo e(__('dashboard.expense_date')); ?></label>
                                                        <input type="date" name="date" id="date" class="form-control" value="<?php echo e(isset($expense) ? $expense->date : (old('date') ? old('date') : date('Y-m-d'))); ?>" required>
                                                    </div>

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="image"><?php echo app('translator')->get('dashboard.upload_or_take_image'); ?></label>
                                                        <input type="file" name="image" id="image"
                                                            class="form-control"
                                                            accept="image/*"
                                                            capture="environment">
                                                    </div>

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="price" class="required"><?php echo e(__('dashboard.amount')); ?></label>
                                                        <input type="number" step="any" name="price" id="price" class="form-control" required value="<?php echo e(isset($expense) ? $expense->price : old('price')); ?>">
                                                    </div>

                                                    <div class="form-group col-12 mt-5">
                                                        <label for="notes"><?php echo e(__('dashboard.notes')); ?></label>
                                                        <textarea name="description" id="notes" class="form-control"><?php echo e(isset($expense) ? $expense->notes : old('notes')); ?></textarea>
                                                    </div>
                                                </div>


                                            </div>
                                            <!--end::Card header-->
                                        </div>
                                        <!--end::General options-->
                                    </div>
                                </div>
                                <!--end::Tab pane-->
                            </div>
                            <!--end::Tab content-->
                            <div class="d-flex justify-content-end">
                                <!--begin::Button-->
                                <a href="<?php echo e(isset($expense) ? route('expenses.edit',$expense->id) : route('expenses.index')); ?>" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-light me-5"><?php echo app('translator')->get('dashboard.cancel'); ?></a>
                                <!--end::Button-->

                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                    <span class="indicator-label"><?php echo app('translator')->get('dashboard.save_changes'); ?></span>
                                    <span class="indicator-progress"><?php echo app('translator')->get('dashboard.please_wait'); ?>
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                        </div>
                        <!--end::Main column-->
                    </form>
                    <!--end::Form-->
                </div>

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
        .nav-line-tabs
        {
            margin-bottom:20px !important;
        }

        
        
    </style>
<?php $__env->stopPush(); ?> 
<?php $__env->startPush('js'); ?>
    <script>
        $("#select2").select2();

        $(document).ready(function() {
        // when load page check the value
        toggleSubProductFields();

        // when change type render toggle function
        $('#type').change(function() {
            toggleSubProductFields();
        });

        function toggleSubProductFields() {
            if ($('#type').val() === 'main') {
                $('#sub-product-fields').removeClass('d-none');
                $('#select2').prop('required', true);
                $('#sub_count').prop('required', true);
            } else {
                $('#sub-product-fields').addClass('d-none');
                $('#select2').prop('required', false);
                $('#sub_count').prop('required', false);
            }
        }
    });


    $("#expense_item_id").select2();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/expense_items/create.blade.php ENDPATH**/ ?>