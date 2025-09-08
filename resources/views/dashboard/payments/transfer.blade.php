@extends('dashboard.layouts.app')

@section('pageTitle' , __('dashboard.transfer_between_accounts'))

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

                    <h3 class="fw-bolder m-0">{{__('dashboard.transfer')}}</h3>

                </div>

                <!--end::Card title-->

            </div>

            <!--begin::Card header-->

            <!--begin::Content-->

            <!-- <div id="kt_account_settings_profile_details" class="collapse show">

                <form id="kt_ecommerce_add_product_form" 

                      data-kt-redirect="{{ route('payments.transfer') }}" 

                      action="{{ route('money-transfer') }}" 

                      method="post" enctype="multipart/form-data" 

                      class="form d-flex flex-column flex-lg-row store"> -->
                <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_ecommerce_add_product_form" 
                      data-kt-redirect="{{ route('payments.transfer') }}" 
                      action="{{route('money-transfer') }}" 
                      method="post" enctype="multipart/form-data" 
                      class="form d-flex flex-column flex-lg-row store">
                      
                      @csrf 
                      
                      <div class="card-body border-top p-9">
                          
                          <!-- Input group for Total amount -->
                          
                          <div class="row mb-5">
                            <div class="row mb-5">
                                <div class="col-6">
                                    <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.from_account') }}</label>
                                    <div class="col-lg-12">
                                        <select name="sender_account_id" id="account_id" class="form-select form-select-lg form-select-solid mb-3 mb-lg-0" required>
                                            <option value="">{{ __('dashboard.choose_from_account') }}</option>
                                            @foreach($bankAccounts as $bankAccount)
                                                <option value="{{ $bankAccount->id }}" data-currency="{{ $bankAccount->currency }}"
                                                {{ isset($payment) && $payment->account_id == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.to_account') }}</label>
                                    <div class="col-lg-12">
                                    <select name="receiver_id" id="receiver_id" class="form-select form-select-lg form-select-solid mb-3 mb-lg-0" required>
                                            <option value="">{{ __('dashboard.choose_to_account') }}</option>
                                            @foreach($bankAccounts as $bankAccount)
                                                <option value="{{ $bankAccount->id }}" data-currency="{{ $bankAccount->currency }}"
                                                {{ isset($payment) && $payment->receiver_id == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

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




                        <input type="hidden" name="type" value="transfer">

                        <!-- Notes input -->

                        <div class="row mb-6">

                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.notes') }}</label>

                            <div class="col-lg-12">

                                <textarea name="description" class="form-control form-control-lg form-control-solid" placeholder="{{ __('dashboard.notes') }}">{{ isset($payment) ? $payment->description : old('description') }}</textarea>

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
        $(function(){
            var $receiver = $('#receiver_id');
            if ($receiver.length && $receiver.is('select')) {
                $receiver.select2({ width: '100%' });
            }

            var $account = $('#account_id');
            if ($account.length && $account.is('select')) {
                $account.select2({ width: '100%' });
            }
        });
    </script>

@endpush