@extends('dashboard.layouts.app')

@section('content')

<<!-- نافذة منبثقة للفلاتر -->
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
        <input id="date-range" type="text">
        <p>@lang('dashboard.number_of_appointments')</p>
        <div class="stats">
            <div class="stat-item">
                <h3>{{ number_format($approvedOrders) }}</h3>
                <p>@lang('dashboard.Approved_appointments')</p>
            </div>
            <div class="stat-item">
                <h3>{{ number_format($paidOrders) }}</h3>
                <p>@lang('dashboard.Pending_appointments')</p>
            </div>
            <div class="stat-item">
                <h3>{{ number_format($orders) }}</h3>
                <p>@lang('dashboard.Total_appointments')</p>
            </div>
            <div class="stat-item">
                <h3>AED {{ number_format($payments->sum('amount'), 2, ',', '.') }}</h3>
                <p>@lang('dashboard.Aedpayments')</p>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="dashboard-container" style="margin-top: 20px; margin-bottom: 50px;">
        <h1 class="mb-3">@lang('dashboard.Analytics')</h1>

        <div class="stats mb-4">
            <button class="btn btn-primary col-1 custom-print-btn" onclick="window.print()">
                @lang('dashboard.Print')
            </button>
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
                    <th>@lang('dashboard.pending')</th>
                    <th>@lang('dashboard.rejected')</th>
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
                        <td>{{ $service->orders->where('status', 'pending')->count() }}</td>
                        <td>{{ $service->orders->where('status', 'rejected')->count() }}</td>
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
            button.addEventListener('click', function (event) {
                if (!confirm("Are you sure you want to delete this service?")) {
                    event.preventDefault();
                }
            });
        });
        const today = new Date();
        const last7DaysStart = new Date(today);
        last7DaysStart.setDate(today.getDate() - 7);

        function formatDate(date) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('ar-EG', options);
        }

        const dateRangeInput = document.getElementById('date-range');
        dateRangeInput.value = `${formatDate(today)} - ${formatDate(last7DaysStart)}`;

        const getDatesArray = (start, end) => {
            const dates = [];
            let currentDate = new Date(start);
            while (currentDate <= end) {
                dates.push(currentDate.toISOString().split('T')[0]);
                currentDate.setDate(currentDate.getDate() + 1);
            }
            return dates;
        };

        const labels = getDatesArray(last7DaysStart, today);
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'الإيرادات (AED)',
                    data: Array(labels.length).fill(0),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                            callback: function (value) {
                                return value.toLocaleString();
                            }
                        }
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
                            label: function (tooltipItem) {
                                return `الإيرادات: AED ${tooltipItem.raw.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    </script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
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