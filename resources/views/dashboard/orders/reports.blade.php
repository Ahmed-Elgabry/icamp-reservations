@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.orders'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            @include('dashboard.orders.nav')

            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer">
                    <h3 class="card-title fw-bolder m-0 mt-4">
                        <a href="{{ route('orders.edit', $order->id) }}">
                            @lang('dashboard.report') {{ $order->customer->name }}
                        </a>
                        <i class="fa fa-edit"></i>
                    </h3>
                    <div>
                        <button id="mark-all-completed" class="btn btn-sm btn-success mt-4" style="height:50px">
                            @lang('dashboard.mark_all_completed') <i class="fa fa-check"></i>
                        </button>
                        <button id="mark-all-not-completed" class="btn btn-sm btn-danger mt-4" style="height:50px">
                            @lang('dashboard.mark_all_not_completed') <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body border-top p-9">
                    <form id="update-reports-form" action="{{ route('update.reports', $order->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-primary">@lang('dashboard.save_changes')</button>
                        </div>

                        <div class="row mt-10 d-none d-md-flex">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-1 small-text">م</div>
                                    <div class="col-2 small-text">الصوره</div>
                                    <div class="col-1 small-text">البيان</div>
                                    <div class="col-2 small-text">الكمية المتاحة</div>
                                    <div class="col-2 small-text">الكمية المطلوبة</div>
                                    <div class="col-2 small-text">حالة الاكتمال</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-1 small-text">م</div>
                                    <div class="col-2 small-text">الصوره</div>
                                    <div class="col-1 small-text">البيان</div>
                                    <div class="col-2 small-text">الكمية المتاحة</div>
                                    <div class="col-2 small-text">الكمية المطلوبة</div>
                                    <div class="col-2 small-text">حالة الاكتمال</div>
                                </div>
                            </div>
                        </div>
                        <div class="row report-item">
                            @foreach ($reports as $index => $report)
                                @php
                                    $orderReport = $order->reports->firstWhere('service_report_id', $report->id);
                                @endphp
                                <div class="col-md-6 mt-2">
                                    <div class="single-item">
                                        <div class="row">
                                            <div class="col-1 col-md-1">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="col-1 col-md-1">
                                                @if ($latest = $report->image)
                                                    <a href="{{ asset($report->image) }}" target="_blank">
                                                        <img src="{{ asset($latest) }}" alt="{{ $report->name }}"
                                                            class="report-image">
                                                    </a>
                                                @else
                                                    <img src="{{ asset('dashboard/assets/media/avatars/blank.png') }}"
                                                        alt="{{ $report->name }}" class="report-image">
                                                @endif
                                            </div>

                                            <div class="col-2 col-md-2">
                                                <label>{{ $report->name }}</label>
                                            </div>
                                            <div class="col-2 col-md-2">
                                                <input type="number" value="{{ $report->count }}" min="0" max="100"
                                                    name="count[{{ $report->id }}]" class="form-control" readonly>
                                            </div>

                                            <!-- عرض قيمة ordered_count -->
                                            <div class="col-2 col-md-2">
                                                <input type="number" name="ordered_count[{{ $report->id }}]" min="0"
                                                    max="{{ $report->count }}" class="form-control"
                                                    value="{{ $orderReport->ordered_count ?? '' }}">
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <input type="checkbox" class="reports-check" data-report="{{ $report->id }}"
                                                    name="reports[{{ $report->id }}]" value="completed" {{ $orderReport && $orderReport->is_completed == 'completed' ? 'checked' : '' }}>
                                                <i class="fa fa-check text-success"></i>

                                                <input type="checkbox" class="reports-check-not"
                                                    data-report="{{ $report->id }}" name="reports_not[{{ $report->id }}]"
                                                    value="not_completed" {{ $orderReport && $orderReport->is_completed == 'not_completed' ? 'checked' : '' }}>
                                                <i class="fa fa-times text-danger"></i>

                                                <div class="incomplete-reason"
                                                    style="display: {{ $orderReport && $orderReport->is_completed == 'not_completed' ? 'block' : 'none' }}">
                                                    <label>@lang('dashboard.not_completed_reason')</label>
                                                    <textarea class="form-control"
                                                        name="not_completed_reason[{{ $report->id }}]"
                                                        data-report="{{ $report->id }}">{{ $orderReport ? $orderReport->not_completed_reason : '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-control">
                            <textarea name="report_text" id="report_text"
                                class="form-control form-control-lg form-control-solid">{{ $order->report_text }}</textarea>
                        </div>
                        @if (isset($order))
                                <!--begin::Input group-->
                                <div class="row mb-0 mt-5">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        {{ __('dashboard.Inventory withdrawal') }}
                                    </label>
                                    <!--begin::Label-->
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <div class="form-check form-check-solid form-switch fv-row">
                                            <input class="form-check-input w-45px h-30px" type="checkbox"
                                                {{ $order->inventory_withdrawal == '1' ? 'checked="checked"' : '' }}
                                                id="inventory_withdrawal" name="inventory_withdrawal">
                                            <label class="form-check-label" for="inventory_withdrawal"></label>
                                        </div>
                                    </div>
                                    <!--begin::Label-->
                                </div>
                                <!--end::Input group-->
                            @endif
                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-primary">@lang('dashboard.save_changes')</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('.reports-check').on('change', function () {
                var isChecked = $(this).is(':checked');
                var notCheckedElem = $(this).closest('.col-3').find('.reports-check-not');

                if (isChecked) {
                    notCheckedElem.prop('checked', false);
                    $(this).closest('.col-3').find('.incomplete-reason').hide();
                }
            });

            $('.reports-check-not').on('change', function () {
                var isChecked = $(this).is(':checked');
                var checkedElem = $(this).closest('.col-3').find('.reports-check');

                if (isChecked) {
                    checkedElem.prop('checked', false);
                    $(this).closest('.col-3').find('.incomplete-reason').show();
                } else {
                    $(this).closest('.col-3').find('.incomplete-reason').hide();
                }
            });

            $('#mark-all-completed').on('click', function () {
                $('.reports-check').each(function () {
                    if (!$(this).is(':checked')) {
                        $(this).prop('checked', true).trigger('change');
                    }
                });
                $('.reports-check-not').prop('checked', false).closest('.col-3').find('.incomplete-reason')
                    .hide();
            });

            $('#mark-all-not-completed').on('click', function () {
                $('.reports-check-not').each(function () {
                    if (!$(this).is(':checked')) {
                        $(this).prop('checked', true).trigger('change');
                    }
                });
                $('.reports-check').prop('checked', false);
            });
        });
    </script>
@endpush

@push('css')
    <style>
        .report-item {
            margin: 10px 0;
            padding: 13px 0;
            border-top: 1px solid #eee;
        }

        .incomplete-reason {
            margin-top: 10px;
        }

        .single-item {
            padding: 10px;
            border-radius: 10px;
            border: 1px dotted #ccc;
        }

        .col-2 img {
            display: block;
            margin: 0 auto;
        }

        .report-item {
            margin: 10px 0;
            padding: 13px 0;
            border-top: 1px solid #eee;
        }

        .incomplete-reason {
            margin-top: 10px;
        }

        .single-item {
            padding: 10px;
            border-radius: 10px;
            border: 1px dotted #ccc;
            margin-bottom: 10px;
        }

        .report-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
        }

        @media (max-width: 788px) {
            .single-item .row>div {
                margin-bottom: 5px;
            }

            .incomplete-reason {
                margin-top: 5px;
            }
        }
        .small-text {
            font-size: 1rem;
        }

        @media (max-width: 964px) {
            .small-text {
                font-size: 0.65rem;
                font-weight: 900;
            }
        }
    </style>
@endpush
