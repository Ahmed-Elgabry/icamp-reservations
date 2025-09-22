@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.accept_terms'))
@section('content')

    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            @include('dashboard.orders.nav')

            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h3 class="card-title">{{ __('dashboard.accept_terms') }}</h3>
                    @if(isset($order->signature_path))  
                        @can('orders.destroy')
                            <div class="mt-3">
                                 <form id="kt_ecommerce_add_product_form" class="d-inline store" action="{{ route('signature.destroy', $order) }}" method="post" data-success-message="@lang('dashboard.deleted_successfully')" data-kt-redirect="{{ request()->fullUrl() }}">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-danger btn-sm text-white d-inline-flex align-items-center gap-1" onclick="return confirm('@lang('dashboard.confirm_delete')')">
                                        <i class="fa fa-trash"></i>
                                        {{ __('dashboard.delete') }}
                                    </button>
                                </form>
                            </div>
                        @endcan
                    @endif
                </div>
                
                <div class="card-body pt-0">

                    @isset($order)
                        <div class="row mb-6">

                                <div class="d-block">
                                    <div class="border rounded p-3 bg-light-subtle">
                                        <div class="row g-3 small">
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                                                <div class="fw-bold">{{ $order->id }}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted">{{ __('dashboard.customer_name') }}</div>
                                                <div class="fw-bold">{{ $order->customer->name }}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted">{{ __('dashboard.phone')}}</div>
                                                <div class="fw-bold">{{ $order->customer->phone }}</div>
                                            </div>
                                        </div>
                                    </div>
                            
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                @lang('dashboard.Customer_Signature')
                            </label>

                            <div class="col-lg-8 d-flex flex-column gap-3 mb-2">
                                @if(isset($order->signature_path))
                                    <div class="text-success fw-bold">
                                        {{ $order?->signature }}
                                    </div>
                                    <img src="{{ Storage::url($order->signature_path) }}" alt="Signature" style="max-height:80px;">
                                @else
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control"
                                                value="{{ route('signature.show', $order) }}"
                                                readonly
                                                onclick="this.select();document.execCommand('copy');">
                                            <button type="button" class="btn btn-outline-primary"
                                                    onclick="navigator.clipboard.writeText('{{ route('signature.show', $order) }}')">
                                                Copy Link
                                            </button>
                                        </div>
                                        <small class="text-muted">@lang('dashboard.desc_Customer_Signature')</small>
                                @endif
                            </div>

                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                @lang('dashboard.terms')
                            </label>

                            <div class="col-lg-8">
                                @php
                                    $locale = app()->getLocale();
                                    $field = 'commercial_license_' . ($locale === 'ar' ? 'ar' : 'en');
                                    $termsHtml = $termsSittng->{$field} ?? ($termsSittng->commercial_license_ar ?? $termsSittng->commercial_license_en ?? '');
                                    $plainLength = Str::length(strip_tags($termsHtml));
                                @endphp

                                <div class="terms-view border rounded p-3" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
                                    {!! $termsHtml !!}
                                </div>

                                <small class="text-muted d-block mt-1">
                                    {{ $plainLength }} @lang('dashboard.characters')
                                </small>
                            </div>

                            @if(app()->getLocale() === 'ar')
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    @lang('dashboard.terms') (English)
                                </label>
                                <div class="col-lg-8">
                                    @php
                                        $termsHtmlEn = $termsSittng->commercial_license_en ?? '';
                                        $plainLengthEn = Str::length(strip_tags($termsHtmlEn));
                                    @endphp
                                    <div class="terms-view border rounded p-3" dir="ltr">
                                        {!! $termsHtmlEn !!}
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ $plainLengthEn }} @lang('dashboard.characters')
                                    </small>
                                </div>
                            @endif

                            <label class="col-lg-4 col-form-label fw-bold fs-6 mb-2">
                                @lang('dashboard.additional_notes')
                            </label>

                            <div class="col-lg-8">

                                <form method="POST" action="{{ route('orders.updateNotes', $order) }}" class="row g-3">
                                    @csrf
                                    @method('PATCH')

                                        <textarea
                                            id="termsAdditionalNotes"
                                            name="terms_notes"
                                            class="form-control form-control-solid fs-6 @error('terms_notes') is-invalid @enderror"
                                            rows="4"
                                        >{{ old('terms_notes', $order->terms_notes ?? '') }}</textarea>

                                        <small class="text-muted d-block mt-1">
                                            {{ Str::length(old('terms_notes', $order->terms_notes ?? '')) }}
                                            @lang('dashboard.characters')
                                        </small>

                                        @error('terms_notes')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-3">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                @lang('dashboard.save')
                                            </button>
                                        </div>
                                </form>
                            </div>

                        </div>
                    @endisset


                </div>
            </div>
        </div>
    </div>
    <!--end::Post-->

@endsection

@push('js')
<script>
    // Show confirmation before submitting the delete form; on confirm, let sending-forms.js handle AJAX
    $(document).on('click', '#kt_ecommerce_add_product_form #kt_ecommerce_add_product_submit', function(e){
        var $btn = $(this);
        var $form = $btn.closest('form');
        // Only intercept for DELETE signature form
        if ($form.attr('action') && $form.attr('action').includes('/sign/')) {
            e.preventDefault();
            Swal.fire({
                text: `${$.localize.data['app']['common']['check_for_delete']}`,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: `${$.localize.data['app']['common']['ok_delete']}`,
                cancelButtonText: `${$.localize.data['app']['common']['no_cancel']}`,
                customClass: { confirmButton: 'btn fw-bold btn-danger', cancelButton: 'btn fw-bold btn-active-light-primary' }
            }).then(function(res){
                if (res.isConfirmed) {
                    $form.trigger('submit');
                }
            });
        }
    });

    // As a fallback, reload the page on success in case redirect attribute is ignored
    $(document).on('store:success', '#kt_ecommerce_add_product_form', function(){
        window.location.reload();
    });
</script>
@endpush
