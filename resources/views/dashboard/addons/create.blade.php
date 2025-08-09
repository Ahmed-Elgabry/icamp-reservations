@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.addons'))
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
                    <h3 class="fw-bolder m-0">{{ isset($addon) ? $addon->name : __('dashboard.create_title', ['page_title' => __('dashboard.addons')]) }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_ecommerce_add_product_form" 
                    data-kt-redirect="{{  isset($addon) ? route('addons.edit',$addon->id) : route('addons.create') }}" 
                action="{{ isset($addon) ?  route('addons.update',$addon->id) : route('addons.store')}}"
                          method="post" enctype="multipart/form-data" 
                        class="form d-flex flex-column flex-lg-row store" >
                    <!--begin::Card body-->
                    @csrf 
                    @if(isset($addon)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
 

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.name')</label>
                            <div class="col-lg-8">
                                <input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" 
                                    placeholder="@lang('dashboard.name')" 
                                    value="{{ isset($addon) ? $addon->name : old('name') }}">
                            </div>
                        </div>

                        

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.price')</label>
                            <div class="col-lg-8">
                                <input type="number" step="0.01" name="price"  required class="form-control form-control-lg form-control-solid" 
                                    placeholder="@lang('dashboard.price')" 
                                    value="{{ isset($addon) ? $addon->price : old('price') }}">
                            </div>
                        </div> 

                    
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                            <div class="col-lg-8">
                                <textarea name="description" class="form-control form-control-lg form-control-solid" 
                                        placeholder="@lang('dashboard.notes')">{{ isset($addon) ? $addon->description : old('description') }}</textarea>
                            </div>
                        </div>


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
