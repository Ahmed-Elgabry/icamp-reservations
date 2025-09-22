<!-- resources/views/partials/popup.blade.php -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">@lang('dashboard.filter_orders_by_price')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('orders.index') }}">
                    <div class="mb-3">
                        <label for="price_min" class="form-label">@lang('dashboard.min_price')</label>
                        <input type="number" class="form-control" id="price_min" name="price_min"
                            placeholder="@lang('dashboard.min_price')" value="{{ request()->query('min_price') }}">
                    </div>
                    <div class="mb-3">
                        <label for="price_max" class="form-label">@lang('dashboard.max_price')</label>
                        <input type="number" class="form-control" id="price_max" name="price_max"
                            placeholder="@lang('dashboard.max_price')" value="{{ request()->query('max_price') }}">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">@lang('dashboard.price')</label>
                        <input type="text" data-kt-ecommerce-category-filter="search"
                            class="form-control form-control-solid w-250px ps-14"
                            placeholder="@lang('dashboard.price')" />
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