

<?php $__env->startSection('pageTitle', __('dashboard.accept_terms')); ?>
<?php $__env->startSection('content'); ?>

    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <?php echo $__env->make('dashboard.orders.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h3 class="card-title"><?php echo e(__('dashboard.accept_terms')); ?></h3>
                    <?php if(isset($order->signature_path)): ?>  
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders.destroy')): ?>
                            <div class="mt-3">
                                 <form id="kt_ecommerce_add_product_form" class="d-inline store" action="<?php echo e(route('signature.destroy', $order)); ?>" method="post" data-success-message="<?php echo app('translator')->get('dashboard.deleted_successfully'); ?>" data-kt-redirect="<?php echo e(request()->fullUrl()); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-danger btn-sm text-white d-inline-flex align-items-center gap-1">
                                        <i class="fa fa-trash"></i>
                                        <?php echo e(__('dashboard.delete')); ?>

                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div class="card-body pt-0">

                    <?php if(isset($order)): ?>
                        <div class="row mb-6">

                                <div class="d-block">
                                    <div class="border rounded p-3 bg-light-subtle">
                                        <div class="row g-3 small">
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted"><?php echo e(__('dashboard.order_id')); ?></div>
                                                <div class="fw-bold"><?php echo e($order->id); ?></div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted"><?php echo e(__('dashboard.customer_name')); ?></div>
                                                <div class="fw-bold"><?php echo e($order->customer->name); ?></div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted"><?php echo e(__('dashboard.phone')); ?></div>
                                                <div class="fw-bold"><?php echo e($order->customer->phone); ?></div>
                                            </div>
                                        </div>
                                    </div>
                            
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <?php echo app('translator')->get('dashboard.Customer_Signature'); ?>
                            </label>

                            <div class="col-lg-8 d-flex flex-column gap-3 mb-2">
                                <?php if(isset($order->signature_path)): ?>
                                    <div class="text-success fw-bold">
                                        <?php echo e($order?->signature); ?>

                                    </div>
                                    <img src="<?php echo e(Storage::url($order->signature_path)); ?>" alt="Signature" style="max-height:80px;">
                                <?php else: ?>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control"
                                                value="<?php echo e(route('signature.show', $order)); ?>"
                                                readonly
                                                onclick="this.select();document.execCommand('copy');">
                                            <button type="button" class="btn btn-outline-primary"
                                                    onclick="navigator.clipboard.writeText('<?php echo e(route('signature.show', $order)); ?>')">
                                                Copy Link
                                            </button>
                                        </div>
                                        <small class="text-muted"><?php echo app('translator')->get('dashboard.desc_Customer_Signature'); ?></small>
                                <?php endif; ?>
                            </div>

                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <?php echo app('translator')->get('dashboard.terms'); ?>
                            </label>

                            <div class="col-lg-8">
                                <?php
                                    $locale = app()->getLocale();
                                    $field = 'commercial_license_' . ($locale === 'ar' ? 'ar' : 'en');
                                    $termsHtml = $termsSittng->{$field} ?? ($termsSittng->commercial_license_ar ?? $termsSittng->commercial_license_en ?? '');
                                    $plainLength = Str::length(strip_tags($termsHtml));
                                ?>

                                <div class="terms-view border rounded p-3" dir="<?php echo e($locale === 'ar' ? 'rtl' : 'ltr'); ?>">
                                    <?php echo $termsHtml; ?>

                                </div>

                                <small class="text-muted d-block mt-1">
                                    <?php echo e($plainLength); ?> <?php echo app('translator')->get('dashboard.characters'); ?>
                                </small>
                            </div>

                            <?php if(app()->getLocale() === 'ar'): ?>
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <?php echo app('translator')->get('dashboard.terms'); ?> (English)
                                </label>
                                <div class="col-lg-8">
                                    <?php
                                        $termsHtmlEn = $termsSittng->commercial_license_en ?? '';
                                        $plainLengthEn = Str::length(strip_tags($termsHtmlEn));
                                    ?>
                                    <div class="terms-view border rounded p-3" dir="ltr">
                                        <?php echo $termsHtmlEn; ?>

                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <?php echo e($plainLengthEn); ?> <?php echo app('translator')->get('dashboard.characters'); ?>
                                    </small>
                                </div>
                            <?php endif; ?>

                            <label class="col-lg-4 col-form-label fw-bold fs-6 mb-2">
                                <?php echo app('translator')->get('dashboard.additional_notes'); ?>
                            </label>

                            <div class="col-lg-8">

                                <form method="POST" action="<?php echo e(route('orders.updateNotes', $order)); ?>" class="row g-3">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>

                                        <textarea
                                            id="termsAdditionalNotes"
                                            name="terms_notes"
                                            class="form-control form-control-solid fs-6 <?php $__errorArgs = ['terms_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            rows="4"
                                        ><?php echo e(old('terms_notes', $order->terms_notes ?? '')); ?></textarea>

                                        <small class="text-muted d-block mt-1">
                                            <?php echo e(Str::length(old('terms_notes', $order->terms_notes ?? ''))); ?>

                                            <?php echo app('translator')->get('dashboard.characters'); ?>
                                        </small>

                                        <?php $__errorArgs = ['terms_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                        <div class="mt-3">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <?php echo app('translator')->get('dashboard.save'); ?>
                                            </button>
                                        </div>
                                </form>
                            </div>

                        </div>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
    <!--end::Post-->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
    // Show confirmation before submitting the delete form; on confirm, let sending-forms.js handle AJAX
    $(document).on('click', '#kt_ecommerce_add_product_form #kt_ecommerce_add_product_submit', function(e){
        var $btn = $(this);
        var $form = $btn.closest('form');
        // Only intercept for DELETE signature form
        if ($form.attr('action') && $form.attr('action').includes('/sign/')) {
            e.preventDefault();
            Swal.fire({
                text: `${$.localize.data['app']['common']['check_for_delete']}`,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: `${$.localize.data['app']['common']['ok_delete']}`,
                cancelButtonText: `${$.localize.data['app']['common']['no_cancel']}`,
                customClass: { confirmButton: 'btn fw-bold btn-danger', cancelButton: 'btn fw-bold btn-active-light-primary' }
            }).then(function(res){
                if (res.isConfirmed) {
                    $form.trigger('submit');
                }
            });
        }
    });

    // As a fallback, reload the page on success in case redirect attribute is ignored
    $(document).on('store:success', '#kt_ecommerce_add_product_form', function(){
        window.location.reload();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/orders/accept_terms.blade.php ENDPATH**/ ?>