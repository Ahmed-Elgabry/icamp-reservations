@section('pageTitle' , __('dashboard.bank-accounts'))

@extends('dashboard.layouts.app')
@section('content')


<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

<div class="row">

    <div class="card">
        <div class="card-body">
            <!--begin::Search Form-->
                <div class="row">

                    <!-- Order Date Range -->
                    <div class="col-md-4 mt-5">
                        <div class="form-group">
                            <label for="">أسم الحساب</label>
                           <p>{{$bank->name}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 mt-5">
                        <div class="form-group">
                            <label for="end_date">الرصيد:</label>
                            <p>{{$bank->balance}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 mt-5">
                        <div class="form-group">
                            <label for="end_date">رقم الحساب:</label>
                            <p>{{$bank->account_number}}</p>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        {{$bank->notes}}
                    </div>
                </div>
        </div>
    </div>
    <hr> 
    <div class="col-md-12">
        <!--begin::Category-->
    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        {{ __('dashboard.search') }} <i class="fas fa-arrow-down"></i>
                    </button>
                </h5>
            </div>

            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <!--begin::Search Form-->
                    <form action="?" method="GET">
                        <div class="row">

                            <!-- Order Date Range -->
                            <div class="col-md-6 mt-5">
                                <div class="form-group">
                                    <label for="start_date">بحث من :</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request()->start_date }}" placeholder="بحث من ">
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <div class="form-group">
                                    <label for="end_date">بحث الي:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->end_date }}"  placeholder="بحث الي">
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> {{ __('dashboard.search') }}
                                    </button>
                                    @if(request()->all())
                                        <a href="?" class="btn btn-secondary">
                                            {{ __('dashboard.reset') }}
                                        </a>
                                    @endif 
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Search Form-->
                </div>
            </div>
        </div>
    </div>
    <!--end::Category-->
    <hr>


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
                            <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="@lang('dashboard.search_title', ['page_title' => __('dashboard.bank-accounts')])" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        @can('payments.create')
                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                            @lang('dashboard.create_title', ['page_title' => __('dashboard.payments')])
                        </a>
                        @endcan 
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @include('dashboard.banks.table')
                <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->
            </div> <!--- end row -->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
					
@endsection
