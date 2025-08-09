
@section('pageTitle', __('dashboard.warehouse_sales'))
@extends('dashboard.layouts.app')
@section('content')

    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            @include('dashboard.orders.nav')

            <!--begin::Category-->
            <div class="card card-flush">
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
                            <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="@lang('dashboard.search_title', ['page_title' => __('dashboard.items')])" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->


                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Add customer-->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewCount">
                                @lang('dashboard.create_title', ['page_title' => __('dashboard.warehouse_sales')])
                        </button>
                        <!--end::Add customer-->
                        <span class="w-5px h-2px"></span>

                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">

                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>{{ __('dashboard.items') }}</th>
                        <th>{{ __('dashboard.count') }}</th>
                        <th>{{ __('dashboard.total_amount') }}</th>
                        <th>{{ __('dashboard.notes') }}</th>
                        <th>{{ __('dashboard.created_at') }}</th>
                        <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600">
                        <!--begin::Table row-->
                        @foreach ($items as $item)
                            <tr>
                                <td><span class="badge bg-primary">{{ $item?->stock->name }}</span></td>
                                <td>{{ $item?->quantity }}</td>
                                <td class="fw-bold">{{ $item?->total_price }}</td>
                                <td>{{ $item?->notes }}</td>
                                <td>{{ $item?->created_at->format('Y-m-d h:i A') }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        {{ __('dashboard.actions') }}
                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    </a>

                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" type="button" data-toggle="modal" data-target="#editCount-{{ $item->id }}" class="menu-link px-3">{{ __('actions.edit') }}</a>
                                        </div>

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('warehouse_sales.destroy', $item->id) }}"
                                                method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <a href="#"
                                            class="menu-link px-3 text-danger"
                                            onclick="event.preventDefault();
                                                        if(confirm('@lang('dashboard.delete')')) {
                                                            document.getElementById('delete-form-{{ $item->id }}').submit();
                                                        }">
                                                @lang('dashboard.delete')
                                            </a>
                                        </div>

                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                                <!--end::Action=-->
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="editCount-{{ $item->id }}" tabindex="-1" aria-labelledby="editCountLabel-{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 id="editCountLabel-{{ $item->id }}" class="modal-title">{{ __('actions.edit') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('dashboard.close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('warehouse_sales.update', $item->id) }}" id="editCountForm-{{ $item->id }}" method="POST">
                                    @csrf @method('PUT')

                                        <div class="mb-5 fv-row col-md-12">
                                            <label for="item_id" class="required form-label">{{ __('dashboard.items') }}</label>
                                            <select name="stock_id" id="item_id" class="form-control" data-price="0" required>
                                                @foreach($stocks as $stock)
                                                    <option value="{{ $stock->id }}" data-price="{{ $stock->price }}" @selected($stock->id == $item->stock_id)>{{ $stock->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="quantity">{{ __('dashboard.count') }}</label>
                                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $item->quantity }}">
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="price">{{ __('dashboard.total_amount') }}</label>
                                            <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" value="{{ $item->total_price }}">
                                        </div>

                                        <div class="mb-5 fv-row col-md-12">
                                            <label class=" form-label">{{ __('dashboard.notes') }}</label>
                                            <textarea name="notes" id="" class="form-control mb-2">{{  $item->notes }}</textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" form="editCountForm-{{ $item->id }}" class="btn btn-primary">@lang('dashboard.save_changes')</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        @endforeach
                        <!--end::Table row-->


                </tbody>
                <!--end::Table body-->
                </table>

                    <!-- Modal -->
                    <div class="modal fade" id="addNewCount" tabindex="-1" role="dialog" aria-labelledby="addNewCountLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addNewCountLabel"> @lang('dashboard.create_title', ['page_title' => __('dashboard.payments')])</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('warehouse_sales.store') }}" id="saveCountDetails" method="POST">
                                        @csrf
                                        <!--begin::Input group-->
                                        <input type="hidden" value="{{ $order->id }}" name="order_id">

                                        <div class="mb-5 fv-row col-md-12">
                                            <label for="item_id" class="required form-label">{{ __('dashboard.items') }}</label>
                                            <select name="stock_id" id="item_id" class="form-control item_id" data-price="0" required>
                                                @foreach($stocks as $stock)
                                                    <option value="{{ $stock->id }}" data-price="{{ $stock->price }}">{{ $stock->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="quantity">{{ __('dashboard.count') }}</label>
                                            <input type="number" name="quantity" id="quantity" class="form-control" value="1">
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="price">{{ __('dashboard.total_amount') }}</label>
                                            <input type="number" step="0.01" name="total_price" id="total_price" class="form-control total_price" value="0">
                                        </div>

                                        <!--begin::Input group-->
                                        <div class="mb-5 fv-row col-md-12">
                                            <label class=" form-label">{{ __('dashboard.notes') }}</label>
                                            <textarea name="notes" id="" class="form-control mb-2">{{  old('notes') }}</textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" form="saveCountDetails" class="btn btn-primary">@lang('dashboard.save_changes')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->


@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.item_id').select2();
            $('.item_id').on('change', function() {
                let price = $(this).find(':selected').data('price') || 0;
                $('#item_price').val(price);
                calcTotal();
            });

            $(document).on('input', '#quantity', function() {
                calcTotal();
            });

            $(document).on('input', '#item_price', function() {
                calcTotal();
            });

            function calcTotal() {
                let quantity = parseFloat($('#quantity').val()) || 0;
                let price = parseFloat($('#item_id').find(':selected').data('price')) || 0;
                let totalPrice = quantity * price;

                $('#total_price').val(totalPrice.toFixed(2));
            }

            calcTotal();
        });

        $(function () {
            $(document).on('shown.bs.modal', '.modal', function () {
                const $modal = $(this);
                $modal.find('.js-stock').each(function () {
                const $sel = $(this);
                if (!$sel.hasClass('select2-hidden-accessible')) {
                    $sel.select2({
                    dropdownParent: $modal,
                    width: '100%',
                    placeholder: '{{ __("dashboard.items") }}'
                    });
                }
                const current = $sel.data('initial');
                if (current && $sel.val() != String(current)) {
                    $sel.val(String(current)).trigger('change.select2');
                }
                });
            });

            $(document).on('hidden.bs.modal', '.modal', function () {
                $(this).find('.js-stock.select2-hidden-accessible').select2('destroy');
            });
            });
    </script>
@endpush
