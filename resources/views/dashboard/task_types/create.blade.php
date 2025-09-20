@extends('dashboard.layouts.app')
@section('pageTitle', isset($taskType) ? __('dashboard.edit_task_type') : __('dashboard.add_task_type'))

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                     data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                        {{ isset($taskType) ? __('dashboard.edit_task_type') : __('dashboard.add_task_type') }}
                    </h1>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <!--end::Separator-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('home') }}" class="text-muted text-hover-primary">@lang('dashboard.home')</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('task-types.index') }}" class="text-muted text-hover-primary">@lang('dashboard.task_types')</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-dark">
                            {{ isset($taskType) ? __('dashboard.edit_task_type') : __('dashboard.add_task_type') }}
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

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
                            <h3 class="fw-bolder m-0">{{ isset($taskType) ? __('dashboard.edit_task_type') : __('dashboard.add_task_type') }}</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Content-->
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <!--begin::Form-->
                        <form id="kt_task_type_form" method="POST"
                              action="{{ isset($taskType) ? route('task-types.update', $taskType->id) : route('task-types.store') }}"
                              class="form fv-plugins-bootstrap5 fv-plugins-framework"
                              data-kt-redirect="{{ route('task-types.index') }}"
                              novalidate="novalidate">
                            @csrf
                            @if(isset($taskType)) @method('PUT') @endif

                            <!--begin::Card body-->
                            <div class="card-body border-top p-9">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.task_type_name')</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <input type="text" name="name" required
                                               class="form-control form-control-lg form-control-solid"
                                               placeholder="@lang('dashboard.task_type_name')"
                                               value="{{ isset($taskType) ? $taskType->name : old('name') }}">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.task_type_description')</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <textarea name="description" rows="3"
                                              class="form-control form-control-lg form-control-solid"
                                              placeholder="@lang('dashboard.task_type_description')">{{
                                                preg_replace('/\R+/', "\n",
                                                    isset($taskType) ?
                                                    strip_tags($taskType->description) :
                                                    strip_tags(old('description'))
                                                )
                                             }}</textarea>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.task_type_status')</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <select name="status" required aria-label="Select Status"
                                                class="form-select form-select-solid form-select-lg fw-bold">
                                            <option value="active"
                                                    @if(isset($taskType) && $taskType->status == 'active') selected 
                                                    @elseif(!isset($taskType)) selected @endif
                                            >@lang('dashboard.active')</option>
                                            <option value="inactive"
                                                    @if(isset($taskType) && $taskType->status == 'inactive') selected @endif
                                            >@lang('dashboard.inactive')</option>
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->

                            <!--begin::Card footer-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <!--begin::Button-->
                                <a href="{{ route('task-types.index') }}" id="kt_task_type_cancel"
                                   class="btn btn-light me-5">@lang('dashboard.cancel')</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_task_type_submit" class="btn btn-primary">
                                    <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                    <span class="indicator-progress">@lang('dashboard.please_wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Card footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Basic info-->
            </div>
            <!--end::Container-->
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Form validation
        $('#kt_task_type_form').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                status: {
                    required: true
                }
            }
        });
    </script>
@endsection
