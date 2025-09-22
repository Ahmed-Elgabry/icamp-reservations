@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.add_revenue'))
@section('content')
<!-------------->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{__('dashboard.add_funds')}}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_ecommerce_add_product_form"
                    data-kt-redirect="{{  isset($payment) ? route('payments.edit',$payment->id) : (isset($bankAccount) ? route('payments.create', $bankAccount->id) : route('general_payments.create_add_funds')) }}"
                    action="{{ isset($payment) ? route('general_payments.update_add_funds', $payment->id) : route('general_payments.store_add_funds') }}"
                    method="post" enctype="multipart/form-data"
                    class="form d-flex flex-column flex-lg-row store">
                    @csrf
                    @if(isset($payment)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
                        @if(isset($bankAccount))
                        <!-- Scenario 1: Direct account ID passed -->
                        <input type="hidden" name="account_id" id="account_id" value="{{ $bankAccount->id }}">
                        <input type="hidden" name="source" value="charge_account">

                        <!-- Show selected account info -->
                        <div class="row mb-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.bank_account') }}</label>
                            <div class="col-lg-12">
                                <div class="form-control form-control-lg form-control-solid bg-light">
                                    {{ $bankAccount->name }}
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Scenario 2: Show bank account selection list -->
                        <div class="row mb-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.bank_account') }}</label>
                            <div class="col-lg-12">
                                <select name="account_id" id="account_id" class="form-select form-select-lg form-select-solid" required>
                                    @if(isset($bankAccounts) && count($bankAccounts))
                                    <option value="" disabled selected>{{ __('dashboard.choose') }}</option>
                                    @foreach($bankAccounts as $account)
                                    <option value="{{ $account->id }}"
                                        {{ (string)old('account_id', isset($payment) ? ($payment->account_id ?? null) : null) === (string)$account->id ? 'selected' : '' }}>
                                        {{ $account->name  }}
                                    </option>
                                    @endforeach
                                    @else
                                    <option value="" disabled selected>{{ __('dashboard.choose') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Image upload field for list scenario -->
                        <div class="form-group row my-2 mb-6 mx-1 col-12">
                            <label for="image">@lang('dashboard.upload_or_take_image')</label>
                            <input type="file" name="image" id="image"
                                class="form-control"
                                accept="image/*"
                                capture="environment">
                            <div id="imageInfo" class="mt-2 text-muted" style="display: none;">
                                <small id="imageDetails"></small>
                            </div>
                        </div>

                        <!-- Source for add funds page -->
                        <input type="hidden" name="source" value="add_funds_page">
                        @endif
                        <!-- Input group for Total amount -->
                        <div class="row mb-5">
                            <div class="col-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.amount') }}</label>
                                <div class="col-lg-12">
                                    <input step="0.01" type="number" name="amount" id="amount" value="{{ isset($payment) ? $payment->amount : '' }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.date') }}</label>
                                <div class="col-lg-12">
                                    <input type="date" name="date" id="date" value="{{ isset($payment) ? $payment->date : date('Y-m-d') }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="row mb-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.payment_method') }}</label>
                            <div class="col-lg-12">
                                <select name="payment_method" class="form-select form-select-lg form-select-solid">
                                    @foreach(paymentMethod() as $paymentSelect)
                                    <option value="{{ $paymentSelect }}"
                                        {{ old('payment_method', 'bank_transfer') == $paymentSelect ? 'selected' : '' }}>
                                        {{ __('dashboard.'. $paymentSelect) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Notes input -->
                        <div class="row mb-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.description') }}</label>
                            <div class="col-lg-12">
                                <textarea name="description" class="form-control form-control-lg form-control-solid" placeholder="{{ __('dashboard.description') }}">{{ isset($payment) ? $payment->description : old('description') }}</textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>

                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">{{ __('dashboard.save_changes') }}</span>
                                <span class="indicator-progress">{{ __('dashboard.please_wait') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Actions-->
                    </div>
                </form>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->

        @if(!isset($bankAccount) && isset($bankAccounts) && count($bankAccounts))
        <!--begin::Payments Table-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <h3 class="fw-bolder m-0">{{ __('dashboard.recent_payments') }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th>{{ __('dashboard.amount') }}</th>
                            <th class="">{{ __('dashboard.payment_method') }}</th>
                            <th class="">{{ __('dashboard.bank_account') }}</th>
                            <th class="">{{ __('dashboard.verified') }}</th>
                            <th class="">{{ __('dashboard.attached') }}</th>
                            <th class="">{{ __('dashboard.description') }}</th>
                            <th class="">{{ __('dashboard.date') }}</th>
                            <th class="">{{ __('dashboard.time') }}</th>
                            <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @if(isset($recentPayments))
                        @foreach ($recentPayments as $payment)
                        <!--begin::Table row-->
                        <tr data-id="{{$payment->id}}">
                            <!--begin::Checkbox-->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$payment->id}}" />
                                </div>
                            </td>
                            <!--begin::Amount-->
                            <td>
                                <div class="d-flex">
                                    <div class="ms-5">
                                        <a href="#" data-kt-ecommerce-category-filter="search" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">{{ number_format($payment->price ?? $payment->amount, 2) }}</a>
                                    </div>
                                </div>
                            </td>
                            <!--begin::Payment Method-->
                            <td>{{ $payment->payment_method ? __('dashboard.'. $payment->payment_method) : __('dashboard.not_specified') }}</td>
                            <!--begin::Bank Account-->
                            <td>{{ $payment->account->name ?? '-' }}</td>
                            <!--begin::Verification-->
                            <td>
                                {{ $payment->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                @can('general_payments.approve')
                                @if($payment->verified)
                                <a href="{{ route('order.verified' , [$payment->id , 'general_revenue_deposit']) }}" class="btn btn-sm btn-danger">{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                @else
                                <a href="{{ route('order.verified' , [$payment->id , 'general_revenue_deposit']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                @endif
                                @endcan
                            </td>
                            <!--begin::Attached-->
                            <td>
                                @if($payment->image_path || $payment->image)
                                @php
                                $imagePath = $payment->image_path ?? $payment->image;
                                @endphp
                                <button type="button" class="btn btn-sm btn-primary" onclick="previewImage('{{ asset('storage/' . $imagePath) }}', '{{ $payment->id }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @else
                                <span class="text-muted">{{ __('dashboard.no_data') }}</span>
                                @endif
                            </td>
                            <!--begin::Description-->
                            <td data-kt-ecommerce-category-filter="category_name">
                                {{ $payment->description ?? $payment->notes ?? '-' }}
                            </td>
                            <!--begin::Date-->
                            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                            <!--begin::Time-->
                            <td>{{ $payment->created_at->format('h:i A') }}</td>
                            <!--begin::Actions-->
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    {{ __('dashboard.actions') }}
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon--></a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    @if($payment->order_id)
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3 payment-receipt-link" data-verified="{{ $payment->verified ? '1' : '0' }}" data-url="{{ route('payments.receipt', ['order' => $payment->order_id, 'payment' => $payment->id]) }}">
                                            {{ __('dashboard.receipt') }}
                                        </a>
                                    </div>
                                    @endif
                                    @can('general_payments.update_add_funds')
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" onclick="openEditModal({{ $payment->id }}, '{{ $payment->amount }}', '{{ $payment->date }}', '{{ $payment->payment_method }}', '{{ $payment->description }}', '{{ $payment->account_id }}')">{{ __('actions.edit') }}</a>
                                    </div>
                                    @endcan
                                    @can('general_payments.destroy_add_funds')
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-url="{{route('general_payments.destroy_add_funds', $payment->id)}}" data-id="{{$payment->id}}"> @lang('dashboard.delete')</a>
                                    </div>
                                    @endcan
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                            <!--end::Actions-->
                        </tr>
                        <!--end::Table row-->
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10" class="text-center text-muted">{{ __('dashboard.no_data_found') }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Payments Table-->
        @endif

    </div>
    <!--end::Container-->
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel">{{ __('actions.edit') }} {{ __('dashboard.payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPaymentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Bank Account Selection -->
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.bank_account') }}</label>
                        <div class="col-lg-12">
                            <select name="account_id" id="editAccountId" class="form-select form-select-lg form-select-solid" required>
                                <option value="" disabled>{{ __('dashboard.choose') }}</option>
                                @if(isset($bankAccounts) && count($bankAccounts))
                                @foreach($bankAccounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->name }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Amount and Date Row -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.amount') }}</label>
                            <div class="col-lg-12">
                                <input step="0.01" type="number" name="amount" id="editAmount" class="form-control form-control-lg form-control-solid" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.date') }}</label>
                            <div class="col-lg-12">
                                <input type="date" name="date" id="editDate" class="form-control form-control-lg form-control-solid" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.payment_method') }}</label>
                        <div class="col-lg-12">
                            <select name="payment_method" id="editPaymentMethod" class="form-select form-select-lg form-select-solid">
                                @foreach(paymentMethod() as $paymentSelect)
                                <option value="{{ $paymentSelect }}">
                                    {{ __('dashboard.'. $paymentSelect) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.description') }}</label>
                        <div class="col-lg-12">
                            <textarea name="description" id="editDescription" class="form-control form-control-lg form-control-solid" placeholder="{{ __('dashboard.description') }}"></textarea>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.upload_or_take_image') }}</label>
                        <div class="col-lg-12">
                            <input type="file" name="image" id="editImage" class="form-control form-control-lg form-control-solid" accept="image/*" capture="environment">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">{{ __('dashboard.save_changes') }}</span>
                        <span class="indicator-progress d-none">{{ __('dashboard.please_wait') }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">@lang('dashboard.attached')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="@lang('dashboard.attached')" class="img-fluid" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('dashboard.close')</button>
                <a id="downloadImageBtn" href="" download class="btn btn-primary">
                    <i class="fas fa-download"></i> @lang('dashboard.save_changes')
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    // Initialize Select2 only when the element exists and is a <select>
    $(function() {
        var $receiver = $('#receiver_id');
        if ($receiver.length && $receiver.is('select')) {
            $receiver.select2({
                width: '100%'
            });
        }

        var $account = $('#account_id');
        if ($account.length && $account.is('select')) {
            $account.select2({
                width: '100%'
            });
        }

        // Initialize Select2 for edit modal account dropdown
        var $editAccount = $('#editAccountId');
        if ($editAccount.length && $editAccount.is('select')) {
            $editAccount.select2({
                width: '100%',
                dropdownParent: $('#editPaymentModal')
            });
        }

        // Initialize DataTable with error handling
        try {
            var $table = $('#kt_ecommerce_category_table');
            if ($table.length && $table.find('tbody tr').length > 0) {
                // Only initialize DataTable if table exists and has data
                if ($.fn.DataTable && !$table.hasClass('dataTable')) {
                    $table.DataTable({
                        responsive: true,
                        columnDefs: [{
                                targets: 0, // Checkbox column
                                orderable: false,
                                searchable: false
                            },
                            {
                                targets: -1, // Actions column
                                orderable: false,
                                searchable: false
                            }
                        ],
                        order: [
                            [7, 'desc']
                        ], // Sort by created_at column (index 7)
                        pageLength: 10,
                        language: {
                            search: "{{ __('dashboard.search') }}",
                            lengthMenu: "{{ __('dashboard.show') }} _MENU_ {{ __('dashboard.entries') }}",
                            info: "{{ __('dashboard.showing') }} _START_ {{ __('dashboard.to') }} _END_ {{ __('dashboard.of') }} _TOTAL_ {{ __('dashboard.entries') }}",
                            paginate: {
                                first: "{{ __('dashboard.first') }}",
                                last: "{{ __('dashboard.last') }}",
                                next: "{{ __('dashboard.next') }}",
                                previous: "{{ __('dashboard.previous') }}"
                            }
                        }
                    });
                }
            }
        } catch (error) {
            console.warn('DataTable initialization failed:', error);
        }

        // Camera capture handlers (jQuery)
        var $openBtn = $('#openCameraBtn');
        var $input = $('#cameraInput');
        var $preview = $('#photoPreview');
        var $removeBtn = $('#removePhotoBtn');
        var objectUrl = null;

        if ($openBtn.length && $input.length) {
            $openBtn.on('click', function() {
                $input.trigger('click');
            });
        }
        if ($input.length) {
            $input.on('change', function() {
                var file = this.files && this.files[0];
                if (!file) return;
                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                    objectUrl = null;
                }
                objectUrl = URL.createObjectURL(file);
                $preview.attr('src', objectUrl).removeClass('d-none');
                if ($removeBtn.length) {
                    $removeBtn.removeClass('d-none');
                }
            });
        }
        if ($removeBtn.length) {
            $removeBtn.on('click', function() {
                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                    objectUrl = null;
                }
                $input.val('');
                $preview.attr('src', '').addClass('d-none');
                $removeBtn.addClass('d-none');
            });
        }
    });

    // Image preview function
    function previewImage(imageSrc, paymentId) {
        document.getElementById('previewImage').src = imageSrc;
        document.getElementById('downloadImageBtn').href = imageSrc;

        // Show the modal
        var modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        modal.show();
    }

    // Edit payment modal function
    function openEditModal(paymentId, amount, date, paymentMethod, description, accountId) {
        // Set form action URL
        const form = document.getElementById('editPaymentForm');
        form.action = "{{ route('general_payments.update_add_funds', ':id') }}".replace(':id', paymentId);

        // Fill form fields
        document.getElementById('editAmount').value = amount;
        document.getElementById('editDate').value = date;
        document.getElementById('editPaymentMethod').value = paymentMethod;
        document.getElementById('editDescription').value = description;
        document.getElementById('editAccountId').value = accountId;

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
        modal.show();
    }

    // Handle edit form submission
    $(document).on('submit', '#editPaymentForm', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const submitLabel = submitBtn.find('.indicator-label');
        const submitProgress = submitBtn.find('.indicator-progress');

        // Show loading state
        submitLabel.addClass('d-none');
        submitProgress.removeClass('d-none');
        submitBtn.prop('disabled', true);

        // Create FormData to handle file uploads
        const formData = new FormData(form[0]);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Hide modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editPaymentModal'));
                modal.hide();

                // Show success message
                Swal.fire({
                    title: '{{ __("dashboard.success") }}',
                    text: '{{ __("dashboard.payment_updated_successfully") }}',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reload page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            },
            error: function(xhr, status, error) {
                let errorMessage = '{{ __("dashboard.error_occurred") }}';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: '{{ __("dashboard.error") }}',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: '{{ __("dashboard.ok") }}'
                });
            },
            complete: function() {
                // Reset button state
                submitLabel.removeClass('d-none');
                submitProgress.addClass('d-none');
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Image file selection feedback
    $(document).ready(function() {
        $('#image').on('change', function(e) {
            const file = e.target.files[0];
            const imageInfo = $('#imageInfo');
            const imageDetails = $('#imageDetails');

            if (file) {
                console.log('Image selected:', {
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    lastModified: file.lastModified
                });

                // Show file information to user
                imageDetails.html(`
                        <strong>File selected:</strong> ${file.name}<br>
                        <strong>Size:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB<br>
                        <strong>Type:</strong> ${file.type}
                    `);
                imageInfo.show();

                // Validate file
                if (file.size > 2048 * 1024) { // 2MB limit
                    imageDetails.append('<br><span class="text-danger">⚠️ File size exceeds 2MB limit</span>');
                }

                if (!file.type.startsWith('image/')) {
                    imageDetails.append('<br><span class="text-danger">⚠️ File must be an image</span>');
                }
            } else {
                imageInfo.hide();
            }
        });

        // Image file selection feedback for edit modal
        $('#editImage').on('change', function(e) {
            const file = e.target.files[0];
            const imageInfo = $('#editImageInfo');
            const imageDetails = $('#editImageDetails');

            if (file) {
                console.log('Edit modal image selected:', {
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    lastModified: file.lastModified
                });

                // Show file information to user
                imageDetails.html(`
                        <strong>File selected:</strong> ${file.name}<br>
                        <strong>Size:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB<br>
                        <strong>Type:</strong> ${file.type}
                    `);
                imageInfo.show();

                // Validate file
                if (file.size > 2048 * 1024) { // 2MB limit
                    imageDetails.append('<br><span class="text-danger">⚠️ File size exceeds 2MB limit</span>');
                }

                if (!file.type.startsWith('image/')) {
                    imageDetails.append('<br><span class="text-danger">⚠️ File must be an image</span>');
                }
            } else {
                imageInfo.hide();
            }
        });
    });

    // Delete functionality
    $(document).on('click', '[data-kt-ecommerce-category-filter="delete_row"]', function(e) {
        e.preventDefault();

        const deleteUrl = $(this).data('url');
        const itemId = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: '{{ __("dashboard.are_you_sure") }}',
            text: '{{ __("dashboard.you_wont_be_able_to_revert_this") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("dashboard.yes_delete_it") }}',
            cancelButtonText: '{{ __("dashboard.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: '{{ __("dashboard.deleting") }}',
                    text: '{{ __("dashboard.please_wait") }}',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: '{{ __("dashboard.deleted") }}',
                            text: '{{ __("dashboard.item_has_been_deleted") }}',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Remove the row from table
                        row.fadeOut(500, function() {
                            $(this).remove();

                            // Check if table is empty
                            const table = $('#kt_ecommerce_category_table');
                            if (table.find('tbody tr').length === 0) {
                                table.find('tbody').html('<tr><td colspan="10" class="text-center text-muted">{{ __("dashboard.no_data_found") }}</td></tr>');
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = '{{ __("dashboard.error_occurred") }}';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: '{{ __("dashboard.error") }}',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: '{{ __("dashboard.ok") }}'
                        });
                    }
                });
            }
        });
    });
</script>
@endpush