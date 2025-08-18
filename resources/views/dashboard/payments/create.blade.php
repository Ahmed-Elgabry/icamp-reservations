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
                    <h3 class="fw-bolder m-0">{{ isset($payment) ? $payment->notes : __('dashboard.payments')}}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_ecommerce_add_product_form" 
                      data-kt-redirect="{{  isset($payment) ? route('payments.edit',$payment->id) : route('payments.create') }}" 
                      action="{{ isset($payment) ? route('accounts.update', $payment->id) : route('accounts.store') }}" 
                      method="post" enctype="multipart/form-data" 
                      class="form d-flex flex-column flex-lg-row store">
                    @csrf 
                    @if(isset($payment)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
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

                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.bank_account') }}</label>
                                <div class="col-lg-12">
                                    <select name="account_id" id="account_id" class="form-select form-select-lg form-select-solid mb-3 mb-lg-0">
                                        <option value="">{{ __('dashboard.choose_bank_account') }}</option>
                                        @foreach($bankAccounts as $bankAccount)
                                            <option value="{{ $bankAccount->id }}" data-currency="{{ $bankAccount->currency }}"
                                            {{ isset($payment) && $payment->account_id == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name }}</option>
                                        @endforeach
                                    </select>
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
    </div>
    <!--end::Container-->
</div>
@endsection

@push('js')
    <script>
        $('#receiver_id').select2();
        $('#account_id').select2();
    </script>
@endpush
