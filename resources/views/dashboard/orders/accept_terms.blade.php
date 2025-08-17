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
                </div>

                <div class="card-body pt-0">

                    @isset($order)
                        <div class="row mb-6">
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

                                <textarea
                                    id="customerSignature"
                                    class="form-control form-control-solid fs-6 mb-2"
                                    rows="4"
                                    readonly
                                >{{ old('commercial_license', $termsSittng->commercial_license ?? '') }}</textarea>

                                <small class="text-muted d-block mt-1">
                                    {{ Str::length($termsSittng->commercial_license ?? '') }} @lang('dashboard.characters')
                                </small>
                            </div>

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
