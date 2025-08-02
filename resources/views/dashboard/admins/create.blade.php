@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.admins'))
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
                    <h3 class="fw-bolder m-0">@lang('dashboard.admin')</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_ecommerce_add_product_form" 
                    data-kt-redirect="{{  isset($user) ? route('admins.edit',$user->id) : route('admins.create') }}" 
                action="{{ isset($user) ?  route('admins.update',$user->id) : route('admins.store')}}"
                          method="post" enctype="multipart/form-data" 
                        class="form d-flex flex-column flex-lg-row store" >
                    <!--begin::Card body-->
                    @csrf 
                    @if(isset($user)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.image')</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Image input-->
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: e url('assets/media/svg/avatars/blank.svg')">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: @if(isset($user)) url({{$user->image}}) @else url(assets/media/avatars/300-1.jpg) @endif"></div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="image" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="avatar_remove">
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Remove-->
                                </div>
                                <!--end::Image input-->
                                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.name')</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text" name="name"  required class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="@lang('dashboard.name')" value="{{ isset($user) ? $user->name : old('name') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        

                         <!--begin::Input group-->
                         <div class="row mb-6 text-right">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="required">@lang('dashboard.email')</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style="text-align:right" required type="email" name="email" class="text-right form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.email')" value="{{ isset($user) ? $user->email : old('email') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="row mb-6 text-right">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="">@lang('dashboard.phone')</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style="text-align:right"  type="tel" name="phone"  class="text-right form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.phone')" value="{{ isset($user) ? $user->phone : old('phone') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="row mb-6 text-right">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="@if(!isset($user)) required @endif">@lang('dashboard.password')</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style="text-align:right" @if(!isset($user)) required @endif type="password" name="password" class="text-right form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.password')">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        
                     <!--begin::Input group-->
                     <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="required">@lang('dashboard.role')</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <select name="role_id" required aria-label="Select a Country" data-control="select2" data-placeholder="@lang('dashboard.select_role')" 
                                    class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-05ls" tabindex="-1" aria-hidden="true">
                                        <option value="" data-select2-id="select2-data-12-wa4o">@lang('dashboard.select_role')</option>
                                        @foreach ($roles as $role)
                                        <option value="{{$role->id}}" 
                                            @if(isset($user)) 
                                                {{$user->roleName()  == $role->nickname_ar ? "selected" : ""}}
                                            @else 
                                                {{old('role_id')  == $role->id ? "selected" : ""}}
                                            @endif 
                                            >{{lang() == "ar" ? $role->nickname_ar : $role->nickname_en }}</option>
                                        @endforeach
                                    </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                     <!--begin::Input group-->
                     <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="required">@lang('dashboard.lang')</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <select name="lang" required aria-label="Select a Country" data-control="select2" data-placeholder="@lang('dashboard.lang')" 
                                    class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-05ls" tabindex="-1" aria-hidden="true">
                                        <option value="" data-select2-id="select2-data-12-wa4o">@lang('dashboard.lang')</option>
                                        @foreach (languages() as $lang)
                                        <option value="{{$lang}}" 
                                            @if(isset($user)) 
                                                {{$user->lang  == $lang ? "selected" : ""}}
                                            @else 
                                                {{old('lang')  == $lang ? "selected" : ""}}
                                            @endif 
                                            >{{ $lang }}</option>
                                        @endforeach
                                    </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                       
                        <!--begin::Input group-->
                        <div class="row mb-0 mt-5">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.active')</label>
                            <!--begin::Label-->
                            <!--begin::Label-->
                            <div class="col-lg-8 d-flex align-items-center">
                                <div class="form-check form-check-solid form-switch fv-row">
                                    <input class="form-check-input w-45px h-30px" type="checkbox" 
                                        @if(isset($user))
                                            {{$user->is_active == '1' ? 'selected' : ''}}
                                        @else 
                                             {{old('is_active') == '1' ? 'selected' : ''}}
                                        @endif
                                    id="is_active" name="is_active" checked="checked">
                                    <label class="form-check-label" for="is_active"></label>
                                </div>
                            </div>
                            <!--begin::Label-->
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
