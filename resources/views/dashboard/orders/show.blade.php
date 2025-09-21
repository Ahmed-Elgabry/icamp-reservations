@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.orders'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-5 mb-xl-10">
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
                        <div class="col-md-3 text-center">
                            <div class="fw-semibold text-muted">{{ __('dashboard.customer_phone') }}</div>
                            <div class="fw-bold">{{ $order->customer->phone }}</div>
                        </div>
                    </div>
                </div>
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
                </div>

                
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.the_first_and_last_name')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ $order->customer->name }}</p>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.people_count')</label>
                        <div class="col-lg-8">
                        <p class="form-control-plaintext">{{ $order->people_count }}</p>
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
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.addons')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ number_format($order->addons()->sum('order_addon.price'), 2) }}</p>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.deposit')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ number_format($order->deposit, 2) }}</p>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.insurance_amount')</label>
                        <div class="col-lg-8">
                            <p class="form-control-plaintext">{{ number_format($order->insurance_amount, 2) }}</p>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.internal_notes')</label>
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
                    <div>
                        <a href="{{ route('payments.show',$order->id) }}">
                          @lang('dashboard.payments') <span class="badge badge-primary">{{$order->totalPaidAmount()}} {{__('dashboard.from')}} {{ $order->price + $order->insurance_amount }}</span>
                       </a>
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