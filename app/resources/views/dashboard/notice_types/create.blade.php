@extends('dashboard.layouts.app')

@section('pageTitle', isset($noticeType) ? __('dashboard.edit_notice_type') : __('dashboard.add_notice_type'))

@section('content')
    <div class="card card-flush">
        <div class="card-header mt-5 mb-0">
            <h2>{{ isset($noticeType) ? __('dashboard.edit_notice_type') : __('dashboard.add_notice_type') }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ isset($noticeType) ? route('notice-types.update', $noticeType->id) : route('notice-types.store') }}" method="POST">
                @csrf
                @if(isset($noticeType))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">@lang('dashboard.name') *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $noticeType->name ?? '') }}" required>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" id="is_active"
                        {{ (old('is_active', $noticeType->is_active ?? true) ? 'checked' : '') }}>
                    <label class="form-check-label" for="is_active">@lang('dashboard.active')</label>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                        @lang('dashboard.save')
                    </button>
                    <a href="{{ route('notice-types.index') }}" class="btn btn-light">
                        @lang('dashboard.cancel')
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
