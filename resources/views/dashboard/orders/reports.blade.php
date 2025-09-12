@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.reports'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            @include('dashboard.orders.nav')

            <div class="card mb-5 mb-xl-10">
                 <!-- customer information -->
                  <div class="pt-5 px-9 gap-2 gap-md-5">
                    <div class="row g-3 small">
                        <div class="col-md-1 text-center">
                            <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                            <div class="fw-bold">{{ $order->id }}</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="fw-semibold text-muted">{{ __('dashboard.customer_name') }}</div>
                            <div class="fw-bold">{{ $order->customer->name }}</div>
                        </div>
                    </div>
                </div>
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

                        <div class="table-responsive report-item">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center">@lang('dashboard.serial')</th>
                                        <th class="text-center">@lang('dashboard.image')</th>
                                        <th>@lang('dashboard.item')</th>
                                        <th class="text-center">@lang('dashboard.requested_qty')</th>
                                        <th class="text-center">@lang('dashboard.placed_qty')</th>
                                        <th class="text-center">@lang('dashboard.completion_status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $index => $report)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">
                                                @if ($latest = $report->image)
                                                    <a href="{{ asset($report->image) }}" target="_blank">
                                                        <img src="{{ asset($latest) }}" alt="{{ $report->name }}" class="report-image">
                                                    </a>
                                                @else
                                                    <img src="{{ asset('dashboard/assets/media/avatars/blank.png') }}" alt="{{ $report->name }}" class="report-image">
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $report->name }}</div>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="ordered_count[{{ $report->id }}]" min="0" max="{{ $report->count }}" class="form-control text-muted" value="{{ $report->count }}" readonly>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="set_qty[{{ $report->id }}]" min="0" max="{{ $report->count }}" class="form-control" value="{{ ($report->set_qty == 0) ? $report->count : $report->set_qty }}">
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="btn-group w-100" role="group" aria-label="Report Status">
                                                        <input type="radio" class="btn-check reports-check" name="reports[{{ $report->id }}]" id="completed-{{ $report->id }}" value="completed" data-report="{{ $report->id }}" {{ $report && $report->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success" for="completed-{{ $report->id }}"><i class="fa fa-check me-1"></i> @lang('dashboard.completed')</label>

                                                        <input type="radio" class="btn-check reports-check-not" name="reports[{{ $report->id }}]" id="not-completed-{{ $report->id }}" value="not_completed" data-report="{{ $report->id }}" {{ $report && !$report->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-danger" for="not-completed-{{ $report->id }}"><i class="fa fa-times me-1"></i> @lang('dashboard.not_completed')</label>
                                                    </div>

                                                    <div class="incomplete-reason mt-2" data-report="{{ $report->id }}" @if($report && $report->is_completed) style="display:none" @endif>
                                                        <label class="form-label small">@lang('dashboard.not_completed_reason')</label>
                                                        <textarea class="form-control" rows="2" name="not_completed_reason[{{ $report->id }}]">{{ $report ? $report->not_completed_reason : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                       

                        <div class="table-responsive report-item">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center">@lang('dashboard.serial')</th>
                                        <th class="text-center">@lang('dashboard.image')</th>
                                        <th>@lang('dashboard.item')</th>
                                        <th class="text-center">@lang('dashboard.available')</th>
                                        <th class="text-center">@lang('dashboard.requested_qty')</th>
                                        <th class="text-center">@lang('dashboard.completion_status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($service->stocks as $index => $stock)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">
                                                @if ($latest = $stock->image)
                                                    <a href="{{ asset($stock->image) }}" target="_blank">
                                                        <img src="{{ asset($latest) }}" alt="{{ $stock->name }}" class="report-image">
                                                    </a>
                                                @else
                                                    <img src="{{ asset('dashboard/assets/media/avatars/blank.png') }}" alt="{{ $stock->name }}" class="report-image">
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $stock->name }}</div>
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" name="count_stock[{{ $stock->pivot->id }}]" class="form-control text-muted" value="{{ $stock->pivot->count ?? '' }}" >
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <input type="number" value="{{ $stock->pivot->required_qty }}" name="required_qty_stock[{{ $stock->pivot->id }}]" class="form-control" style="width:80px">
                                                    <div class="d-flex flex-column">
                                                        <button type="button" class="btn btn-sm btn-danger btn-decrement" data-pivot-id="{{ $stock->pivot->id }}" data-stock-id="{{ $stock->id }}" data-stock-name="{{ $stock->name }}" data-status="decrement"><i class="fa fa-minus"></i></button>
                                                        @if ($stock->pivot->latest_activity)
                                                            <small class="d-block mt-1 text-center">
                                                                <span class="badge rounded-pill {{ $stock->pivot->latest_activity === 'increment' ? 'bg-success' : ($stock->pivot->latest_activity === 'decrement' ? 'bg-danger' : 'bg-secondary') }}">
                                                                    {{ $stock->pivot->latest_activity ? __('dashboard.'.$stock->pivot->latest_activity) : '—' }}
                                                                </span>
                                                            </small>
                                                        @endif
                                                        <button type="button" class="btn btn-sm btn-success btn-decrement mt-1" data-pivot-id="{{ $stock->pivot->id }}" data-stock-id="{{ $stock->id }}" data-stock-name="{{ $stock->name }}" data-status="increment"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="btn-group w-100" role="group" aria-label="stock Status">
                                                        <input type="radio" class="btn-check stock-check" name="stock[{{ $stock->pivot->id }}]" id="completed-stock-{{ $stock->pivot->id }}" value="completed" data-stock="{{ $stock->pivot->id }}" {{ $stock && $stock->pivot->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success" for="completed-stock-{{ $stock->pivot->id }}"><i class="fa fa-check me-1"></i> @lang('dashboard.completed')</label>

                                                        <input type="radio" class="btn-check stock-check-not" name="stock[{{ $stock->pivot->id }}]" id="not-completed-stock-{{ $stock->pivot->id }}" value="not_completed" data-stock="{{ $stock->pivot->id }}" {{ $stock && !$stock->pivot->is_completed ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-danger" for="not-completed-stock-{{ $stock->pivot->id }}"><i class="fa fa-times me-1"></i> @lang('dashboard.not_completed')</label>
                                                    </div>

                                                    <div class="incomplete-reason mt-2" data-stock="{{ $stock->pivot->id }}" @if($stock && $stock->pivot->is_completed) style="display:none" @endif>
                                                        <label class="form-label small">@lang('dashboard.not_completed_reason')</label>
                                                        <textarea class="form-control incomplete-reason" rows="2" name="not_completed_reason_stock[{{ $stock->pivot->id }}]" data-report="{{ $stock->pivot->id }}">{{ $stock ? $stock->pivot->not_completed_reason : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="form-control">
                            <textarea name="report_text" id="report_text"
                                class="form-control form-control-lg form-control-solid">{{ $order->report_text }}</textarea>
                        </div>

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
            $(document).on('change', '.reports-check', function () {
                if (this.checked) {
                    $('.reports-check-not,.stock-check-not').prop('checked', false);
                    $('.incomplete-reason').hide();
                }
            });

            $(document).on('change', '.reports-check-not', function () {
                if (this.checked) {
                    $('.reports-check,.stock-check').prop('checked', false);
                    $('.incomplete-reason').show();
                } else {
                    $('.incomplete-reason').hide();
                }
            });

            $('#mark-all-completed').on('click', function () {
                $('.reports-check,.stock-check').each(function () {
                    $(this).prop('checked', true).trigger('change');
                });
            });

            $('#mark-all-not-completed').on('click', function () {
                $('.reports-check-not,.stock-check-not').each(function () {
                    $(this).prop('checked', true).trigger('change');
                });
            });
        });

      $(document).on('click', '.btn-decrement', function (e) {
          e.preventDefault();
          console.log('.btn-decrement clicked (resources)', this);

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
                title: status === 'decrement' ? "@lang('dashboard.increment_label')" : "@lang('dashboard.decrement_label')" ,
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
