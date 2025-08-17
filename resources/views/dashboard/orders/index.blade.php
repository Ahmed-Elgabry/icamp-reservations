@section('pageTitle', __('dashboard.orders'))

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

                        <!-- زر فتح النافذة المنبثقة -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            @lang('dashboard.price_fillter')
                        </button>

                        @include('dashboard.layouts.popUpSearch.orderSearchPopup')

                    </div>
                    <!--end::Search-->
                    <!-- QR Scanner -->

                    <button class="btn btn-light-primary m-2"
                        id="startCamera">@lang('dashboard.open_camera_to_scan_qr')</button>
                    <button class="btn btn-light-info m-2" id="takePhoto"
                        style="display: none;">@lang('dashboard.take_picture')</button>
                    <button class="btn btn-light-danger m-2" id="stopCamera"
                        style="display: none;">@lang('dashboard.stop_cqamera')</button>

                    <div id="reader" style="width: 500px; height: 300px; display: none;"></div>
                    <canvas id="canvas" style="display:none;"></canvas>

                    <script>
                        let html5QrCode;

                        // بدء تشغيل الكاميرا
                        document.getElementById('startCamera').addEventListener('click', function () {
                            const readerDiv = document.getElementById('reader');
                            readerDiv.style.display = 'block'; // عرض الكاميرا

                            // إظهار أزرار الإيقاف والتقاط الصورة
                            document.getElementById('stopCamera').style.display = 'inline-block';
                            document.getElementById('takePhoto').style.display = 'inline-block';

                            html5QrCode = new Html5Qrcode("reader");

                            Html5Qrcode.getCameras().then(devices => {
                                if (devices && devices.length) {
                                    let cameraId = devices[0].id;

                                    html5QrCode.start(
                                        cameraId, {
                                        fps: 10,
                                        qrbox: {
                                            width: 250,
                                            height: 250
                                        }
                                    },
                                        qrCodeMessage => {
                                            alert(`تم مسح QR Code: ${qrCodeMessage}`);
                                        },
                                        errorMessage => {
                                            console.log(`لم يتم التعرف على QR Code: ${errorMessage}`);
                                        }
                                    ).catch(err => {
                                        console.error(`خطأ في بدء الكاميرا: ${err}`);
                                    });
                                } else {
                                    alert("لم يتم العثور على كاميرا.");
                                }
                            }).catch(err => {
                                console.error(`خطأ في الحصول على الكاميرات: ${err}`);
                            });
                        });

                        // إيقاف تشغيل الكاميرا
                        document.getElementById('stopCamera').addEventListener('click', function () {
                            html5QrCode.stop().then(() => {
                                document.getElementById('reader').style.display = 'none'; // إخفاء الكاميرا
                                document.getElementById('stopCamera').style.display = 'none';
                                document.getElementById('takePhoto').style.display = 'none';
                            }).catch(err => {
                                console.error(`خطأ في إيقاف الكاميرا: ${err}`);
                            });
                        });

                        // التقاط صورة من الفيديو
                        document.getElementById('takePhoto').addEventListener('click', function () {
                            const videoElement = document.querySelector("#reader video");
                            if (videoElement) {
                                const canvas = document.getElementById('canvas');
                                canvas.style.display = 'block';
                                const context = canvas.getContext('2d');
                                canvas.width = videoElement.videoWidth;
                                canvas.height = videoElement.videoHeight;

                                // نسخ إطار الفيديو إلى الـcanvas
                                context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

                                // تحويل الـcanvas إلى صورة
                                const imageData = canvas.toDataURL('image/png');
                                const link = document.createElement('a');
                                link.href = imageData;
                                link.download = 'photo.png'; // اسم الصورة
                                link.click(); // تحميل الصورة
                            }
                        });
                    </script>
                    <!-- QR Scanner End -->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Add customer-->
                    @can('orders.create')
                        <a href="{{ route('orders.create')}}"
                            class="btn btn-primary">@lang('dashboard.create_title', ['page_title' => __('dashboard.orders')])</a>
                    @endcan
                    <!--end::Add customer-->
                    <span class="w-5px h-2px"></span>
                    @can('orders.deleteAll')
                        <button type="button" data-route="{{route('orders.deleteAll')}}"
                            class="btn btn-danger delete_all_button">
                            <i class="feather icon-trash"></i>@lang('dashboard.delete_selected')</button>
                    @endcan
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                {{$orders->links()}}
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="">@lang('dashboard.sequence')</th>
                            <th class="">@lang('dashboard.reservation_number')</th>
                            <th class="">@lang('dashboard.date')</th>
                            <th class="">@lang('dashboard.service')</th>
                            <th class="">@lang('dashboard.customer')</th>
                            <th class="">@lang('dashboard.Duration of hours')</th>
                            <th class="">@lang('dashboard.time_from')</th>
                            <th class="">@lang('dashboard.paied')</th>
                            <th class="">@lang('dashboard.status')</th>
                            <th class="">@lang('dashboard.insurance_status')</th>
                            <th class="">@lang('dashboard.rate link')</th>
                            <th class="">@lang('dashboard.created_by')</th>
                            <th class="">@lang('dashboard.Agree_to_the_terms')</th>
                            <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->

                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($orders as $order)
                            <!--begin::Table row-->
                            <tr data-id="{{$order->id}}">
                                <td>{{ $orders->firstItem() + $loop->index }}</td>
                                <td> <span class="badge bg-primary">{{ $order->id }}</span></td>

                                <!--begin::Order Date-->
                                <td>
                                    <a href="{{ route('orders.edit', $order->id) }}">{{$order->date }}</a>
                                </td>
                                <!--end::Order Date-->

                                <!--begin::Services-->
                                <td>
                                    <a href="{{ route('orders.edit', $order->id) }}">
                                        <ul>
                                            @foreach($order->services as $service)
                                                <li>{{$service->name}}</li>
                                            @endforeach
                                        </ul>
                                    </a>
                                </td>
                                <!--end::Services-->

                                <!--begin::Customer Name-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="{{ route('orders.edit', $order->id) }}"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                                {{$order->customer->name}}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <!--end::Customer Name-->

                                <!--begin::Order Hours-->
                                <td>{{ $order->addHoursCount() }}</td>
                                <!--end::Order Hours-->

                                <!--begin::Time From-->
                                <td>{{ $order->time_from ? \Carbon\Carbon::createFromFormat('H:i:s', $order->time_from)->format('h:i A') : '' }}
                                </td>
                                <!--end::Time From-->

                                <!--begin::Payments-->
                                @php $totalPrice = ($order->price + $order->deposit + $order->insurance_amount + $order->addons->sum('price')) @endphp
                                <td>
                                    <span class="text-success">
                                        {{ __('dashboard.paied') }}
                                        {{ number_format($order->payments->sum('price')) }}
                                    </span>
                                    {{ __('dashboard.out of') }}
                                    {{ number_format($totalPrice) }}

                                    <span class="text-danger">
                                        {{ __('dashboard.remaining') }}
                                        @if ($order->insurance_status == 'returned')
                                            {{ number_format($order->insurance_amount) }}
                                        @else
                                            {{ number_format($totalPrice - $order->payments->sum('price')) }}
                                        @endif
                                    </span>
                                </td>

                                <!--end::Payments-->

                                <!--begin::Order Status-->
                                <td>{{ __('dashboard.' . $order->status) }}</td>
                                <!--end::Order Status-->

                                <td>
                                    <span @class(['badge text-white' , 'bg-success' => $order->insurance_status == 'returned' , 'bg-danger' => $order->insurance_status == null , 'bg-secondary' => $order->insurance_status == 'confiscated_full' , 'bg-primary' => $order->insurance_status == 'confiscated_partial' ])>
                                        @if ($order->insurance_status)
                                            {{ __('dashboard.' . $order->insurance_status) }}
                                        @else
                                            {{ __('dashboard.no_result') }}
                                        @endif
                                    </span>
                                </td>
                                <!--begin::Order Status-->

                                <!--end::Order Status-->

                                <!--begin::Rate Link-->
                                <td>
                                    <a href="{{route('rate', $order->id)}}"
                                        class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                        Click Rate <i class="fa fa-link"></i>
                                    </a>
                                </td>
                                <!--end::Rate Link-->

                                <!--begin::Created By-->
                                <td>
                                    @if($order->created_by)
                                        {{ __($order->user->name) }}
                                    @else
                                        {{ __('dashboard.no_creator') }}
                                    @endif
                                </td>
                                <!--end::Created By-->

                                <!--begin::Agree_to_the_terms-->
                                <td>
                                    @if($order->signature_path)
                                        {{ __('dashboard.Done_agree_to_the_terms') }}
                                    @else
                                        Null
                                    @endif
                                </td>
                                <!--end::Agree_to_the_terms-->

                                <!--begin::Actions-->
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        {{__('dashboard.actions')}}
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

                                        @can('orders.edit')
                                            <div class="menu-item px-3">
                                                <a href="{{route('orders.edit', $order->id)}}"
                                                    class="menu-link px-3">{{__('dashboard.edit')}}</a>
                                            </div>
                                        @endcan

                                        @can('orders.destroy')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3"
                                                    data-kt-ecommerce-category-filter="delete_row"
                                                    data-url="{{route('orders.destroy', $order->id)}}"
                                                    data-id="{{$order->id}}">{{__('dashboard.delete')}}</a>
                                            </div>
                                        @endcan

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
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->

@endsection
