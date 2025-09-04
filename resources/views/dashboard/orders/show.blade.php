@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.orders'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

           

            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer">
                    <h3 class="card-title fw-bolder m-0"><a href="{{ route('orders.edit',$order->id) }}">{{ $order->customer->name }}</a>
                        <i class="fa fa-edit"></i>
                    </h3>
                </div>


                <div class="d-flex">
                        <div class="menu-item p-0">
                            <a href="{{route('orders.quote', $order->id)}}" class="btn btn-sm btn btn-primary m-2">
                                @lang('dashboard.Offer Price') <i class="fa fa-file"></i>
                            </a>
                        </div>
                        <div class="menu-item p-0">
                            <a href="{{route('orders.invoice', $order->id)}}" class="btn btn-sm btn btn-primary m-2">
                                @lang('dashboard.invoice') <i class="fa fa-file"></i>
                            </a>
                        </div>
                        @can('payments.show')
                        <div class="menu-item p-0">
                            <a href="{{route('payments.show', $order->id)}}" class="btn btn-sm btn btn-primary m-2">
                                {{__('dashboard.payments')}} 
                            </a>
                        </div>
                        @endcan 
                        @can('expenses.show')
                        <div class="menu-item p-0">
                            <a href="{{route('expenses.show', $order->id)}}" class="btn btn-sm btn btn-primary m-2">
                                {{__('dashboard.expenses')}}
                            </a>
                        </div>
                        @endcan 
                        @can('orders.reports')
                        <div class="menu-item p-0">
                            <a href="{{route('orders.reports', $order->id)}}" class="btn btn-sm btn btn-primary m-2">
                                {{__('dashboard.reports')}}
                            </a>
                        </div>
                        @endcan 
                </div>

                
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.customer')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->customer->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.service')</label>
                        <div class="col-lg-8">
                            <ul class="">
                                @foreach($order->services as $service)
                                    <li>{{ $service->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.price')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ number_format($order->price, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->notes }}</p>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.date')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->date }}</p>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_from')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->time_from }}</p>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_to')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->time_to }}</p>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_of_receipt')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->time_of_receipt }}</p>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.image_before_receiving')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">
                            @if($order->image_before_receiving)
                                <a href="{{ asset($order->image_before_receiving) }}">
                                    <img src="{{ asset($order->image_before_receiving) }}" style="width:120px;height:120px">
                                </a>
                            @endif 
                            </p>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.delivery_time')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->delivery_time }}</p>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.image_after_delivery')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">
                            @if($order->image_after_delivery)
                                <a href="{{ asset($order->image_after_delivery) }}">
                                    <img src="{{ asset($order->image_after_delivery) }}" style="width:120px;height:120px">
                                </a>
                            @endif 
                            </p>
                        </div>
                    </div>

                    @if($order->rate)
                    <div class="rating-form-group text-center">
                        <div class="rating-form-stars">
                            @for($i = 5; $i >= 1;$i--)
                                <label @if($order->rate->rating >= $i) class="checked" @endif f for="star{{$i}}">★</label>
                            @endfor
                        </div>
                        {{$order->rate->review}}
                    </div>
                    @endif

                    <hr>

                     <!-- Payments Details -->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                           <a href="{{ route('payments.show',$order->id) }}">
                                <i class="fa fa-edit"></i> @lang('dashboard.payments') <span class="badge badge-primary">{{$order->payments->sum('price')}}</span>
                           </a>
                        </label>
                        <div class="col-lg-8">
                            <ul class="">
                                @foreach($order->payments as $payment)
                                    <li>
                                        {{ __('dashboard.price') }}: {{ number_format($payment->price, 2) }},
                                        {{ __('dashboard.payment_method') }}: {{ $payment->payment_method }},
                                        {{ __('dashboard.notes') }}: {{ $payment->notes }}
                                        @if($payment->isInsuranceReturned())
                                            <br><span class="badge badge-success">{{ __('dashboard.insurance_returned_note') }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Expenses Details -->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                            <a href="{{ route('expenses.show',$order->id) }}">
                                <i class="fa fa-edit"></i> @lang('dashboard.expenses') <span class="badge badge-primary">{{$order->expenses->sum('price')}}</span>
                            </a>
                        </label>
                        <div class="col-lg-8">
                            <ul class="">
                                @foreach($order->expenses as $expense)
                                    <li>
                                        {{ __('dashboard.price') }}: {{ number_format($expense->price, 2) }},
                                        {{ __('dashboard.notes') }}: {{ $expense->notes }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                   
                   

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.status')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ __('dashboard.' . $order->status) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<style>
        .rating-form-stars {
            display: flex;
            justify-content: space-between;
            max-width: 200px;
            margin: 0 auto 20px;
        }

        .rating-form-stars label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .rating-form-stars  .checked {
            color: #f5c518;
        }
</style>
@endpush