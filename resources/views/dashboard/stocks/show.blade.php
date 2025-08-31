 
@section('pageTitle' , __('dashboard.stocks'))
@extends('dashboard.layouts.app')
@section('content')
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Subheader-->

            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Profile Overview d-flex flex-row -->
                  
                        <!--begin::Aside  flex-row-auto offcanvas-mobile w-300px w-xl-350px -->
                        <div class="" id="kt_profile_aside" style="padding-left: 20px;">
                            <!--begin::Profile Card-->
                            <div class="card card-custom card-stretch" style=";">
                                <!--begin::Body-->
                                <div class="card-body pt-4">
                                    <!--begin::User-->
                                    <div class="d-flex align-items-center">
                                        @if($stock->image)
                                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                            <a href="{{ asset($stock->image) }}"> <img src="{{ asset($stock->image) }}" style="width:120px;height:120px;"></a>
                                        </div>
                                        @endif 
                                        <div>
                                            <a href="{{ route('stocks.edit',$stock->id) }}" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">
                                                {{$stock->name}} <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!--end::User-->

                                    <!--begin::Nav-->
                                    <div class="navi navi-bold navi-hover navi-active navi-link-rounded py-2" style="padding:0">
                                        <div class="navi-item mb-2">
                                            <a href="#" class="navi-link py-4">
                                                <span class="navi-text font-size-lg"> <strong>@lang('dashboard.quantity'): </strong>{{$stock->quantity}} </span>
                                            </a>
                                        </div>
                                        <hr />
                                        <div class="navi-item mb-2">
                                            <a href="#" class="navi-link py-4">
                                                <span class="navi-text font-size-lg"> <strong>@lang('dashboard.Percentage'): </strong>{{$stock->percentage}} </span>
                                            </a>
                                        </div>
                                        <hr />
                                        <div class="navi-item mb-2">
                                            <a href="#" class="navi-link py-4">
                                                <span class="navi-text font-size-lg">
                                                    {{$stock->description}}
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Profile Card-->
                        </div>
                        <!--end::Aside-->
                        <div class="card card-custom gutter-b" style="width: 100%;">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">@lang('dashboard.activities')</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <ul>
                                    @foreach($orders as $order)
                                        <li>
                                            <a href="{{ route('orders.show',$order->id) }}">
                                                [{{$order->id}}] {{$order->customer->name}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--end::Container-->
                <!--end::Entry-->
            </div>
        </div>
    </div>
</div>

@endsection
