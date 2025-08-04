@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.services'))
@section('content')
    <!-------------->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Basic info-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                    data-bs-target="#kt_account_profile_details" aria-expanded="true"
                    aria-controls="kt_account_profile_details">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            {{ isset($service) ? $service->name : __('dashboard.create_title', ['page_title' => __('dashboard.services')]) }}
                        </h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div id="kt_account_settings_profile_details" class="collapse show">
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_service_form"
                        action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}"
                        data-kt-redirect="{{ isset($service) ? route('services.edit', $service->id) : route('services.create') }}"
                        method="post" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row store">
                        @csrf
                        @if (isset($service))
                            @method('PUT')
                        @endif

                        <div class="card-body border-top p-9">
                            <!-- Service Name Input -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.name')</label>
                                <div class="col-lg-8">
                                    <input type="text" name="name" required
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="@lang('dashboard.name')"
                                        value="{{ isset($service) ? $service->name : old('name') }}">
                                </div>
                            </div>

                            <!-- Service Price Input -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.price')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="price" step="0.01" required
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="@lang('dashboard.price')"
                                        value="{{ isset($service) ? $service->price : old('price') }}">
                                </div>
                            </div>

                            <!-- Service Days Input -->
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

                            <!-- Service Description Input -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                                <div class="col-lg-8">
                                    <textarea name="description" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.notes')">{{ isset($service) ? $service->description : old('description') }}</textarea>
                                </div>
                            </div>

                            <!-- Stock Items Section -->
                            <div id="stock-items-section">
                                @if (isset($service))
                                    @foreach ($service->stocks as $index => $serviceStock)
                                        <div class="row align-items-center stock-item-row mb-2">
                                            <div class="col-1">
                                                <!-- Add serial number here -->
                                                <span>{{ $index + 1 }}</span>
                                            </div>
                                            <div class="col-5">
                                                <select name="stocks[]"
                                                    class="form-select-stock form-select-lg col-12 form-select-solid" required>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}"
                                                            {{ $serviceStock->id == $stock->id ? 'selected' : '' }}>
                                                            {{ $stock->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" name="counts[]" min="1"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="@lang('dashboard.count')"
                                                    value="{{ $serviceStock->pivot->count }}" required>
                                            </div>
                                            <div class="col-2">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-stock-row">@lang('dashboard.delete')</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <!-- Button to Add More Stock Items -->
                            <div class="row mt-4">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-primary btn-sm" id="add-stock-item">
                                        <i class="fa fa-plus"></i> @lang('dashboard.add_stock_item')
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <h3>@lang('dashboard.report')</h3>
                            <div id="reports-section">
                                @if (isset($service))
                                    @foreach ($reports as $index => $report)
                                        <div class="row align-reports-center reports-item-row mb-2" data-order="{{ $report->order }}">
                                            <div class="col-1">
                                                @if ($latest = $report->latestImage)
                                                    <img src="{{ asset('storage/' . $latest->image) }}" alt="preview-image" class="preview-image" style="width:50px;height:50px;">
                                                @else
                                                    <img src="" alt="" class="preview-image"
                                                        style="width:50px;height:50px;display:none;">
                                                @endif
                                            </div>
                                            <div class="col-1">
                                                <!-- Add serial number here -->
                                                <span>{{ $index + 1 }}</span>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" name="reports[]"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="@lang('dashboard.name')" value="{{ $report->name }}"
                                                    required>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="reports_counts[]" min="1"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="@lang('dashboard.count')" value="{{ $report->count }}"
                                                    required>
                                            </div>
                                            <div class="col-2">
                                                <input type="file" name="reports_images[{{ $index }}][]" accept="image/*" class="form-control form-control-lg form-control-solid image-upload" multiple id="imageInput">

                                                <div class="progress mt-2 d-none" id="progressWrapper">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                                        style="width: 0%;" id="progressBar"
                                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                <button type="button" class="btn btn-sm btn-light-primary move-up"
                                                        {{ $index == 0 ? 'disabled' : '' }}>
                                                    <i class="fa fa-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary move-down"
                                                        {{ $index == count($reports) - 1 ? 'disabled' : '' }}>
                                                    <i class="fa fa-arrow-down"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-danger btn-sm remove-report-row">@lang('dashboard.delete')
                                                </button>
                                            </div>
                                            <input type="hidden" name="report_orders[]" value="{{ $report->order }}">
                                            <input type="hidden" name="report_ids[]" value="{{ $report->id }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Button to Add More Report Items -->
                            <div class="row mt-4">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-primary btn-sm" id="add-report-item">
                                        <i class="fa fa-plus"></i> @lang('dashboard.add_report_item')
                                    </button>
                                </div>
                            </div>



                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end mt-4">
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                    <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                    <span class="indicator-progress">@lang('dashboard.please_wait')
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Basic info-->
        </div>
        <!--end::Container-->
    </div>



@endsection
@section('scripts')
<script>
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
            bar.style.width    = '0%';
            bar.innerText      = '0%';
            bar.setAttribute('aria-valuenow', 0);

            let progress = 0;
            const interval = setInterval(() => {
            progress += 5;
            bar.style.width    = progress + '%';
            bar.innerText      = progress + '%';
            bar.setAttribute('aria-valuenow', progress);

            if (progress >= 100) {
                clearInterval(interval);
                setTimeout(() => {
                wrapper.classList.add('d-none');
                bar.style.width    = '0%';
                bar.innerText      = '0%';
                bar.setAttribute('aria-valuenow', 0);
                }, 1500);
            }
            }, 100);
        });
        });
    }

    $(document).ready(function() {

        attachProgressListeners();

        $('#add-stock-item').click(function() {
            var newRowCount = $('.stock-item-row').length + 1; // تحديث الترقيم
            var newRow = `
            <div class="row align-items-center stock-item-row mb-2" data-index="${newRowCount}">
                <div class="col-1">
                    <span class="row-number">${newRowCount}</span>
                </div>
                <div class="col-5">
                    <select name="stocks[]" class="form-select-stock col-12 form-select-lg form-select-solid" required>
                       @foreach ($stocks as $stock)
                            <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                       @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" name="counts[]" min="1" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.count')" required>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-sm remove-stock-row">@lang('dashboard.delete')</button>
                </div>
            </div>`;

            $('#stock-items-section').append(newRow);
            initializeSelect2();
            updateSelectOptions();
            updateRowNumbers(); // تحديث الأرقام بعد إضافة صف جديد
        });

        $(document).on('click', '.remove-stock-row', function() {
            $(this).closest('.stock-item-row').remove();
            updateRowNumbers(); // تحديث الأرقام بعد إزالة صف
        });

        function updateRowNumbers() {
            $('.stock-item-row').each(function(index) {
                $(this).find('.row-number').text(index + 1); // تحديث الرقم في كل صف
            });
        }

        // الكود المتبقي لم يتغير
        // ...

        $('#add-report-item').click(function() {
            attachProgressListeners();
            var newRowCount = $('.reports-item-row').length + 1; // تحديث الترقيم
            var newRow = `
            <div class="row align-reports-center reports-item-row mb-2" data-index="${newRowCount}">
                <div class="col-1">
                    <span class="row-number">${newRowCount}</span>
                </div>
                <div class="col-5">
                    <input type="text" name="reports[]" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.name')" required>
                </div>
                <div class="col-2 ">
                    <input type="number" name="reports_counts[]" min="1" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.count')" required>
                </div>
                <div class="col-2">
                    <input type="file" name="reports_images[${newRowCount}][]" accept="image/*" class="form-control form-control-lg form-control-solid image-upload">
                    <div class="progress mt-2 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-sm btn-light-primary move-up">
                        <i class="fa fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light-primary move-down">
                        <i class="fa fa-arrow-down"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm remove-report-row">@lang('dashboard.delete')</button>
                </div>
                <input type="hidden" name="report_orders[]" value="">
            </div>`;

            $('#reports-section').append(newRow);
            updateMoveButtons();
            updateOrder();
            updateRowNumbers(); // تحديث الأرقام بعد إضافة صف جديد
        });

        $(document).on('click', '.remove-report-row', function() {
            $(this).closest('.reports-item-row').remove();
            updateRowNumbers(); // تحديث الأرقام بعد إزالة صف
        });

        function updateRowNumbers() {
            $('.reports-item-row').each(function(index) {
                $(this).find('.row-number').text(index + 1); // تحديث الرقم في كل صف
            });
        }

        // الكود المتبقي لم يتغير
        // ...
    });
</script>
@endsection
@push('css')
    <style>
        .form-select-solid,
        .select2-container--bootstrap5.select2-container--open .form-select-solid {
            background-color: #eef3f7;
        }

        .progress {
            height: 10px;
        }

        .preview-image {
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
@endpush
