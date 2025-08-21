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
                                    <div class="col-1 small-text">{{ __('dashboard.serial') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.image') }}</div>
                                    <div class="col-1 small-text">{{ __('dashboard.item') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.requested_qty') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.placed_qty') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.completion_status') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-1 small-text">{{ __('dashboard.serial') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.image') }}</div>
                                    <div class="col-1 small-text">{{ __('dashboard.item') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.requested_qty') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.placed_qty') }}</div>
                                    <div class="col-2 small-text">{{ __('dashboard.completion_status') }}</div>
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
                                                <input type="number"  name="ordered_count[{{ $report->id }}]" min="0"
                                                    max="{{ $report->count }}" class="form-control text-muted"
                                                    value="{{ $orderReport->ordered_count ?? '' }}">
                                            </div>

                                            <div class="col-2 col-md-2">
                                                <input type="number" value="{{ $report->count }}" min="0" max="100"
                                                    name="count[{{ $report->id }}]" class="form-control">
                                            </div>
                                            <div class="col-12 col-md-4 mb-3">
                                                <div class="card p-3 shadow-sm border">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="fw-bold">{{ $report->title ?? 'Report' }}</span>
                                                    </div>

                                                    <div class="btn-group w-100" role="group" aria-label="Report Status">
                                                        <input type="radio" class="btn-check reports-check"
                                                            name="reports[{{ $report->id }}]"
                                                            id="completed-{{ $report->id }}"
                                                            value="completed"
                                                            data-report="{{ $report->id }}"
                                                            {{ $report && $report->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success" for="completed-{{ $report->id }}">
                                                            <i class="fa fa-check me-1"></i> @lang('dashboard.completed')
                                                        </label>

                                                        <input type="radio" class="btn-check reports-check-not"
                                                            name="reports[{{ $report->id }}]"
                                                            id="not-completed-{{ $report->id }}"
                                                            value="not_completed"
                                                            data-report="{{ $report->id }}"
                                                            {{ $report && !$report->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-danger" for="not-completed-{{ $report->id }}">
                                                            <i class="fa fa-times me-1"></i> @lang('dashboard.not_completed')
                                                        </label>
                                                    </div>

                                                    <div class="incomplete-reason mt-3 col-12"
                                                        data-report="{{ $report->id }}"
                                                        @if($report && $report->is_completed) style="display:none" @endif>
                                                        <label class="form-label">@lang('dashboard.not_completed_reason')</label>
                                                        <textarea class="form-control" rows="2"
                                                                name="not_completed_reason[{{ $report->id }}]"
                                                                data-report="{{ $report->id }}">{{ $report ? $report->not_completed_reason : '' }}</textarea>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row report-item">
                            @foreach ($service->stocks as $index => $stock)
                                <div class="col-md-6 mt-2">
                                    <div class="single-item">
                                        <div class="row">
                                            <div class="col-1 col-md-1">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="col-1 col-md-1">
                                                @if ($latest = $stock->image)
                                                    <a href="{{ asset($stock->image) }}" target="_blank">
                                                        <img src="{{ asset($latest) }}" alt="{{ $stock->name }}"
                                                            class="report-image">
                                                    </a>
                                                @else
                                                    <img src="{{ asset('dashboard/assets/media/avatars/blank.png') }}"
                                                        alt="{{ $stock->name }}" class="report-image">
                                                @endif
                                            </div>

                                            <div class="col-2 col-md-2">
                                                <label>{{ $stock->name }}</label>
                                            </div>
                                            <div class="col-2 col-md-2">
                                                    <input type="number"  name="count_stock[{{ $stock->pivot->id }}]"
                                                        min="0"
                                                        class="form-control text-muted"
                                                        value="{{ $stock->pivot->count ?? '' }}">
                                                </div>

                                                <div class="col-2 col-md-2">
                                                    <input type="number"
                                                        value="{{ $stock->pivot->required_qty }}"
                                                        name="required_qty_stock[{{ $stock->pivot->id }}]"
                                                        class="form-control">
                                                        <button type="button"
                                                                class="btn btn-danger btn-decrement mt-2"
                                                                data-pivot-id="{{ $stock->pivot->id }}"
                                                                data-stock-id="{{ $stock->id }}"
                                                                data-stock-name="{{ $stock->name }}"
                                                                data-status="decrement">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-success btn-decrement mt-2"
                                                                data-pivot-id="{{ $stock->pivot->id }}"
                                                                data-stock-id="{{ $stock->id }}"
                                                                data-stock-name="{{ $stock->name }}"
                                                                data-status="increment">
                                                                <i class="fa fa-plus"></i>
                                                        </button>    
                                                </div>
   
                                            <div class="col-12 col-md-4 mb-3">
                                                <div class="card p-3 shadow-sm border">
                                                    <div class="btn-group w-100" role="group" aria-label="stock Status">
                                                        <input type="radio" class="btn-check stock-check"
                                                            name="stock[{{ $stock->pivot->id }}]"
                                                            id="completed-stock-{{ $stock->pivot->id }}"
                                                            value="completed"
                                                            data-stock="{{ $stock->pivot->id }}"
                                                            {{ $stock && $stock->pivot->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success" for="completed-stock-{{ $stock->pivot->id }}">
                                                            <i class="fa fa-check me-1"></i> @lang('dashboard.completed')
                                                        </label>

                                                        <input type="radio" class="btn-check stock-check-not"
                                                            name="stock[{{ $stock->pivot->id }}]"
                                                            id="not-completed-stock-{{ $stock->pivot->id }}"
                                                            value="not_completed"
                                                            data-stock="{{ $stock->pivot->id }}"
                                                            {{ $stock && !$stock->pivot->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-danger" for="not-completed-stock-{{ $stock->pivot->id }}">
                                                            <i class="fa fa-times me-1"></i> @lang('dashboard.not_completed')
                                                        </label>
                                                       
                                                    </div>

                                                    <div class="incomplete-reason mt-3 col-12"
                                                        data-stock="{{ $stock->pivot->id }}"
                                                        @if($stock && $stock->pivot->is_completed) style="display:none" @endif>
                                                        <label class="form-label">@lang('dashboard.not_completed_reason')</label>
                                                        <textarea class="form-control" rows="2"
                                                                name="not_completed_reason_stock[{{ $stock->pivot->id }}]"
                                                                data-report="{{ $stock->pivot->id }}">{{ $stock ? $stock->pivot->not_completed_reason : '' }}</textarea>
                                                    </div>

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
    <script type="text/javascript">
       $(document).ready(function () {
            $('.reports-check').on('change', function () {
                let reportId = $(this).data('report');
                if ($(this).is(':checked')) {
                    $('.reports-check-not[data-report="' + reportId + '"]').prop('checked', false);
                    $('.incomplete-reason[data-report="' + reportId + '"]').hide();
                }
            });

            $('.reports-check-not').on('change', function () {
                let reportId = $(this).data('report');
                if ($(this).is(':checked')) {
                    $('.reports-check[data-report="' + reportId + '"]').prop('checked', false);
                    $('.incomplete-reason[data-report="' + reportId + '"]').show();
                } else {
                    $('.incomplete-reason[data-report="' + reportId + '"]').hide();
                }
            });

            $('#mark-all-completed').on('click', function () {
                $('.reports-check').each(function () {
                    $(this).prop('checked', true).trigger('change');
                });
            });

            $('#mark-all-not-completed').on('click', function () {
                $('.reports-check-not').each(function () {
                    $(this).prop('checked', true).trigger('change');
                });
            });

            $('.stock-check').on('change', function () {
                let reportId = $(this).data('stock');
                if ($(this).is(':checked')) {
                    $('.stock-check-not[data-stock="' + reportId + '"]').prop('checked', false);
                    $('.incomplete-reason[data-stock="' + reportId + '"]').hide();
                }
            });

            $('.stock-check-not').on('change', function () {
                let reportId = $(this).data('stock');
                if ($(this).is(':checked')) {
                    $('.stock-check[data-stock="' + reportId + '"]').prop('checked', false);
                    $('.incomplete-reason[data-stock="' + reportId + '"]').show();
                } else {
                    $('.incomplete-reason[data-stock="' + reportId + '"]').hide();
                }
            });

        });

       $(document).on('click', '.btn-decrement', function (e) {
            e.preventDefault();

            var $btn       = $(this),
                pivotId    = $btn.data('pivotId'),
                stockId    = $btn.data('stockId'),
                stockName  = $btn.data('stockName') || '',
                available  = parseInt($btn.data('available') || '0', 10),
                status     = $btn.data('status') || 'decrement';

            var $input = $('#required-qty-' + pivotId);
            if (!$input.length) $input = $('[name="required_qty_stock[' + pivotId + ']"]');

            var qty = parseInt(($input.val() || '').trim(), 10);
            if (isNaN(qty) || qty <= 0) {
                Swal.fire({ icon:'error', title:"@lang('dashboard.invalid_title')", text:"@lang('dashboard.invalid_text')", confirmButtonText:"@lang('dashboard.confirm')" });
                return;
            }
            var warn = '';
                 
            var html =
                "@lang('dashboard.confirm')";

            Swal.fire({
                icon: 'warning',
                title: status === 'decrement' ? "@lang('dashboard.decrement_label')" : "@lang('dashboard.increment_label')" ,
                html: status === 'decrement' ? html : '',
                showCancelButton: true,
                confirmButtonText: "@lang('dashboard.confirm')",
                cancelButtonText:  "@lang('dashboard.cancel')",
                reverseButtons: true
            }).then(function (res) {
                if (!res.isConfirmed) return;

                $.ajax({
                    url: "{{ route('stock.decrement') }}",
                    method: "POST",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { id: pivotId, stockId: stockId, qty: qty, status: status },
                    success: function(resp) {
                        Swal.fire({ icon:'success', title:"@lang('dashboard.confirm')", timer:1200, showConfirmButton:false });
                    },
                    error: function(xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error';
                        Swal.fire({ icon:'error', title:'Error', text: msg });
                    }

                });
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
