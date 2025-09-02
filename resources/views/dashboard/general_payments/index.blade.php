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
                    <form action="" method="GET" class="row g-3 align-items-end">

                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">
                                {{ __('dashboard.select') }} {{ __('dashboard.customer') }}
                            </label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">{{ __('dashboard.select') }} {{ __('dashboard.customer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="order_id" class="form-label">
                                {{ __('dashboard.orders') }}
                            </label>
                            <select name="order_id" id="order_id" class="form-select">
                                <option value="">{{ __('dashboard.select') }} {{ __('dashboard.orders') }}</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ request('order_id') == $order->id ? 'selected' : '' }}>
                                        #{{ $order->id }} [{{ $order->customer->name }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="date_from" class="form-label">{{ __('dashboard.date_from') }}</label>
                            <input type="date" name="date_from" id="date_from" class="form-control"
                                value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="date_to" class="form-label">{{ __('dashboard.date_to') }}</label>
                            <input type="date" name="date_to" id="date_to" class="form-control"
                                value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-4 d-flex">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-1"></i> {{ __('dashboard.search') }}
                            </button>
                        </div>

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
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-nowrap">{{ __('dashboard.orders') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.Insurance') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.addons') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.warehouse_sales') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.total') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.created_at') }}</th>
                        </tr>
                    </thead>

                    <tbody class="fw-semibold text-gray-700">
                    @forelse ($orderSummaries as $summary)

                        <tr data-order-id="{{ $summary->order->id }}">

                            {{-- Order ID with Customer Name --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('general_payments.show', $summary->order->id) }}" class="fw-bold text-hover-primary">
                                        #{{ $summary->order->id }}
                                    </a>
                                    <span class="text-muted small">{{ $summary->customer->name }}</span>
                                </div>
                            </td>

                            {{-- Insurance Payments --}}
                            <td>
                                @if($summary->insurance_count)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ number_format($summary->insurance_total, 2) }}</span>
                                        <span class="badge badge-light-primary">{{ $summary->insurance_count }} {{ __('dashboard.Insurance') }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>


                            {{-- Addons --}}
                            <td>
                                @if($summary->addons_count)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ number_format($summary->addons_total, 2) }}</span>
                                        <span class="badge badge-light-primary">{{ $summary->addons_count }} {{ __('dashboard.addons') }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Warehouse Sales --}}
                            <td>
                                @if($summary->warehouse_count)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ number_format($summary->warehouse_total, 2) }}</span>
                                        <span class="badge badge-light-info">{{ $summary->warehouse_count }} {{ __('dashboard.sales') }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>


                            {{-- Total --}}
                            <td>
                                <span class="fw-bold text-success fs-6">{{ number_format($summary->grand_total, 2) }}</span>
                            </td>

                            {{-- Created At --}}
                            <td class="text-muted">
                                {{ $summary->latest_date ? \Carbon\Carbon::parse($summary->latest_date)->diffForHumans() : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-10">— {{ __('dashboard.no_results') }} —</td></tr>
                    @endforelse
                    </tbody>
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
        $("#customer_id,#order_id").select2();
    </script>
@endpush
