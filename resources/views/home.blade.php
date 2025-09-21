@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.dashboard'))
@section('content')


<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1"></h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Secondary button-->
                @can('customers.create')
                <a href="{{ route('customers.create') }}" class="btn btn-sm btn-light">
                    @lang('dashboard.create_title', ['page_title' => __('dashboard.customers')])
                </a>
                @endcan
                <!--end::Secondary button-->
                <!--begin::Primary button-->
                @can('orders.create')
                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                     @lang('dashboard.create_title', ['page_title' => __('dashboard.orders')])
                </a>
                <!--end::Primary button-->
                @endcan
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            
            <div class="row">
                <!-- Customers Count -->
                @can('bookings.index')
                <div class="col-md-4 mb-4">
                    <a href="{{route('customers.index') }}">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="fs-4 fw-bold text-gray-800">{{ __('dashboard.total_customers') }}</h3>
                                <h5 class="fs-2x fw-bolder text-primary">{{ number_format($totalCustomersCount) }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan
                
                @can('bookings.index')
                    <!-- Total Orders Card -->
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('orders.index') }}">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="fs-4 fw-bold text-gray-800">{{ __('dashboard.total_orders') }}</h3>
                                    <h5 class="fs-2x fw-bolder text-primary">{{ number_format($totalOrdersCount) }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Status Cards -->
                    @foreach(getOrderedStatuses() as $status)
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('orders.index', ['status' => $status]) }}">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="fs-4 fw-bold text-gray-800">{{ __('dashboard.order_status.' . $status) }}</h3>
                                    @if(isset($ordersCountByStatus[$status]))
                                    <h5 class="fs-2x fw-bolder {{ getStatusBadgeColor($status) ? 'text-' . getStatusBadgeColor($status) : '' }}">{{ number_format($ordersCountByStatus[$status]) ?? "0" }}</h5>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                @endcan
            </div>

            <div class="row mt-10">


                @can('bookings.index')
                <div class="col-12 mb-xl-10">
                    <!--begin::List widget for Orders-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800"> @lang('dashboard.upcoming_orders')</span>
                            </h3>
                            <!--end::Title-->
                            <!--begin::Toolbar-->
                            <div class="card-toolbar">
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-light">
                                    @lang('dashboard.upcoming_orders')
                                </a>
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-4">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <table class="table table-hover table-row-bordered gy-5">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th class="min-w-50px">#</th>
                                            <th>@lang('dashboard.order_id')</th>
                                            <th>@lang('dashboard.customer_name')</th>
                                            <th>@lang('dashboard.phone')</th>
                                            <th>@lang('dashboard.reservation_date')</th>
                                            <th>@lang('dashboard.status')</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @forelse($upcomingReservations as $key => $order)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order->id) }}" class="text-primary">
                                                        #{{ $order->id }}
                                                    </a>
                                                </td>
                                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                                <td dir="ltr">{{ $order->customer->phone ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->date)->format('Y-m-d') }}</td>
                                                <td>
                                                    <span class="badge badge-light-{{ getStatusBadgeColor($order->status) }}">
                                                        {{ __('dashboard.' . $order->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    @lang('dashboard.no_upcoming_reservations')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::List widget for Orders-->
                </div>
                <!--end::Col-->
                @endcan


                @can('payments.index')
                <div class="col-12 mb-xl-10">
                    <!--begin::Card-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">@lang('dashboard.latest_paid_funds')</span>
                            </h3>
                            <div class="card-toolbar">
                                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-light">
                                    @lang('dashboard.latest_paid_funds')
                                </a>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-4">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <table class="table table-sm table-row-dashed table-row-gray-300 align-middle gs-0">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="w-40px">#</th>
                                            <th class="w-120px">@lang('dashboard.date_time')</th>
                                            <th class="w-80px">@lang('dashboard.order_id')</th>
                                            <th class="w-100px">@lang('dashboard.amount')</th>
                                            <th class="w-150px">@lang('dashboard.amount_details')</th>
                                            <th class="w-100px">@lang('dashboard.payment_method')</th>

                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @forelse($paymentRecords as $index => $payment)
                                            @php
                                                $reservationId = $payment->order_id;
                                                $createdAt = \Carbon\Carbon::parse($payment->created_at);
                                                $date = $createdAt->format('Y-m-d');
                                                $time = $createdAt->format('h:i A');
                                                if ($payment->payment) {
                                                    $source = $payment->payment->statement;
                                                    $paymentMethod = $payment->payment->payment_method;
                                                    $link = route('payments.show', $payment->order->id);
                                                } elseif ($payment->orderAddon) {
                                                    $source = $payment->orderAddon->addon->name;
                                                    $paymentMethod = $payment->orderAddon->payment_method;
                                                    $link = route('orders.addons', $payment->order->id);
                                                } elseif ($payment->orderItem) {
                                                    $source = $payment->orderItem->stock->name;
                                                    $paymentMethod = $payment->orderItem->payment_method;
                                                    $link = route('warehouse_sales.show', $payment->order->id);
                                                } 
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $date }}</span>
                                                    <span class="text-muted">{{ $time }}</span>
                                                </td>
                                                <td>
                                                    @if($reservationId)
                                                        <a href="{{ route('orders.show', $reservationId) }}" class="text-primary fw-bold">
                                                            #{{ $reservationId }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">{{ number_format($payment->amount, 2) }} {{ __('dashboard.currency') }}</td>
                                                <td>
                                                    <a href="{{ $link }}" class="text-primary fw-bold">
                                                        <div class="d-flex flex-column">
                                                            <span class="text-muted fs-8">{{ __('dashboard.' . $payment->source) }}</span>
                                                            <!-- check if there translation -->
                                                            <span class="text-muted fs-8">{{ __("dashboard.".$source) == "dashboard.$source" ? $source : __("dashboard.".$source) }}  - {{$payment->order ? $payment->order->id : ''}}</span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light">
                                                        {{ ucfirst(__("dashboard.".$paymentMethod)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    @lang('dashboard.no_payments_found')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <!--end::Table container-->
                            @if($paymentRecords->count() > 0)
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="text-muted">
                                        @lang('dashboard.showing') {{ $paymentRecords->firstItem() }} @lang('dashboard.to') {{ $paymentRecords->lastItem() }} @lang('dashboard.of') {{ $paymentRecords->total() }} @lang('dashboard.entries')
                                    </div>
                                    <div>
                                        @include('dashboard.pagination.pagination', ['transactions' => $paymentRecords])
                                </div>
                                </div>
                            @endif
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                @endcan

                <!-- Begin Survey Responses Table -->
                @can('survey_responses.view')
                <div class="col-12 mb-10">
                    <div class="card card-flush">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">@lang('dashboard.latest_survey_responses')</span>
                            </h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="min-w-50px">#</th>
                                            <th class="min-w-150px">@lang('dashboard.date_time')</th>
                                            <th class="min-w-100px">@lang('dashboard.order_id')</th>
                                            <th class="min-w-200px">@lang('dashboard.customer')</th>
                                            <th class="min-w-200px">@lang('dashboard.survey')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($surveyResponses as $index => $response)
                                            @php
                                                $createdAt = \Carbon\Carbon::parse($response->created_at);
                                                $date = $createdAt->format('Y-m-d');
                                                $time = $createdAt->format('h:i A');
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $date }}</span>
                                                    <span class="text-muted">{{ $time }}</span>
                                                </td>
                                                <td>
                                                    @if($response->reservation_id)
                                                        <a href="{{ route('orders.show', $response->reservation_id) }}" class="text-primary fw-bold">
                                                            #{{ $response->reservation_id }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($response->order && $response->order->customer)
                                                        <span class="text-dark fw-bold">{{ $response->order->customer->name ?? '' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('surveys.answer', $response->id) }}" class="text-primary fw-bold">
                                                        {{ $response->survey->title ?? '' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    @lang('dashboard.no_survey_responses_found')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                <!-- Begin Low Stock Items Table -->
                @can('inventory.view')
                <div class="col-12 mb-10">
                    <div class="card card-flush">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">@lang('dashboard.low_stock_items')</span>
                            </h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="min-w-50px">#</th>
                                            <th class="min-w-200px">@lang('dashboard.item_name')</th>
                                            <th class="min-w-100px">@lang('dashboard.quantity')</th>
                                            <th class="min-w-100px">@lang('dashboard.status')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($lowStockItems as $index => $stock)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('dashboard.stock.report', $stock->id) }}">
                                                        <span class="text-dark fw-bold">{{ $stock->name ?? 'N/A' }}</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ $stock->quantity }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = $stock->quantity <= 5 ? 'danger' : 'warning';
                                                        $statusText = $stock->quantity <= 5 ? 'Very Low' : 'Low';
                                                    @endphp
                                                    <span class="badge badge-light-{{ $statusClass }}">
                                                        {{ __('dashboard.' . $statusText) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    @lang('dashboard.no_low_stock_items')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                <!-- Begin Recent Expenses Table -->
                @can('expenses.view')
                <div class="col-12 mb-10">
                    <div class="card card-flush">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">@lang('dashboard.recent_expenses')</span>
                            </h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="min-w-50px">#</th>
                                            <th class="min-w-150px">@lang('dashboard.date_time')</th>
                                            <th class="min-w-100px">@lang('dashboard.amount')</th>
                                            <th class="min-w-200px">@lang('dashboard.amount_details')</th>
                                            <th class="min-w-150px">@lang('dashboard.payment_method')</th>
                                            <th class="min-w-150px">@lang('dashboard.source')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentExpenses as $index => $expense)
                                            @php
                                                $createdAt = \Carbon\Carbon::parse($expense->date ?? $expense->created_at);
                                                $date = $createdAt->format('Y-m-d');
                                                $time = $createdAt->format('h:i A');
                                                $link = $expense->source == 'reservation_expenses' ? route('expenses.show', $expense->order->id) : route('expenses.index');
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $date }}</span>
                                                    <span class="text-muted">{{ $time }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="text-danger fw-bold">- {{ number_format($expense->amount, 2) }} </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold">{{ __("dashboard.".$expense->source ?? '') }}</span>
                                                    <!-- check if translation available -->
                                                    @php
                                                        $statement = "dashboard.".$expense->expense->statement != __('dashboard.'.$expense->expense->statement) ? __('dashboard.'.$expense->expense->statement) : $expense->expense->statement;
                                                    @endphp
                                                    <span class="text-muted d-block">{{ $statement  }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-primary">
                                                        {{ ucfirst(__('dashboard.' . $expense->expense->payment_method)) ?? "" }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ $link }}">
                                                        <span class="badge badge-light-info">
                                                            {{ __('dashboard.' . $expense->source) }} {{ $expense->order ? " - ".$expense->order->id : ''}}
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    @lang('dashboard.no_expenses_found')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                <!-- Begin Recent Add Funds Table -->
                <!-- @can('transactions.view') -->
                <div class="col-12 mb-10">
                    <div class="card card-flush">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">@lang('dashboard.recent_add_funds')</span>
                            </h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="min-w-50px">#</th>
                                            <th class="min-w-150px">@lang('dashboard.date_time')</th>
                                            <th class="min-w-100px">@lang('dashboard.amount')</th>
                                            <th class="min-w-200px">@lang('dashboard.amount_details')</th>
                                            <th class="min-w-150px">@lang('dashboard.payment_method')</th>
                                            <th class="min-w-150px">@lang('dashboard.source')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentAddFunds as $index => $fund)
                                            @php
                                                $createdAt = \Carbon\Carbon::parse($fund->date ?? $fund->created_at);
                                                $date = $createdAt->format('Y-m-d');
                                                $time = $createdAt->format('h:i A');
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $date }}</span>
                                                    <span class="text-muted">{{ $time }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="text-success fw-bold">+ {{ number_format($fund->amount, 2) }} </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold">{{ __('dashboard.'.$fund->source) ?? '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-success">
                                                        {{ ucfirst(__('dashboard.' . $fund->generalPayment?->payment_method)) ?? '' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('general_payments.create_add_funds') }}">
                                                        <span class="badge badge-light-info">
                                                            {{ __('dashboard.' . $fund->source) }}
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    @lang('dashboard.no_funds_found')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- @endcan -->

            </div>

        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

@endsection

@push('css')
    <style>
        #kt_content_container .card {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            font-weight: 600;
            padding: 0.5em 0.75em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
