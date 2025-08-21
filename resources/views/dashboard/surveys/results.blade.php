
@section('pageTitle', __('dashboard.answers'))

@extends('dashboard.layouts.app')
@section('content')
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Category-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="{{ __('dashboard.search_answer') }}" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Export-->
                    <button type="button" class="btn btn-flex btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor" />
                                <path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor" />
                                <path d="M18.75 8.25H17.25C16.8358 8.25 16.5 8.58579 16.5 9C16.5 9.41421 16.8358 9.75 17.25 9.75H18C18.4142 9.75 18.75 10.0858 18.75 10.5V16.5C18.75 16.9142 18.4142 17.25 18 17.25H6C5.58579 17.25 5.25 16.9142 5.25 16.5V10.5C5.25 10.0858 5.58579 9.75 6 9.75H6.75C7.16421 9.75 7.5 9.41421 7.5 9C7.5 8.58579 7.16421 8.25 6.75 8.25H5.25C4.42157 8.25 3.75 8.92157 3.75 9.75V17.25C3.75 18.0784 4.42157 18.75 5.25 18.75H18.75C19.5784 18.75 20.25 18.0784 20.25 17.25V9.75C20.25 8.92157 19.5784 8.25 18.75 8.25Z" fill="currentColor" />
                            </svg>
                        </span>
                        @lang('dashboard.export')
                    </button>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('surveys.results.export.excel',1) }}" class="menu-link px-3" data-kt-ecommerce-category-filter="export_excel">
                                @lang('dashboard.export_as_excel')
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('surveys.results.export.pdf',1) }}" class="menu-link px-3" data-kt-ecommerce-category-filter="export_pdf">
                                تصدير كـ PDF
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                    <!--end::Export-->
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
                            <th class="">@lang('dashboard.serial_number')</th>
                            <th class="">@lang('dashboard.order_number')</th>
                            <th class="">@lang('dashboard.customer_name')</th>
                            <th class="">@lang('dashboard.answer_date')</th>
                            <th class="">@lang('dashboard.answers_count')</th>
                            <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->

                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($responses as $response)
                            <!--begin::Table row-->
                            <tr data-id="{{$response->id}}">
                                <td>{{ $responses->firstItem() + $loop->index }}</td>
                                <td> <a href="{{ $response->order ? url('orders/' . $response->order->id . '/edit') : '#' }}" class="badge bg-primary">{{ $response->reservation_id }}</a></td>

                                <!--begin::Customer Name-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="{{ $response->order && $response->order->customer ? url('customers/' . $response->order->customer->id . '/edit') : '#' }}" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                                {{ $response->order? ($response->order->customer ? $response->order->customer->name : __('dashboard.not_available')) : __('dashboard.not_available') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <!--end::Customer Name-->

                                <!--begin::Response Date-->
                                <td>{{ $response->created_at->format('Y-m-d') }}</td>
                                <!--end::Response Date-->

                                <!--begin::Answers Count-->
                                <td>{{ $response->answers->whereNotNull('answer_text')->count() }}</td>
                                <!--end::Answers Count-->

                                <!--begin::Actions-->
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        @lang('dashboard.actions')
                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                    </a>

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="{{route('surveys.answer', $response->id)}}" class="menu-link px-3">@lang('dashboard.view_answers')</a>
                                        </div>
                                    </div>
                                </td>
                                <!--end::Actions-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach

                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
                {{ $responses->links() }}
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->

@endsection
