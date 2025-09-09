<?php $__env->startSection('pageTitle', __('dashboard.warehouse_sales')); ?>


<?php $__env->startSection('content'); ?>
<div class="post d-flex flex-column-fluid" id="kt_post">
  <div id="kt_content_container" class="container-xxl">

    <?php echo $__env->make('dashboard.orders.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
      <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
          <div class="d-flex align-items-center position-relative my-1">
            <span class="svg-icon svg-icon-1 position-absolute ms-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"/>
              </svg>
            </span>
            <input type="text" data-kt-ecommerce-category-filter="search"
                   class="form-control form-control-solid w-250px ps-14"
                   placeholder="<?php echo app('translator')->get('dashboard.search_title', ['page_title' => __('dashboard.items')]); ?>"/>
          </div>
        </div>

        <div class="card-toolbar">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewCount">
            <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.warehouse_sales')]); ?>
          </button>
          <span class="w-5px h-2px"></span>
        </div>
      </div>

      <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
          <thead>
            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
              <th><?php echo e(__('dashboard.items')); ?></th>
              <th><?php echo e(__('dashboard.item_price')); ?></th>
              <th><?php echo e(__('dashboard.quantity')); ?></th>
              <th><?php echo e(__('dashboard.total_amount')); ?></th>
              <th><?php echo e(__('dashboard.payment_method')); ?></th>
              <th><?php echo e(__('dashboard.bank_account')); ?></th>
              <th><?php echo e(__('dashboard.verified')); ?></th>
              <th><?php echo e(__('dashboard.notes')); ?></th>
              <th><?php echo e(__('dashboard.created_at')); ?></th>
              <th class="text-end min-w-70px"><?php echo app('translator')->get('dashboard.actions'); ?></th>
            </tr>
          </thead>
          <tbody class="fw-bold text-gray-600">
          <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><span class="badge bg-primary"><?php echo e($item?->stock->name); ?></span></td>
              <td class="fw-bold"><?php echo e($item?->stock->selling_price  % 1 === 0 ? (int) $item?->stock->selling_price : $item?->stock->selling_price); ?></td>
              <td><?php echo e((int) $item?->quantity); ?></td>
              <td class="fw-bold"><?php echo e($item?->total_price % 1 === 0 ? (int) $item?->total_price : $item?->total_price); ?></td>
              <td><?php echo e(__('dashboard.'. $item->payment_method )); ?></td>
              <td><?php echo e($item?->bank_account); ?></td>
              <td>
                    <?php echo e($item->verified ? __('dashboard.yes') : __('dashboard.no')); ?> <br>
                    <?php if($item->verified): ?>
                        <a href="<?php echo e(route('order.verified' , [$item->id , 'warehouse_sales'])); ?>" class="btn btn-sm btn-danger" ><?php echo e(__('dashboard.mark')); ?> <?php echo e(__('dashboard.unverifyed')); ?></a>
                    <?php else: ?>
                        <a href="<?php echo e(route('order.verified' , [$item->id , 'warehouse_sales'])); ?>" class="btn btn-sm btn-success"><?php echo e(__('dashboard.mark')); ?> <?php echo e(__('dashboard.verified')); ?></a>
                    <?php endif; ?>
              </td>
              <td><?php echo e($item?->notes); ?></td>
              <td><?php echo e($item?->created_at->format('Y-m-d h:i A')); ?></td>
              <td class="text-end">
                <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                  <?php echo e(__('dashboard.actions')); ?>

                  <span class="svg-icon svg-icon-5 m-0">...</span>
                </a>

                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600
                            menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <a href="#"
                           class="menu-link px-3 warehouse-receipt-link"
                           data-verified="<?php echo e($item->verified ? '1' : '0'); ?>"
                           data-url="<?php echo e(route('warehouse.receipt', ['order' => $order->id, 'warehouse' => $item->id])); ?>">
                            <?php echo e(__('dashboard.receipt')); ?>

                        </a>
                    </div>
                    <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3" data-kt-menu-dismiss="true"
                       data-bs-toggle="modal" data-bs-target="#editCount-<?php echo e($item->id); ?>">
                      <?php echo e(__('actions.edit')); ?>

                    </a>
                  </div>

                  <div class="menu-item px-3">
                    <form id="delete-form-<?php echo e($item->id); ?>" action="<?php echo e(route('warehouse_sales.destroy', $item->id)); ?>" method="POST" style="display:none;">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    </form>
                    <a href="#" class="menu-link px-3 text-danger"
                       onclick="event.preventDefault(); if(confirm('<?php echo app('translator')->get('dashboard.delete'); ?>')) document.getElementById('delete-form-<?php echo e($item->id); ?>').submit();">
                      <?php echo app('translator')->get('dashboard.delete'); ?>
                    </a>
                  </div>
                </div>
              </td>
            </tr>

            
            <div class="modal fade" id="editCount-<?php echo e($item->id); ?>" tabindex="-1"
                 aria-labelledby="editCountLabel-<?php echo e($item->id); ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 id="editCountLabel-<?php echo e($item->id); ?>" class="modal-title"><?php echo e(__('actions.edit')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo e(__('dashboard.close')); ?>"></button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo e(route('warehouse_sales.update', $item->id)); ?>"
                          id="editCountForm-<?php echo e($item->id); ?>" method="POST">
                      <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                      <div class="mb-5 fv-row col-md-12">
                        <label class="required form-label"><?php echo e(__('dashboard.items')); ?></label>
                        <select name="stock_id" class="form-control js-stock"
                                data-initial="<?php echo e($item->stock_id); ?>" required>
                          <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($stock->id); ?>" data-price="<?php echo e($stock->selling_price); ?>"
                              <?php if($stock->id == $item->stock_id): echo 'selected'; endif; ?>><?php echo e($stock->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>
                      <input type="hidden" value="warehouse_sales" name="source">     
                      <div class="form-group mt-3">
                        <label><?php echo e(__('dashboard.quantity')); ?></label>
                        <input type="number" name="quantity" class="form-control js-qty"
                               value="<?php echo e((int) $item?->quantity); ?>">
                      </div>

                      <div class="form-group mt-3">
                        <label><?php echo e(__('dashboard.total_amount')); ?></label>
                        <input type="number" step="0.01" name="total_price" class="form-control js-total"
                               value="<?php echo e($item->total_price); ?>">
                      </div>

                        <div class="mb-5 fv-row col-md-12">
                            <label class="required form-label"><?php echo e(__('dashboard.bank_account')); ?></label>
                            <select name="account_id" id="" class="form-select" required>
                                <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($item->account_id == $id): echo 'selected'; endif; ?> value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-5 fv-row col-md-12">
                            <label class="required form-label"><?php echo e(__('dashboard.payment_method')); ?></label>
                            <select name="payment_method" id="" class="form-select" required>
                                <?php $__currentLoopData = paymentMethod(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentSelect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($item->payment_method == $paymentSelect): echo 'selected'; endif; ?> value="<?php echo e($paymentSelect); ?>"><?php echo e(__('dashboard.'. $paymentSelect )); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                      <div class="mb-5 fv-row col-md-12">
                        <label class="form-label"><?php echo e(__('dashboard.notes')); ?></label>
                        <textarea name="notes" class="form-control mb-2"><?php echo e($item->notes); ?></textarea>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" form="editCountForm-<?php echo e($item->id); ?>" class="btn btn-primary">
                      <?php echo app('translator')->get('dashboard.save_changes'); ?>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>

        
        <div class="modal fade" id="addNewCount" tabindex="-1" aria-labelledby="addNewCountLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 id="addNewCountLabel" class="modal-title">
                  <?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.warehouse_sales')]); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo e(__('dashboard.close')); ?>"></button>
              </div>

              <div class="modal-body">
                <form action="<?php echo e(route('warehouse_sales.store')); ?>" id="saveCountDetails" method="POST">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" value="<?php echo e($order->id); ?>" name="order_id">
                  <input type="hidden" value="warehouse_sales" name="source">
                  <div class="mb-5 fv-row col-md-12">
                    <label class="required form-label"><?php echo e(__('dashboard.items')); ?></label>
                    <select name="stock_id" class="form-control js-stock" required>
                      <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($stock->id); ?>" data-price="<?php echo e($stock->selling_price); ?>"><?php echo e($stock->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                 <div class="form-group mt-3">
                    <label><?php echo e(__('dashboard.item_price')); ?></label>
                    <input type="number" step="0.01" disabled class="form-control js-total" value="<?php echo e($stock->selling_price); ?>">
                  </div>
                  <div class="form-group mt-3">
                    <label><?php echo e(__('dashboard.quantity')); ?></label>
                    <input type="number" name="quantity" class="form-control js-qty" value="1">
                  </div>

                  <div class="form-group mt-3">
                    <label><?php echo e(__('dashboard.total_amount')); ?></label>
                    <input type="number" step="0.01" name="total_price" class="form-control js-total" value="0">
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
                              <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>
                      </div>
                  <div class="mb-5 fv-row col-md-12">
                    <label class="form-label"><?php echo e(__('dashboard.notes')); ?></label>
                    <textarea name="notes" class="form-control mb-2"><?php echo e(old('notes')); ?></textarea>
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
    </div> 
  </div> 
</div> 
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    $(document).on('shown.bs.modal', '.modal', function () {
      const $modal = $(this);

      $modal.find('.js-stock').each(function () {
        const $sel = $(this);
        if (!$sel.hasClass('select2-hidden-accessible')) {
          $sel.select2({
            dropdownParent: $modal,
            width: '100%',
            placeholder: '<?php echo e(__("dashboard.items")); ?>'
          });
        }
        const current = $sel.data('initial');
        if (current && String($sel.val()) !== String(current)) {
          $sel.val(String(current)).trigger('change.select2');
        }
      });

      calcModal($modal[0]);
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
      $(this).find('.js-stock.select2-hidden-accessible').select2('destroy');
    });

    $(document).on('input change', '.modal .js-qty, .modal .js-stock', function () {
      const modalEl = this.closest('.modal');
      if (modalEl) calcModal(modalEl);
    });

    function calcModal(modalEl) {
      const $modal   = $(modalEl);
      const $stock   = $modal.find('.js-stock');
      const $qty     = $modal.find('.js-qty');
      const $total   = $modal.find('.js-total');

      const qtyVal   = parseFloat($qty.val()) || 0;
      const priceVal = parseFloat($stock.find(':selected').data('price')) || 0;
      const totalVal = (qtyVal * priceVal).toFixed(2);
      $total.val(totalVal);
      
    }

  });

  $(document).on('click', '.warehouse-receipt-link', function(e) {
      e.preventDefault();

      if ($(this).data('verified') == '1') {
          window.open($(this).data('url'), '_blank');
      } else {
          Swal.fire({
              icon: 'error',
              title: '<?php echo e(__("dashboard.error")); ?>',
              text: '<?php echo e(__("dashboard.warehouse_not_verified_receipt_error")); ?>',
              confirmButtonText: '<?php echo e(__("dashboard.ok")); ?>'
          });
      }
  });
</script>
 <?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/warehouse_sales/show.blade.php ENDPATH**/ ?>