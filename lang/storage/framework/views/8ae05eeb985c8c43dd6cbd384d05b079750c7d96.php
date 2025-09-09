
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['order' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['order' => null]); ?>
<?php foreach (array_filter((['order' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php $__env->startSection('pageTitle', __('dashboard.orders')); ?>

<?php $__env->startSection('content'); ?>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 d-flex align-items-center justify-content-between">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            <?php echo e(isset($order) ? $order->customer->name : __('dashboard.create_title', ['page_title' => __('dashboard.orders')])); ?>

                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-light-primary" id="btnRetrieveRf">
                            <i class="bi bi-cloud-download"></i> <?php echo e(__('dashboard.retrieve_data')); ?>

                        </button>
                    </div>
                </div>

                <div class="collapse show">
                    <form id="kt_ecommerce_add_product_form"
                          data-kt-redirect="<?php echo e(isset($order) ? route('orders.edit', $order->id) : route('orders.create')); ?>"
                          action="<?php echo e(isset($order) ? route('orders.update', $order->id) : route('orders.store')); ?>"
                          method="post" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row store">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($order)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                        <div class="card-body border-top p-9">
                            <input type="hidden" name="created_by" value="<?php echo e(Auth::id()); ?>">
                            <input type="hidden" name="rf_id" id="rf_id" value="">
                            <input type="hidden" name="show_price_notes" id="show_price_notes" value="<?php echo e(isset($order) ? $order->show_price_notes : ''); ?>">
                            <input type="hidden" name="order_data_notes" id="order_data_notes" value="<?php echo e(isset($order) ? $order->order_data_notes : ''); ?>">
                            <input type="hidden" name="invoice_notes" id="invoice_notes" value="<?php echo e(isset($order) ? $order->invoice_notes : ''); ?>">
                            <input type="hidden" name="receipt_notes" id="receipt_notes" value="<?php echo e(isset($order) ? $order->receipt_notes : ''); ?>">

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">
                                    <?php echo app('translator')->get('dashboard.customer_first_and_last_name'); ?>
                                </label>
                                <div class="col-lg-8">
                                    <select name="customer_id"
                                            class="js-select2 form-select form-select-lg form-select-solid"
                                            required>
                                        <option value="" <?php if (! ($customers)): ?> selected <?php endif; ?> disabled><?php echo e(__('dashboard.choose')); ?></option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>"
                                                    data-phone="<?php echo e($customer->mobile_phone ?? ''); ?>"
                                                    data-email="<?php echo e($customer->email ?? ''); ?>"
                                                <?php echo e(isset($order) && $order->customer_id == $customer->id ? 'selected' : ''); ?>>
                                                <?php echo e($customer->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">
                                    <?php echo app('translator')->get('dashboard.people_count'); ?>
                                </label>
                                <div class="col-lg-8">
                                    <input type="number" name="people_count" id="people_count"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->people_count : old('people_count', request('people_count'))); ?>"
                                           required>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6"><?php echo app('translator')->get('dashboard.service'); ?></label>
                                <div class="col-lg-8">
                                    <?php
                                        $initialServiceIds = isset($order)
                                            ? $order->services->pluck('id')->all()
                                            : collect(old('service_ids', request('service_ids', [])))->map(fn($v)=>(int)$v)->all();
                                    ?>
                                    <select name="service_ids[]" id="service_id"
                                            class="js-select2 form-select form-select-lg form-select-solid"
                                            multiple="multiple" required>
                                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($service->id); ?>"
                                                    data-price="<?php echo e($service->price); ?>"
                                                <?php echo e(in_array($service->id, $initialServiceIds) ? 'selected' : ''); ?>>
                                                <?php echo e($service->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">
                                    <?php echo app('translator')->get('dashboard.service_price'); ?>
                                </label>
                                <div class="col-lg-8">
                                    <input type="number" name="price" id="price"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->price : old('price')); ?>" required>
                                </div>
                            </div>

                            <?php if(isset($order)): ?>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6 required"><?php echo app('translator')->get('dashboard.addons'); ?></label>
                                    <div class="col-lg-8">
                                        <input type="number" class="form-control form-control-lg form-control-solid"
                                               value="<?php echo e($addonsPrice ?? 0); ?>" readonly>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required"><?php echo app('translator')->get('dashboard.deposit'); ?></label>
                                <div class="col-lg-8">
                                    <input type="number" name="deposit" id="deposit"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->deposit : old('deposit')); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required"><?php echo app('translator')->get('dashboard.insurance_amount'); ?></label>
                                <div class="col-lg-8">
                                    <input type="number" name="insurance_amount" id="insurance_amount"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->insurance_amount : old('insurance_amount')); ?>"
                                           required>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.notes'); ?></label>
                                <div class="col-lg-8">
                                <textarea name="notes" class="form-control form-control-lg form-control-solid"
                                          placeholder="<?php echo app('translator')->get('dashboard.notes'); ?>"><?php echo e(isset($order) ? $order->notes : old('notes', request('notes'))); ?></textarea>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.date'); ?></label>
                                <div class="col-lg-8">
                                    <input type="date" name="date"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->date : old('date', request('date'))); ?>">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.time_from'); ?></label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_from"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->time_from : old('time_from', request('time_from'))); ?>">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.time_to'); ?></label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_to"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->time_to : old('time_to', request('time_to'))); ?>">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.status'); ?></label>
                                <div class="col-lg-8">
                                    <select name="status" class="form-select form-select-lg form-select-solid" id="status">
                                        <option value="pending_and_show_price"
                                                title="<?php echo app('translator')->get('dashboard.pending_and_show_price_desc'); ?>"
                                            <?php echo e(isset($order) && $order->status == 'pending_and_show_price' ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get('dashboard.pending_and_show_price_desc'); ?>
                                        </option>
                                        <option value="pending_and_Initial_reservation"
                                                title="<?php echo app('translator')->get('dashboard.pending_and_Initial_reservation_desc'); ?>"
                                            <?php echo e(isset($order) && $order->status == 'pending_and_Initial_reservation' ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get('dashboard.pending_and_Initial_reservation'); ?>
                                        </option>
                                        <option value="approved" title="<?php echo app('translator')->get('dashboard.approved_desc'); ?>"
                                            <?php echo e(isset($order) && $order->status == 'approved' ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get('dashboard.approved'); ?>
                                        </option>
                                        <option value="canceled" title="<?php echo app('translator')->get('dashboard.canceled_desc'); ?>"
                                            <?php echo e(isset($order) && $order->status == 'canceled' ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get('dashboard.canceled'); ?>
                                        </option>
                                        <option value="delayed" title="<?php echo app('translator')->get('dashboard.delayed'); ?>"
                                            <?php echo e(isset($order) && $order->status == 'delayed' ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get('dashboard.delayed'); ?>
                                        </option>
                                        <option value="completed" title="<?php echo app('translator')->get('dashboard.completed_desc'); ?>"
                                            <?php echo e(isset($order) && $order->status == 'completed' ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get('dashboard.completed'); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <?php
                                $orderStatus = isset($order)
                                  ? ($order->status != 'pending_and_show_price' && $order->status != 'pending_and_Initial_reservation')
                                  : null;
                            ?>

                            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['row mb-6', 'd-none' => (!$order || $order->status != 'delayed')]) ?>" id="delayed_reson">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.delayed_reson'); ?></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control <?php $__errorArgs = ['delayed_reson'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="delayed_reson"
                                           value="<?php echo e(old('delayed_reson', isset($order) ? $order->delayed_reson : '')); ?>">
                                    <?php $__errorArgs = ['delayed_reson'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['row mb-6', 'd-none' => $orderStatus]) ?>" id="expired_price_offer">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <?php echo app('translator')->get('dashboard.expired_price_offer'); ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <input type="date" name="expired_price_offer"
                                           placeholder="<?php echo app('translator')->get('dashboard.expired_price_offer'); ?>"
                                           class="form-control form-control-lg form-control-solid"
                                           value="<?php echo e(isset($order) ? $order->expired_price_offer : old('expired_price_offer')); ?>">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.client_notes'); ?></label>
                                <div class="col-lg-8">
                                    <textarea name="client_notes" class="form-control"><?php echo e(isset($order) ? $order->client_notes : old('client_notes')); ?></textarea>
                                </div>
                            </div>

                            <?php if(isset($order) && $order->status == 'canceled'): ?>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">رد المبالغ المدفوعه ؟</label>
                                    <div class="col-lg-8">
                                        <select name="refunds" class="form-select form-select-lg form-select-solid">
                                            <option value="">-- Select --</option>
                                            <option value="1" <?php echo e(isset($order) && $order->refunds == '1' ? 'selected' : ''); ?>>Yes</option>
                                            <option value="0" <?php echo e(isset($order) && $order->refunds == '0' ? 'selected' : ''); ?>>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.refunds_notes'); ?></label>
                                    <div class="col-lg-8">
                                    <textarea name="refunds_notes" class="form-control form-control-lg form-control-solid"
                                            placeholder="<?php echo app('translator')->get('dashboard.refunds_notes'); ?>"><?php echo e(isset($order) ? $order->refunds_notes : old('refunds_notes')); ?></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($order) && $order->status == 'delayed'): ?>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.delayed_time'); ?></label>
                                    <div class="col-lg-8">
                                        <input type="time" name="delayed_time"
                                               class="form-control form-control-lg form-control-solid"
                                               value="<?php echo e(isset($order) ? $order->delayed_time : old('delayed_time')); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($order)): ?>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.Customer_Signature'); ?></label>
                                    <div class="col-lg-8 d-flex flex-column gap-3">
                                        <?php if(isset($order->signature_path)): ?>
                                            <div class="text-success fw-bold"><?php echo e($order?->signature); ?></div>
                                            <img src="<?php echo e(Storage::url($order->signature_path)); ?>" alt="Signature" style="max-height:80px;">
                                        <?php else: ?>
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                       value="<?php echo e(route('signature.show', $order)); ?>" readonly
                                                       onclick="this.select();document.execCommand('copy');">
                                                <button type="button" class="btn btn-outline-secondary"
                                                        onclick="navigator.clipboard.writeText('<?php echo e(route('signature.show', $order)); ?>')">
                                                    Copy Link
                                                </button>
                                            </div>
                                            <small class="text-muted"><?php echo app('translator')->get('dashboard.desc_Customer_Signature'); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-end gap-2">
                                <?php if(isset($order)): ?>
                                    <!--begin::Email Button-->
                                    <button type="button" id="send-email-btn" class="btn btn-secondary d-flex align-items-center gap-2">
                                        <img src="<?php echo e(asset('imgs/gmail.png')); ?>" alt="Email Icon" width="20" height="20">
                                        <span class="indicator-label"><?php echo app('translator')->get('dashboard.send_email'); ?></span>
                                    </button>
                                    <!--end::Email Button-->
                                <?php endif; ?>
                                <!--begin::Additional Notes Button-->
                                <button type="button" id="additional-notes-btn" class="btn btn-secondary">
                                    <span class="indicator-label"><?php echo app('translator')->get('dashboard.additional_notes'); ?></span>
                                </button>
                                <!--end::Additional Notes Button-->
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                    <span class="indicator-label"><?php echo app('translator')->get('dashboard.save_changes'); ?></span>
                                    <span class="indicator-progress"><?php echo app('translator')->get('dashboard.please_wait'); ?>
                                      <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="retrieveRfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('dashboard.retrieve_from_form')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo e(__('dashboard.close')); ?>"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label"><?php echo e(__('dashboard.retrieve_from_form')); ?></label>
                    <select id="retrieveRfSelect" class="form-select" style="width:100%">
                        <option value=""></option>
                    </select>
                    <small class="text-muted d-block mt-2"><?php echo e(__('dashboard.retrieve_placeholder')); ?></small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('dashboard.cancel')); ?></button>
                    <button type="button" class="btn btn-primary" id="btnDoRetrieve"><?php echo e(__('dashboard.retrieve')); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!--begin::Modal - Send Email-->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bolder"><?php echo app('translator')->get('dashboard.send_email_to_customer'); ?></h2>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-15">
                    <form id="sendEmailForm" class="form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="order_id" value="<?php echo e(isset($order) ? $order->id : ''); ?>">
                        <div class="row mb-8">
                            <label class="col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.select_documents_to_send'); ?></label>
                            <div class="checkbox-list">
                                <label class="checkbox">
                                    <input type="checkbox" name="documents[]" value="show_price">
                                    <span></span>
                                    <?php echo app('translator')->get('dashboard.show_price_pdf'); ?>
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="documents[]" value="reservation_data">
                                    <span></span>
                                    <?php echo app('translator')->get('dashboard.reservation_data_pdf'); ?>
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="documents[]" value="invoice">
                                    <span></span>
                                    <?php echo app('translator')->get('dashboard.invoice_pdf'); ?>
                                </label>

                                <!-- Addon Receipts -->
                                <?php if(isset($order) && $order->addons->where('pivot.verified', true)->count() > 0): ?>
                                    <div class="mt-3">
                                        <h6 class="fw-bolder"><?php echo app('translator')->get('dashboard.addon_receipts'); ?></h6>
                                        <?php $__currentLoopData = $order->addons->where('pivot.verified', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="checkbox">
                                                <input type="checkbox" name="receipts[addon][]" value="<?php echo e($addon->pivot->id); ?>">
                                                <span></span>
                                                <?php echo app('translator')->get('dashboard.addon_receipt'); ?>: <?php echo e($addon->name); ?>

                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Payment Receipts -->
                                <?php if(isset($order) && $order->payments->where('verified', true)->count() > 0): ?>
                                    <div class="mt-3">
                                        <h6 class="fw-bolder"><?php echo app('translator')->get('dashboard.payment_receipts'); ?></h6>
                                        <?php $__currentLoopData = $order->payments->where('verified', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="checkbox">
                                                <input type="checkbox" name="receipts[payment][]" value="<?php echo e($payment->id); ?>">
                                                <span></span>
                                                <?php echo app('translator')->get('dashboard.payment_receipt'); ?>: <?php echo e($payment->price); ?> - <?php echo e(__('dashboard.'.$payment->payment_method)); ?>

                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Warehouse Receipts -->
                                <?php if(isset($order) && $order->items->where('verified', true)->count() > 0): ?>
                                    <div class="mt-3">
                                        <h6 class="fw-bolder"><?php echo app('translator')->get('dashboard.warehouse_receipts'); ?></h6>
                                        <?php $__currentLoopData = $order->items->where('verified', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="checkbox">
                                                <input type="checkbox" name="receipts[warehouse][]" value="<?php echo e($item->id); ?>">
                                                <span></span>
                                                <?php echo app('translator')->get('dashboard.warehouse_receipt'); ?>: <?php echo e($item->stock->name); ?>

                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <label class="col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.customer_email'); ?></label>
                            <div class="">
                                <input type="email" class="form-control" value="<?php echo e(isset($order) && $order->customer ? $order->customer->email : ''); ?>" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo app('translator')->get('dashboard.cancel'); ?></button>
                    <button type="button" id="sendEmailSubmit" class="btn btn-primary"><?php echo app('translator')->get('dashboard.send'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Send Email-->
    <!--begin::Modal - Additional Notes-->
    <div class="modal fade" id="additionalNotesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bolder"><?php echo app('translator')->get('dashboard.additional_notes'); ?></h2>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-15">
                    <form id="additionalNotesForm" class="form">
                        <!-- Show Price Notes -->
                        <label class="col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.show_price_notes'); ?></label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="showPriceEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>

                        <!-- Order Data Notes -->
                        <label class="col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.order_data_notes'); ?></label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="orderDataEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>

                        <!-- Invoice Notes -->
                        <label class="col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.invoice_notes'); ?></label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="invoiceEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>

                        <!-- Receipt Notes -->
                        <label class="col-form-label fw-bold fs-6"><?php echo app('translator')->get('dashboard.receipt_notes'); ?></label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="receiptEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo app('translator')->get('dashboard.cancel'); ?></button>
                    <button type="button" id="saveAdditionalNotes" class="btn btn-primary"><?php echo app('translator')->get('dashboard.save'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Additional Notes-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.tiny.cloud/1/m181ycw0urzvmmzinvpzqn3nv10wxttgo7gvv77hf6ce6z89/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        (function(){
            const isRTL = "<?php echo e(app()->getLocale()); ?>" === "ar";
            $('.js-select2').each(function(){
                $(this).select2({
                    width: '100%',
                    dir: isRTL ? 'rtl' : 'ltr',
                    dropdownAutoWidth: true
                });
            });

            $('#status').on('change', function() {
                if (this.value === 'pending_and_show_price' || this.value === 'pending_and_Initial_reservation') {
                    $('#expired_price_offer').removeClass('d-none');
                    $('#delayed_reson').addClass('d-none');
                } else if (this.value === 'delayed') {
                    $('#delayed_reson').removeClass('d-none');
                    $('#expired_price_offer').addClass('d-none');
                } else {
                    $('#expired_price_offer,#delayed_reson').addClass('d-none');
                }
            });

            function recalcPrice() {
                let total = 0;
                $('#service_id option:selected').each(function(){
                    total += parseFloat($(this).data('price')) || 0;
                });
                $('#price').val(total.toFixed(2));
            }
            $('#service_id').on('change', recalcPrice);
            recalcPrice();

            $('#btnRetrieveRf').on('click', ()=>{
                const el = document.getElementById('retrieveRfModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
            });

            const $modal = $('#retrieveRfModal');
            $modal.on('shown.bs.modal', function () {
                const $sel = $('#retrieveRfSelect');
                if ($sel.data('select2')) $sel.select2('destroy');

                $sel.select2({
                    dropdownParent: $modal,
                    width: '100%',
                    dir: isRTL ? 'rtl' : 'ltr',
                    placeholder: <?php echo json_encode(__('dashboard.retrieve_placeholder'), 15, 512) ?>,
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                        url: <?php echo json_encode(route('orders.registeration-forms.search'), 15, 512) ?>,
                        dataType: 'json',
                        delay: 250,
                        data: params => ({ q: params.term || '' }),
                        processResults: data => {
                            const results = Array.isArray(data) ? data : (data.results || []);
                            return { results };
                        },
                        cache: true
                    }
                });
            });

            $('#btnDoRetrieve').on('click', function(){
                const id = $('#retrieveRfSelect').val();
                if (!id) {
                    return window.Swal
                        ? Swal.fire({ icon: 'warning', title: <?php echo json_encode(__('dashboard.select_required'), 15, 512) ?> })
                        : alert(<?php echo json_encode(__('dashboard.select_required'), 15, 512) ?>);
                }

                const ask = () => $.get(<?php echo json_encode(route('orders.registeration-forms.fetch', ['id' => '___ID___']), 512) ?>.replace('___ID___', id))
                    .done(fillFormFromPayload)
                    .fail((xhr)=>{
                        window.Swal
                            ? Swal.fire({ icon:'error', title:'Error', text: (xhr.responseJSON && xhr.responseJSON.message) || 'Failed to fetch data.' })
                            : alert('Failed to fetch data.');
                    });

                if (window.Swal) {
                    Swal.fire({
                        title: <?php echo json_encode(__('dashboard.retrieve_confirm_title'), 15, 512) ?>,
                        text:  <?php echo json_encode(__('dashboard.retrieve_confirm_body'), 15, 512) ?>,
                        icon:  'question',
                        showCancelButton: true,
                        confirmButtonText: <?php echo json_encode(__('dashboard.retrieve'), 15, 512) ?>,
                        cancelButtonText:  <?php echo json_encode(__('dashboard.cancel'), 15, 512) ?>
                    }).then((res)=>{ if(res.isConfirmed){ ask(); } });
                } else {
                    if (confirm(<?php echo json_encode(__('dashboard.retrieve_confirm_body'), 15, 512) ?>)) ask();
                }
            });

            function fillFormFromPayload(payload){
                $('#rf_id').val(payload.rf_id || '');
                $('input[name="people_count"]').val(payload.people_count || '');

                if (Array.isArray(payload.service_ids)) {
                    $('#service_id').val(payload.service_ids.map(String)).trigger('change');
                }

                if (payload.date)      $('input[name="date"]').val(payload.date);
                if (payload.time_from) $('input[name="time_from"]').val(payload.time_from);
                if (payload.time_to)   $('input[name="time_to"]').val(payload.time_to);
                if (payload.notes)     $('textarea[name="client_notes"]').val(payload.notes);

                const $cust = $('select[name="customer_id"]');
                const c = payload.customer;
                if (c && c.id) {
                    const exists = $cust.find('option[value="'+c.id+'"]').length > 0;
                    if (!exists) {
                        const opt = new Option(c.name || (c.email || c.phone || ('#'+c.id)), c.id, true, true);
                        $(opt).attr('data-phone', c.phone || '').attr('data-email', c.email || '');
                        $cust.append(opt);
                    }
                    $cust.val(String(c.id)).trigger('change');
                }

                $('#service_id').trigger('change');

                if (window.Swal) Swal.fire({ icon:'success', title: <?php echo json_encode(__('dashboard.loaded_ok'), 15, 512) ?> });
                const m = bootstrap.Modal.getInstance(document.getElementById('retrieveRfModal'));
                m && m.hide();
            }

            (function linkPrefill(){
                const url = new URL(window.location.href);
                const hasPrefill =
                    url.searchParams.has('rf_id') ||
                    url.searchParams.has('people_count') ||
                    url.searchParams.has('service_ids') ||
                    url.searchParams.has('date') ||
                    url.searchParams.has('time_from') ||
                    url.searchParams.has('time_to') ||
                    url.searchParams.has('notes') ||
                    url.searchParams.has('prefill_mobile') ||
                    url.searchParams.has('prefill_email');

                const isEdit = <?php echo json_encode(isset($order), 15, 512) ?>;
                if (!hasPrefill || isEdit) return;

                if (url.searchParams.has('rf_id') && "<?php echo e(Route::has('orders.customers.check') ? '1' : '0'); ?>" === "1") {
                    $.ajax({
                        url: "<?php echo e(route('orders.customers.check')); ?>",
                        method: "GET",
                        dataType: "json",
                        data: { id: url.searchParams.get('rf_id') }
                    }).done(function (c) {
                        if (!c || !c.customer || !c.customer.id) return;
                        const $cust = $('select[name="customer_id"]');
                        const exists = $cust.find('option[value="'+c.customer.id+'"]').length > 0;
                        if (!exists) {
                            const opt = new Option(c.customer.name || (c.customer.email || c.customer.phone || ('#'+c.customer.id)),
                                c.customer.id, true, true);
                            $(opt).attr('data-phone', c.customer.phone || '').attr('data-email', c.customer.email || '');
                            $cust.append(opt);
                        }
                        $cust.val(String(c.customer.id)).trigger('change');
                    });
                }

                const rfId = url.searchParams.get('rf_id');
                if (window.Swal) {
                    Swal.fire({
                        title: isRTL ? 'تم سحب البيانات' : 'Prefilled',
                        text: rfId
                            ? (isRTL ? `تم سحب البيانات من الاستمارة رقم #${rfId}` : `Loaded data from form #${rfId}` )
                            : (isRTL ? 'تم سحب البيانات من الاستمارة' : 'Loaded data from form'),
                        icon: 'success',
                        confirmButtonText: isRTL ? 'حسناً' : 'OK',
                    });
                }
            })();

            /////////////// Start:samuel work ///////////////
            // Initialize TinyMCE
            const editorConfig = {
                plugins: 'link lists code',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link',
                menubar: false,
                height: '50vh',
                skin: 'oxide',
                content_css: 'default',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            };

            // Initialize editors
            tinymce.init({ ...editorConfig, selector: '#showPriceEditor' });
            tinymce.init({ ...editorConfig, selector: '#orderDataEditor' });
            tinymce.init({ ...editorConfig, selector: '#invoiceEditor' });
            tinymce.init({ ...editorConfig, selector: '#receiptEditor' });

            // Additional notes modal functionality
            const additionalNotesModal = new bootstrap.Modal(document.getElementById('additionalNotesModal'));

            let additionalNotesData = {
                notes: '',
                show_price: false,
                order_data: false,
                invoice: false,
                receipt: false
            };

            // Load existing data if any
            function loadExistingData() {
                tinymce.get('showPriceEditor').setContent($('#show_price_notes').val() || '');
                tinymce.get('orderDataEditor').setContent($('#order_data_notes').val() || '');
                tinymce.get('invoiceEditor').setContent($('#invoice_notes').val() || '');
                tinymce.get('receiptEditor').setContent($('#receipt_notes').val() || '');
            }

            // Open modal
            $('#additional-notes-btn').click(function() {
                loadExistingData();
                additionalNotesModal.show();
            });

            // Save additional notes
            $('#saveAdditionalNotes').click(function() {
                // Update hidden fields with editor content
                $('#show_price_notes').val(tinymce.get('showPriceEditor').getContent());
                $('#order_data_notes').val(tinymce.get('orderDataEditor').getContent());
                $('#invoice_notes').val(tinymce.get('invoiceEditor').getContent());
                $('#receipt_notes').val(tinymce.get('receiptEditor').getContent());

                additionalNotesModal.hide();

                // Show success message
                Swal.fire({
                    text: "<?php echo e(__('dashboard.additional_notes_saved')); ?>",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "<?php echo e(__('dashboard.ok')); ?>",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            });

            // Customer change event to check for notices
            $('select[name="customer_id"]').change(function() {
                const customerId = $(this).val();
                if (customerId) {
                    $.get('/orders/check-customer-notices/' + customerId, function(response) {
                        if (response.hasNotices) {
                            // Detect current language direction
                            const textAlign = isRTL ? 'right' : 'left';

                            let noticeContent = `<div style="text-align: ${textAlign}; font-size: 14px; line-height: 1.6;">`;
                            response.notices.forEach(notice => {
                                noticeContent += `
                        <div style="margin-bottom: 15px; padding: 12px; background: #fdfdfd; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <p style="margin: 0 0 6px 0; font-weight: bold; color: #333;">
                                <?php echo e(__('dashboard.notice_label')); ?>:
                            </p>
                            <p style="margin: 0 0 8px 0; color: #555;">${notice.content}</p>
                            <p style="margin: 0; font-size: 12px; color: #888;">
                                <?php echo e(__('dashboard.created_by_at', ['name' => '${notice.created_by}', 'date' => '${notice.created_at}'])); ?>

                                </p>
                            </div>
`;
                            });
                            noticeContent += '</div>';

                            Swal.fire({
                                title: "<?php echo e(__('dashboard.customer_has_notices')); ?>",
                                html: noticeContent,
                                icon: 'warning',
                                confirmButtonText: "<?php echo e(__('dashboard.ok')); ?>",
                                width: '700px',
                                customClass: {
                                    popup: isRTL ? 'swal2-rtl' : ''
                                }
                            });
                        }
                    });
                }
            });

            // Email modal functionality
            const sendEmailModal = new bootstrap.Modal(document.getElementById('sendEmailModal'));

            // Open email modal
            $('#send-email-btn').click(function() {
                sendEmailModal.show();
            });

            // Send email
            $('#sendEmailSubmit').click(function() {
                const formData = new FormData();
                const documents = [];
                const receipts = [];

                // Get main documents
                $('input[name="documents[]"]:checked').each(function() {
                    documents.push($(this).val());
                    formData.append('documents[]', $(this).val());
                });

                // Get addon receipts
                $('input[name="receipts[addon][]"]:checked').each(function() {
                    receipts.push({type: 'addon', id: $(this).val()});
                    formData.append('receipts[addon][]', $(this).val());
                });

                // Get payment receipts
                $('input[name="receipts[payment][]"]:checked').each(function() {
                    receipts.push({type: 'payment', id: $(this).val()});
                    formData.append('receipts[payment][]', $(this).val());
                });

                // Get warehouse receipts
                $('input[name="receipts[warehouse][]"]:checked').each(function() {
                    receipts.push({type: 'warehouse', id: $(this).val()});
                    formData.append('receipts[warehouse][]', $(this).val());
                });

                // Check if at least one document is selected
                if (documents.length === 0 && receipts.length === 0) {
                    Swal.fire({
                        text: "<?php echo e(__('dashboard.please_select_at_least_one_document')); ?>",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "<?php echo e(__('dashboard.ok')); ?>",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return;
                }

                const orderId = $('input[name="order_id"]').val();
                formData.append('_token', $('input[name="_token"]').val());

                // Show loading indicator
                $('#sendEmailSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <?php echo e(__("dashboard.sending")); ?>');

                $.ajax({
                    url: "<?php echo e(route('orders.sendEmail', isset($order) ? $order->id : '')); ?>",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        sendEmailModal.hide();
                        $('#sendEmailSubmit').prop('disabled', false).html('<?php echo e(__("dashboard.send")); ?>');

                        Swal.fire({
                            text: response.message,
                            icon: response.success ? "success" : "error",
                            buttonsStyling: false,
                            confirmButtonText: "<?php echo e(__('dashboard.ok')); ?>",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    },
                    error: function(xhr) {
                        $('#sendEmailSubmit').prop('disabled', false).html('<?php echo e(__("dashboard.send")); ?>');

                        let message = "<?php echo e(__('dashboard.something_went_wrong')); ?>";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            text: message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "<?php echo e(__('dashboard.ok')); ?>",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            });
            /////////////// End:samuel work ///////////////
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/orders/create.blade.php ENDPATH**/ ?>