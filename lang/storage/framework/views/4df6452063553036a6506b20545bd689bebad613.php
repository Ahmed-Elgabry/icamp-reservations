

<?php $__env->startSection('pageTitle' , __('dashboard.payments')); ?>

<?php $__env->startSection('content'); ?>

<!-------------->

<div class="post d-flex flex-column-fluid" id="kt_post">

    <!--begin::Container-->

    <div id="kt_content_container" class="container-xxl">

        <!--begin::Basic info-->

        <div class="card mb-5 mb-xl-10">

            <!--begin::Card header-->

            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

                <!--begin::Card title-->

                <div class="card-title m-0">

                    <h3 class="fw-bolder m-0"><?php echo e(__('dashboard.transfer')); ?></h3>

                </div>

                <!--end::Card title-->

            </div>

            <!--begin::Card header-->

            <!--begin::Content-->

            <!-- <div id="kt_account_settings_profile_details" class="collapse show">

                <form id="kt_ecommerce_add_product_form" 

                      data-kt-redirect="<?php echo e(route('payments.transfer')); ?>" 

                      action="<?php echo e(route('money-transfer')); ?>" 

                      method="post" enctype="multipart/form-data" 

                      class="form d-flex flex-column flex-lg-row store"> -->
                <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_ecommerce_add_product_form" 
                      data-kt-redirect="<?php echo e(route('payments.transfer')); ?>" 
                      action="<?php echo e(route('money-transfer')); ?>" 
                      method="post" enctype="multipart/form-data" 
                      class="form d-flex flex-column flex-lg-row store">
                      
                      <?php echo csrf_field(); ?> 
                      
                      <div class="card-body border-top p-9">
                          
                          <!-- Input group for Total amount -->
                          
                          <div class="row mb-5">
                            <div class="row mb-5">
                                <div class="col-6">
                                    <label class="col-lg-12 col-form-label fw-bold fs-6 required"><?php echo e(__('dashboard.from_account')); ?></label>
                                    <div class="col-lg-12">
                                        <select name="sender_account_id" id="account_id" class="form-select form-select-lg form-select-solid mb-3 mb-lg-0" required>
                                            <option value=""><?php echo e(__('dashboard.choose_from_account')); ?></option>
                                            <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($bankAccount->id); ?>" data-currency="<?php echo e($bankAccount->currency); ?>"
                                                <?php echo e(isset($payment) && $payment->account_id == $bankAccount->id ? 'selected' : ''); ?>><?php echo e($bankAccount->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="col-lg-12 col-form-label fw-bold fs-6 required"><?php echo e(__('dashboard.to_account')); ?></label>
                                    <div class="col-lg-12">
                                    <select name="receiver_id" id="receiver_id" class="form-select form-select-lg form-select-solid mb-3 mb-lg-0" required>
                                            <option value=""><?php echo e(__('dashboard.choose_to_account')); ?></option>
                                            <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($bankAccount->id); ?>" data-currency="<?php echo e($bankAccount->currency); ?>"
                                                <?php echo e(isset($payment) && $payment->receiver_id == $bankAccount->id ? 'selected' : ''); ?>><?php echo e($bankAccount->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">

                                <label class="col-lg-12 col-form-label fw-bold fs-6 required"><?php echo e(__('dashboard.amount')); ?></label>

                                <div class="col-lg-12">

                                    <input step="0.01" type="number" name="amount" id="amount" value="<?php echo e(isset($payment) ? $payment->amount : ''); ?>" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>

                                </div>

                            </div>

                            <div class="col-6">

                                <label class="col-lg-12 col-form-label fw-bold fs-6 required"><?php echo e(__('dashboard.date')); ?></label>

                                <div class="col-lg-12">

                                    <input type="date" name="date" id="date" value="<?php echo e(isset($payment) ? $payment->date : date('Y-m-d')); ?>" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>

                                </div>

                            </div>

                        </div>




                        <input type="hidden" name="type" value="transfer">

                        <!-- Notes input -->

                        <div class="row mb-6">

                            <label class="col-lg-12 col-form-label fw-bold fs-6"><?php echo e(__('dashboard.notes')); ?></label>

                            <div class="col-lg-12">

                                <textarea name="description" class="form-control form-control-lg form-control-solid" placeholder="<?php echo e(__('dashboard.notes')); ?>"><?php echo e(isset($payment) ? $payment->description : old('description')); ?></textarea>

                                <div class="fv-plugins-message-container invalid-feedback"></div>

                            </div>

                        </div>



                        <!--begin::Actions-->

                        <div class="d-flex justify-content-end">

                            <!--begin::Button-->

                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">

                                <span class="indicator-label"><?php echo e(__('dashboard.save_changes')); ?></span>

                                <span class="indicator-progress"><?php echo e(__('dashboard.please_wait')); ?>


                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>

                            </button>

                            <!--end::Button-->

                        </div>

                        <!--end::Actions-->

                    </div>

                </form>

            </div>

            <!--end::Content-->

        </div>

        <!--end::Basic info-->

    </div>

    <!--end::Container-->

</div>

<?php $__env->stopSection(); ?>



<?php $__env->startPush('js'); ?>

    <script>
        $(function(){
            var $receiver = $('#receiver_id');
            if ($receiver.length && $receiver.is('select')) {
                $receiver.select2({ width: '100%' });
            }

            var $account = $('#account_id');
            if ($account.length && $account.is('select')) {
                $account.select2({ width: '100%' });
            }
        });
    </script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/payments/transfer.blade.php ENDPATH**/ ?>