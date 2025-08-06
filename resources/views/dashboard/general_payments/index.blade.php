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
                <div class="col-4 mt-3">

                    <a href="{{route('general_payments.create')}}"
                        class="btn btn-primary">{{ __('dashboard.add') }}</a>
                </div>
                <!--begin::Card title-->
                <div class="card-title">
                    <form action="" method="GET" class="row">
                        <div class="form-group col-4 mt-3 ">
                            <label for="">{{ __('dashboard.orders') }}</label>
                            <select name="order_id" id="order_id" class="form-control">
                                <option value="">{{ __('dashboard.select') }} {{ __('dashboard.orders') }}</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ request('order_id') == $order->id ? 'selected' : '' }}>{{ $order->id }} [{{$order->customer->name }}]</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-4 mt-3">
                            <label for="">{{ __('dashboard.date_from') }}</label>
                            <input type="date" name="date_from" class="form-control"
                                placeholder="{{ __('dashboard.date_from') }}" value="{{ request('date_from') }}">
                        </div>
                        <div class="form-group col-4 mt-3 ">
                            <label for="">{{ __('dashboard.date_to') }}</label>
                            <input type="date" name="date_to" class="form-control"
                                placeholder="{{ __('dashboard.date_to') }}" value="{{ request('date_to') }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">{{ __('dashboard.search') }} <i
                                class="fa fa-search"></i></button>

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
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_ecommerce_category_table .form-check-input"
                                        value="1" />
                                </div>
                            </th>
                            <th>{{ __('dashboard.price') }}</th>
                            <th class="">{{ __('dashboard.orders') }}</th>
                            <th class="">{{ __('dashboard.statement') }}</th>
                            <th class="">{{ __('dashboard.verified') }}</th>
                            <th class="">{{ __('dashboard.notes') }}</th>
                            <th class="">{{ __('dashboard.created_at') }}</th>
                            <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($general_payments as $payment)
                            <tr data-id="{{$payment->id}}">
                                <!--begin::Checkbox-->
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input checkSingle" type="checkbox" value="1"
                                            id="{{$payment->id}}" />
                                    </div>
                                </td>
                                <!--begin::Category=-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="#" data-kt-ecommerce-category-filter="search"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                                {{$payment->price}} {{__('dashboard.' . $payment->payment_method)}}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('general_payments.show', $payment->order_id) }}">
                                        {{ $payment->order_id }} @isset($order) [{{$order->customer->name }}] @endisset
                                    </a>
                                </td>
                                <td>{{__('dashboard.' . $payment->statement)}}</td>
                                <td>
                                    {{ $payment->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                    @if($payment->verified)
                                        <a href="{{ route('payments.verified', $payment->id) }}"
                                            class="btn btn-sm btn-danger">{{ __('dashboard.mark') }}
                                            {{ __('dashboard.unverifyed') }}</a>
                                    @else
                                        <a href="{{ route('payments.verified', $payment->id) }}"
                                            class="btn btn-sm btn-success">{{ __('dashboard.mark') }}
                                            {{ __('dashboard.verified') }} <i class="fa fa-check"></i></a>
                                    @endif
                                </td>
                                <td data-kt-ecommerce-category-filter="category_name">
                                    {{$payment->notes}}
                                </td>
                                <td>
                                    {{$payment->created_at->diffForHumans() }}
                                </td>
                                <!--end::Category=-->
                                <!--begin::Action=-->
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        {{ __('dashboard.actions') }}
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
                                            <a href="{{ route('payments.print', $payment->id) }}"
                                                class="menu-link px-3">{{ __('dashboard.invoice') }}</a>
                                        </div>
                                        @can('payments.destroy')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3"
                                                    data-kt-ecommerce-category-filter="delete_row"
                                                    data-url="{{route('payments.destroy', $payment->id)}}"
                                                    data-id="{{$payment->id}}"> @lang('dashboard.delete')</a>
                                            </div>
                                        @endcan
                                    </div>
                                </td>
                                <!--end::Action=-->
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
        $("#customer_id").select2();
    </script>
@endpush
