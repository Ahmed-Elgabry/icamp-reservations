@extends('dashboard.layouts.app')

@push('styles')
<style>
    /* Responsive table styles */
    @media (max-width: 768px) {
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        
        .table thead {
            display: none;
        }
        
        .table tbody {
            display: block;
            width: 100%;
        }
        
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table tbody td:before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 1rem;
            flex: 0 0 40%;
        }
        
        .table tbody td:last-child {
            border-bottom: none;
        }
        
        /* For the stocks section */
        .stock-item-row {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
        }
        
        .stock-item-row > div {
            flex: 1 0 50%;
            padding: 0.5rem;
        }
        
        .stock-item-row > div:before {
            content: attr(data-label);
            font-weight: bold;
            display: block;
            margin-bottom: 0.25rem;
        }
    }
    
    /* Fix for select2 in responsive mode */
    .select2-container {
        width: 100% !important;
    }
    
    /* Make images responsive */
    .preview-image {
        max-width: 100%;
        height: auto;
        object-fit: cover;
    }
</style>
@endpush
@section('pageTitle', __('dashboard.services'))
@props(['reports' => []])
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     data-bs-target="#kt_account_profile_details" aria-expanded="true"
                     aria-controls="kt_account_profile_details">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            {{ isset($service) ? $service->name : __('dashboard.create_title', ['page_title' => __('dashboard.services')]) }}
                        </h3>
                    </div>
                </div>

                <div id="kt_account_settings_profile_details" class="collapse show">
                    <form id="kt_ecommerce_add_service_form"
                          action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}"
                          data-kt-redirect="{{ isset($service) ? route('services.edit', $service->id) : route('services.create') }}"
                          method="post" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row store">
                        @csrf
                        @if (isset($service))
                            @method('PUT')
                        @endif

                        <div class="card-body border-top p-9">
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.name')</label>
                                <div class="col-lg-8">
                                    <input type="text" name="name" required
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="@lang('dashboard.name')"
                                           value="{{ isset($service) ? $service->name : old('name') }}">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.registeration_forms_service')</label>
                                <div class="col-lg-8">
                                    <input type="checkbox" name="registeration_forms" class="" id="registeration_forms" {{ isset($service) && $service->registeration_forms ? 'checked' : '' }} >
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.price')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="price" step="0.01" required
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="@lang('dashboard.price')"
                                           value="{{ isset($service) ? $service->price : old('price') }}">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.hours')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="hours" required
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="@lang('dashboard.hours')"
                                           value="{{ isset($service) ? $service->hours : old('hours') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-6">
                                    <label class="col-lg-12 col-form-label fw-bold fs-6 required">@lang('dashboard.hour_from')</label>
                                    <div class="col-lg-12">
                                        <input type="time" name="hour_from" required
                                               class="form-control form-control-lg form-control-solid"
                                               placeholder="@lang('dashboard.hour_from')"
                                               value="{{ isset($service) ? $service->hour_from : old('hour_from') }}">
                                    </div>
                                </div>
                                <div class="col-6 mb-6">
                                    <label class="col-lg-12 col-form-label fw-bold fs-6 required">@lang('dashboard.hour_to')</label>
                                    <div class="col-lg-12">
                                        <input type="time" name="hour_to" required
                                               class="form-control form-control-lg form-control-solid"
                                               placeholder="@lang('dashboard.hour_to')"
                                               value="{{ isset($service) ? $service->hour_to : old('hour_to') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                                <div class="col-lg-8">
                                    <textarea name="description" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.notes')">{{ isset($service) ? $service->description : old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="row flex-wrap mt-4 justify-content-center">
                                <button type="button" class="btn btn-primary btn-sm mb-2 w-auto" id="add-stock-item">
                                    <i class="fa fa-plus"></i> @lang('dashboard.add_stock_item')
                                </button>
                                <button type="button" class="btn btn-primary btn-sm mb-2 w-auto" style="margin-right: 5px;" id="add-report-item">
                                    <i class="fa fa-plus"></i> @lang('dashboard.add_report_item')
                                </button>
                            </div>

                            <hr>
                            <h3>@lang('dashboard.report')</h3>

                            <div id="reports-section">
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table class="table table-bordered table-responsive">
                                        <thead >
                                            <tr>
                                                <th class="text-nowrap">@lang('dashboard.sequence')</th>
                                                <th class="text-nowrap">@lang('dashboard.image')</th>
                                                <th class="text-nowrap">@lang('dashboard.item_name')</th>
                                                <th class="text-nowrap">@lang('dashboard.required_qty')</th>
                                                <th class="text-nowrap">@lang('dashboard.upload_image')</th>
                                                <th class="text-nowrap">@lang('dashboard.edit_sort')</th>
                                                <th class="text-nowrap">@lang('dashboard.controll')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reports as $index => $report)
                                                <tr data-index="{{ $index }}" class="reports-item-row align-middle">
                                                    <td class="text-center text-nowrap" data-label="@lang('dashboard.sequence')">
                                                        <span class="row-number">{{ $index + 1 }}</span>
                                                    </td>
                                                    <td class="text-nowrap" data-label="@lang('dashboard.image')">
                                                        @if ($report->image)
                                                            <img src="{{ asset($report->image) }}" class="preview-image" style="width:75px;height:75px;object-fit:cover;">
                                                        @else
                                                            <img src="{{ asset('images/logo.png') }}" class="preview-image" style="width:50px;height:50px;object-fit:cover;">
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap" data-label="@lang('dashboard.item_name')">
                                                        <input type="text" name="reports[]" class="form-control form-control-lg form-control-solid w-100"
                                                               value="{{ $report->name }}" placeholder="@lang('dashboard.name')" required>
                                                    </td>
                                                    <td class="text-nowrap" data-label="@lang('dashboard.required_qty')">
                                                        <input type="number" name="reports_counts[]" min="1" class="form-control form-control-lg form-control-solid w-100"
                                                               value="{{ $report->count }}" placeholder="@lang('dashboard.count')" required style="min-width: 100px;">
                                                    </td>
                                                    <td class="text-nowrap" data-label="@lang('dashboard.upload_image')">
                                                        <input type="file" name="reports_images[{{ $index }}]" accept="image/*"
                                                               class="form-control form-control-lg form-control-solid image-upload w-100">
                                                        <div class="progress mt-2 d-none">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated w-100" role="progressbar"
                                                                 style="width:0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                                        </div>
                                                    </td>
                                                    <td class="text-nowrap" data-label="@lang('dashboard.edit_sort')">
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-light-primary js-report-move"
                                                                    data-direction="up"
                                                                    data-url="{{ route('services.reports.move', [$service->id, $report->id]) }}"
                                                                    {{ $index == 0 ? 'disabled' : '' }}>
                                                                <i class="fa fa-arrow-up"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-light-primary js-report-move"
                                                                    data-direction="down"
                                                                    data-url="{{ route('services.reports.move', [$service->id, $report->id]) }}"
                                                                    {{ $index == count($reports) - 1 ? 'disabled' : '' }}>
                                                                <i class="fa fa-arrow-down"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="text-nowrap" data-label="@lang('dashboard.controll')">
                                                        @can('services.delete.internal')
                                                            <a href="#"
                                                               class="btn btn-danger btn-sm js-delete-stock"
                                                               data-url="{{ route('stocks.destroyServiceReport', ['report' => $report->id]) }}">
                                                                @lang('dashboard.delete')
                                                            </a>
                                                        @endcan
                                                    </td>
                                                    <input type="hidden" name="report_orders[]" value="{{ $report->order ?? ($index + 1) }}">
                                                    <input type="hidden" name="report_ids[]" value="{{ $report->id }}">
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div id="stocks-section" class="table-responsive">
                                @if (isset($service))
                                    @foreach ($service->stocks as $index => $serviceStock)                                        <div class="table-row d-flex align-items-center stock-item-row mb-2" data-index="{{ $index + 1 }}" style="display: flex; flex-wrap: nowrap; min-width: 100%;">
                                            <div class="table-cell" style="flex: 0 0 8.33%; padding: 0.5rem;">
                                            @if ($serviceStock->image )
                                            <img src="{{$serviceStock->image }}" alt="preview-image" class="preview-image img-fluid rounded-circle" style="width:50px;height:50px;object-fit:cover;">
                                            @endif
                                            </div>
                                            <div class="table-cell" style="flex: 0 0 8.33%; padding: 0.5rem;">
                                                <span class="d-flex flex-row text-nowrap">{{ $index + 1 }} (المخزن)</span>
                                            </div>
                                            <div class="table-cell" style="flex: 1 0 33.33%; padding: 0.5rem;">
                                                <select name="stocks[]" class="form-select-stock select2-custome col-12 form-select-lg form-select-solid" required>
                                                    <option value="" disabled>{{ __('dashboard.select') }}</option>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}" {{ $serviceStock->id == $stock->id ? 'selected' : '' }}>{{ $stock->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="table-cell" style="flex: 0 0 16.66%; padding: 0.5rem;">
                                                <input type="number" name="counts[]" min="1"
                                                       class="form-control form-control-lg form-control-solid"
                                                       placeholder="@lang('dashboard.count')"
                                                       value="{{ $serviceStock->pivot->count }}" required
                                                       style="min-width: 100px;">
                                            </div>
                                            <div class="table-cell" style="flex: 0 0 16.66%; padding: 0.5rem;">
                                                @can('services.delete.internal')
                                                    <a href="#" class="btn btn-danger btn-sm js-delete-stock"
                                                       data-url="{{ route('stocks.destroyServiceStock', ['service' => $service->id, 'stock' => $serviceStock->id]) }}">
                                                        @lang('dashboard.delete')
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                    <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                    <span class="indicator-progress">@lang('dashboard.please_wait')
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function initSelect2(scope) {

        const $scope = scope ? $(scope) : $(document);
        $scope.find('.select2-custome').each(function () {
            const $el = $(this);
            $el.select2();
        });
    }

    function attachProgressListeners() {
        document.querySelectorAll('.image-upload').forEach(input => {
            if (input._hasProgressListener) return;
            input._hasProgressListener = true;

            input.addEventListener('change', () => {
                const files = input.files;
                if (!files.length) return;

                const row     = input.closest('.reports-item-row');
                const wrapper = row.querySelector('.progress');
                const bar     = row.querySelector('.progress-bar');

                wrapper.classList.remove('d-none');
                bar.style.width = '0%';
                bar.innerText   = '0%';
                bar.setAttribute('aria-valuenow', 0);

                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    bar.style.width = progress + '%';
                    bar.innerText   = progress + '%';
                    bar.setAttribute('aria-valuenow', progress);
                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            wrapper.classList.add('d-none');
                            bar.style.width = '0%';
                            bar.innerText   = '0%';
                            bar.setAttribute('aria-valuenow', 0);
                        }, 800);
                    }
                }, 80);
            });
        });
    }

    function renumberStockRows() {
        $('#stocks-section .stock-item-row').each(function (i) {
            $(this).find('.row-number').text((i + 1) + ' (المخزن)');
        });
    }

    function renumberReportRows() {
        $('.reports-item-row').each(function (i) {
            $(this).find('.row-number').text(i + 1);
        });
    }

    function refreshReportMoveButtons() {
        const $rows = $('.reports-item-row');
        $rows.find('.move-up, .move-down').prop('disabled', false);
        $rows.first().find('.move-up').prop('disabled', true);
        $rows.last().find('.move-down').prop('disabled', true);
    }

    function updateReportOrders() {
        $('.reports-item-row').each(function (i) {
            $(this).find('input[name="report_orders[]"]').val(i + 1);
        });
    }

    $(function () {
        attachProgressListeners();
        initSelect2();

        $('#add-stock-item').on('click', function () {
            const newIndex = $('#stocks-section .stock-item-row').length + 1;
            const newRowHtml = `
            <div class="row align-items-center stock-item-row mb-2" data-index="${newIndex}">
                <div class="col-1">
                    <img src="{{ asset('images/logo.png') }}" alt="preview-image" class="preview-image" style="width:50px;height:50px;display:none;">
                </div>
                <div class="col-1">
                    <span class="row-number text-nowrap">${newIndex} (المخزن)</span>
                </div>
                <div class="col-4">
                    <select name="stocks[]" class="form-select-stock select2-custome col-12 form-select-lg form-select-solid" required>
                        <option value="" selected disabled>{{ __('dashboard.select') }}</option>
                        @foreach ($stocks as $stock)
                            <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <input type="number" name="counts[]" min="1"
                           class="form-control form-control-lg form-control-solid"
                           placeholder="@lang('dashboard.count')" required>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-sm remove-stock-row">@lang('dashboard.delete')</button>
                </div>
            </div>`;
            const $row = $(newRowHtml);
            $('#stocks-section').append($row);
            initSelect2($row);
            renumberStockRows();
        });

        $(document).on('click', '.remove-stock-row', function () {
            $(this).closest('.stock-item-row').remove();
            renumberStockRows();
        });

        $('#add-report-item').on('click', function () {
            const newIndex = $('.reports-item-row').length + 1;
            const newRow = `
            <div class="row align-reports-center reports-item-row mb-2" data-index="${newIndex}">
                <div class="col-1">
                    <span class="row-number">${newIndex}</span>
                </div>
                <div class="col-5">
                    <input type="text" name="reports[]" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.name')" required>
                </div>
                <div class="col-2">
                    <input type="number" name="reports_counts[]" min="1" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.count')" required>
                </div>
                <div class="col-2">
                    <input type="file" name="reports_images[${newIndex}]" accept="image/*" class="form-control form-control-lg form-control-solid image-upload">
                    <div class="progress mt-2 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-sm btn-light-primary move-up"><i class="fa fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-sm btn-light-primary move-down"><i class="fa fa-arrow-down"></i></button>
                    <button type="button" class="btn btn-danger btn-sm remove-report-row">@lang('dashboard.delete')</button>
                </div>
                <input type="hidden" name="report_orders[]" value="${newIndex}">
            </div>`;
            $('#reports-section').append(newRow);
            attachProgressListeners();
            renumberReportRows();
            refreshReportMoveButtons();
            updateReportOrders();
        });

        $(document).on('click', '.remove-report-row', function () {
            $(this).closest('.reports-item-row').remove();
            renumberReportRows();
            refreshReportMoveButtons();
            updateReportOrders();
        });

        $(document).on('click', '.move-up', function () {
            const $row = $(this).closest('.reports-item-row');
            const $prev = $row.prev('.reports-item-row');
            if ($prev.length) {
                $row.insertBefore($prev);
                renumberReportRows();
                refreshReportMoveButtons();
                updateReportOrders();
            }
        });

        $(document).on('click', '.move-down', function () {
            const $row = $(this).closest('.reports-item-row');
            const $next = $row.next('.reports-item-row');
            if ($next.length) {
                $row.insertAfter($next);
                renumberReportRows();
                refreshReportMoveButtons();
                updateReportOrders();
            }
        });

        $(document).off('click', '[data-kt-ecommerce-category-filter="delete_row"]');

        function toast(type, msg) {
            if (window.toastr && toastr[type]) toastr[type](msg);
            else alert(msg);
        }
        function confirmTop(opts, onOk) {
            if (window.Swal) {
                Swal.fire({
                    title: opts.title || 'Confirm',
                    text:  opts.text  || 'Are you sure?',
                    icon: 'warning',
                    position: 'top',
                    width: 360,
                    showCancelButton: true,
                    confirmButtonText: opts.confirmText || 'Delete',
                    cancelButtonText:  opts.cancelText  || 'Cancel',
                    customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary' },
                    buttonsStyling: false
                }).then(res => { if (res.isConfirmed) onOk(); });
            } else {
                if (window.confirm(opts.text || 'Are you sure?')) onOk();
            }
        }

        $(document).on('click', '.js-delete-stock', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $link = $(this);
            const url   = $link.data('url');
            const $row  = $link.closest('.stock-item-row');

            if (!url) { toast('error', 'Missing delete URL'); return; }

            confirmTop({
                title: "{{ __('dashboard.confirm_delete') }}",
                confirmText: "{{ __('dashboard.delete') }}",
                cancelText:  "{{ __('dashboard.cancel') }}"
            }, function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (resp) {
                        $row.remove();
                        renumberStockRows();
                        toast('success', resp?.message || "{{ __('dashboard.success') }}");
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.error || xhr.responseJSON?.message || "{{ __('dashboard.error') }}";
                        toast('error', msg);
                    }
                });
            });
        });
    });

    (function ($) {
        function toast(type, msg) {
            if (window.toastr && toastr[type]) toastr[type](msg);
            else alert(msg);
        }

        function renumberReportRows() {
            $('.reports-item-row').each(function (i) {
            $(this).find('.row-number').text(i + 1);
            $(this).find('input[name="report_orders[]"]').val(i + 1);
            });
        }

        function refreshReportMoveButtons() {
            const $rows = $('.reports-item-row');
            $rows.find('.js-report-move[data-direction="up"], .js-report-move[data-direction="down"]').prop('disabled', false);
            $rows.first().find('.js-report-move[data-direction="up"]').prop('disabled', true);
            $rows.last().find('.js-report-move[data-direction="down"]').prop('disabled', true);
        }

        $(document).on('click', '.js-report-move', function () {
            const $btn = $(this);
            const url = $btn.data('url');
            const dir = $btn.data('direction');
            const $row = $btn.closest('.reports-item-row');

            if (!url || !dir) return;

            $btn.prop('disabled', true);

            $.ajax({
            url: url,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { direction: dir },
            success: function () {
                if (dir === 'up') {
                const $prev = $row.prev('.reports-item-row');
                if ($prev.length) $row.insertBefore($prev);
                } else {
                const $next = $row.next('.reports-item-row');
                if ($next.length) $row.insertAfter($next);
                }
                renumberReportRows();
                refreshReportMoveButtons();
                toast('success', "{{ __('dashboard.success') }}");
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.errors?.order?.[0]
                || xhr.responseJSON?.message
                || "{{ __('dashboard.server_error') }}";
                toast('error', msg);
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
            });
        });

        $(document).on('click', '.remove-report-row', function () {
            $(this).closest('.reports-item-row').remove();
            renumberReportRows();
            refreshReportMoveButtons();
        });

        $(function () {
            renumberReportRows();
            refreshReportMoveButtons();
        });
    })(jQuery);
    </script>

@endsection

@push('css')
<style>
    .form-select-solid,
    .progress { height: 10px; }
    .preview-image { object-fit: cover; border-radius: 5px; }
</style>
@endpush
