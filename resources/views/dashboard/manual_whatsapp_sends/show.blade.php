@extends('dashboard.layouts.app')

@section('title', __('dashboard.manual_whatsapp_send_details'))

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bold m-0">@lang('dashboard.manual_whatsapp_send_details')</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('manual-whatsapp-sends.index') }}" class="btn btn-light">
                <i class="fa-solid fa-arrow-left"></i>
                @lang('dashboard.back')
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Basic Information -->
        <div class="row mb-8">
            <div class="col-12">
                <h4 class="fw-bold mb-4">@lang('dashboard.basic_information')</h4>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">@lang('dashboard.title')</label>
                    <p class="text-gray-800">{{ $manualWhatsappSend->title }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">@lang('dashboard.template')</label>
                    <p class="text-gray-800">{{ $manualWhatsappSend->template ? $manualWhatsappSend->template->name : '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">@lang('dashboard.status')</label>
                    <p>
                        <span class="badge {{ $manualWhatsappSend->getStatusBadgeClass() }}">
                            {{ $manualWhatsappSend->getStatusLabel() }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">@lang('dashboard.created_by')</label>
                    <p class="text-gray-800">{{ $manualWhatsappSend->creator ? $manualWhatsappSend->creator->name : '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">@lang('dashboard.created_at')</label>
                    <p class="text-gray-800">{{ $manualWhatsappSend->created_at ? $manualWhatsappSend->created_at->format('Y-m-d H:i:s') : '-' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-bold">@lang('dashboard.updated_at')</label>
                    <p class="text-gray-800">{{ $manualWhatsappSend->updated_at ? $manualWhatsappSend->updated_at->format('Y-m-d H:i:s') : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Progress Information -->
        <div class="row mb-8">
            <div class="col-12">
                <h4 class="fw-bold mb-4">@lang('dashboard.progress_information')</h4>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2x fw-bold text-primary">{{ $manualWhatsappSend->total_count }}</div>
                    <div class="text-muted">@lang('dashboard.total_messages')</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2x fw-bold text-success">{{ $manualWhatsappSend->sent_count }}</div>
                    <div class="text-muted">@lang('dashboard.sent_messages')</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2x fw-bold text-danger">{{ $manualWhatsappSend->failed_count }}</div>
                    <div class="text-muted">@lang('dashboard.failed_messages')</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2x fw-bold text-info">
                        {{ $manualWhatsappSend->total_count > 0 ? round(($manualWhatsappSend->sent_count / $manualWhatsappSend->total_count) * 100, 1) : 0 }}%
                    </div>
                    <div class="text-muted">@lang('dashboard.success_rate')</div>
                </div>
            </div>
        </div>

        <!-- Custom Message -->
        @if($manualWhatsappSend->custom_message)
        <div class="row mb-8">
            <div class="col-12">
                <h4 class="fw-bold mb-4">@lang('dashboard.custom_message')</h4>
                <div class="bg-light p-4 rounded">
                    <p class="mb-0">{{ $manualWhatsappSend->custom_message }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Attachments -->
        @if($manualWhatsappSend->attachments && count($manualWhatsappSend->attachments) > 0)
        <div class="row mb-8">
            <div class="col-12">
                <h4 class="fw-bold mb-4">@lang('dashboard.attachments')</h4>
                <div class="row">
                    @foreach($manualWhatsappSend->attachments as $attachment)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-file fs-1 text-primary mb-3"></i>
                                <p class="mb-2">{{ basename($attachment) }}</p>
                                <a href="{{ Storage::disk('public')->url($attachment) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-download"></i>
                                    @lang('dashboard.download')
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Send Results -->
        @if($manualWhatsappSend->send_results && count($manualWhatsappSend->send_results) > 0)
        <div class="row mb-8">
            <div class="col-12">
                <h4 class="fw-bold mb-4">@lang('dashboard.send_results')</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>@lang('dashboard.phone_number')</th>
                                <th>@lang('dashboard.name')</th>
                                <th>@lang('dashboard.status')</th>
                                <th>@lang('dashboard.message')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($manualWhatsappSend->send_results as $result)
                            <tr>
                                <td>{{ $result['phone'] }}</td>
                                <td>{{ $result['name'] }}</td>
                                <td>
                                    <span class="badge {{ $result['status'] == 'success' ? 'badge-light-success' : 'badge-light-danger' }}">
                                        {{ $result['status'] == 'success' ? __('dashboard.success') : __('dashboard.failed') }}
                                    </span>
                                </td>
                                <td>{{ $result['message'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if($manualWhatsappSend->error_message)
        <div class="row mb-8">
            <div class="col-12">
                <h4 class="fw-bold mb-4 text-danger">@lang('dashboard.error_message')</h4>
                <div class="alert alert-danger">
                    <p class="mb-0">{{ $manualWhatsappSend->error_message }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
