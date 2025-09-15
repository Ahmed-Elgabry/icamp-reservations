@extends('dashboard.layouts.app')

@section('title', __('dashboard.whatsapp_templates'))

@section('content')
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="card-title mb-0">
                    <form method="GET" action="{{ route('dashboard.whatsapp-templates.index') }}" class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <select name="type" class="form-select form-select-solid" style="min-width: 200px;" onchange="this.form.submit()">
                                <option value="">@lang('dashboard.all_types')</option>
                                @foreach(\App\Models\WhatsappMessageTemplate::getTypes() as $type => $label)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(request()->has('type'))
                            <a href="{{ route('dashboard.whatsapp-templates.index') }}" class="btn btn-light btn-sm">
                                <i class="fa-solid fa-times"></i>
                                @lang('dashboard.clear_filter')
                            </a>
                        @endif
                    </form>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('dashboard.manual-whatsapp-sends.index') }}" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-paper-plane"></i>
                        @lang('dashboard.manual_whatsapp_sends')
                    </a>
                    <a href="{{ route('dashboard.whatsapp-templates.create') }}" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        @lang('dashboard.add_new_template')
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">

            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">@lang('dashboard.name')</th>
                        <th class="min-w-125px">@lang('dashboard.type')</th>
                        <th class="min-w-125px">@lang('dashboard.template_status')</th>
                        <th class="min-w-125px">@lang('dashboard.created_at')</th>
                        <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse($templates as $template)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $template->name }}</span>
                                    @if($template->description)
                                        <span class="text-muted fs-7">{{ Str::limit($template->description, 50) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-light-info">
                                    {{ \App\Models\WhatsappMessageTemplate::getTypes()[$template->type] ?? $template->type }}
                                </span>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" 
                                           type="checkbox" 
                                           data-id="{{ $template->id }}"
                                           style="width: 3rem !important; height: 1.5rem !important;"
                                           {{ $template->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>{{ $template->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    @lang('dashboard.actions')
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="{{ route('dashboard.whatsapp-templates.show', $template->id) }}" class="menu-link px-3">@lang('dashboard.view')</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{ route('dashboard.whatsapp-templates.edit', $template->id) }}" class="menu-link px-3">@lang('dashboard.edit')</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3 delete-template" data-id="{{ $template->id }}">@lang('dashboard.delete')</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fa-solid fa-inbox fs-1 text-muted mb-3"></i>
                                    <span class="text-muted fs-6">@lang('dashboard.no_templates_found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    {!! $templates->links('pagination::bootstrap-4') !!}
                </nav>
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

@push('scripts')
<script>
$(document).ready(function() {
    let deleteTemplateId = null;

    // Status toggle
    $('.status-toggle').change(function() {
        const templateId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        const $toggle = $(this);
        
        $.ajax({
            url: `/dashboard/whatsapp-templates/${templateId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    // Revert toggle
                    $toggle.prop('checked', !isChecked);
                }
            },
            error: function() {
                toastr.error('@lang("dashboard.something_went_wrong")');
                // Revert toggle
                $toggle.prop('checked', !isChecked);
            }
        });
    });

    // Delete template
    $('.delete-template').click(function(e) {
        e.preventDefault();
        deleteTemplateId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        if (deleteTemplateId) {
            const form = $('<form>', {
                method: 'POST',
                action: `/dashboard/whatsapp-templates/${deleteTemplateId}`
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
        }
    });
});
</script>
@endpush
