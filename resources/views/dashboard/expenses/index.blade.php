@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.expenses'))

@section('content')


<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Category-->
        <div class="card card-flush">
        <!--begin::Category-->
         <div class="card card-flush mt-10">
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
                        <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="@lang('dashboard.search_title', ['page_title' => __('dashboard.expense-items')])" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    @can('expenses.create')
                    <!--begin::Add customer-->
                    <a href="{{ route('expenses.create')}}" class="btn btn-success">
                        @lang('dashboard.create_title', ['page_title' => __('dashboard.expenses')])
                        <i class="fa fa-plus"></i>
                    </a>
                    <!--end::Add customer-->
                    @endcan

                </div>
                <!--end::Card toolbar-->
            </div>

            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">


            @include('dashboard.expenses.table')

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->

            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->

@endsection
@push('css')
<style>
    .total-price-h {
        padding: 20px;
    background: #fffcfc;
    width: 30%;
    text-align: center;
    border-radius: 5%;
    border: 1px solid #000;
    }
</style>
@endpush
