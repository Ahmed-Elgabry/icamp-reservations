@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.add_bank_accounts'))
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
                    <h3 class="fw-bolder m-0">{{ isset($bank) ? $bank->notes : __('dashboard.bank-accounts')}}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_ecommerce_add_product_form" 
                      data-kt-redirect="{{  isset($bank) ? route('bank-accounts.edit',$bank->id) : route('bank-accounts.create') }}" 
                      action="{{ isset($bank) ? route('bank-accounts.update', $bank->id) : route('bank-accounts.store') }}" 
                      method="post" enctype="multipart/form-data" 
                      class="form d-flex flex-column flex-lg-row store">
                    @csrf 
                    @if(isset($bank)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
                        <!-- Input group for Total Price -->
                        <div class="row mb-5">
                            <div class="col-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.name') }}</label>
                                <div class="col-lg-12">
                                    <input type="text" name="name" id="name" value="{{ isset($bank) ? $bank->name : old('name') }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.balance') }}</label>
                                <div class="col-lg-12">
                                    <input step="0.01" type="number" name="balance" id="balance" {{ isset($bank) ? 'disabled' :'' }} value="{{ isset($bank) ? $bank->balance : old('balance') }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.account_number') }}</label>
                                <div class="col-lg-12">
                                    <input type="text" name="account_number" id="account_number" value="{{ isset($bank) ? $bank->account_number : old('account_number') }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0">
                                </div>
                            </div>
                        </div>

                        <!-- Notes input -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('dashboard.notes') }}</label>
                            <div class="col-lg-8">
                                <textarea name="notes" class="form-control form-control-lg form-control-solid" placeholder="{{ __('dashboard.notes') }}">{{ isset($bank) ? $bank->notes : old('notes') }}</textarea>
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
