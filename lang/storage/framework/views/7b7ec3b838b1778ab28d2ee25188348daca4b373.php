<!-- resources/views/partials/popup.blade.php -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">تصفية الأسهم حسب الكمية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="<?php echo e(route('stocks.index')); ?>">
                    <div class="mb-3">
                        <label for="quantity_min" class="form-label"><?php echo app('translator')->get('dashboard.min_quantity'); ?></label>
                        <input type="number" class="form-control" id="quantity_min" name="quantity_min"
                            placeholder="<?php echo app('translator')->get('dashboard.min_quantity'); ?>" value="<?php echo e(request()->query('quantity_min')); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="quantity_max" class="form-label"><?php echo app('translator')->get('dashboard.max_quantity'); ?></label>
                        <input type="number" class="form-control" id="quantity_max" name="quantity_max"
                            placeholder="<?php echo app('translator')->get('dashboard.max_quantity'); ?>" value="<?php echo e(request()->query('quantity_max')); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label"><?php echo app('translator')->get('dashboard.Quantity'); ?></label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                            placeholder="<?php echo app('translator')->get('dashboard.Quantity'); ?>" value="<?php echo e(request()->query('quantity')); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('dashboard.submit_fillter'); ?></button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/layouts/popUpSearch/stockSearchPopup.blade.php ENDPATH**/ ?>