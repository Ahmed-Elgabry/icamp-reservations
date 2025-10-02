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
                    @can('bookings.scan-qr')

                    <!--QR Scanner:Start-->
                    <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal"
                        data-bs-target="#qrScannerModal">
                        <i class="fas fa-qrcode me-2"></i>@lang('dashboard.scan_qr')
                    </button>
                    <!--QR Scanner:End-->
                    @endcan
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Add customer-->
                    @can('bookings.create')
                    <a href="{{ route('orders.create')}}"
                        class="btn btn-primary">@lang('dashboard.create_title', ['page_title' => __('dashboard.orders')])</a>
                    @endcan
                    <!--end::Add customer-->
                    <span class="w-5px h-2px"></span>
                    @can('bookings.destroy')
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
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0">
                                <th class="fw-bolder">@lang('dashboard.sequence')</th>
                                <th class="fw-bolder">@lang('dashboard.reservation_number')</th>
                                <th class="fw-bolder">@lang('dashboard.customer')</th>
                                <th class="fw-bolder">@lang('dashboard.service')</th>
                                <th class="fw-bolder">@lang('dashboard.Duration of hours')</th>
                                <th class="fw-bolder">@lang('dashboard.time_from')</th>
                                <th class="fw-bolder">@lang('dashboard.paied')</th>
                                <th class="fw-bolder">@lang('dashboard.status')</th>
                                @if (request('status') == 'delayed')
                                <th>@lang('dashboard.old_date_vs_new_date')</th>
                                <th>@lang('dashboard.delayed_reson')</th>
                                @endif
                                <th class="">@lang('dashboard.insurance_status')</th>
                                <th class="">@lang('dashboard.rate link')</th>
                                <th class="">@lang('dashboard.created_by')</th>
                                <th class="">@lang('dashboard.Agree_to_the_terms')</th>
                                <!-- <th class="">@lang('dashboard.date')</th> -->
                                <th class="">@lang('dashboard.created_date')</th>
                                <th class="">@lang('dashboard.created_time')</th>
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
                                <td>
                                    @can('bookings.edit')
                                    <a href="{{ route('orders.edit', $order->id) }}" class="badge bg-primary">{{ $order->id }}</a>
                                    @else
                                    <span>{{$order->id }}</span>
                                    @endcan

                                </td>

                                <!--begin::Customer Name-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            @can('bookings.show')
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                                {{$order->customer->name}}
                                            </a>
                                            @else
                                            {{$order->customer->name}}
                                            </span>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                                <!--end::Customer Name-->

                                <!--begin::Services-->
                                <td>
                                    <span>
                                        <ul>
                                            @foreach($order->services as $service)
                                            <li>{{$service->name}}</li>
                                            @endforeach
                                        </ul>
                                    </span>
                                </td>
                                <!--end::Services-->

                                <!--begin::Order Hours-->
                                <td>{{ $order->addHoursCount() }}</td>
                                <!--end::Order Hours-->

                                <!--begin::Time From-->
                                <td>{{ $order->time_from ? \Carbon\Carbon::createFromFormat('H:i:s', $order->time_from)->format('h:i A') : '' }}</td>
                                <!--end::Time From-->
                                @php
                                $totalPaid = $order->totalPaidAmount();
                                $totalPrice = $order->price + $order->insurance_amount;
                                $remaining = $totalPrice - $totalPaid;
                                @endphp
                                <!--begin::Payments-->
                                <td>
                                    <span class="text-success">
                                        {{ __('dashboard.paied') }}
                                        {{ number_format( $totalPaid ) }}
                                    </span>
                                    {{ __('dashboard.out of') }}
                                    {{ number_format($totalPrice) }}

                                    <span class="text-danger">
                                        {{ __('dashboard.remaining') }}
                                        <!-- @if ($order->insurance_status == 'returned')
                                            {{ number_format($order->insurance_amount) }}
                                        @else -->
                                        {{ number_format(($order->price + $order->insurance_amount) - $totalPaid) }}

                                        <!-- @endif -->
                                    </span>
                                </td>
                                <!--end::Payments-->

                                <!--begin::Order Status-->

                                <td>{{ __('dashboard.' . $order->status) }}</td>
                                <!--end::Order Status-->

                                @if (request('status') == 'delayed')
                                <td class="text-nowrap">
                                    {{ $order->date . ' / '  . $order->expired_price_offer }}
                                </td>
                                <td>
                                    {{ Str::limit($order->delayed_reson , 50)}}
                                </td>
                                @endif

                                <!--begin::Order Status-->

                                <td>
                                    <span
                                        @class([ 'badge text-white' , 'bg-success'=> $order->insurance_status === 'returned',
                                        'bg-dark' => $order->insurance_status === 'confiscated_full',
                                        'bg-warning' => $order->insurance_status === 'confiscated_partial',
                                        'bg-danger' => $order->insurance_status === null && $order->payments()->where('statement', 'the_insurance')->sum("price") < 1, 'bg-info'=> $order->insurance_status === null && $order->payments()->where('statement', 'the_insurance')->sum("price") > 1,
                                            ])
                                            >
                                            @if($order->payments()->where('statement', 'the_insurance')->where('verified', "1")->exists())
                                            @if($order->insurance_status === 'returned')
                                            {{ __('dashboard.insurance_returned_note') }}
                                            @elseif($order->insurance_status === 'confiscated_full')
                                            {{ __('dashboard.insurance_confiscated_full') }}
                                            @elseif($order->insurance_status === 'confiscated_partial')
                                            {{ __('dashboard.insurance_confiscated_partial') }}
                                            @elseif($order->insurance_status === null && $order->payments()->where('statement', 'the_insurance')->sum("price") < 1)
                                                {{ __('dashboard.insurance_null') }}
                                                @elseif($order->insurance_status === null && $order->payments()->where('statement', 'the_insurance')->sum("price") > 1)
                                                {{ __('dashboard.insurance_not_returned') }}
                                                @endif
                                                {{__("dashboard.by")}} {{" " . $order->insuranceHandledBy?->name}}
                                                @else
                                                {{ __('dashboard.no_insurance_payment_verified') }}
                                                @endif
                                    </span>
                                </td>
                                <!--begin::Order Status-->


                                <!--begin::Rate Link-->
                                <td>
                                    <a href="{{route('surveys.public', $order->id)}}" target="_blank"
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
                                    {{ __('dashboard.no') }}
                                    @endif
                                </td>
                                <!--end::Agree_to_the_terms-->

                                <!--begin::Order Date-->
                                <!-- <td>
                                        <span>{{$order->date }}</span>
                                    </td> -->
                                <!--end::Order Date-->

                                <!--begin::Created Date-->
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <!--end::Created Date-->

                                <!--begin::Created Time-->
                                <td>{{ $order->created_at->format('h:i A') }}</td>
                                <!--end::Created Time-->

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

                                        @can('bookings.view')
                                        <div class="menu-item px-3">
                                            <a href="{{route('orders.show', $order->id)}}"
                                                class="menu-link px-3">{{__('dashboard.show')}}</a>
                                        </div>
                                        @endcan

                                        @can('bookings.edit')
                                        <div class="menu-item px-3">
                                            <a href="{{route('orders.edit', $order->id)}}"
                                                class="menu-link px-3">{{__('dashboard.edit')}}</a>
                                        </div>
                                        @endcan

                                        @can('bookings.destroy')
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3"
                                                onclick="confirmDelete('{{route('orders.destroy', $order->id)}}', '{{ csrf_token() }}')">{{__('dashboard.delete')}}</a>
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
                </div>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('dashboard.scan_qr_code')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div id="qr-scanner-container" class="mb-3">
                        <video id="qr-video" width="100%" style="border-radius: 8px;"></video>
                    </div>
                    <div id="qr-result" class="alert alert-info d-none"></div>
                    <button id="start-scan-btn" class="btn btn-primary">
                        <i class="fas fa-camera me-2"></i>@lang('dashboard.start_scanning')
                    </button>
                    <button id="stop-scan-btn" class="btn btn-secondary d-none">
                        <i class="fas fa-stop me-2"></i>@lang('dashboard.stop_scanning')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Include the QR scanning library -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
                const scannerModal = document.getElementById('qrScannerModal');
                const video = document.getElementById('qr-video');
                const startScanBtn = document.getElementById('start-scan-btn');
                const stopScanBtn = document.getElementById('stop-scan-btn');
                const resultContainer = document.getElementById('qr-result');
                let scanning = false;
                let stream = null;

                // Function to start scanning
                function startScanning() {
                    resultContainer.classList.add('d-none');
                    startScanBtn.classList.add('d-none');
                    stopScanBtn.classList.remove('d-none');

                    navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: "environment"
                            }
                        })
                        .then(function(s) {
                            stream = s;
                            video.srcObject = stream;
                            video.setAttribute("playsinline", true);
                            video.play();
                            scanning = true;
                            requestAnimationFrame(tick);
                        })
                        .catch(function(err) {
                            console.error("Error accessing camera: ", err);
                            alert("@lang('dashboard.camera_access_error')");
                            resetScanner();
                        });
                }

                // Function to stop scanning
                function stopScanning() {
                    scanning = false;
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                    resetScanner();
                }

                // Function to reset scanner UI
                function resetScanner() {
                    startScanBtn.classList.remove('d-none');
                    stopScanBtn.classList.add('d-none');
                    video.srcObject = null;
                }

                // Function to process each video frame
                function tick() {
                    if (!scanning) return;

                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);

                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "dontInvert",
                        });

                        if (code) {
                            // QR code detected!
                            stopScanning();
                            resultContainer.textContent = "@lang('dashboard.qr_detected_redirecting')";
                            resultContainer.classList.remove('d-none');

                            // Check if the URL is a valid order edit URL
                            // if (code.data.includes('orders.edit')) {
                            setTimeout(() => {
                                window.location.href = code.data;
                            }, 1500);
                            {
                                {
                                    --
                                } else {
                                    --
                                }
                            } {
                                {
                                    --resultContainer.textContent = "@lang('dashboard.invalid_qr_code')";
                                    --
                                }
                            } {
                                {
                                    --resultContainer.classList.remove('alert-info');
                                    --
                                }
                            } {
                                {
                                    --resultContainer.classList.add('alert-warning');
                                    --
                                }
                            }

                            {
                                {
                                    -- // Show the start button again after a delay--}}
                                    {
                                        {
                                            --setTimeout(() => {
                                                    --
                                                }
                                            } {
                                                {
                                                    --resetScanner();
                                                    --
                                                }
                                            } {
                                                {
                                                    --
                                                }, 3000);
                                            --
                                        }
                                    } {
                                        {
                                            --
                                        }--
                                    }
                                }
                            }
                        }

                        if (scanning) {
                            requestAnimationFrame(tick);
                        }
                    }

                    // Event listeners
                    startScanBtn.addEventListener('click', startScanning);
                    stopScanBtn.addEventListener('click', stopScanning);

                    // Reset scanner when modal is closed
                    scannerModal.addEventListener('hidden.bs.modal', function() {
                        stopScanning();
                    });
                });
</script>

<!-- Fix delete functionality -->
<script>
    // Override confirmDelete function specifically for this page
    window.confirmDelete = function(deleteUrl, csrfToken) {
        // Check if Swal is available, if not use basic confirm
        if (typeof Swal === 'undefined') {
            if (confirm('{{ __("dashboard.delete_warning_message") ?? "Are you sure you want to delete this item? This action cannot be undone." }}')) {
                // Create and submit delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                // Add CSRF token
                const csrfTokenField = document.createElement('input');
                csrfTokenField.type = 'hidden';
                csrfTokenField.name = '_token';
                csrfTokenField.value = csrfToken || '{{ csrf_token() }}';
                form.appendChild(csrfTokenField);

                // Add DELETE method
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
            return;
        }

        Swal.fire({
            title: '{{ __("dashboard.confirm_delete") ?? "Confirm Delete" }}',
            text: '{{ __("dashboard.delete_warning_message") ?? "Are you sure you want to delete this item? This action cannot be undone." }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("dashboard.yes_delete") ?? "Yes, Delete" }}',
            cancelButtonText: '{{ __("dashboard.cancel") ?? "Cancel" }}',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                // Add CSRF token
                const csrfTokenField = document.createElement('input');
                csrfTokenField.type = 'hidden';
                csrfTokenField.name = '_token';
                csrfTokenField.value = csrfToken || '{{ csrf_token() }}';
                form.appendChild(csrfTokenField);

                // Add DELETE method
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    };
</script>
@endpush

@push('css')
<style>
    #qr-scanner-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    #qr-video {
        background: linear-gradient(135deg, #B98220 0%, #6A3D1C 100%);
    }
</style>
@endpush