<!-- resources/views/partials/popup.blade.php -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">تصفية الأسهم حسب الكمية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('stocks.index') }}">
                    <div class="mb-3">
                        <label for="quantity_min" class="form-label">@lang('dashboard.min_quantity')</label>
                        <input type="number" class="form-control" id="quantity_min" name="quantity_min"
                            placeholder="@lang('dashboard.min_quantity')" value="{{ request()->query('quantity_min') }}">
                    </div>
                    <div class="mb-3">
                        <label for="quantity_max" class="form-label">@lang('dashboard.max_quantity')</label>
                        <input type="number" class="form-control" id="quantity_max" name="quantity_max"
                            placeholder="@lang('dashboard.max_quantity')" value="{{ request()->query('quantity_max') }}">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">@lang('dashboard.Quantity')</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                            placeholder="@lang('dashboard.Quantity')" value="{{ request()->query('quantity') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('dashboard.submit_fillter')</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>