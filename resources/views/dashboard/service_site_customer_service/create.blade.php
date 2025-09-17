@extends('dashboard.layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('dashboard.service_site_customer_service') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <form id="kt_ecommerce_add_product_form" 
                data-kt-redirect="{{ isset($item) ? route('service_site_customer_service.edit', $item->id) : route('service_site_customer_service.create') }}" 
                action="{{ isset($item) ? route('service_site_customer_service.update', $item->id) : route('service_site_customer_service.store') }}" 
                method="POST" enctype="multipart/form-data" 
                class="form d-flex flex-column flex-lg-row store">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="serviceSite">{{ __('dashboard.service_site') }}</label>
                    <div id="editor-serviceSite" style="min-height:150px;">{!! old('serviceSite', isset($item) ? $item->serviceSite : '') !!}</div>
                    <input type="hidden" name="serviceSite" id="input-serviceSite" value="{!! old('serviceSite', isset($item) ? $item->serviceSite : '') !!}">
                </div>

                <div class="form-group">
                    <label for="workername_en">{{ __('dashboard.worker_name_en') }}</label>
                    <div id="editor-workername-en" style="min-height:120px;">{!! old('workername_en', isset($item) ? $item->workername_en : '') !!}</div>
                    <input type="hidden" name="workername_en" id="input-workername-en" value="{!! old('workername_en', isset($item) ? $item->workername_en : '') !!}">
                </div>

                <div class="form-group">
                    <label for="workername_ar">{{ __('dashboard.worker_name_ar') }}</label>
                    <div id="editor-workername-ar" style="min-height:120px;">{!! old('workername_ar', isset($item) ? $item->workername_ar : '') !!}</div>
                    <input type="hidden" name="workername_ar" id="input-workername-ar" value="{!! old('workername_ar', isset($item) ? $item->workername_ar : '') !!}">
                </div>

                <div class="form-group">
                    <label for="workerphone">{{ __('dashboard.worker_phone') }}</label>
                    <input type="tel" name="workerphone" class="form-control" value="{{ old('workerphone', isset($item) ? $item->workerphone : '') }}">
                </div>

                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                    <span class="indicator-label">{{ isset($item) ? __('dashboard.update') : __('dashboard.create') }}</span>
                    <span class="indicator-progress">{{ __('dashboard.please_wait') }}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </form>
        </div>

        <div class="col-md-4">
            <h4>Existing Entries</h4>
            <ul class="list-group">
                @foreach(isset($items) ? $items : [] as $row)
                    <li class="list-group-item">
                        <div><strong>ID:</strong> {{ $row->id }}</div>
                        <div><strong>Phone:</strong> {{ $row->workerphone }}</div>
                        <div class="mt-2">
                            <a href="{{ route('service_site_customer_service.edit', $row->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('service_site_customer_service.destroy', $row->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
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
