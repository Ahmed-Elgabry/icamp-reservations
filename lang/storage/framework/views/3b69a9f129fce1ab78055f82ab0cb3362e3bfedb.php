

<?php $__env->startSection('pageTitle' , __('dashboard.orders')); ?>
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
                            <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="<?php echo app('translator')->get('dashboard.search_title', ['page_title' => __('dashboard.addons')]); ?>" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAddonModal">
                            <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.addons')]); ?>
                        </button>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th><?php echo e(__('dashboard.addon_type')); ?></th>
                            <th><?php echo e(__('dashboard.addon_price')); ?></th>
                            <th><?php echo e(__('dashboard.quantity')); ?></th>
                            <th><?php echo e(__('dashboard.total_price')); ?></th>
                            <th><?php echo e(__('dashboard.payment_method')); ?></th>
                            <th><?php echo e(__('dashboard.bank_account')); ?></th>
                            <th><?php echo e(__('dashboard.verified')); ?></th>
                            <th><?php echo e(__('dashboard.notes')); ?></th>
                            <th><?php echo e(__('dashboard.actions')); ?></th>
                        </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600">
                        <?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderAddon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-id="<?php echo e($orderAddon->id); ?>">
                                <td data-kt-ecommerce-category-filter="category_name"><?php echo e($orderAddon->name); ?></td>
                                <td><?php echo e($orderAddon->price); ?></td>
                                <td ><?php echo e($orderAddon->pivot->count); ?></td>
                                <td><?php echo e($orderAddon->pivot->price); ?></td>
                                <td><?php echo e(__('dashboard.'. $orderAddon->pivot->payment_method )); ?></td>
                                <td><?php echo e($orderAddon->pivot->account->name); ?></td>
                                <td>
                                    <?php echo e($orderAddon->pivot->verified ? __('dashboard.yes') : __('dashboard.no')); ?> <br>
                                    <?php if($orderAddon->pivot->verified): ?>
                                        <a href="<?php echo e(route('order.verified' , [$orderAddon->pivot->id , 'addon'])); ?>" class="btn btn-sm btn-danger" ><?php echo e(__('dashboard.mark')); ?> <?php echo e(__('dashboard.unverifyed')); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('order.verified' , [$orderAddon->pivot->id , 'addon'])); ?>" class="btn btn-sm btn-success"><?php echo e(__('dashboard.mark')); ?> <?php echo e(__('dashboard.verified')); ?></a>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($orderAddon->pivot->description); ?></td>


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
                                               class="menu-link px-3 receipt-link"
                                               data-verified="<?php echo e($orderAddon->pivot->verified == 1 ? '1' : '0'); ?>"
                                               data-url="<?php echo e(route('addons.receipt', ['order' => $order->id, 'addon' => $orderAddon->pivot->id])); ?>">
                                                <?php echo e(__('dashboard.receipt')); ?>

                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                           <form action="<?php echo e(route('orders.removeAddon', $orderAddon->pivot->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn">
                                                    <?php echo e(__('dashboard.delete')); ?>

                                                </button>
                                            </form>
                                        </div>
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <button class="btn" data-toggle="modal" data-target="#editAddonModal-<?php echo e($orderAddon->pivot->id); ?>">
                                                <?php echo e(__('dashboard.edit')); ?>

                                            </button>
                                        </div>
                                    <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                                <!--end::Action=-->
                            </tr>

                            <!-- Modal for editing an addon -->
                            <div class="modal fade" id="editAddonModal-<?php echo e($orderAddon->pivot->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editAddonModalLabel-<?php echo e($orderAddon->pivot->id); ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editAddonModalLabel-<?php echo e($orderAddon->pivot->id); ?>"><?php echo e(__('dashboard.edit')); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editAddonForm-<?php echo e($orderAddon->pivot->id); ?>" action="<?php echo e(route('ordersUpdate.addons', $orderAddon->pivot->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>

                                                <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                                                <input type="hidden" name="source" value="reservation_addon">
                                                <div class="form-group">
                                                    <label for="addon_id"><?php echo e(__('dashboard.addon_type')); ?></label>
                                                    <select name="addon_id" id="edit_addon_id_<?php echo e($orderAddon->pivot->id); ?>" class="form-control select2">
                                                        <option value="" data-price="0"><?php echo e(__('dashboard.choose')); ?> <?php echo e(__('dashboard.addon')); ?></option>
                                                        <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($addon->id); ?>" data-price="<?php echo e($addon->price); ?>" <?php echo e($orderAddon->id == $addon->id ? 'selected' : ''); ?>><?php echo e($addon->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="edit_service_price_<?php echo e($orderAddon->pivot->id); ?>"><?php echo e(__('dashboard.addon_price')); ?></label>
                                                    <input type="number" step="0.01" name="service_price" id="edit_service_price_<?php echo e($orderAddon->pivot->id); ?>" class="form-control" value="<?php echo e($orderAddon->price); ?>" readonly>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="edit_count_<?php echo e($orderAddon->id); ?>"><?php echo e(__('dashboard.quantity')); ?></label>
                                                    <input type="number" name="count" id="edit_count_<?php echo e($orderAddon->pivot->id); ?>" class="form-control" value="<?php echo e($orderAddon->pivot->count); ?>">
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="edit_price_<?php echo e($orderAddon->pivot->id); ?>"><?php echo e(__('dashboard.total_price')); ?></label>
                                                    <input type="number" step="0.01" name="price" id="edit_price_<?php echo e($orderAddon->pivot->id); ?>" class="form-control" value="<?php echo e($orderAddon->pivot->price); ?>">
                                                </div>
                                                <div class="mb-5 fv-row col-md-12">
                                                    <label class="required form-label"><?php echo e(__('dashboard.payment_method')); ?></label>
                                                    <select name="payment_method" id="" class="form-select" required>
                                                        <?php $__currentLoopData = paymentMethod(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentSelect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option <?php if($orderAddon->pivot->payment_method == $paymentSelect): echo 'selected'; endif; ?> value="<?php echo e($paymentSelect); ?>"><?php echo e(__('dashboard.'. $paymentSelect )); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="mb-5 fv-row col-md-12">
                                                    <label class="required form-label"><?php echo e(__('dashboard.bank_account')); ?></label>
                                                    <select name="account_id" id="account_id" class="form-select" required>
                                                        <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option <?php if($orderAddon->pivot->account_id === $bankAccount->id): echo 'selected'; endif; ?> value="<?php echo e($bankAccount->id); ?>"><?php echo e($bankAccount->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="edit_description_<?php echo e($orderAddon->pivot->id); ?>"><?php echo e(__('dashboard.notes')); ?></label>
                                                    <textarea name="description" id="edit_description_<?php echo e($orderAddon->pivot->id); ?>" class="form-control"><?php echo e($orderAddon->pivot->description); ?></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" form="editAddonForm-<?php echo e($orderAddon->pivot->id); ?>" class="btn btn-primary"><?php echo e(__('dashboard.save_changes')); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->

    <!-- Modal for adding a new addon -->
    <div class="modal fade" id="addAddonModal" tabindex="-1" role="dialog" aria-labelledby="addAddonModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddonModalLabel"><?php echo e(__('dashboard.create_title', ['page_title' => __('dashboard.addons')])); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAddonForm" action="<?php echo e(route('ordersStore.addons', $order->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="addon_id"><?php echo e(__('dashboard.addon_type')); ?></label>
                            <select name="addon_id" id="addon_id" class="form-control">
                                <option value="" data-price="0"><?php echo e(__('dashboard.choose')); ?> <?php echo e(__('dashboard.addon')); ?></option>
                                <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($addon->id); ?>" data-price="<?php echo e($addon->price); ?>"><?php echo e($addon->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="addon_price"><?php echo e(__('dashboard.addon_price')); ?></label>
                            <input type="number" step="0.01" name="addon_price" id="addon_price" class="form-control" value="0" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label for="count"><?php echo e(__('dashboard.quantity')); ?></label>
                            <input type="number" name="count" id="count" class="form-control" value="1">
                        </div>
                        <div class="form-group mt-3">
                            <label for="price"><?php echo e(__('dashboard.total_price')); ?></label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" value="0">
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
                            <select name="account_id" id="account_id" class="form-select" required>
                                <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bankAccount->id); ?>"><?php echo e($bankAccount->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="description"><?php echo e(__('dashboard.notes')); ?></label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="addAddonForm" class="btn btn-primary"><?php echo e(__('dashboard.save_changes')); ?></button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>
    $(document).ready(function() {
            // Initialize select2
            $('#addon_id').select2();

            // Update service price field when addon is selected
            $('#addon_id').on('change', function() {
                let price = $(this).find(':selected').data('price');
        $('#addon_price').val(price ?? 0); // Display the service price in the correct field
                updateTotalPrice();
            });

            // Update total price when count or price is changed
            $('#count, #price').on('input', function() {
                updateTotalPrice();
            });

            function updateTotalPrice() {
                let count = parseFloat($('#count').val());
                let servicePrice = parseFloat($('#addon_price').val()); // Get the service price from the correct field
                count = isNaN(count) ? 0 : count;
                servicePrice = isNaN(servicePrice) ? 0 : servicePrice;
                let totalPrice = count * servicePrice;
                $('#price').val(totalPrice.toFixed(2)); // Keep the total price logic
            }
        });

        $(document).ready(function() {
            // Update service price when addon changes
            $(document).on('change', 'select[id^="edit_addon_id_"]', function() {
                let selectedAddon = $(this).find(':selected');
                let price = parseFloat(selectedAddon.data('price'));
                let addonId = $(this).attr('id').split('_').pop();
                $('#edit_service_price_' + addonId).val(price); // Display the service price in the new field
                updateTotalPriceEdit(addonId);
            });

            // Update total price when count changes
            $(document).on('keyup', 'input[id^="edit_count_"]', function() {
                let addonId = $(this).attr('id').split('_').pop();
                updateTotalPriceEdit(addonId);
            });

            // Function to update total price
            function updateTotalPriceEdit(addonId) {
                let count = parseFloat($('#edit_count_' + addonId).val());
                let servicePrice = parseFloat($('#edit_service_price_' + addonId).val()); // Get the service price from the new field
                count = isNaN(count) ? 0 : count;
                servicePrice = isNaN(servicePrice) ? 0 : servicePrice;
                let totalPrice = (count * servicePrice).toFixed(2);
                $('#edit_price_' + addonId).val(totalPrice); // Update the total price logic
            }
        });

        $(document).on('click', '.receipt-link', function(e) {
            e.preventDefault();

            if ($(this).data('verified') == '1') {
                window.open($(this).data('url'), '_blank');
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: '<?php echo e(__("dashboard.error")); ?>',
                    text: '<?php echo e(__("dashboard.addon_not_verified_receipt_error")); ?>',
                    confirmButtonText: '<?php echo e(__("dashboard.ok")); ?>'
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/orders/addons.blade.php ENDPATH**/ ?>