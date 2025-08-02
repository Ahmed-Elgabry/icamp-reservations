@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.stocks'))
@section('content')
<!-------------->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @include('dashboard.stocks.nav')

        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                data-bs-target="#kt_account_profile_details" aria-expanded="true"
                aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">
                        {{ isset($stock) ? $stock->name : __('dashboard.create_title', ['page_title' => __('dashboard.stocks')]) }}
                    </h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_ecommerce_add_product_form"
                    data-kt-redirect="{{  isset($stock) ? route('stocks.edit', $stock->id) : route('stocks.create') }}"
                    action="{{ isset($stock) ? route('stocks.update', $stock->id) : route('stocks.store') }}"
                    method="POST" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row store">

                    @csrf
                    @if(isset($stock))
                        @method('PUT') 
                    @endif

                    <div class="card-body border-top p-9">

                        <!-- Image input group -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.image')</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true"
                                    style="background-image: url('')">
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="background-image: url('{{ isset($stock) ? asset($stock->image) : asset('/images/stocks.png') }}')">
                                    </div>
                                    @if ($errors->has('image'))
                                        <div class="alert alert-danger">{{ $errors->first('image') }}</div>
                                    @endif
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="Change image">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="image">
                                        <input type="hidden" name="avatar_remove">
                                    </label>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        title="Cancel image">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        title="Remove image">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Name input group -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.name')</label>
                            <div class="col-lg-8">
                                <input type="text" name="name"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="@lang('dashboard.name')"
                                    value="{{ isset($stock) ? $stock->name : old('name') }}" required>
                            </div>
                        </div>

                        <!-- Price input group -->
                        <div class="row mb-6">
                            <label
                                class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.price')</label>
                            <div class="col-lg-8">
                                <input type="number" step="0.01" name="price"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="@lang('dashboard.price')"
                                    value="{{ isset($stock) ? $stock->price : old('price') }}" required>
                            </div>
                        </div>

                        <!-- Selling price input group -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.selling_price')</label>
                            <div class="col-lg-8">
                                <input type="number" step="0.01" name="selling_price"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="@lang('dashboard.selling_price')"
                                    value="{{ isset($stock) ? $stock->selling_price : old('selling_price') }}">
                            </div>
                        </div>

                        <!-- Quantity input group -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.quantity')</label>
                            <div class="col-lg-8">
                                <input type="number" name="quantity"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="@lang('dashboard.quantity')"
                                    value="{{ isset($stock) ? $stock->quantity : old('quantity') }}">
                            </div>
                        </div>

                        <!-- Percentage input group -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.Percentage')</label>
                            <div class="col-lg-8">
                                <input type="text" name="percentage"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="@lang('dashboard.Percentage')"
                                    value="{{ isset($stock) ? $stock->percentage : old('percentage') }}">
                            </div>
                        </div>

                        <!-- Description input group -->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                            <div class="col-lg-8">
                                <textarea name="description" class="form-control form-control-lg form-control-solid"
                                    placeholder="@lang('dashboard.notes')">{{ isset($stock) ? $stock->description : old('description') }}</textarea>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                <span class="indicator-progress">@lang('dashboard.please_wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
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
@push('css')
    <style>
        .image-input.image-input-outline .image-input-wrapper {
            background-size: contain;
            background-position: 50% 50%;
        }
    </style>
@endpush