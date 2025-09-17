@extends('dashboard.layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('dashboard.service_site_customer_service') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="d-flex flex-coulmn">

        <form id="kt_ecommerce_add_product_form" 
        data-kt-redirect="{{ isset($item) ? route('service_site_customer_service.edit', $item->id) : route('service_site_customer_service.create') }}" 
        action="{{ isset($item) ? route('service_site_customer_service.update', $item->id) : route('service_site_customer_service.store') }}" 
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
                <div class="d-flex flex-row flex-wrap w-100 gap-25 mb-25">
                    <div class="form-group">
                        <label for="workername_en">{{ __('dashboard.worker_name_en') }}</label>
                        <div id="editor-workername-en" class="w-100" dir="ltr" style="min-height:120px; direction: ltr; text-align: left;">{!! old('workername_en', isset($item) ? $item->workername_en : '') !!}</div>
                        <input type="hidden" name="workername_en" id="input-workername-en" value="{!! old('workername_en', isset($item) ? $item->workername_en : '') !!}">
                    </div>

                    <div class="form-group mb-25">
                        <label for="workername_ar">{{ __('dashboard.worker_name_ar') }}</label>
                        <div id="editor-workername-ar" class="w-100" dir="rtl" style="min-height:120px; direction: rtl; text-align: right;">{!! old('workername_ar', isset($item) ? $item->workername_ar : '') !!}</div>
                        <input type="hidden" name="workername_ar" id="input-workername-ar" value="{!! old('workername_ar', isset($item) ? $item->workername_ar : '') !!}">
                    </div>
                </div>
                <div class="form-group mb-15 position-relative w-100">
                    <label for="workerphone">{{ __('dashboard.worker_phone') }}</label>
                    <input type="tel" name="workerphone" class="form-control" style="direction: rtl; text-align: right;" value="{{ old('workerphone', isset($item) ? $item->workerphone : '') }}">
                </div>

                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary w-fit mb-10">
                    <span class="indicator-label">{{ isset($item) ? __('dashboard.update') : __('dashboard.create') }}</span>
                    <span class="indicator-progress">{{ __('dashboard.please_wait') }}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </form>
    </div>
        
        <div class="col-md-4">
            <ul class="list-group">
                @foreach(isset($items) ? $items : [] as $row)
                    <li class="list-group-item">
                        <div><strong>{{ __('dashboard.id') }}:</strong> {{ $row->id }}</div>
                        <div><strong>{{ __('dashboard.phone') }}:</strong> {{ $row->workerphone }}</div>
                        <div class="mt-2">
                            <a href="{{ route('service_site_customer_service.edit', $row->id) }}" class="btn btn-sm btn-secondary">{{ __('dashboard.edit') }}</a>
                            <form action="{{ route('service_site_customer_service.destroy', $row->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('dashboard.delete_confirmation') }}');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">{{ __('dashboard.delete') }}</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
@endsection

@section('scripts')
<script src="{{ asset('dashboard/custom/js/sending-forms.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toolbarOptions = [
            [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
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

        const quillService = new Quill('#editor-serviceSite', { theme: 'snow', modules: { toolbar: toolbarOptions } });
        const quillEn = new Quill('#editor-workername-en', { theme: 'snow', modules: { toolbar: toolbarOptions } });
        const quillAr = new Quill('#editor-workername-ar', { theme: 'snow', modules: { toolbar: toolbarOptions } });

        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            document.getElementById('input-serviceSite').value = quillService.root.innerHTML;
            document.getElementById('input-workername-en').value = quillEn.root.innerHTML;
            document.getElementById('input-workername-ar').value = quillAr.root.innerHTML;
        });
    });
</script>
@endsection
