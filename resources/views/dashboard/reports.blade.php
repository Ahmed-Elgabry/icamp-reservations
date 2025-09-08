@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.reports'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Card-->
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">@lang('dashboard.reports')</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title" style="color: white;">@lang('dashboard.top_selling_services')</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($topServices as $service)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $service->name }}
                                                <span class="badge badge-primary badge-pill">{{ $service->completed_orders_count ?? $service->orders_count }}
                                                    @lang('dashboard.sales')</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4 ">
                                <div class="card-header bg-info text-white">
                                    <h4 class="card-title" style="color: white;">@lang('dashboard.bank-accounts')</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>@lang('dashboard.name')</th>
                                                <th>@lang('dashboard.balance')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bankAccounts as $account)
                                                <tr>
                                                    <td>{{ $account->name }}</td>
                                                    <td>{{ number_format($account->balance, 2) }} {{__("dashboard.AED")}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-success text-white">
                                    <h4 class="card-title" style="color: white;">مدفوعات</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>@lang('dashboard.payment_id')</th>
                                                <th>@lang('dashboard.amount')</th>
                                                <th>@lang('dashboard.date')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($payments))
                                                @foreach ($payments as $payment)
                                                    <tr>
                                                        <td>{{ $payment->id }}</td>
                                                        <td>{{ number_format($payment->price, 2) }} {{__("dashboard.AED")}}</td>
                                                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                    
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">@lang('dashboard.no_payments_found')</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-info text-white">
                                    <h4 class="card-title" style="color: white;">@lang('dashboard.expenses')</h4>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <!-- زر لفتح نافذة البحث -->
                                    <button type="button" class="btn m-5 btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#searchModal">
                                        ابحث بالتاريخ
                                    </button>
                                    @include('dashboard.layouts.popUpSearch.DetaSearchPopup')

                                </div>
                                <div class="card-body">
                                    <canvas id="expensesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-warning text-white">
                                    <h4 class="card-title" style="color: white;">@lang('dashboard.general_payments')</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="monthlyPaymentsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-warning text-white">
                                    <h4 class="card-title" style="color: white;">@lang('dashboard.orders_payments')</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="reservationsPaymentsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // بيانات المصاريف بالدولار
            var expenseLabels = {!! json_encode($expenses->pluck('expenseItem.name')) !!};
            var expenseDataInDollar = {!! json_encode($expenses->pluck('total')) !!};

            // معدل تحويل الدولار إلى الدرهم الإماراتي
            var conversionRate = 3.67;

            // تحويل البيانات من الدولار إلى الدرهم الإماراتي
            var expenseDataInAED = expenseDataInDollar.map(function (value) {
                return (value * conversionRate).toFixed(2); // تحويل الدولار إلى الدرهم الإماراتي
            });

            // حساب إجمالي المصروفات بالدرهم الإماراتي
            var totalExpensesInAED = expenseDataInAED.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

            // إنشاء labels جديدة تحتوي على النسب المئوية والقيمة بالدرهم الإماراتي
            var expenseLabelsWithPercentage = expenseLabels.map((label, index) => {
                let percentage = ((expenseDataInAED[index] / totalExpensesInAED) * 100).toFixed(2); // حساب النسبة المئوية
                return `${label} (${percentage}%) - ${expenseDataInAED[index]} درهم إمراتي`; // دمج الاسم مع النسبة والقيمة
            });

            var expensesCtx = document.getElementById('expensesChart').getContext('2d');
            new Chart(expensesCtx, {
                type: 'pie',
                data: {
                    labels: expenseLabelsWithPercentage, // استخدام labels مع النسبة المئوية والقيمة
                    datasets: [{
                        data: expenseDataInAED,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: "@lang('dashboard.expenses_by_item')"
                    },
                    plugins: {
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.dataset.data.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                                let percentage = (value * 100 / sum).toFixed(2) + "%";
                                return percentage;
                            },
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            },
                            align: 'end',
                            anchor: 'end'
                        }
                    }
                }
            });

            // بيانات المدفوعات الشهرية
            var monthlyLabels = {!! json_encode($monthlyPayments->pluck('month')) !!};
            var monthlyData = {!! json_encode($monthlyPayments->pluck('total')) !!};

            var monthlyCtx = document.getElementById('monthlyPaymentsChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: "@lang('dashboard.general_payments')",
                        data: monthlyData,
                        backgroundColor: '#36A2EB'
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: "@lang('dashboard.general_payments')"
                    }
                }
            });

            // Orders payments (reservations revenues) chart - last up to 7 transactions by ID
            var resLabels = {!! json_encode($reservations_revenues->pluck('id')) !!};
            var resData = {!! json_encode($reservations_revenues->pluck('price')) !!}.map(function(v){ return Number(v); });
            var resLabelsNamed = (resLabels || []).map(function(id){ return '#' + id; });
            var reservationsCtx = document.getElementById('reservationsPaymentsChart').getContext('2d');
            new Chart(reservationsCtx, {
                type: 'bar',
                data: {
                    labels: resLabelsNamed,
                    datasets: [{
                        label: "@lang('dashboard.orders_payments')",
                        data: resData,
                        backgroundColor: '#FF9F40'
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: "@lang('dashboard.orders_payments')"
                    }
                }
            });

        });
    </script>
@endpush