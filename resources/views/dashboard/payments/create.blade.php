@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.payments'))
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
                data-kt-redirect="{{  isset($payment) ? route('payments.edit',$payment->id) : (isset($bankAccount) ? route('payments.create', $bankAccount->id) : route('payments.create')) }}"
                action="{{ isset($payment) ? route('accounts.update', $payment->id) : route('accounts.store') }}"
                method="post" enctype="multipart/form-data"
                class="form d-flex flex-column flex-lg-row store">
                @csrf 
                @if(isset($payment)) @method('PUT') @endif
                
                <div class="card-body border-top p-9">
                        @if(isset($bankAccount))
                            <input type="hidden" name="account_id" id="account_id" value="{{ $bankAccount->id }}">
                            <input type="hidden" name="source" value="account_charge">
                        @else
                            <div class="row mb-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.bank_account') }}</label>
                                <div class="col-lg-12">
                                    <select name="account_id" id="account_id" class="form-select form-select-lg form-select-solid" required>
                                        @if(isset($bankAccounts) && count($bankAccounts))
                                            @foreach($bankAccounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ (string)old('account_id', isset($payment) ? ($payment->account_id ?? null) : (isset($firstBankAccount) ? $firstBankAccount->id : null)) === (string)$account->id ? 'selected' : '' }}>
                                                    {{ $account->name  }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled selected>{{ __('dashboard.choose') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
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

        @if(isset($recentAccountCharges) && count($recentAccountCharges) > 0)
        <!--begin::Recent Account Charges Table-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <h3 class="fw-bolder m-0">{{ __('dashboard.recent_account_charges') }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_account_charges_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                            <th class="w-10px pe-2 text-center">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_account_charges_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="fw-bolder">{{ __('dashboard.amount') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.bank_account') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.verified') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.attached') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.description') }}</th>
                            <th class="">{{ __('dashboard.date') }}</th>
                            <th class="">{{ __('dashboard.time') }}</th>
                            <th class="text-end min-w-70px fw-bolder">@lang('dashboard.actions')</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($recentAccountCharges as $charge)
                            <!--begin::Table row-->
                            <tr data-id="{{$charge->id}}">
                                <!--begin::Checkbox-->
                                <td class="text-center">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$charge->id}}"/>
                                    </div>
                                </td>
                                <!--begin::Amount-->
                                <td class="text-center fw-bold">
                                    <div class="d-flex justify-content-center">
                                        <div class="">
                                            <a href="#" data-kt-ecommerce-category-filter="search" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">{{ number_format($charge->price ?? $charge->amount, 2) }}</a>
                                        </div>
                                    </div>
                                </td>
                                <!--begin::Bank Account-->
                                <td class="text-center">{{ $charge->account->name ?? '-' }}</td>
                                <!--begin::Verification-->
                                <td class="text-center">
                                    {{ $charge->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                    @if($charge->verified)
                                        <a href="{{ route('order.verified' , [$charge->id , 'general_revenue_deposit']) }}" class="btn btn-sm btn-danger">{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                    @else
                                        <a href="{{ route('order.verified' , [$charge->id , 'general_revenue_deposit']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                    @endif
                                </td>
                                <!--begin::Attached-->
                                <td class="text-center">
                                    @if($charge->image_path || $charge->image)
                                        @php
                                            $imagePath = $charge->image_path ?? $charge->image;
                                        @endphp
                                        <button type="button" class="btn btn-sm btn-primary" onclick="previewChargeImage('{{ asset('storage/' . $imagePath) }}', '{{ $charge->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">{{ __('dashboard.no_data') }}</span>
                                    @endif
                                </td>
                                <!--begin::Description-->
                                <td class="text-center" data-kt-ecommerce-category-filter="category_name">
                                    {{ $charge->description ?? $charge->notes ?? '-' }}
                                </td>
                                <!--begin::Date-->
                                    <td>{{ $charge->created_at->format('Y-m-d') }}</td>
                                    <!--begin::Time-->
                                    <td>{{ $charge->created_at->format('h:i A') }}</td>
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
                                        @if($charge->order_id)
                                        <div class="menu-item px-3">
                                            <a  class="menu-link px-3 charge-receipt-link" data-verified="{{ $charge->verified ? '1' : '0' }}" data-url="{{ route('payments.receipt', ['order' => $charge->order_id, 'payment' => $charge->id]) }}">
                                                {{ __('dashboard.receipt') }}
                                            </a>
                                        </div>
                                        @endif
                                        @can('payments.edit')
                                        <div class="menu-item px-3">
                                            <a  class="menu-link px-3" onclick="openEditChargeModal({{ $charge->id }}, '{{ $charge->amount }}', '{{ $charge->date }}', '{{ $charge->payment_method }}', '{{ $charge->description }}', '{{ $charge->account_id }}')">{{ __('actions.edit') }}</a>
                                        </div>
                                        @endcan
                                        @can('payments.destroy')
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" onclick="confirmDelete('{{route('general_payments.destroy', $charge->id)}}', '{{ csrf_token() }}')"> @lang('dashboard.delete')</a>
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
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Recent Account Charges Table-->
        @endif

    </div>
    <!--end::Container-->
</div>

<!-- Edit Account Charge Modal -->
<div class="modal fade" id="editChargeModal" tabindex="-1" aria-labelledby="editChargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChargeModalLabel">{{ __('actions.edit') }} {{ __('dashboard.account_charge') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editChargeForm" method="POST" class="store" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Bank Account Selection -->
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.bank_account') }}</label>
                        <div class="col-lg-12">
                            <select name="account_id" id="editChargeAccountId" class="form-select form-select-lg form-select-solid" required>
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
                                <input step="0.01" type="number" name="amount" id="editChargeAmount" class="form-control form-control-lg form-control-solid" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.date') }}</label>
                            <div class="col-lg-12">
                                <input type="date" name="date" id="editChargeDate" class="form-control form-control-lg form-control-solid" required>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.description') }}</label>
                        <div class="col-lg-12">
                            <textarea name="description" id="editChargeDescription" class="form-control form-control-lg form-control-solid" placeholder="{{ __('dashboard.description') }}"></textarea>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.upload_or_take_image') }}</label>
                        <div class="col-lg-12">
                            <input type="file" name="image" id="editChargeImage" class="form-control form-control-lg form-control-solid" accept="image/*" capture="environment">
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

<!-- Image Preview Modal for Charges -->
<div class="modal fade" id="chargeImagePreviewModal" tabindex="-1" aria-labelledby="chargeImagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chargeImagePreviewModalLabel">@lang('dashboard.attached')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewChargeImage" src="" alt="@lang('dashboard.attached')" class="img-fluid" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('dashboard.close')</button>
                <a id="downloadChargeImageBtn" href="" download class="btn btn-primary">
                    <i class="fas fa-download"></i> @lang('dashboard.save_changes')
                </a>
            </div>
        </div>
@endsection

@push('js')
    <script>
        // Initialize Select2 only when the element exists and is a <select>
        $(function () {
            var $receiver = $('#receiver_id');
            if ($receiver.length && $receiver.is('select')) {
                $receiver.select2({ width: '100%' });
            }

            var $account = $('#account_id');
            if ($account.length && $account.is('select')) {
                $account.select2({ width: '100%' });
            }
        });

        // Function to open edit charge modal
        function openEditChargeModal(id, amount, date, paymentMethod, description, accountId) {
            $('#editChargeAmount').val(amount);
            $('#editChargeDate').val(date);
            $('#editChargePaymentMethod').val(paymentMethod);
            $('#editChargeDescription').val(description);
            $('#editChargeAccountId').val(accountId);
            
            // Set the form action URL
            $('#editChargeForm').attr('action', '/dashboard/accounts/' + id);
            
            // Show the modal
            $('#editChargeModal').modal('show');
        }

        // Function to preview charge image
        function previewChargeImage(imageUrl, chargeId) {
            $('#previewChargeImage').attr('src', imageUrl);
            $('#downloadChargeImageBtn').attr('href', imageUrl);
            $('#chargeImagePreviewModal').modal('show');
        }

        // Handle charge receipt links
        $(document).on('click', '.charge-receipt-link', function(e) {
            e.preventDefault();

            if ($(this).data('verified') == '1') {
                window.open($(this).data('url'), '_blank');
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __("dashboard.error") }}',
                    text: '{{ __("dashboard.charge_not_verified_receipt_error") }}',
                    confirmButtonText: '{{ __("dashboard.ok") }}'
                });
            }
        });

        // Edit form will be handled by sending-forms.js since it has the 'store' class

        // Delete confirmation function for this page
        window.confirmDelete = function(deleteUrl, csrfToken) {
            // Check if Swal is available, if not use basic confirm
            if (typeof Swal === 'undefined') {
                if (confirm('{{ __("dashboard.delete_warning_message") ?? "Are you sure you want to delete this item? This action cannot be undone." }}')) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfTokenField = document.createElement('input');
                    csrfTokenField.type = 'hidden';
                    csrfTokenField.name = '_token';
                    csrfTokenField.value = csrfToken || '{{ csrf_token() }}';
                    form.appendChild(csrfTokenField);
                    
                    // Add DELETE method
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
                return;
            }
            
            Swal.fire({
                title: '{{ __("dashboard.confirm_delete") ?? "Confirm Delete" }}',
                text: '{{ __("dashboard.delete_warning_message") ?? "Are you sure you want to delete this item? This action cannot be undone." }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __("dashboard.yes_delete") ?? "Yes, Delete" }}',
                cancelButtonText: '{{ __("dashboard.cancel") ?? "Cancel" }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfTokenField = document.createElement('input');
                    csrfTokenField.type = 'hidden';
                    csrfTokenField.name = '_token';
                    csrfTokenField.value = csrfToken || '{{ csrf_token() }}';
                    form.appendChild(csrfTokenField);
                    
                    // Add DELETE method
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };
    </script>
@endpush