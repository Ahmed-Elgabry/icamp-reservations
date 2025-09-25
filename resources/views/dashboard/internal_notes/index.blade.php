@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.internal_notes'))
@section('content')
<div class="container">
    <h1>{{ __('dashboard.internal_notes') }}</h1>
@can('internal-notes.create')
    <div class="row mb-4">
        <div class="col-md-6">
            <form class="form store" id="internal-note-form" action="{{ isset($internalNote) ? route('internal-notes.update', $internalNote->id) : route('internal-notes.store') }}" method="POST" enctype="multipart/form-data" data-kt-redirect="{{ url()->current() }}">
                @csrf
                @if(isset($internalNote))
                    @method('PUT')
                @endif
                <input type="hidden" name="id" id="note-id" value="{{ isset($internalNote) ? $internalNote->id : '' }}">
                <div class="mb-3">
                    <label class="form-label">{{ __('dashboard.note_name') }}</label>
                    <input type="text" name="note_name" id="note-name" class="form-control" value="{{ old('note_name', isset($internalNote) ? $internalNote->note_name : '') }}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('dashboard.note_content') }}</label>
                    <!-- Quill editor container -->
                    <div id="editor-note-content" class="form-control" style="min-height:140px">{!! old('note_content', isset($internalNote) ? $internalNote->note_content : '') !!}</div>
                    <!-- Hidden textarea that will receive HTML from Quill on submit -->
                    <textarea name="note_content" id="note-content" class="d-none">{{ old('note_content', isset($internalNote) ? $internalNote->note_content : '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" id="kt_ecommerce_add_product_submit">{{ isset($internalNote) ? __('dashboard.update_item') : __('dashboard.create_item') }}</button>
            </form>
        </div>
    </div>
@endcan
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="kt_ecommerce_category_table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('dashboard.note_name') }}</th>
                            <th>{{ __('dashboard.note_content') }}</th>
                            <th>{{ __('dashboard.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="notes-table-body">
                        @forelse(isset($notes) ? $notes : [] as $note)
                            <tr data-id="{{ $note->id }}">
                                <td class="note-name">{{ $note->note_name }}</td>
                                <td class="note-content">{!! $note->note_content !!}</td>
                                <td>
                                    @can('internal-notes.edit')

                                    <a href="{{ route('internal-notes.edit', $note->id) }}" class="btn btn-sm btn-secondary">{{ __('dashboard.edit') }}</a>
                                    @endcan
                                    @can('internal-notes.destroy')
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        data-kt-ecommerce-category-filter="delete_row"
                                        data-id="{{ $note->id }}"
                                        data-url="{{ route('internal-notes.destroy', $note->id) }}"
                                    >{{ __('dashboard.delete') }}</button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('dashboard.no_data_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        // Initialize Quill for the note content with extended toolbar, fonts, colors and background support
        if (typeof Quill !== 'undefined') {
            // Register custom font whitelist
            var Font = Quill.import('formats/font');
            Font.whitelist = ['arial', 'roboto', 'times-new-roman', 'courier-new', 'open-sans', 'serif', 'monospace'];
            Quill.register(Font, true);
            // Add CSS so the font names in the picker map to actual font-family values
            var style = document.createElement('style');
            style.type = 'text/css';
            style.innerHTML = "\
                .ql-font-roboto { font-family: 'Roboto', sans-serif; }\
                .ql-font-arial { font-family: Arial, Helvetica, sans-serif; }\
                .ql-font-times-new-roman { font-family: 'Times New Roman', Times, serif; }\
                .ql-font-courier-new { font-family: 'Courier New', Courier, monospace; }\
                .ql-font-open-sans { font-family: 'Open Sans', sans-serif; }\
                .ql-font-serif { font-family: serif; }\
                .ql-font-monospace { font-family: monospace; }";
            document.head.appendChild(style);

            // Toolbar with many options: fonts, sizes, colors, background, align, lists, scripts, media, code, clean...
            var toolbarOptions = [
                [{ 'font': Font.whitelist }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                ['blockquote', 'code-block', 'formula'],
                ['link', 'image', 'video'],
                ['clean']
            ];

            var quill = new Quill('#editor-note-content', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions,
                    clipboard: {
                        matchVisual: false
                    }
                }
            });

            // If there's initial HTML in the hidden textarea, load it into Quill
            var hiddenTextarea = document.getElementById('note-content');
            if (hiddenTextarea && hiddenTextarea.value.trim() !== '') {
                quill.root.innerHTML = hiddenTextarea.value;
            }

            // On form submit, copy Quill HTML into the hidden textarea
            var form = document.getElementById('internal-note-form');
            if (form) {
                form.addEventListener('submit', function(e){
                    // Put the editor HTML into the hidden textarea so it posts with the form
                    hiddenTextarea.value = quill.root.innerHTML;
                });
            }
        }
    });
    </script>
@endsection
