@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.statistics'))
@section('content')

<!-- نافذة منبثقة للفلاتر -->
<div class="modal fade" id="filtersModal" tabindex="-1" aria-labelledby="filtersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtersModalLabel">تصفية البيانات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('statistics.index') }}">
                    <div class="mb-3">
                        <label for="from_date" class="form-label">من التاريخ</label>
                        <input type="date" id="from_date" name="from_date" class="form-control"
                            value="{{ request('from_date') }}">
                    </div>

                    <div class="mb-3">
                        <label for="to_date" class="form-label">إلى التاريخ</label>
                        <input type="date" id="to_date" name="to_date" class="form-control"
                            value="{{ request('to_date') }}">
                    </div>

                    <div class="mb-3">
                        <label for="created_by" class="form-label">الموظف</label>
                        <select id="created_by" name="created_by" class="form-control">
                            <option value="">الكل</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="services" class="form-label">انواع المخيمات</label>
                        <select id="services" name="services" class="form-control">
                            <option value="">الكل</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ request('services') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="submit" class="btn btn-primary">تصفية</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- زر فتح النافذة المنبثقة -->
<div class="row m-4">
    <button type="button" class="btn col-2 btn-primary" data-bs-toggle="modal" data-bs-target="#filtersModal">
        فتح الفلاتر
    </button>
</div>
<h1 class="text-center" style="font-family: cairo, sans-serif">@lang('dashboard.statistics')</h1>

<div class="dashboard-container">
    <input id="date-range" type="text" value="{{ ($startDate ?? '') }} — {{ ($endDate ?? '') }}">
    <p>@lang('dashboard.number_of_appointments')</p>
    <div class="stats">
        <div class="stat-item">
            <h3>{{ number_format(($ordersCountByStatus['completed'] ?? 0)) }}</h3>
            <p>@lang('dashboard.completed_orders')</p>
        </div>
        <div class="stat-item">
            <h3>{{ number_format(($ordersCountByStatus['approved'] ?? 0) + ($ordersCountByStatus['delayed'] ?? 0)) }}</h3>
            <p>@lang('dashboard.verified_orders')</p>
        </div>
        <div class="stat-item">
            <h3>{{ number_format(($ordersCountByStatus['pending_and_show_price'] ?? 0) + ($ordersCountByStatus['pending_and_Initial_reservation'] ?? 0)) }}</h3>
            <p>@lang('dashboard.pending_orders')</p>
        </div>
        <div class="stat-item">
            <h3>{{ number_format(($ordersCountByStatus['canceled'] ?? 0)) }}</h3>
            <p>@lang('dashboard.cancelled_orders')</p>
        </div>
        <div class="stat-item">
            <h3>{{ number_format($reservation_revenues) }}</h3>
            <p>@lang('dashboard.reservations_revenues')</p>
        </div>
        <div class="stat-item">
            <h3> {{ number_format($general_revenues, 2, ',', '.') }}{{__('dashboard.AED')}}</h3>
            <p>@lang('dashboard.general_payments')</p>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>
    <!-- ملخص يومي اختياري -->
    @if(isset($revenues_by_day) && count($revenues_by_day))
    <div class="table-responsive  mt-4">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>اليوم</th>
                    <th>الإيرادات العامة (AED)</th>
                    <th>إيرادات الحجوزات (AED)</th>
                    <th>الإجمالي (AED)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenues_by_day as $row)
                <tr>
                    <td>{{ $row['day'] ?? $row->day }}</td>
                    <td>{{ number_format(($row['general_total'] ?? $row->general_total) ?? 0, 2) }}</td>
                    <td>{{ number_format(($row['reservation_total'] ?? $row->reservation_total) ?? 0, 2) }}</td>
                    <td>{{ number_format(($row['total'] ?? $row->total) ?? 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<div class="dashboard-container" style="margin-top: 20px; margin-bottom: 50px;">
    <h1 class="mb-3">@lang('dashboard.Analytics')</h1>

    <div class="stats mb-4">
        @can('statistics.export')
        <button class="btn btn-primary col-1 custom-print-btn" onclick="window.print()">
            @lang('dashboard.Print')
        </button>
        @endcan
    </div>
    <table class="table align-middle table-row-dashed fs-6 gy-5" style="max-width:1200px ;"
        id="kt_ecommerce_category_table">
        <!--begin::Table head-->
        <thead>
            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                <th>@lang('dashboard.Employee')</th>
                <th>@lang('dashboard.service')</th>
                <th>@lang('dashboard.orders')</th>
                <th>@lang('dashboard.approved')</th>
                <th>@lang('dashboard.pending_and_show_price')</th>
                <th>@lang('dashboard.pending_and_Initial_reservation')</th>
                <th>@lang('dashboard.delayed')</th>
                <th>@lang('dashboard.canceled')</th>
                <th>@lang('dashboard.completed')</th>
                <th>@lang('dashboard.customers')</th>
                <!-- <th>@lang('dashboard.notes')</th> -->
                <th>@lang('dashboard.price')</th>
            </tr>
        </thead>
        <!--end::Table head-->

        <!--begin::Table body-->
        <tbody class="fw-bold text-gray-600">
            @foreach ($topServices as $service)
            <tr data-id="{{ $service->id }}">
                <td>
                    {{ $service->orders->pluck('user.name')->implode(', ') }}
                </td>
                <td>
                    <div class="d-flex">
                        <div class="ms-5">
                            <a href="{{ route('services.edit', $service->id) }}"
                                class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1"
                                data-kt-ecommerce-category-filter="category_name">{{ $service->name }}</a>
                        </div>
                    </div>
                </td>
                <td>{{ $service->orders->count() }}</td>
                <td>{{ $service->orders->where('status', 'approved')->count() }}</td>
                <td>{{ $service->orders->where('status', 'pending_and_show_price')->count() }}</td>
                <td>{{ $service->orders->where('status', 'pending_and_Initial_reservation')->count() }}</td>
                <td>{{ $service->orders->where('status', 'delayed')->count() }}</td>
                <td>{{ $service->orders->where('status', 'canceled')->count() }}</td>
                <td>{{ $service->orders->where('status', 'completed')->count() }}</td>
                <td>{{ $service->orders->pluck('customer')->unique('id')->count() }}</td>
                <!-- <td>{{ $service->description }}</td> -->
                <td>{{ number_format($service->price, 2) }} AED</td>
            </tr>
            @endforeach
        </tbody>
        <!--end::Table body-->
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.querySelectorAll('[data-kt-ecommerce-category-filter="delete_row"]').forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm("Are you sure you want to delete this service?")) {
                event.preventDefault();
            }
        });
    });
    // إعداد بيانات الرسم البياني من الخادم
    const revenuesByDay = @json($revenues_by_day ?? []);
    const labels = revenuesByDay.map(r => r.day);
    const generalData = revenuesByDay.map(r => Number(r.general_total ?? 0));
    const reservationData = revenuesByDay.map(r => Number(r.reservation_total ?? 0));
    const totalData = revenuesByDay.map(r => Number(r.total ?? 0));

    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                    label: 'الإيرادات العامة (AED)',
                    data: generalData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                },
                {
                    label: 'إيرادات الحجوزات (AED)',
                    data: reservationData,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                },
                {
                    label: 'الإجمالي (AED)',
                    data: totalData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.15)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            stacked: false,
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'الإيرادات (AED)',
                        font: {
                            size: 16,
                            weight: 'strong'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            try {
                                return Number(value).toLocaleString();
                            } catch (e) {
                                return value;
                            }
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const v = Number(tooltipItem.raw || 0).toLocaleString();
                            return `${tooltipItem.dataset.label}: AED ${v}`;
                        }
                    }
                }
            }
        }
    });
</script>

<style>
    body {
        /* font-family: 'Cairo', sans-serif; */
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        width: 90%;
        max-width: 1600px;
        margin: auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
        width: 100%;
    }

    .stat-item {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-item h3 {
        margin: 10px 0;
        font-size: 24px;
        color: #333;
        font-weight: bold;
    }

    .stat-item p {
        font-size: 16px;
        color: #777;
    }

    .chart-container {
        width: 100%;
        height: 400px;
        margin-top: 20px;
        position: relative;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    canvas {
        width: 100% !important;
        height: 100% !important;
    }

    input#date-range {
        text-align: center;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    p {
        font-size: 18px;
        margin-bottom: 20px;
        color: #555;
        text-align: center;
    }
</style>
@endsection
