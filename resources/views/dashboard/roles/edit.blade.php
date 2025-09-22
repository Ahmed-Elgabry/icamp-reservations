@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.update_title', ['page_title' => __('dashboard.role')]))

@section('style')
<style>
    /* ألوان اللوجو البني */
    :root {
        --primary-brown: #8B4513;
        --secondary-brown: #A0522D;
        --light-brown: #D2B48C;
        --dark-brown: #654321;
        --cream: #F5F5DC;
    }

    .permissionCard {
        border: 2px solid #e4e6ef !important;
        border-radius: 15px !important;
        margin-bottom: 1.5rem !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1) !important;
        background: white !important;
        overflow: hidden !important;
        position: relative !important;
    }

    .permissionCard:hover {
        box-shadow: 0 8px 25px rgba(139, 69, 19, 0.2) !important;
        transform: translateY(-3px) !important;
        border-color: var(--primary-brown) !important;
    }

    .role-title {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%) !important;
        border-radius: 0 !important;
        padding: 1.2rem !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        color: white !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
        border-bottom: 3px solid var(--secondary-brown) !important;
    }

    .permissionCard .card {
        border: none !important;
        box-shadow: none !important;
        margin-bottom: 0 !important;
        border-radius: 0 !important;
    }

    .permissionCard ul {
        padding: 1.2rem !important;
        margin: 0 !important;
        max-height: 320px !important;
        overflow-y: auto !important;
        background: linear-gradient(to bottom, #fefefe 0%, #f9f9f9 100%) !important;
    }

    .permissionCard ul::-webkit-scrollbar {
        width: 6px !important;
    }

    .permissionCard ul::-webkit-scrollbar-track {
        background: var(--cream) !important;
        border-radius: 3px !important;
    }

    .permissionCard ul::-webkit-scrollbar-thumb {
        background: var(--primary-brown) !important;
        border-radius: 3px !important;
        transition: background 0.3s ease !important;
    }

    .permissionCard ul::-webkit-scrollbar-thumb:hover {
        background: var(--dark-brown) !important;
    }

    .permissionCard li {
        padding: 0.7rem 0 !important;
        border-bottom: 1px solid #f0f0f0 !important;
        transition: all 0.2s ease !important;
    }

    .permissionCard li:last-child {
        border-bottom: none !important;
    }

    .permissionCard li:hover {
        background: rgba(139, 69, 19, 0.05) !important;
        padding-left: 0.3rem !important;
        border-radius: 5px !important;
    }

    .title_lable {
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        color: #4a4a4a !important;
        margin-left: 0.7rem !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        line-height: 1.4 !important;
    }

    .title_lable:hover {
        color: var(--primary-brown) !important;
        font-weight: 600 !important;
    }

    .form-check-input:checked {
        background-color: var(--primary-brown) !important;
        border-color: var(--primary-brown) !important;
        box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25) !important;
    }

    .form-check-input:focus {
        border-color: var(--secondary-brown) !important;
        box-shadow: 0 0 0 0.25rem rgba(139, 69, 19, 0.25) !important;
    }

    .form-check-input {
        width: 1.2em !important;
        height: 1.2em !important;
        border: 2px solid #dee2e6 !important;
        transition: all 0.2s ease !important;
    }

    .selectP {
        margin: 0 !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
    }

    .permissions-container {
        background: linear-gradient(135deg, #faf9f7 0%, #f5f4f2 100%) !important;
        border-radius: 15px !important;
        padding: 2.5rem !important;
        margin-top: 1.5rem !important;
        border: 1px solid var(--light-brown) !important;
    }

    .permissions-header {
        background: white !important;
        border-radius: 12px !important;
        padding: 2rem !important;
        margin-bottom: 2.5rem !important;
        box-shadow: 0 6px 20px rgba(139, 69, 19, 0.1) !important;
        border: 2px solid var(--light-brown) !important;
    }

    .select-all-container {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 1.5rem !important;
    }

    .select-all-container .fas {
        color: var(--primary-brown) !important;
        font-size: 2.2rem !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%) !important;
        border-color: var(--primary-brown) !important;
        border: none !important;
        padding: 0.8rem 2rem !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background: linear-gradient(135deg, var(--dark-brown) 0%, var(--primary-brown) 100%) !important;
        border-color: var(--dark-brown) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3) !important;
    }

    /* تحسين المظهر العام */
    .card {
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(139, 69, 19, 0.08) !important;
    }

    .card-header {
        border-bottom: 2px solid var(--light-brown) !important;
        background: linear-gradient(to right, #fefefe 0%, #faf9f7 100%) !important;
    }

    @media (max-width: 768px) {
        .permissionCard {
            margin-bottom: 1rem !important;
        }

        .role-title {
            font-size: 0.9rem !important;
            padding: 1rem !important;
        }

        .selectP {
            font-size: 0.85rem !important;
        }

        .permissions-container {
            padding: 1.5rem !important;
        }

        .permissions-header {
            padding: 1.5rem !important;
        }
    }

    /* تأثيرات إضافية للجمال */
    .permissionCard::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, var(--primary-brown), var(--secondary-brown));
        border-radius: 15px 15px 0 0;
    }

    .role-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, var(--secondary-brown), var(--primary-brown));
    }
</style>
@endsection

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
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">@lang('dashboard.create_title', ['page_title' => __('dashboard.role')])</h1>
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
                    <li class="breadcrumb-item text-dark">@lang('dashboard.update', ['page_title' => __('dashboard.role')])</li>
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
                        <h3 class="fw-bolder m-0">@lang('dashboard.update', ['page_title' => __('dashboard.role')])</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->

                <!--begin::Content-->
                <div id="kt_content_container" class="container-xxl">
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_product_form" method="POST" action="{{ route('roles.update' , $role->id) }}" class="form fv-plugins-bootstrap5 fv-plugins-framework store"
                        novalidate="novalidate" data-kt-redirect="{{ route('roles.index') }}">
                        @csrf
                        @method('PUT')
                        <!--begin::Card body-->
                        <div class="row mb-0">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.select_all_permissions')</label>
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
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.name')</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                            <input type="text" name="nickname_ar"
                                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                placeholder="@lang('dashboard.nickname_ar')" value="{{ $role->nickname_ar }}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                            <input type="text" name="nickname_en" id="nickname_en"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="@lang('dashboard.nickname_en')" value="{{ $role->nickname_en }}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                                <input type="hidden" name="name" id="name" value="{{ $role->name }}">
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
                            <div class="permissions-container">
                                <div class="container-xxl">
                                    <!--begin::Table-->
                                    <div class="row">
                                        {!! $html !!}
                                    </div>
                                    <!--end::Table-->
                                </div>
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
    $('#nickname_en').on('keyup', function() {
        $('#name').val($.trim($(this).val()).replace(/[^a-z0-9-]/gi, '-').replace(/-+/g, '-').replace(/^-|-$/g,
            '').toLowerCase());
    });
</script>
<script>
    $(function() {
        // Handle group parent checkboxes
        $('.group-parent').change(function(e) {
            var group = $(this).data('group');
            var checked = $(this).is(':checked');

            // Select/deselect all permissions in group
            $('.' + group).prop('checked', checked);
        });

        // Handle checkChilds (select all)
        $('.checkChilds').change(function(e) {
            var parent = $(this).data('parent');
            var checked = $(this).is(':checked');

            // Select/deselect all permissions in group
            $('.' + parent).prop('checked', checked);

            // Update group parent state
            var groupParent = $('#' + parent + '_parent');
            if (groupParent.length) {
                groupParent.prop('checked', checked);
            }
        });

        // Handle individual permissions
        $('.childs').change(function(e) {
            var parent = $(this).data('parent');
            var totalChilds = $('.' + parent).length;
            var checkedChilds = $('.' + parent + ':checked').length;

            // Update group parent state
            var groupParent = $('#' + parent + '_parent');
            if (groupParent.length) {
                groupParent.prop('checked', totalChilds === checkedChilds);
            }

            // Update checkChilds state
            var checkChilds = $('.checkChilds_' + parent);
            if (checkChilds.length) {
                checkChilds.prop('checked', totalChilds === checkedChilds);
            }
        });

        // Select all (main checkbox)
        $('#checkedAll').change(function(e) {
            var checked = $(this).is(':checked');

            // Select/deselect all checkboxes
            $('input[type="checkbox"]').prop('checked', checked);
        });

        // Update initial checkbox states
        $('.checkChilds').each(function() {
            var childClass = $(this).data('parent');
            console.log($('#' + childClass).prop("checked"));
            var count = 0

            $("." + childClass).each(function() {
                if (!this.checked) {
                    count = count + 1
                }
            });

            if (!$('#' + childClass).prop("checked")) {
                count = count + 1
            }

            if (count > 0) {
                $(this).prop('checked', false)
            } else {
                $(this).prop('checked', true)
            }

        });
    });

    $(function() {
        $('.roles-parent').change(function(e) {
            var id = $(this).attr('id');
            if (!this.checked) {
                var count = 0
                $("." + id).each(function() {
                    if (this.checked) {
                        count = count + 1
                    }
                });

                if (count > 0) {
                    $('#' + id).attr('checked', true)
                } else {
                    $('#' + id).attr('checked', false)
                }
            }
        });
    });



    $(function() {
        $('.checkChilds').change(function() {
            var childClass = $(this).data('parent');
            if (this.checked) {
                $('.' + childClass).prop("checked", true);
                $('#' + childClass).prop("checked", true);
            } else {
                $('.' + childClass).prop("checked", false);
                $('#' + childClass).prop("checked", false);
            }
        });
    });

    $(function() {
        $('.childs').change(function() {
            var parent = $(this).data('parent');
            if (this.checked) {
                $('#' + parent).prop("checked", true);
                var count = 0
                $("." + parent).each(function() {
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


    $("#checkedAll").change(function() {
        if (this.checked) {
            $("input[type=checkbox]").each(function() {
                this.checked = true
            })
        } else {
            $("input[type=checkbox]").each(function() {
                this.checked = false;
            })
        }
    });

    // Filter roles search
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.permission-search').forEach(function(searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const groupClass = this.getAttribute('data-group');
                const permissionList = document.querySelector('.permission-list[data-group="' + groupClass + '"]');
                const permissionItems = permissionList.querySelectorAll('.permission-item');

                permissionItems.forEach(function(item) {
                    const permissionText = item.getAttribute('data-permission-text');
                    const permissionName = item.getAttribute('data-permission-name');

                    if (searchTerm === '' || permissionText.includes(searchTerm) || permissionName.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>


@endsection