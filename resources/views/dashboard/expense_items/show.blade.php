@extends('dashboard.layouts.app')
@section('pageTitle', __($expenseItem->name ))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#expense_details" data-kt-ecommerce-category-filter="search">  {{ $expenseItem->name}} </a>
                </li>
            </ul>
            <hr>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="expense_details" role="tab-panel">
                    <div class="card card-flush py-4">

                        <div class="card-body pt-10 row mb-10">
                            <div class="mb-5 fv-row col-md-6">
                                <a href="{{ route('expense-items.edit', $expenseItem->id) }}">
                                    أسم البند <i class="fa fa-edit"></i>
                                </a>
                                <div class="card-text  rounded p-3">{{ $expenseItem->name }}</div>
                            </div>
                            <div class="mb-5 fv-row col-md-6">
                                <label class="form-label">ملاحظات</label>
                                <div class="card-text  rounded p-3">{{ $expenseItem->notes }}</div>
                            </div>
                            <div class="mb-5 fv-row col-md-6">
                                <label class="form-label">إجمالي المصاريف</label>
                                <div class="card-text  rounded p-3">{{ $totalExpenses }}</div>
                            </div>
                            <div class="mb-5 fv-row col-md-6">
                                <label class="form-label">مصاريف الشهر الحالي</label>
                                <div class="card-text  rounded p-3">{{ $currentMonthExpenses }}</div>
                            </div>
                        </div>
                        <hr style="height:20px;background:#dbe4e9">


                        @include('dashboard.expenses.table')

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
    <style>
                .card-text {
            font-size: 1rem;
            font-weight: 500;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .list-group-item {
            font-size: 1rem;
        }
        .badge {
            font-size: 1rem;
        }
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        .border {
            border-color: #dee2e6 !important;
        }

        .table-responsive {
            padding: 20px;
            text-align: right;
        }
    </style>
@endpush    