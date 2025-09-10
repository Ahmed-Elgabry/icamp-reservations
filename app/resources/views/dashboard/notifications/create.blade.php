@extends('dashboard.layouts.app')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card card-flush">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15"
                        role="tablist">
                        <!--begin:::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-5 active" data-bs-toggle="tab"
                                href="#kt_ecommerce_settings_general" aria-selected="true" role="tab">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen001.svg-->
                                <span class="svg-icon svg-icon-2 me-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 2.375L2 9.575V20.575C2 21.175 2.4 21.575 3 21.575H9C9.6 21.575 10 21.175 10 20.575V14.575C10 13.975 10.4 13.575 11 13.575H13C13.6 13.575 14 13.975 14 14.575V20.575C14 21.175 14.4 21.575 15 21.575H21C21.6 21.575 22 21.175 22 20.575V9.575L13 2.375C12.4 1.875 11.6 1.875 11 2.375Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->@lang('dashboard.notification')
                            </a>
                        </li>
                        <!--end:::Tab item-->

                        <!--begin:::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-5" data-bs-toggle="tab"
                                href="#kt_ecommerce_settings_localization" aria-selected="false" role="tab"
                                tabindex="-1">
                                <!--begin::Svg Icon | path: icons/duotune/maps/map004.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3"
                                        d="M20 3H4C2.89543 3 2 3.89543 2 5V16C2 17.1046 2.89543 18 4 18H4.5C5.05228 18 5.5 18.4477 5.5 19V21.5052C5.5 22.1441 6.21212 22.5253 6.74376 22.1708L11.4885 19.0077C12.4741 18.3506 13.6321 18 14.8167 18H20C21.1046 18 22 17.1046 22 16V5C22 3.89543 21.1046 3 20 3Z"
                                        fill="currentColor"></path>
                                    <rect x="6" y="12" width="7" height="2" rx="1"
                                        fill="currentColor"></rect>
                                    <rect x="6" y="7" width="12" height="2" rx="1"
                                        fill="currentColor"></rect>
                                </svg>
                                <!--end::Svg Icon-->@lang('dashboard.sms')

                            </a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-5" data-bs-toggle="tab"
                                href="#kt_ecommerce_settings_products" aria-selected="false" role="tab" tabindex="-1">
                                <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm005.svg-->
                                <span class="svg-icon svg-icon-2 me-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                            d="M18.4 5.59998C21.9 9.09998 21.9 14.8 18.4 18.3C14.9 21.8 9.2 21.8 5.7 18.3L18.4 5.59998Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM19.9 11H13V8.8999C14.9 8.6999 16.7 8.00005 18.1 6.80005C19.1 8.00005 19.7 9.4 19.9 11ZM11 19.8999C9.7 19.6999 8.39999 19.2 7.39999 18.5C8.49999 17.7 9.7 17.2001 11 17.1001V19.8999ZM5.89999 6.90002C7.39999 8.10002 9.2 8.8 11 9V11.1001H4.10001C4.30001 9.4001 4.89999 8.00002 5.89999 6.90002ZM7.39999 5.5C8.49999 4.7 9.7 4.19998 11 4.09998V7C9.7 6.8 8.39999 6.3 7.39999 5.5ZM13 17.1001C14.3 17.3001 15.6 17.8 16.6 18.5C15.5 19.3 14.3 19.7999 13 19.8999V17.1001ZM13 4.09998C14.3 4.29998 15.6 4.8 16.6 5.5C15.5 6.3 14.3 6.80002 13 6.90002V4.09998ZM4.10001 13H11V15.1001C9.1 15.3001 7.29999 16 5.89999 17.2C4.89999 16 4.30001 14.6 4.10001 13ZM18.1 17.1001C16.6 15.9001 14.8 15.2 13 15V12.8999H19.9C19.7 14.5999 19.1 16.0001 18.1 17.1001Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->@lang('dashboard.email')
                            </a>
                        </li>
                        <!--end:::Tab item-->

                    </ul>
                    <!--end:::Tabs-->
                    <!--begin:::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade active show" id="kt_ecommerce_settings_general" role="tabpanel">
                            <!--begin::Form-->
                            <form method="POST" class="form fv-plugins-bootstrap5 fv-plugins-framework notify-form"
                                action="{{ route('send-notification') }}">
                                @csrf
                                <input type="hidden" name="type" value="notify">
                                <!--begin::Heading-->
                                <div class="row mb-7">
                                    <div class="col-md-9 offset-md-3">
                                        <h2>@lang('dashboard.notification_data')</h2>
                                    </div>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Input group-->
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">@lang('dashboard.notification_title_ar')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the title of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" name="title_ar"
                                            value="">
                                        <!--end::Input-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>@lang('dashboard.notification_message_ar')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the description of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" name="body_ar"></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->


                                <!--begin::Input group-->
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">@lang('dashboard.notification_title_en')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the title of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" name="title_en"
                                            value="">
                                        <!--end::Input-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>@lang('dashboard.notification_message_en')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the description of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" name="body_en"></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-md-3 text-md-end">@lang('dashboard.send_to')</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-md-9">
                                        <select name="user_type" aria-label="Select a Currency" data-control="select2"
                                            data-placeholder="@lang('dashboard.send_to')"
                                            class="form-select form-select-solid form-select-lg select2-hidden-accessible"
                                            data-select2-id="select2-data-19-dyeg" tabindex="-1" aria-hidden="true">
                                            <option value="" data-select2-id="select2-data-21-t9jb">
                                                @lang('dashboard.send_to')</option>
                                            <option value="admins">المشرفين</option>
                                            <option value="all_users">كل المستخدمين</option>
                                            <option value="active_users">المتسخدمين النشطين</option>
                                            <option value="not_active_users">المتسخدمين الغير نشطين</option>
                                            <option value="blocked_users">المتسخدمين المحظورين</option>
                                            <option value="not_blocked_users">المتسخدمين الغير محظورين</option>

                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Action buttons-->
                                <div class="row py-5">
                                    <div class="col-md-9 offset-md-3">
                                        <div class="d-flex">
                                            <!--begin::Button-->
                                            <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                                class="btn btn-light me-3">@lang('dashboard.cancel')</button>
                                            <!--end::Button-->
                                            <!--begin::Button-->
                                            <button type="submit" id="kt_ecommerce_add_product_submit"
                                                data-kt-ecommerce-settings-type="submit" class="btn btn-primary">
                                                <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                                <span class="indicator-progress">@lang('dashboard.please_wait')
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <!--end::Button-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Action buttons-->
                                <div>

                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end:::Tab pane-->
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade" id="kt_ecommerce_settings_products" role="tabpanel">
                            <!--begin::Form-->
                            <form method="POST" class="form fv-plugins-bootstrap5 fv-plugins-framework notify-form"
                                action="{{ route('send-notification') }}">
                                @csrf
                                <input type="hidden" name="type" value="mail">
                                <!--begin::Heading-->
                                <div class="row mb-7">
                                    <div class="col-md-9 offset-md-3">
                                        <h2>@lang('dashboard.notification_data')</h2>
                                    </div>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Input group-->
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">@lang('dashboard.notification_title_ar')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the title of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" name="title_ar"
                                            value="">
                                        <!--end::Input-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>@lang('dashboard.notification_message_ar')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the description of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" name="body_ar"></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->


                                <!--begin::Input group-->
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">@lang('dashboard.notification_title_en')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the title of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" name="title_en"
                                            value="">
                                        <!--end::Input-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>@lang('dashboard.notification_message_en')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the description of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" name="body_en"></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-md-3 text-md-end">@lang('dashboard.send_to')</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-md-9">
                                        <select name="user_type" aria-label="Select a Currency" data-control="select2"
                                            data-placeholder="@lang('dashboard.send_to')"
                                            class="form-select form-select-solid form-select-lg select2-hidden-accessible"
                                            data-select2-id="select2-data-19-dyeg" tabindex="-1" aria-hidden="true">
                                            <option value="" data-select2-id="select2-data-21-t9jb">
                                                @lang('dashboard.send_to')</option>
                                            <option value="admins">المشرفين</option>
                                            <option value="all_users">كل المستخدمين</option>
                                            <option value="active_users">المتسخدمين النشطين</option>
                                            <option value="not_active_users">المتسخدمين الغير نشطين</option>
                                            <option value="blocked_users">المتسخدمين المحظورين</option>
                                            <option value="not_blocked_users">المتسخدمين الغير محظورين</option>

                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Action buttons-->
                                <div class="row py-5">
                                    <div class="col-md-9 offset-md-3">
                                        <div class="d-flex">
                                            <!--begin::Button-->
                                            <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                                class="btn btn-light me-3">@lang('dashboard.cancel')</button>
                                            <!--end::Button-->
                                            <!--begin::Button-->
                                            <button type="submit" id="kt_ecommerce_add_product_submit"
                                                data-kt-ecommerce-settings-type="submit" class="btn btn-primary">
                                                <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                                <span class="indicator-progress">@lang('dashboard.please_wait')
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <!--end::Button-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Action buttons-->
                                <div>

                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end:::Tab pane-->

                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade" id="kt_ecommerce_settings_localization" role="tabpanel">
                            <!--begin::Form-->
                            <form method="POST" class="form fv-plugins-bootstrap5 fv-plugins-framework notify-form"
                                action="{{ route('update-settings') }}">
                                @csrf
                                <input type="hidden" name="type" value="sms">

                                <!--begin::Heading-->
                                <div class="row mb-7">
                                    <div class="col-md-9 offset-md-3">
                                        <h2>@lang('dashboard.notification_data')</h2>
                                    </div>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Input group-->
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">@lang('dashboard.notification_title_ar')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the title of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" name="title_ar"
                                            value="">
                                        <!--end::Input-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>@lang('dashboard.notification_message_ar')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the description of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" name="body_ar"></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->


                                <!--begin::Input group-->
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span class="required">@lang('dashboard.notification_title_en')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the title of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" name="title_en"
                                            value="">
                                        <!--end::Input-->
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <div class="col-md-3 text-md-end">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>@lang('dashboard.notification_message_en')</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                aria-label="Set the description of the store for SEO."
                                                data-kt-initialized="1"></i>
                                        </label>
                                        <!--end::Label-->
                                    </div>
                                    <div class="col-md-9">
                                        <!--begin::Input-->
                                        <textarea class="form-control form-control-solid" name="body_en"></textarea>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-md-3 text-md-end">@lang('dashboard.send_to')</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-md-9">
                                        <select name="user_type" aria-label="Select a Currency" data-control="select2"
                                            data-placeholder="@lang('dashboard.send_to')"
                                            class="form-select form-select-solid form-select-lg select2-hidden-accessible"
                                            data-select2-id="select2-data-19-dyeg" tabindex="-1" aria-hidden="true">
                                            <option value="" data-select2-id="select2-data-21-t9jb">
                                                @lang('dashboard.send_to')</option>
                                            <option value="admins">المشرفين</option>
                                            <option value="all_users">كل المستخدمين</option>
                                            <option value="active_users">المتسخدمين النشطين</option>
                                            <option value="not_active_users">المتسخدمين الغير نشطين</option>
                                            <option value="blocked_users">المتسخدمين المحظورين</option>
                                            <option value="not_blocked_users">المتسخدمين الغير محظورين</option>

                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Action buttons-->
                                <div class="row py-5">
                                    <div class="col-md-9 offset-md-3">
                                        <div class="d-flex">
                                            <!--begin::Button-->
                                            <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                                class="btn btn-light me-3">@lang('dashboard.cancel')</button>
                                            <!--end::Button-->
                                            <!--begin::Button-->
                                            <button type="submit" id="kt_ecommerce_add_product_submit"
                                                data-kt-ecommerce-settings-type="submit" class="btn btn-primary">
                                                <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                                <span class="indicator-progress">@lang('dashboard.please_wait')
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <!--end::Button-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Action buttons-->
                                <div></div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end:::Tab pane-->


                    </div>
                    <!--end:::Tab content-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.notify-form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action')
                $.ajax({
                    url: url,
                    method: 'post',
                    data: new FormData($(this)[0]),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".send-notify-button").html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            ).attr('disable', true)
                    },
                    success: (response) => {
                        $(".text-danger").remove()
                        $('.store input').removeClass('border-danger')
                        $(".send-notify-button").html("ارسال").attr('disable', false)
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: 'تمت الارسال بنجاح',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        $(this).trigger("reset")
                    },
                    error: function(xhr) {
                        $(".send-notify-button").html("ارسال").attr('disable', false)
                        $(".text-danger").remove()
                        $('.store input').removeClass('border-danger')

                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('.store input[name=' + key + ']').addClass(
                                'border-danger')
                            $('.store input[name=' + key + ']').after(
                                `<span class="mt-5 text-danger">${value}</span>`);
                            $('.store select[name=' + key + ']').after(
                                `<span class="mt-5 text-danger">${value}</span>`);
                        });
                    },
                });

            });
        });
    </script>
@endsection
