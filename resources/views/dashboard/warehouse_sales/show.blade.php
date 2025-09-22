@section('pageTitle', __('dashboard.warehouse_sales'))
@extends('dashboard.layouts.app')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">

        @include('dashboard.orders.nav')

        <div class="card card-flush">
            <!-- customer information -->
            <div class="pt-5 px-9 gap-2 gap-md-5">
                <div class="row g-3 small">
                    <div class="col-md-1 text-center">
                        <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                        <div class="fw-bold">{{ $order->id }}</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="fw-semibold text-muted">{{ __('dashboard.customer_name') }}</div>
                        <div class="fw-bold">{{ $order->customer->name }}</div>
                    </div>
                </div>
            </div>
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" data-kt-ecommerce-category-filter="search"
                            class="form-control form-control-solid w-250px ps-14"
                            placeholder="@lang('dashboard.search_title', ['page_title' => __('dashboard.items')])" />
                    </div>
                </div>

                <div class="card-toolbar">
                    @can('bookings.warehouse-sales.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewCount">
                        @lang('dashboard.create_title', ['page_title' => __('dashboard.warehouse_sales')])
                    </button>
                    @endcan
                    <span class="w-5px h-2px"></span>
                </div>
            </div>

            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>{{ __('dashboard.items') }}</th>
                            <th>{{ __('dashboard.item_price') }}</th>
                            <th>{{ __('dashboard.quantity') }}</th>
                            <th>{{ __('dashboard.total_amount') }}</th>
                            <th>{{ __('dashboard.payment_method') }}</th>
                            <th>{{ __('dashboard.bank_account') }}</th>
                            <th>{{ __('dashboard.verified') }}</th>
                            <th>{{ __('dashboard.notes') }}</th>
                            <th>{{ __('dashboard.created_date') }}</th>
                            <th>{{ __('dashboard.created_time') }}</th>
                            <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($items as $item)
                        <tr>
                            <td><span class="badge bg-primary">{{ $item?->stock->name }}</span></td>
                            <td class="fw-bold">{{ $item?->stock->selling_price  % 1 === 0 ? (int) $item?->stock->selling_price : $item?->stock->selling_price }}</td>
                            <td>{{ (int) $item?->quantity }}</td>
                            <td class="fw-bold">{{ $item?->total_price % 1 === 0 ? (int) $item?->total_price : $item?->total_price }}</td>
                            <td>{{__('dashboard.'. $item->payment_method )}}</td>
                            <td>{{ $item?->account->name }}</td>
                            <td>
                                {{ $item->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                @if($item->verified)
                                <a href="{{ route('order.verified' , [$item->id , 'warehouse_sales']) }}" class="btn btn-sm btn-danger">{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                @else
                                <a href="{{ route('order.verified' , [$item->id , 'warehouse_sales']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                @endif
                            </td>
                            <td>{{ $item?->notes }}</td>
                            <td>{{ $item?->created_at->format('Y-m-d') }}</td>
                            <td>{{ $item?->created_at->format('h:i A') }}</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    {{ __('dashboard.actions') }}
                                    <span class="svg-icon svg-icon-5 m-0">...</span>
                                </a>

                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600
                            menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="#"
                                            class="menu-link px-3 warehouse-receipt-link"
                                            data-verified="{{ $item->verified ? '1' : '0' }}"
                                            data-url="{{ route('warehouse.receipt', ['order' => $order->id, 'warehouse' => $item->id]) }}">
                                            {{ __('dashboard.receipt') }}
                                        </a>
                                    </div>
                                    @can('bookings.warehouse-sales.edit')
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-menu-dismiss="true"
                                            data-bs-toggle="modal" data-bs-target="#editCount-{{ $item->id }}">
                                            {{ __('actions.edit') }}
                                        </a>
                                    </div>
                                    @endcan
                                    <div class="menu-item px-3">
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('warehouse_sales.destroy', $item->id) }}" method="POST" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                        @can('bookings.warehouse-sales.destroy')
                                        <a href="#" class="menu-link px-3 text-danger"
                                            onclick="confirmDelete('{{ route('warehouse_sales.destroy', $item->id) }}', '{{ csrf_token() }}')">
                                            @lang('dashboard.delete')
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Modal (inside loop) --}}
                        <div class="modal fade" id="editCount-{{ $item->id }}" tabindex="-1"
                            aria-labelledby="editCountLabel-{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 id="editCountLabel-{{ $item->id }}" class="modal-title">{{ __('actions.edit') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('dashboard.close') }}"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('warehouse_sales.update', $item->id) }}"
                                            id="editCountForm-{{ $item->id }}" method="POST">
                                            @csrf @method('PUT')

                                            <div class="mb-5 fv-row col-md-12">
                                                <label class="required form-label">{{ __('dashboard.items') }}</label>
                                                <select name="stock_id" class="form-control js-stock"
                                                    data-initial="{{ $item->stock_id }}" required>
                                                    <option value="">{{ __('dashboard.select') }}</option>
                                                    @foreach($stocks as $stock)
                                                    <option value="{{ $stock->id }}" data-price="{{ $stock->selling_price }}"
                                                        @selected($stock->id == $item->stock_id)>{{ $stock->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" value="warehouse_sales" name="source">
                                            <div class="form-group mt-3">
                                                <label>{{ __('dashboard.quantity') }}</label>
                                                <input type="number" name="quantity" class="form-control js-qty"
                                                    value="{{(int) $item?->quantity }}">
                                            </div>

                                            <div class="form-group mt-3">
                                                <label>{{ __('dashboard.total_amount') }}</label>
                                                <input type="number" step="0.01" name="total_price" class="form-control js-total"
                                                    value="{{ $item->total_price }}">
                                            </div>

                                            <div class="mb-5 fv-row col-md-12">
                                                <label class="required form-label">{{ __('dashboard.bank_account') }}</label>
                                                <select name="account_id" id="" class="form-select" required>
                                                    <option value="">{{ __('dashboard.select') }}</option>
                                                    @foreach($bankAccounts as $id => $name)
                                                    <option @selected($item->account_id == $id) value="{{$id}}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-5 fv-row col-md-12">
                                                <label class="required form-label">{{ __('dashboard.payment_method') }}</label>
                                                <select name="payment_method" id="" class="form-select" required>
                                                    <option value="">{{ __('dashboard.select') }}</option>
                                                    @foreach(paymentMethod() as $paymentSelect)
                                                    <option @selected($item->payment_method == $paymentSelect) value="{{$paymentSelect}}">{{__('dashboard.'. $paymentSelect )}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-5 fv-row col-md-12">
                                                <label class="form-label">{{ __('dashboard.notes') }}</label>
                                                <textarea name="notes" class="form-control mb-2">{{ $item->notes }}</textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" form="editCountForm-{{ $item->id }}" class="btn btn-primary">
                                            @lang('dashboard.save_changes')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>

                {{-- Add Modal --}}
                <div class="modal fade" id="addNewCount" tabindex="-1" aria-labelledby="addNewCountLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 id="addNewCountLabel" class="modal-title">
                                    @lang('dashboard.create_title', ['page_title' => __('dashboard.warehouse_sales')])
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('dashboard.close') }}"></button>
                            </div>

                            <div class="modal-body">
                                <form action="{{ route('warehouse_sales.store') }}" id="saveCountDetails" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $order->id }}" name="order_id">
                                    <input type="hidden" value="warehouse_sales" name="source">
                                    <div class="mb-5 fv-row col-md-12">
                                        <label class="required form-label">{{ __('dashboard.items') }}</label>
                                        <select name="stock_id" class="form-control js-stock" required>
                                            <option value="">{{ __('dashboard.select') }}</option>
                                            @foreach($stocks as $stock)
                                            <option value="{{ $stock->id }}" data-price="{{ $stock->selling_price }}">{{ $stock->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label>{{ __('dashboard.item_price') }}</label>
                                        <input type="number" step="0.01" disabled class="form-control js-total" value="{{ $stock->selling_price }}">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label>{{ __('dashboard.quantity') }}</label>
                                        <input type="number" name="quantity" class="form-control js-qty" value="1">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label>{{ __('dashboard.total_amount') }}</label>
                                        <input type="number" step="0.01" name="total_price" class="form-control js-total" value="0">
                                    </div>


                                    <div class="mb-5 fv-row col-md-12">
                                        <label class="required form-label">{{ __('dashboard.payment_method') }}</label>
                                        <select name="payment_method" id="" class="form-select" required>
                                            <option value="">{{ __('dashboard.select') }}</option>
                                            @foreach(paymentMethod() as $paymentSelect)
                                            <option value="{{$paymentSelect}}">{{__('dashboard.'. $paymentSelect )}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-5 fv-row col-md-12">
                                        <label class="required form-label">{{ __('dashboard.bank_account') }}</label>
                                        <select name="account_id" id="" class="form-select" required>
                                            <option value="">{{ __('dashboard.select') }}</option>
                                            @foreach($bankAccounts as $id => $name)
                                            <option value="{{$id}}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-5 fv-row col-md-12">
                                        <label class="form-label">{{ __('dashboard.notes') }}</label>
                                        <textarea name="notes" class="form-control mb-2">{{ old('notes') }}</textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" form="saveCountDetails" class="btn btn-primary">@lang('dashboard.save_changes')</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- card-body --}}
        </div> {{-- card --}}
    </div> {{-- container --}}
</div> {{-- post --}}
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        $(document).on('shown.bs.modal', '.modal', function() {
            const $modal = $(this);

            $modal.find('.js-stock').each(function() {
                const $sel = $(this);
                if (!$sel.hasClass('select2-hidden-accessible')) {
                    $sel.select2({
                        dropdownParent: $modal,
                        width: '100%',
                        placeholder: '{{ __("dashboard.items") }}'
                    });
                }
                const current = $sel.data('initial');
                if (current && String($sel.val()) !== String(current)) {
                    $sel.val(String(current)).trigger('change.select2');
                }
            });

            calcModal($modal[0]);
        });

        $(document).on('hidden.bs.modal', '.modal', function() {
            $(this).find('.js-stock.select2-hidden-accessible').select2('destroy');
        });

        $(document).on('input change', '.modal .js-qty, .modal .js-stock', function() {
            const modalEl = this.closest('.modal');
            if (modalEl) calcModal(modalEl);
        });

        function calcModal(modalEl) {
            const $modal = $(modalEl);
            const $stock = $modal.find('.js-stock');
            const $qty = $modal.find('.js-qty');
            const $total = $modal.find('.js-total');

            const qtyVal = parseFloat($qty.val()) || 0;
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
                title: '{{ __("dashboard.error") }}',
                text: '{{ __("dashboard.warehouse_not_verified_receipt_error") }}',
                confirmButtonText: '{{ __("dashboard.ok") }}'
            });
        }
    });

    // Add warehouse sales form validation
    $('#saveCountDetails').on('submit', function(e) {
        let stockSelect = $(this).find('select[name="stock_id"]')[0];
        let paymentMethodSelect = $(this).find('select[name="payment_method"]')[0];
        let accountSelect = $(this).find('select[name="account_id"]')[0];

        // Validate stock selection
        if (!stockSelect.value || stockSelect.value === '') {
            stockSelect.setCustomValidity('{{ __("dashboard.required") }}');
            stockSelect.reportValidity();
            e.preventDefault();
            return false;
        } else {
            stockSelect.setCustomValidity('');
        }

        // Validate payment method selection
        if (!paymentMethodSelect.value || paymentMethodSelect.value === '') {
            paymentMethodSelect.setCustomValidity('{{ __("dashboard.required") }}');
            paymentMethodSelect.reportValidity();
            e.preventDefault();
            return false;
        } else {
            paymentMethodSelect.setCustomValidity('');
        }

        // Validate account selection
        if (!accountSelect.value || accountSelect.value === '') {
            accountSelect.setCustomValidity('{{ __("dashboard.required") }}');
            accountSelect.reportValidity();
            e.preventDefault();
            return false;
        } else {
            accountSelect.setCustomValidity('');
        }
    });

    // Edit warehouse sales form validation
    $('form[id^="editCountForm"]').on('submit', function(e) {
        let stockSelect = $(this).find('select[name="stock_id"]')[0];
        let paymentMethodSelect = $(this).find('select[name="payment_method"]')[0];
        let accountSelect = $(this).find('select[name="account_id"]')[0];

        // Validate stock selection
        if (!stockSelect.value || stockSelect.value === '') {
            stockSelect.setCustomValidity('{{ __("dashboard.required") }}');
            stockSelect.reportValidity();
            e.preventDefault();
            return false;
        } else {
            stockSelect.setCustomValidity('');
        }

        // Validate payment method selection
        if (!paymentMethodSelect.value || paymentMethodSelect.value === '') {
            paymentMethodSelect.setCustomValidity('{{ __("dashboard.required") }}');
            paymentMethodSelect.reportValidity();
            e.preventDefault();
            return false;
        } else {
            paymentMethodSelect.setCustomValidity('');
        }

        // Validate account selection
        if (!accountSelect.value || accountSelect.value === '') {
            accountSelect.setCustomValidity('{{ __("dashboard.required") }}');
            accountSelect.reportValidity();
            e.preventDefault();
            return false;
        } else {
            accountSelect.setCustomValidity('');
        }
    });

    // Clear validation when user selects a valid option
    $(document).on('change', 'select[name="stock_id"], select[name="payment_method"], select[name="account_id"]', function() {
        if (this.value && this.value !== '') {
            this.setCustomValidity('');
        }
    });
</script>
@endpush