@section('pageTitle', __('dashboard.payments'))
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
                <div class="title">
                    <h1 class="text-dark fw-bolder fs-3 mb-0">@lang('dashboard.reservation_revenue')</h1>
                </div>
                        <!--begin::Card title-->
                <div class="card-title">
                    <form action="" method="GET" class="row">
                        <div class="form-group col-6 mt-3">
                            <label for=""> {{ __('dashboard.customer') }}</label>
                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value="">{{ __('dashboard.customer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-6 mt-3 ">
                            <label for="">{{ __('dashboard.order_id') }}</label>
                            <select name="order_id" id="order_id" class="form-control">
                                <option value=""> {{ __('dashboard.order_id') }}</option>
                                @foreach(App\Models\Order::all() as $order)
                                    @if($order->status == "approved" || $order->status == "delayed" || $order->status == "completed")
                                    <option value="{{ $order->id }}" {{ request('order_id') == $order->id ? 'selected' : '' }}>{{ $order->id }} [{{$order->customer->name }}]</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-6 mt-3">
                            <label for="">{{ __('dashboard.date_from') }}</label>
                            <input type="date" name="date_from" class="form-control" placeholder="{{ __('dashboard.date_from') }}" value="{{ request('date_from') }}">
                        </div>
                        <div class="form-group col-6 mt-3 ">
                            <label for="">{{ __('dashboard.date_to') }}</label>
                            <input type="date" name="date_to" class="form-control" placeholder="{{ __('dashboard.date_to') }}" value="{{ request('date_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">{{ __('dashboard.search') }} <i class="fa fa-search"></i></button>

                    </form>

                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    @if(count(request()->all()))
                        <a href="?">
                            <i class="fa fa-list"></i> {{__('dashboard.showall')}}
                        </a>
                    @endif
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
                        <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                            <th class="w-10px pe-2 text-center">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="fw-bolder">{{ __('dashboard.price') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.orders') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.statement') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.notes') }}</th>
                            <th class="fw-bolder">@lang('dashboard.created_date')</th>
                            <th class="fw-bolder">@lang('dashboard.created_time')</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($payments as $payment)
                            <tr data-id="{{$payment->id}}">
                                <!--begin::Checkbox-->
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$payment->id}}"/>
                                    </div>
                                </td>
                                <!--begin::Category=-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="#" data-kt-ecommerce-category-filter="search" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                                {{$payment->price}} {{__('dashboard.'. $payment->payment_method )}}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('payments.show', $payment->order_id) }}">
                                        {{ $payment->order_id }} [{{$payment->order->customer->name }}]
                                    </a>
                                </td>
                                <td>{{__('dashboard.'. $payment->statement )}}</td>
                                <td data-kt-ecommerce-category-filter="category_name">
                                    {{$payment->notes}}
                                </td>
                                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                <td>{{ $payment->created_at->format('h:i A') }}</td>
                                <!--end::Category=-->
                                <!--begin::Action=-->
                                <!-- <td class="text-end">-->
                            </tr>
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
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
        $("#customer_id ,#order_id").select2();
    </script>
@endpush
