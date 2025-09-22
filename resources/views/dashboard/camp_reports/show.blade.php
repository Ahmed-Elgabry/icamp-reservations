@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.camp_report_details'))

@section('content')
<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <h3 class="card-title">@lang('dashboard.camp_report_details')</h3>
        <div class="card-toolbar">
            @can('camp-reports.edit')
            <a href="{{ route('camp-reports.edit', $campReport) }}" class="btn btn-primary me-2">
                @lang('dashboard.edit')
            </a>
            @endcan
            <a href="{{ route('camp-reports.index') }}" class="btn btn-secondary">
                @lang('dashboard.back_to_list')
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-5 mb-8">
            <!-- Report Metadata -->
            <div class="col-md-6">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-4">
                        <span class="bullet bg-primary me-3"></span>
                        <div>
                            <span class="text-gray-600 fs-6">@lang('dashboard.report_date')</span>
                            <span class="fw-bold fs-6 d-block">{{ $campReport->report_date->format('Y-m-d') }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <span class="bullet bg-success me-3"></span>
                        <div>
                            <span class="text-gray-600 fs-6">@lang('dashboard.service')</span>
                            <span class="fw-bold fs-6 d-block">{{ $campReport->service->name ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-4">
                        <span class="bullet bg-info me-3"></span>
                        <div>
                            <span class="text-gray-600 fs-6">@lang('dashboard.camp_name')</span>
                            <span class="fw-bold fs-6 d-block">{{ $campReport->camp_name ?? ''}}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <span class="bullet bg-warning me-3"></span>
                        <div>
                            <span class="text-gray-600 fs-6">@lang('dashboard.created_by')</span>
                            <span class="fw-bold fs-6 d-block">{{ $campReport->creator->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($campReport->general_notes)
        <div class="mb-1">
            <p class="fw-bold text-primary">@lang('dashboard.general_notes'):</p>
            <span class="text-dark">{!! nl2br(e($campReport->general_notes)) !!}</span>
        </div>
        @endif
        <hr style="height: 4px;border: none;">
        @if($campReport->items->count())
        <div class="mt-4">
            <h5 class="fw-bold mb-3">@lang('dashboard.report_items')</h5>
            <div class="list-group">
                @foreach($campReport->items as $item)
                <div class="list-group-item border rounded p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-primary">{{ $item->item_name }}</strong>
                    </div>

                    <div class="row g-3">
                        @if($item->notes)
                        <div class="col-md-3">
                            <small class="text-muted d-block">@lang('dashboard.notes')</small>
                            <div class="small">{!! nl2br(e($item->notes)) !!}</div>
                        </div>
                        @endif

                        @if($item->photo_path)
                        <div class="col-md-3">
                            <small class="text-muted d-block">@lang('dashboard.photo_attachment')</small>
                            <img src="{{ Storage::url($item->photo_path) }}"
                                class="img-thumbnail"
                                style="max-height: 100px; object-fit: cover;">
                        </div>
                        @endif

                        @if($item->audio_path)
                        <div class="col-md-3">
                            <small class="text-muted d-block">@lang('dashboard.audio_attachment')</small>
                            <audio controls style="width: 100%; height: 30px;">
                                <source src="{{ Storage::url($item->audio_path) }}">
                            </audio>
                        </div>
                        @endif

                        @if($item->video_path)
                        <div class="col-md-3">
                            <small class="text-muted d-block">@lang('dashboard.video_attachment')</small>
                            <video controls
                                style="max-height: 100px; width: 100%; border-radius: 4px;">
                                <source src="{{ Storage::url($item->video_path) }}">
                            </video>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
