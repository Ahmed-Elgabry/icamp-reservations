@extends('dashboard.layouts.app')
@section('pageTitle' , "customers")
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
                    <h3 class="fw-bolder m-0">{{ isset($customer) ? $customer->name : __('dashboard.create_title', ['page_title' => __('dashboard.customers')]) }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_ecommerce_add_product_form" 
                    data-kt-redirect="{{  isset($customer) ? route('customers.edit',$customer->id) : route('customers.create') }}" 
                action="{{ isset($customer) ?  route('customers.update',$customer->id) : route('customers.store')}}"
                          method="post" enctype="multipart/form-data" 
                        class="form d-flex flex-column flex-lg-row store" >
                    <!--begin::Card body-->
                    @csrf 
                    @if(isset($customer)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.full_name')</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text" name="name"  required class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="@lang('dashboard.full_name')" value="{{ isset($customer) ? $customer->name : old('name') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        

                         <!--begin::Input group-->
                         <div class="row mb-6 ">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6 required">
                                <span class="">@lang('dashboard.email')</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style=""  type="email" required name="email" class=" form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.email')" value="{{ isset($customer) ? $customer->email : old('email') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="row mb-6 ">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6 required">
                                <span class="">@lang('dashboard.phone')</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style=""  type="tel" required name="phone"  class=" form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.phone')" value="{{ isset($customer) ? $customer->phone : old('phone') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="row mb-6 ">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6 ">
                                <span class="">@lang('dashboard.notes')</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <textarea  name="notes" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.notes')">{{ isset($customer) ? $customer->notes : old('notes') }}</textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                <span class="indicator-progress">@lang('dashboard.please_wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                    <!--end::Actions-->
                    </div>
                    <!--end::Card body-->
                   
            
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
        $('#account_type').on('change', function (){
            account_type_location();
        });

        function account_type_location()
        {
            if($('#account_type').val() != 'security_director')
            {
                $('#locations').removeAttr('multiple');
            }else{
                $('#locations').attr('multiple','multiple');
                console.log(2);
            }
        }account_type_location();
    </script>
@endsection
