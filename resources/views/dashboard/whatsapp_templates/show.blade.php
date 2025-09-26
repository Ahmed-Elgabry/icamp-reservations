@extends('dashboard.layouts.app')

@section('pageTitle' , __('dashboard.view_template'))

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold">{{ $template->name }}</h3>
            </div>
            <div class="card-toolbar">
                    @can('whatsapp-templates.edit')
                <a href="{{ route('whatsapp-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-pencil"></i>
                    @lang('dashboard.edit')
                </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="fw-semibold text-gray-800 fs-6 mb-2">@lang('dashboard.template_name'):</div>
                    <div class="text-muted fs-6">{{ $template->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-semibold text-gray-800 fs-6 mb-2">@lang('dashboard.template_type'):</div>
                    <span class="badge badge-light-info">
                        {{ \App\Models\WhatsappMessageTemplate::getTypes()[$template->type] ?? $template->type }}
                    </span>
                </div>
            </div>

            @if($template->description)
                <div class="mb-7">
                    <div class="fw-semibold text-gray-800 fs-6 mb-2">@lang('dashboard.description'):</div>
                    <div class="text-muted fs-6">{{ $template->description }}</div>
                </div>
            @endif

            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="fw-semibold text-gray-800 fs-6 mb-2">@lang('dashboard.status'):</div>
                    @if($template->is_active)
                        <span class="badge badge-light-success">@lang('dashboard.active')</span>
                    @else
                        <span class="badge badge-light-danger">@lang('dashboard.inactive')</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="fw-semibold text-gray-800 fs-6 mb-2">@lang('dashboard.created_at'):</div>
                    <div class="text-muted fs-6">{{ $template->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="fw-bold">@lang('dashboard.arabic_message')</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="message-preview" dir="rtl">
                                {!! nl2br(e($template->message_ar)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="fw-bold">@lang('dashboard.english_message')</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="message-preview" dir="ltr">
                                {!! nl2br(e($template->message_en)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="separator my-10"></div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="fw-bold">@lang('dashboard.arabic_preview') (@lang('dashboard.with_placeholders'))</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="message-preview" dir="rtl">
                                {!! nl2br(e($template->getProcessedMessage('ar', 'أحمد محمد', 'https://example.com/survey/123'))) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-bordered">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="fw-bold">@lang('dashboard.english_preview') (@lang('dashboard.with_placeholders'))</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="message-preview" dir="ltr">
                                {!! nl2br(e($template->getProcessedMessage('en', 'Ahmed Mohamed', 'https://example.com/survey/123'))) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('whatsapp-templates.index') }}" class="btn btn-light">
                    <i class="fa-solid fa-arrow-left"></i>
                    @lang('dashboard.back_to_list')
                </a>

                <div>
                    @can('whatsapp-templates.edit')
                    <a href="{{ route('whatsapp-templates.edit', $template->id) }}" class="btn btn-primary me-2">
                        <i class="fa-solid fa-pencil"></i>
                        @lang('dashboard.edit')
                    </a>
                    @endcan
                    @can('whatsapp-templates.destroy')
                    <button type="button" class="btn btn-danger" id="deleteTemplate" data-id="{{ $template->id }}">
                        <i class="fa-solid fa-trash"></i>
                        @lang('dashboard.delete')
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">@lang('dashboard.delete_template')</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <p>@lang('dashboard.delete_template_confirmation')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('dashboard.cancel')</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">@lang('dashboard.delete')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.message-preview {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    min-height: 150px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.5;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#deleteTemplate').click(function() {
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        const templateId = $('#deleteTemplate').data('id');

        const form = $('<form>', {
            method: 'POST',
            action: `/dashboard/whatsapp-templates/${templateId}`
        });

        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: '_method',
            value: 'DELETE'
        }));

        $('body').append(form);
        form.submit();
    });
});
</script>
@endpush
