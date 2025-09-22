@extends('dashboard.layouts.app')

@section('title', __('dashboard.edit_template'))

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bold">@lang('dashboard.edit_template')</h3>
        </div>
    </div>

    <form action="{{ route('whatsapp-templates.update', $template->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">@lang('dashboard.template_name')</label>
                        <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                            placeholder="@lang('dashboard.enter_template_name')"
                            value="{{ old('name', $template->name) }}" required />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">@lang('dashboard.template_type')</label>
                        <select name="type" class="form-select form-select-solid" required>
                            <option value="">@lang('dashboard.select_type')</option>
                            @foreach($types as $key => $value)
                            <option value="{{ $key }}"
                                {{ old('type', $template->type) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="fv-row mb-7">
                <label class="fw-semibold fs-6 mb-2">@lang('dashboard.description')</label>
                <textarea name="description" class="form-control form-control-solid" rows="3"
                    placeholder="@lang('dashboard.enter_description')">{{ old('description', $template->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">@lang('dashboard.arabic_message')</label>
                        <textarea id="message_ar" name="message_ar" class="form-control form-control-solid" style="min-height: 200px;">{{ old('message_ar', $template->message_ar) }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">@lang('dashboard.english_message')</label>
                        <textarea id="message_en" name="message_en" class="form-control form-control-solid" style="min-height: 200px;">{{ old('message_en', $template->message_en) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="fv-row mb-7">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                        style="width: 3rem !important; height: 1.5rem !important;"
                        {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold fs-6 ms-3" for="is_active">
                        @lang('dashboard.active_template')
                    </label>
                </div>
            </div>

            <div class="alert alert-info">
                <h5>@lang('dashboard.available_placeholders'):</h5>
                <ul class="mb-0">
                    <li><strong>[اسم العميل]</strong> / <strong>[Customer Name]</strong> - @lang('dashboard.customer_name_placeholder')</li>
                    <li><strong>[رابط التقييم]</strong> / <strong>[🔗 Evaluation Link]</strong> - @lang('dashboard.evaluation_link_placeholder')</li>
                </ul>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <a href="{{ route('whatsapp-templates.index') }}" class="btn btn-light me-3">
                    @lang('dashboard.cancel')
                </a>
                <button type="submit" class="btn btn-primary">
                    @lang('dashboard.save')
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor 5 for Arabic message
        ClassicEditor
            .create(document.querySelector('#message_ar'), {
                toolbar: {
                    items: [
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', '|',
                        'undo', 'redo'
                    ]
                },
                height: '200px'
            })
            .then(editor => {
                // Set editor height
                editor.editing.view.change(writer => {
                    writer.setStyle('height', '200px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error('CKEditor 5 Arabic initialization error:', error);
            });

        // Initialize CKEditor 5 for English message
        ClassicEditor
            .create(document.querySelector('#message_en'), {
                toolbar: {
                    items: [
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', '|',
                        'undo', 'redo'
                    ]
                },
                height: '200px'
            })
            .then(editor => {
                // Set editor height
                editor.editing.view.change(writer => {
                    writer.setStyle('height', '200px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error('CKEditor 5 English initialization error:', error);
            });
    });
</script>
@endpush
