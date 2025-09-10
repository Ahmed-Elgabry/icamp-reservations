@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.create_bank_account'))
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
                    <h3 class="fw-bolder m-0">{{__('dashboard.create_bank_account')}}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_ecommerce_add_product_form"
                data-kt-redirect="{{ route('payments.create-bank-account') }}"
                action="{{ route('payments.store-bank-account') }}"
                method="post" enctype="multipart/form-data"
                class="form d-flex flex-column flex-lg-row store">
                @csrf 
                
                <div class="card-body border-top p-9">
                    
                    <!-- Account Name -->
                    <div class="row mb-6">
                        <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.account_name') }}</label>
                        <div class="col-lg-12">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" 
                                placeholder="{{ __('dashboard.enter_account_name') }}" required>
                            @error('name')
                                <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Account Number -->
                    <div class="row mb-6">
                        <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.account_number') }}</label>
                        <div class="col-lg-12">
                            <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" 
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" 
                                placeholder="{{ __('dashboard.enter_account_number') }}" required>
                            @error('account_number')
                                <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Bank Name and Branch -->
                    <div class="row mb-5">
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.bank_name') }}</label>
                            <div class="col-lg-12">
                                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" 
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" 
                                    placeholder="{{ __('dashboard.enter_bank_name') }}">
                                @error('bank_name')
                                    <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.branch') }}</label>
                            <div class="col-lg-12">
                                <input type="text" name="branch" id="branch" value="{{ old('branch') }}" 
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" 
                                    placeholder="{{ __('dashboard.enter_branch') }}">
                                @error('branch')
                                    <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Balance and Account Type -->
                    <div class="row mb-5">
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.initial_balance') }}</label>
                            <div class="col-lg-12">
                                <input step="0.01" type="number" name="balance" id="balance" value="{{ old('balance', '0.00') }}" 
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" 
                                    placeholder="0.00" required>
                                @error('balance')
                                    <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.account_type') }}</label>
                            <div class="col-lg-12">
                                <select name="account_type" id="account_type" class="form-select form-select-lg form-select-solid">
                                    <option value="">{{ __('dashboard.choose_account_type') }}</option>
                                    <option value="savings" {{ old('account_type') == 'savings' ? 'selected' : '' }}>{{ __('dashboard.savings') }}</option>
                                    <option value="checking" {{ old('account_type') == 'checking' ? 'selected' : '' }}>{{ __('dashboard.checking') }}</option>
                                    <option value="business" {{ old('account_type') == 'business' ? 'selected' : '' }}>{{ __('dashboard.business') }}</option>
                                    <option value="other" {{ old('account_type') == 'other' ? 'selected' : '' }}>{{ __('dashboard.other') }}</option>
                                </select>
                                @error('account_type')
                                    <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes input -->
                    <div class="row mb-6">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.notes') }}</label>
                        <div class="col-lg-12">
                            <textarea name="notes" class="form-control form-control-lg form-control-solid" 
                                placeholder="{{ __('dashboard.enter_notes') }}">{{ old('notes') }}</textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="row mb-6">
                        <div class="col-lg-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold fs-6" for="is_active">
                                    {{ __('dashboard.active_account') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('payments.create') }}" class="btn btn-light me-3">{{ __('dashboard.cancel') }}</a>
                        <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                            <span class="indicator-label">{{ __('dashboard.create_account') }}</span>
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
</div>
<!--end::Container-->
</div>
@endsection

@push('js')
<script>
    // Initialize Select2 only when the element exists and is a <select>
    $(function () {
        var $accountType = $('#account_type');
        if ($accountType.length && $accountType.is('select')) {
            $accountType.select2({ width: '100%' });
        }
    });
</script>
@endpush
