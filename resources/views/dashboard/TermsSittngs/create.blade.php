@section('pageTitle', __('dashboard.terms_sittngs'))
@extends('dashboard.layouts.app')

@section('content')
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
                    <h3 class="fw-bolder m-0">
                        {{ isset($termsSittng) ? __('dashboard.update_terms_setting') : __('dashboard.create_terms_setting') }}
                    </h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">

                <form
                    action="{{ isset($termsSittng) ? route('terms_sittngs.update', $termsSittng->id) : route('terms_sittngs.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($termsSittng))
                        @method('PUT')
                    @endif
                    <div class="card-body border-top p-9">
                        <div class="row">

                            <!-- Logo Field -->
                            <div class="form-group col-6 mb-3">
                                <label for="logo" class="form-label">{{ __('dashboard.logo') }}</label>
                                <input type="file" name="logo" id="logo" class="form-control">
                                @if (isset($termsSittng->logo))
                                    <img src="{{ asset('storage/' . $termsSittng->logo) }}" alt="Logo" width="100"
                                        class="mt-2">
                                @else
                                    <img src="{{ asset('assets/media/logos/logo-1.png') }}" alt="{{ __('dashboard.logo') }}"
                                        width="100" class="mt-2">
                                @endif

                            </div>
                            <!-- Commercial License Field -->
                            <div class="form-group col-6 mb-3">
                                <label for="commercial_license"
                                    class="form-label">{{ __('dashboard.commercial_license') }}</label>
                                <input type="file" name="commercial_license" id="commercial_license"
                                    class="form-control">
                                @if (isset($termsSittng->commercial_license))
                                    <img src="{{ asset('storage/' . $termsSittng->commercial_license) }}"
                                        alt="Commercial License" width="100" class="mt-2">
                                @else
                                    <img src="{{ asset('assets/media/logos/logo-1.png') }}"
                                        alt="{{ __('dashboard.commercial_license') }}" width="100" class="mt-2">
                                @endif
                            </div>
                            <!-- Description Field -->
                            <div class="form-group col-6 mb-3">
                                <label for="description"
                                    class="form-label">{{ __('dashboard.company_description') }}</label>
                                <textarea name="description" id="description" class="form-control"
                                    rows="3">{{ old('description', $termsSittng->description ?? '') }}</textarea>
                            </div>

                            <!-- Company Name Field -->
                            <div class="form-group col-6 mb-3">
                                <label for="company_name" class="form-label">{{ __('dashboard.company_name') }}</label>
                                <input type="text" name="company_name" id="company_name" class="form-control"
                                    value="{{ old('company_name', $termsSittng->company_name ?? '') }}">
                            </div>
                            <!-- <div class="form-group col-12 mb-3">
                                <label for="terms" class="form-label">{{ __('dashboard.terms') }} :</label>
                                <textarea name="terms"  class="form-control"
                                    rows="3">{{ old('terms', $termsSittng->terms ?? '') }}</textarea>
                            </div> -->

                            <!-- <script type="text/javascript">
                                CKEDITOR.replace('terms', {
                                    filebrowserUploadMethod: 'form',
                                    filebrowserUploadUrl: '/ckeditor/upload',
                                    language: 'ar', 
                                    height: 200 
                                });
                            </script> -->
                        </div>
                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($termsSittng) ? __('dashboard.update_settings') : __('dashboard.save_settings') }}
                            </button>
                        </div>
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