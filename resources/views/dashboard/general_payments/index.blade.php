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
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-nowrap">{{ __('dashboard.orders') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.Insurance') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.addons') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.warehouse_sales') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.expenses') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.notes') }}</th>
                            <th class="text-nowrap">{{ __('dashboard.created_at') }}</th>
                            <th class="text-end min-w-100px">@lang('dashboard.actions')</th>
                        </tr>
                    </thead>

                    <tbody class="fw-semibold text-gray-700">
                    @forelse ($paymentsByOrder as $orderId => $orderPayments)
                        @php
                            $firstPayment  = $orderPayments->first();
                            $order         = $firstPayment->order ?? null;
                            $customerName  = $order?->customer?->name ?? null;

                            $paymentsCount = $orderPayments->count();
                            $paymentsTotal = $orderPayments->sum('price');

                            $addonsTotal = $order?->addons?->sum(fn($q) => $q->pivot->price) ?? 0;
                            $itemsTotal  = $order?->items?->sum(fn($q) => $q->total_price) ?? 0;
                            $expensesTotal = $order?->expenses?->sum(fn($q) => $q->price) ?? 0;

                            $addonsCount   = $order?->addons?->count() ?? 0;
                            $itemsCount    = $order?->items?->count() ?? 0;
                            $expensesCount = $order?->expenses?->count() ?? 0;
                        @endphp

                        <tr data-id="{{ $firstPayment->id }}">

                            {{-- Order Id --}}
                            <td>
                                @if($order)
                                    <a href="{{ route('general_payments.show', $orderId) }}" class="text-hover-primary fw-bold">
                                        #{{ $orderId }}
                                    </a>
                                    @if($customerName)
                                        <div class="text-muted small">{{ $customerName }}</div>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Payments summary --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ number_format($paymentsTotal, 2) }}</span>
                                    <span class="badge badge-light-primary mt-1">{{ $paymentsCount }} {{ __('dashboard.Insurance') }}</span>
                                </div>
                            </td>

                            {{-- Addons --}}
                            <td>
                                @if($addonsCount)
                                    <details>
                                        <summary class="cursor-pointer">
                                            <span class="badge badge-light">{{ $addonsCount }}</span>
                                            <span class="text-muted mx-1">•</span>
                                            <span class="fw-bold">{{ __('dashboard.total') }}: {{ number_format($addonsTotal, 2) }}</span>
                                        </summary>
                                        <div class="mt-2">
                                            <ul class="mb-0 ps-3 small">
                                                @foreach($order->addons as $addon)
                                                    <li class="mb-1">
                                                        {{ $addon->name ?? '—' }}
                                                        <span class="text-muted">—</span>
                                                        {{ number_format(isset($addon->pivot?->price) ? $addon->pivot->price : ($addon->price ?? 0), 2) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </details>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Items (warehouse sales) --}}
                            <td>
                                @if($itemsCount)
                                    <details>
                                        <summary class="cursor-pointer">
                                            <span class="badge badge-light">{{ $itemsCount }}</span>
                                            <span class="text-muted mx-1">•</span>
                                            <span class="fw-bold">{{ __('dashboard.total') }}: {{ number_format($itemsTotal, 2) }}</span>
                                        </summary>
                                        <div class="mt-2">
                                            <ul class="mb-0 ps-3 small">
                                                @foreach($order->items as $item)
                                                    @php
                                                        $line = isset($item->total_price)
                                                            ? (float)$item->total_price
                                                            : (float)($item->price ?? 0) * (int)($item->qty ?? 1);
                                                    @endphp
                                                    <li class="mb-1">
                                                        {{ $item->stock->name ?? $item->name ?? '—' }}
                                                        <span class="text-muted">—</span>
                                                        {{ number_format($line, 2) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </details>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Expenses --}}
                            <td>
                                @if($expensesCount)
                                    <details>
                                        <summary class="cursor-pointer">
                                            <span class="badge badge-light">{{ $expensesCount }}</span>
                                            <span class="text-muted mx-1">•</span>
                                            <span class="fw-bold">{{ __('dashboard.total') }}: {{ number_format($expensesTotal, 2) }}</span>
                                        </summary>
                                        <div class="mt-2">
                                            <ul class="mb-0 ps-3 small">
                                                @foreach($order->expenses as $ex)
                                                    <li class="mb-1">
                                                        {{ $ex->description ?? '—' }}
                                                        <span class="text-muted">—</span>
                                                        {{ number_format($ex->amount ?? $ex->price ?? 0, 2) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </details>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Notes (latest payment) --}}
                            <td class="text-muted">{{ $firstPayment->notes }}</td>

                            {{-- Latest payment time --}}
                            <td class="text-muted">{{ optional($orderPayments->max('created_at'))->diffForHumans() }}</td>

                            {{-- Actions (using latest payment id) --}}
                            <td class="text-end">
                                @can('payments.destroy')
                                    <a href="#" class="btn btn-sm btn-light btn-danger"
                                    data-kt-ecommerce-category-filter="delete_row"
                                    data-url="{{ route('payments.destroy', $firstPayment->id) }}"
                                    data-id="{{ $firstPayment->id }}">
                                        @lang('dashboard.delete')
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center text-muted py-10">— {{ __('dashboard.no_results') }} —</td></tr>
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
        $("#customer_id").select2();
    </script>
@endpush
