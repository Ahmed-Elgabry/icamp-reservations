 @extends('dashboard.layouts.app')
@section('pageTitle', isset($page) ? __('dashboard.edit') : __('dashboard.create_item'))
@section('content')
<div class="container-xxl">
    <div class="card">
        <div class="card-body">
            <form class="form {{ isset($page) ? 'update' : 'store' }}" id="kt_ecommerce_add_product_form"
                  action="{{ isset($page) ? route('pages.update', $page) : route('pages.store') }}"
                  method="POST" data-kt-redirect="{{ route('pages.index') }}">
                @csrf
                @if(isset($page)) @method('PUT') @endif

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('dashboard.name')</label>
                    <div class="col-lg-9">
                        <input type="text" name="name" class="form-control" value="{{ old('name', $page->name ?? '') }}" required>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('dashboard.url')</label>
                    <div class="col-lg-9">
                        <input type="text" name="url" class="form-control" value="{{ old('url', $page->url ?? '') }}" placeholder="@lang('dashboard.path_placeholder')" required>
                        <small class="text-muted">@lang('dashboard.path_example')</small>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('dashboard.is_available')</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_available" value="0">
                            <input class="form-check-input" type="checkbox" value="1" name="is_available" id="is_available"
                                   {{ old('is_available', $page->is_available ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available"></label>
                        </div>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">@lang('dashboard.is_authenticated')</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_authenticated" value="0">
                            <input class="form-check-input" type="checkbox" value="1" name="is_authenticated" id="is_authenticated"
                                   {{ old('is_authenticated', $page->is_authenticated ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_authenticated"></label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('pages.index') }}" class="btn btn-light me-2">@lang('dashboard.cancel')</a>
                    <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                        <span class="indicator-label">@lang('dashboard.save')</span>
                        <span class="indicator-progress">@lang('dashboard.please_wait')
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection





