@extends('dashboard.layouts.app')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">@lang('dashboard.orders')</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('home') }}" class="text-muted text-hover-primary">@lang('dashboard.home')</a>
                    </li>
                    <!--end::Item-->

                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">@lang('dashboard.orders')</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-solid w-250px ps-14" placeholder="Search Order">
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Flatpickr-->
                        <div class="input-group w-250px">
                            <input class="form-control form-control-solid rounded rounded-end-0 flatpickr-input"
                                placeholder="Pick date range" id="kt_ecommerce_sales_flatpickr" type="hidden">
                            {{-- <input class="form-control form-control-solid rounded rounded-end-0 form-control input" placeholder="Pick date range" tabindex="0" type="text" readonly="readonly"> --}}
                            <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr088.svg-->
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                                            rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor">
                                        </rect>
                                        <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                                            transform="rotate(45 8.46447 7.05029)" fill="currentColor"></rect>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </button>
                        </div>
                        <!--end::Flatpickr-->
                        <div class="w-100 mw-150px">
                            <!--begin::Select2-->
                            <select class="form-select form-select-solid select2-hidden-accessible" data-control="select2"
                                data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-order-filter="status"
                                data-select2-id="select2-data-10-pnkc" tabindex="-1" aria-hidden="true">
                                <option data-select2-id="select2-data-12-5axm"></option>
                                <option value="all">All</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Completed">Completed</option>
                                <option value="Denied">Denied</option>
                                <option value="Expired">Expired</option>
                                <option value="Failed">Failed</option>
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Refunded">Refunded</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Delivering">Delivering</option>
                            </select>
                            <!--end::Select2-->
                        </div>
                        <!--begin::Add product-->
                        <a href="../../demo1/dist/apps/ecommerce/catalog/add-product.html" class="btn btn-primary">Add
                            Order</a>
                        <!--end::Add product-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div id="kt_ecommerce_sales_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="kt_ecommerce_sales_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="" style="width: 29.25px;">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                    data-kt-check-target="#kt_ecommerce_sales_table .form-check-input"
                                                    value="1">
                                            </div>
                                        </th>
                                        <th class="min-w-100px sorting" tabindex="0"
                                            aria-controls="kt_ecommerce_sales_table" rowspan="1" colspan="1"
                                            aria-label="Order ID: activate to sort column ascending"
                                            style="width: 111.062px;">Order ID</th>
                                        <th class="min-w-175px sorting" tabindex="0"
                                            aria-controls="kt_ecommerce_sales_table" rowspan="1" colspan="1"
                                            aria-label="Customer: activate to sort column ascending"
                                            style="width: 225.266px;">Customer</th>
                                        <th class="text-end min-w-70px sorting" tabindex="0"
                                            aria-controls="kt_ecommerce_sales_table" rowspan="1" colspan="1"
                                            aria-label="Status: activate to sort column ascending"
                                            style="width: 80.875px;">Status</th>
                                        <th class="text-end min-w-100px sorting" tabindex="0"
                                            aria-controls="kt_ecommerce_sales_table" rowspan="1" colspan="1"
                                            aria-label="Total: activate to sort column ascending"
                                            style="width: 111.062px;">Total</th>
                                        <th class="text-end min-w-100px sorting" tabindex="0"
                                            aria-controls="kt_ecommerce_sales_table" rowspan="1" colspan="1"
                                            aria-label="Date Added: activate to sort column ascending"
                                            style="width: 111.062px;">Date Added</th>
                                        <th class="text-end min-w-100px sorting" tabindex="0"
                                            aria-controls="kt_ecommerce_sales_table" rowspan="1" colspan="1"
                                            aria-label="Date Modified: activate to sort column ascending"
                                            style="width: 111.062px;">Date Modified</th>
                                        <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Actions" style="width: 111.109px;">Actions</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600">

                                    @foreach ($orders as $order)
                                        <tr class="{{ $loop->iteration % 2 == 0 ? 'even' : 'odd' }}">
                                            <!--begin::Checkbox-->
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" value="1">
                                                </div>
                                            </td>
                                            <!--end::Checkbox-->
                                            <!--begin::Order ID=-->
                                            <td data-kt-ecommerce-order-filter="order_id">
                                                <a href="{{ route('orders.show', $order->id) }}"
                                                    class="text-gray-800 text-hover-primary fw-bolder">{{ $order->order_num }}</a>
                                            </td>
                                            <!--end::Order ID=-->
                                            <!--begin::Customer=-->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!--begin:: Avatar -->
                                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                        <a href="../../demo1/dist/apps/user-management/users/view.html">
                                                            <div class="symbol-label fs-3 bg-light-danger text-danger">
                                                                {{ $order->user->name }}</div>
                                                        </a>
                                                    </div>
                                                    <!--end::Avatar-->
                                                    <div class="ms-5">
                                                        <!--begin::Title-->
                                                        <a href="../../demo1/dist/apps/user-management/users/view.html"
                                                            class="text-gray-800 text-hover-primary fs-5 fw-bolder">{{ $order->user->name }}</a>
                                                        <!--end::Title-->
                                                    </div>
                                                </div>
                                            </td>
                                            <!--end::Customer=-->
                                            <!--begin::Status=-->
                                            <td class="text-end pe-0" data-order="{{ $order->status }}">
                                                <!--begin::Badges-->
                                                <div style="display: none">{{ $order->status }}</div>
                                                <div
                                                    class="badge  {{ $order->status == 'cancelled' ? ' badge-light-danger' : ' badge-light-success' }} ">
                                                    {{ $order->status_name }}</div>
                                                <!--end::Badges-->
                                            </td>
                                            <!--end::Status=-->
                                            <!--begin::Total=-->
                                            <td class="text-end pe-0">
                                                <span
                                                    class="fw-bolder">{{ $order->final_cost . ' ' . __('dashboard.RS') }}</span>
                                            </td>
                                            <!--end::Total=-->
                                            <!--begin::Date Added=-->
                                            <td class="text-end" data-order="{{ $order->created_at->format('Y-m-d') }}">
                                                <span class="fw-bolder">{{ $order->created_at->format('d/m/Y') }}</span>
                                            </td>
                                            <!--end::Date Added=-->
                                            <!--begin::Date Modified=-->
                                            <td class="text-end" data-order="{{ $order->created_at->format('Y-m-d') }}">
                                                <span class="fw-bolder">{{ $order->updated_at->format('d/m/Y') }}</span>
                                            </td>
                                            <!--end::Date Modified=-->
                                            <!--begin::Action=-->
                                            <td class="text-end">
                                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">Actions
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                    <span class="svg-icon svg-icon-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('orders.show', $order->id) }}"
                                                            class="menu-link px-3">View</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('orders.edit', $order->id) }}"
                                                            class="menu-link px-3">Edit</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3"
                                                            data-url="{{ route('orders.destroy', $order->id) }}"
                                                            data-id="{{ $order->id }}"
                                                            data-kt-ecommerce-order-filter="delete_row">Delete</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                            <!--end::Action=-->
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Container-->
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('dashboard/custom/js/orders.js') }}"></script>
@endsection
