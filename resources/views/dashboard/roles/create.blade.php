@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.create_title', ['page_title' => __('dashboard.role')]))

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
                    @lang('dashboard.create_title', ['page_title' => __('dashboard.role')])</h1>
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
                        <a href="{{ route('roles.index') }}"
                            class="text-muted text-hover-primary">@lang('dashboard.roles')</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-dark">
                        @lang('dashboard.create', ['page_title' => __('dashboard.role')])</li>
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
                        <h3 class="fw-bolder m-0">@lang('dashboard.create', ['page_title' => __('dashboard.role')])</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->

                <!--begin::Content-->
                <div id="kt_content_container" class="container-xxl">
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_product_form" method="POST" action="{{ route('roles.store') }}"
                        class="form fv-plugins-bootstrap5 fv-plugins-framework store" novalidate="novalidate"
                        data-kt-redirect="{{ route('roles.index') }}">
                        @csrf
                        <!--begin::Card body-->
                        <div class="row mb-0">
                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.select_all_permissions')</label>
                            <!--begin::Label-->
                            <!--begin::Label-->
                            <div class="col-lg-8 d-flex align-items-center">
                                <div class="form-check form-check-solid form-switch fv-row">
                                    <input class="form-check-input w-45px h-30px" type="checkbox" id="checkedAll">
                                    <label class="form-check-label" for="checkedAll"></label>
                                </div>
                            </div>
                            <!--begin::Label-->
                        </div>

                        <div class="card-body border-top p-9">

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.name')</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                            <input type="text" name="nickname_ar"
                                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                placeholder="@lang('dashboard.nickname_ar')"
                                                value="{{ old('nickname_ar') }}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                            <input type="text" name="nickname_en" id="nickname_en"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="@lang('dashboard.nickname_en')"
                                                value="{{ old('nickname_en') }}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                                <input type="hidden" name="name" id="name" value="{{ old('name') }}">
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Basic info-->


                        <!--begin::Notifications-->
                        <div class="card mb-5 mb-xl-10">
                            <!--begin::Card header-->
                            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                                data-bs-target="#kt_account_notifications" aria-expanded="true"
                                aria-controls="kt_account_notifications">
                                <div class="card-title m-0">
                                    <h3 class="fw-bolder m-0">@lang('dashboard.permissions')</h3>
                                </div>
                            </div>
                            <!--begin::Card header-->

                            <!--begin::Card body-->
                            <div class="container-xxl">
                                <!--begin::Table-->
                                <div class="row">

                                    {!! $html !!}
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Notifications-->

                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <!--begin::Button-->
                            <a href="{{ route('roles.index') }}" id="kt_ecommerce_add_product_cancel"
                                class="btn btn-light me-5">@lang('dashboard.cancel')</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                <span class="indicator-progress">@lang('dashboard.please_wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->

            </div>
        </div>
        <!--end::Container-->
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('#nickname_en').on('keyup', function () {
        $('#name').val($.trim($(this).val()).replace(/[^a-z0-9-]/gi, '-').replace(/-+/g, '-').replace(/^-|-$/g,
            '').toLowerCase());
    });
</script>
<script>
    $(function () {

        $('.roles-parent').change(function (e) {
            var id = $(this).attr('id');
            if (!this.checked) {
                var count = 0
                $("." + id).each(function () {
                    if (this.checked) {
                        count = count + 1
                    }
                });

                if (count > 0) {
                    $('#' + id).prop('checked', true)
                } else {
                    $('#' + id).prop('checked', false)
                }
            }
        });
        $('.checkChilds').change(function () {
            var childClass = $(this).data('parent');
            if (this.checked) {
                $('.' + childClass).prop("checked", true);
                $('#' + childClass).prop("checked", true);
            } else {
                $('.' + childClass).prop("checked", false);
                $('#' + childClass).prop("checked", false);
            }
        });

        $('.childs').change(function () {
            var parent = $(this).data('parent');
            if (this.checked) {
                $('#' + parent).prop("checked", true);
                var count = 0
                $("." + parent).each(function () {
                    if (!this.checked) {
                        count = count + 1
                    }
                });
                if (count > 0) {
                    $('.checkChilds_' + parent).prop('checked', false)
                } else {
                    $('.checkChilds_' + parent).prop('checked', true)
                }
            } else {
                $('.checkChilds_' + parent).prop('checked', false)
            }
        });
    });

    $(function () {
        $("#checkedAll").change(function () {
            if (this.checked) {
                $("input[type=checkbox]").each(function () {
                    this.checked = true
                })
            } else {
                $("input[type=checkbox]").each(function () {
                    this.checked = false;
                })
            }
        });
    });
</script>

@endsection