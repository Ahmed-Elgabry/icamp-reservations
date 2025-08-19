
@section('pageTitle', __('dashboard.survey_settings'))

@extends('dashboard.layouts.app')
@section('content')
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Survey Settings Form-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="fw-bold">@lang('dashboard.survey_settings')</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('surveys.settings.update', 1) }}" class="form">
                    @csrf
                    @method('PUT')

                    <!--begin::Email Settings Section-->
                    <div class="mb-10">
                        <h3 class="fw-bold mb-5">@lang('dashboard.email_settings')</h3>

                        <!-- SMTP Host -->
                        <div class="mb-5">
                            <label class="form-label" for="smtp_host">@lang('dashboard.smtp_host')</label>
                            <input type="text" class="form-control @error('smtp_host') is-invalid @enderror"
                                id="smtp_host" name="smtp_host"
                                value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}"
                                placeholder="smtp.example.com">
                            @error('smtp_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SMTP Port -->
                        <div class="mb-5">
                            <label class="form-label" for="smtp_port">@lang('dashboard.smtp_port')</label>
                            <input type="number" class="form-control @error('smtp_port') is-invalid @enderror"
                                id="smtp_port" name="smtp_port"
                                value="{{ old('smtp_port', $settings['smtp_port'] ?? '587') }}"
                                placeholder="587">
                            @error('smtp_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SMTP Username -->
                        <div class="mb-5">
                            <label class="form-label" for="smtp_username">@lang('dashboard.smtp_username')</label>
                            <input type="text" class="form-control @error('smtp_username') is-invalid @enderror"
                                id="smtp_username" name="smtp_username"
                                value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}"
                                placeholder="username@example.com">
                            @error('smtp_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SMTP Password -->
                        <div class="mb-5">
                            <label class="form-label" for="smtp_password">@lang('dashboard.smtp_password')</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('smtp_password') is-invalid @enderror"
                                    id="smtp_password" name="smtp_password"
                                    value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}"
                                    placeholder="••••••••">
                            </div>
                            @error('smtp_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">@lang('dashboard.leave_empty_to_keep_current')</div>
                        </div>

                        <!-- SMTP Encryption -->
                        <div class="mb-5">
                            <label class="form-label" for="smtp_encryption">@lang('dashboard.smtp_encryption')</label>
                            <input type="text" class="form-control @error('smtp_encryption') is-invalid @enderror"
                                id="smtp_encryption" name="smtp_encryption"
                                value="{{ old('smtp_encryption', $settings['smtp_encryption'] ?? '') }}"
                                placeholder="noreply@example.com">

                            @error('smtp_encryption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- From Email -->
                        <div class="mb-5">
                            <label class="form-label" for="from_email">@lang('dashboard.from_email')</label>
                            <input type="email" class="form-control @error('from_email') is-invalid @enderror"
                                id="from_email" name="from_email"
                                value="{{ old('from_email', $settings['from_email'] ?? '') }}"
                                placeholder="noreply@example.com">
                            @error('from_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- From Name -->
                        <div class="mb-5">
                            <label class="form-label" for="from_name">@lang('dashboard.from_name')</label>
                            <input type="text" class="form-control @error('from_name') is-invalid @enderror"
                                id="from_name" name="from_name"
                                value="{{ old('from_name', $settings['from_name'] ?? '') }}"
                                placeholder="App Name">
                            @error('from_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Email Settings Section-->

                    <!--begin::Schedule Settings Section-->
                    <div class="mb-10">
                        <h3 class="fw-bold mb-5">@lang('dashboard.schedule_settings')</h3>

                        <!-- Days After Completion -->
                        <div class="mb-5">
                            <label class="form-label" for="days_after_completion">@lang('dashboard.days_after_completion')</label>
                            <input type="number" class="form-control @error('days_after_completion') is-invalid @enderror"
                                id="days_after_completion" name="days_after_completion"
                                value="{{ old('days_after_completion', $settings['days_after_completion'] ?? 0) }}"
                                min="0" max="30">
                            @error('days_after_completion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">@lang('dashboard.days_after_completion_help')</div>
                        </div>

                        <!-- Send Time -->
                        <div class="mb-5">
                            <label class="form-label" for="send_time">@lang('dashboard.send_time')</label>
                            <input type="time" class="form-control @error('send_time') is-invalid @enderror"
                                id="send_time" name="send_time"
                                value="{{ old('send_time', $settings['send_time'] ?? '15:00') }}">
                            @error('send_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">@lang('dashboard.send_time_help')</div>
                        </div>

                        <!-- Enabled -->
                        <div class="mb-5">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input @error('enabled') is-invalid @enderror"
                                    type="checkbox" id="enabled" name="enabled" value="1"
                                    {{ old('enabled', $settings['enabled'] ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enabled">
                                    @lang('dashboard.enable_survey_emails')
                                </label>
                            </div>
                            @error('enabled')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Schedule Settings Section-->

                    <!--begin::Form Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('surveys.results', 1) }}" class="btn btn-light me-3">
                            @lang('dashboard.cancel')
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">
                                @lang('dashboard.save_changes')
                            </span>
                        </button>
                    </div>
                    <!--end::Form Actions-->
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Survey Settings Form-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
@endsection
