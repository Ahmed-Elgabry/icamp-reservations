@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.service_site_customer_service'))
@section('content')
<div class="container">
    <h1>{{ __('dashboard.service_site_customer_service') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="d-flex flex-coulmn">

        <form id="kt_ecommerce_add_product_form"
        data-kt-redirect="{{ isset($item) ? route('bookings.service-site-customer-service.edit', $item->id) : route('bookings.service-site-customer-service.create') }}"
        action="{{ isset($item) ? route('bookings.service-site-customer-service.update', $item->id) : route('bookings.service-site-customer-service.store') }}"
        method="POST" enctype="multipart/form-data"
                class="form d-flex flex-column store" dir="rtl">
                @csrf
                @if(isset($item))
                @method('PUT')
                @endif

                <div class="form-group mb-25">
                    <label for="serviceSite">{{ __('dashboard.service_site') }}</label>
                    <div id="editor-serviceSite" dir="rtl" style="min-height:150px; direction: rtl; text-align: right;">{!! old('serviceSite', isset($item) ? $item->serviceSite : '') !!}</div>
                    <input type="hidden" name="serviceSite" id="input-serviceSite" value="{!! old('serviceSite', isset($item) ? $item->serviceSite : '') !!}">
                </div>
                    <div class="d-flex flex-row flex-wrap w-100 justify-content-between overflow-hidden mb-25">
                        <div class="form-group w-45 mb-25 ">
                            <label for="workername_en">{{ __('dashboard.worker_name_en') }}</label>
                            <div id="editor-workername-en" class="w-100" dir="ltr" style="min-height:120px; direction: ltr; text-align: left;">{!! old('workername_en', isset($item) ? $item->workername_en : '') !!}</div>
                            <input type="hidden" name="workername_en" id="input-workername-en" value="{!! old('workername_en', isset($item) ? $item->workername_en : '') !!}">
                        </div>

                        <div class="form-group mb-15 w-45">
                            <label for="workername_ar">{{ __('dashboard.worker_name_ar') }}</label>
                            <div id="editor-workername-ar" class="w-100" dir="rtl" style="min-height:120px; direction: rtl; text-align: right;">{!! old('workername_ar', isset($item) ? $item->workername_ar : '') !!}</div>
                            <input type="hidden" name="workername_ar" id="input-workername-ar" value="{!! old('workername_ar', isset($item) ? $item->workername_ar : '') !!}">
                        </div>
                    </div>
                <div class="form-group mb-15  w-100 d-flex flex-column">
                    <label for="workerphone">{{ __('dashboard.worker_phone') }}</label>
                    <div class="d-flex flex-column align-items-start position-relative w-100">
                        <input type="tel" name="workerphone" class="form-control w-100 ltr-input" dir="ltr" style="direction: ltr; text-align: left;" value="{{ old('workerphone', isset($item) ? $item->workerphone : '') }}">
                    </div>
                </div>

                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary w-fit mb-10">
                    <span class="indicator-label">{{ isset($item) ? __('dashboard.update_item') : __('dashboard.create_item') }}</span>
                    <span class="indicator-progress">{{ __('dashboard.please_wait') }}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </form>
    </div>

        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('dashboard.service_site') }}</th>
                            <th>{{ __('dashboard.worker_name_en') }}</th>
                            <th>{{ __('dashboard.worker_name_ar') }}</th>
                            <th>{{ __('dashboard.phone') }}</th>
                            <th class="text-center">{{ __('dashboard.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(isset($items) ? $items : [] as $row)
                            <tr>
                                <td>{!! $row->service_site !!}</td>
                                <td>{!! $row->workername_en !!}</td>
                                <td>{!! $row->workername_ar !!}</td>
                                <td>{{ $row->workerphone }}</td>
                                <td class="text-center d-flex flex-row flex-nowrap">
                                    @can('bookings.service-site-customer-service.edit')
                                    <a href="{{ route('bookings.service-site-customer-service.edit', $row->id) }}" class="btn btn-sm btn-secondary">{{ __('dashboard.edit') }}</a>
                                    @endcan
                                    @can('bookings.service-site-customer-service.destroy')
                                    <form action="{{ route('bookings.service-site-customer-service.destroy', $row->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('dashboard.delete_confirmation') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('dashboard.delete') }}</button>
                                    </form>
                                    @endcan
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('dashboard.no_data_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toolbarOptions = [
            [{ 'font': [] }, { 'size': ['small', '14px', '18px', '32px'] }],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['link', 'clean']
        ];

        // Register fonts and sizes if needed
        var Font = Quill.import('formats/font');
        Font.whitelist = ['sans-serif', 'serif', 'monospace'];
        Quill.register(Font, true);

        var Size = Quill.import('attributors/style/size');
        Size.whitelist = ['small', '14px', '18px', '32px'];
        Quill.register(Size, true);

        // Initialize Quill editors
        const quillService = new Quill('#editor-serviceSite', { theme: 'snow', modules: { toolbar: toolbarOptions } });
        const quillEn = new Quill('#editor-workername-en', { theme: 'snow', modules: { toolbar: toolbarOptions } });
        const quillAr = new Quill('#editor-workername-ar', { theme: 'snow', modules: { toolbar: toolbarOptions } });

        // Enforce RTL direction on Arabic editors and serviceSite editor
        // The worker name EN field should remain LTR (quillEn)
        var setEditorRTL = function(quill){
            var root = quill.root;
            root.setAttribute('dir', 'rtl');
            root.style.direction = 'rtl';
            root.style.textAlign = 'right';
            // add caret support class
            root.classList.add('rtl-input');
        };
        setEditorRTL(quillService);
        setEditorRTL(quillAr);

        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            document.getElementById('input-serviceSite').value = quillService.root.innerHTML;
            document.getElementById('input-workername-en').value = quillEn.root.innerHTML;
            document.getElementById('input-workername-ar').value = quillAr.root.innerHTML;
        });
    });
</script>
@endsection

@section('styles')
<style>
    /* RTL input/editor adjustments */
    .rtl-input{ font-size:14px; caret-color: auto; }
    /* Make sure Quill placeholder text aligns right in RTL editors */
    .ql-editor[dir="rtl"]::before{ right: 0; left: auto; }
    /* If you have inline country select next to phone input, ensure it stays inline */
    .country-select-wrap { display: inline-flex; align-items: center; gap:8px; }
</style>
@endsection
