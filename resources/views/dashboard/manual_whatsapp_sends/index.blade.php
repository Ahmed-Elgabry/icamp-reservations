@extends('dashboard.layouts.app')

@section('title', __('dashboard.manual_whatsapp_sends'))

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="card-title mb-0">
                <h3 class="fw-bold m-0">@lang('dashboard.manual_whatsapp_sends')</h3>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                @can('manual-whatsapp-sends.create')
                <a href="{{ route('manual-whatsapp-sends.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    @lang('dashboard.send_manual_message')
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">@lang('dashboard.title')</th>
                        <th class="min-w-125px">@lang('dashboard.template')</th>
                        <th class="min-w-100px">@lang('dashboard.status')</th>
                        <th class="min-w-100px">@lang('dashboard.progress')</th>
                        <th class="min-w-125px">@lang('dashboard.created_by')</th>
                        <th class="min-w-125px">@lang('dashboard.created_at')</th>
                        <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse($sends as $send)
                    <tr>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">{{ $send->title }}</span>
                                @if($send->custom_message)
                                <span class="text-muted fs-7">{{ Str::limit($send->custom_message, 50) }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light-info">
                                {{ $send->template->name }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $send->getStatusBadgeClass() }}">
                                {{ $send->getStatusLabel() }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">
                                    {{ $send->sent_count }}/{{ $send->total_count }}
                                </span>
                                @if($send->total_count > 0)
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar"
                                        style="width: {{ ($send->sent_count / $send->total_count) * 100 }}%">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-800">{{ $send->creator->name }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $send->created_at->format('Y-m-d H:i') }}</span>
                        </td>
                        <td class="text-end">
                            @can('manual-whatsapp-sends.show')
                            <a href="{{ route('manual-whatsapp-sends.show', $send) }}"
                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fa-solid fa-inbox fs-1 text-muted mb-3"></i>
                                <span class="text-muted fs-6">@lang('dashboard.no_manual_sends_found')</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sends->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-5">
            <div class="text-muted">
                @lang('dashboard.showing') {{ $sends->firstItem() }} @lang('dashboard.to') {{ $sends->lastItem() }}
                @lang('dashboard.of') {{ $sends->total() }} @lang('dashboard.results')
            </div>
            {{ $sends->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
