@extends('dashboard.layouts.app')
@section('pageTitle', isset($meeting) ? __('dashboard.edit_meeting') : __('dashboard.create_meeting'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">
                    {{ isset($meeting) ? __('dashboard.edit_meeting') : __('dashboard.create_meeting') }}
                </span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('meetings.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_meetings')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form action="{{ isset($meeting) ? route('meetings.update', $meeting->id) : route('meetings.store') }}" method="POST">
                @csrf
                @if(isset($meeting))
                    @method('PUT')
                @endif

                <!-- Basic Information Section -->
                <div class="mb-10">
                    <h4 class="text-gray-800 mb-5">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        @lang('dashboard.basic_information')
                    </h4>

                    <div class="row g-5">
                        <div class="col-md-4">
                            <label class="form-label required">@lang('dashboard.date')</label>
                            <input type="date" name="date" class="form-control form-control-solid"
                                   value="{{ old('date', isset($meeting->date) ? \Carbon\Carbon::parse($meeting->date)->format('Y-m-d') : '') }}"
                                   required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">@lang('dashboard.start_time')</label>
                            <input type="time" name="start_time" class="form-control form-control-solid"
                                   value="{{ old('start_time', $meeting->start_time ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">@lang('dashboard.end_time')</label>
                            <input type="time" name="end_time" class="form-control form-control-solid"
                                   value="{{ old('end_time', $meeting->end_time ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row g-5 mt-3">
                        <div class="col-md-4">
                            <label class="form-label required">@lang('dashboard.meeting_location')</label>
                            <input type="text" name="location" class="form-control form-control-solid"
                                   value="{{ old('location', $meeting->location ?? '') }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">@lang('dashboard.notes')</label>
                            <textarea name="notes" class="form-control form-control-solid" rows="1">{{ old('notes', $meeting->notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Attendees Section -->
                <div class="mb-10">
                    <h4 class="text-gray-800 mb-5 required">
                        <i class="fas fa-users text-primary me-2"></i>
                        @lang('dashboard.attendees')
                    </h4>

                    <div class="mb-5">
{{--                        <label class="form-label required">@lang('dashboard.select_attendees')</label>--}}
                        <select name="attendees[]" id="attendees-select" class="form-select form-select-solid" multiple="multiple" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                        @if(isset($meeting) && $meeting->attendees->contains('user_id', $user->id))
                                            selected
                                        @endif
                                        @if(is_array(old('attendees')) && in_array($user->id, old('attendees')))
                                            selected
                                    @endif
                                >
                                    {{ $user->name }} ({{ $user->job }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Topics Section -->
                <div class="mb-10">
                    <h4 class="text-gray-800 mb-5">
                        <i class="fas fa-list-ul text-primary me-2"></i>
                        @lang('dashboard.topics')
                    </h4>

                    <div id="topics-container">
                        @if(isset($meeting) && $meeting->topics->count() > 0)
                            @foreach($meeting->topics as $index => $topic)
                                <div class="topic-row card card-bordered mb-5">
                                    <div class="card-body">
                                        <div class="row mb-4 g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">@lang('dashboard.topic')</label>
                                                <input type="text" name="topics[{{ $index }}][topic]"
                                                       class="form-control form-control-solid"
                                                       value="{{ old("topics.$index.topic", $topic->topic) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">@lang('dashboard.assigned_to')</label>
                                                <select name="topics[{{ $index }}][assigned_to]"
                                                        class="form-select form-select-solid select2">
                                                    <option value="">@lang('dashboard.select_user')</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ $topic->assigned_to == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-4 g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">@lang('dashboard.discussion')</label>
                                                <textarea name="topics[{{ $index }}][discussion]"
                                                          class="form-control form-control-solid" rows="2">{{ old("topics.$index.discussion", $topic->discussion) }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">@lang('dashboard.action_items')</label>
                                                <textarea name="topics[{{ $index }}][action_items]"
                                                          class="form-control form-control-solid" rows="2">{{ old("topics.$index.action_items", $topic->action_items) }}</textarea>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-light-danger remove-topic">
                                            <i class="fas fa-trash me-2"></i>@lang('dashboard.remove_topic')
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="topic-row card card-bordered mb-5">
                                <div class="card-body">
                                    <div class="row mb-4 g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('dashboard.topic')</label>
                                            <input type="text" name="topics[0][topic]" class="form-control form-control-solid">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('dashboard.assigned_to')</label>
                                            <select name="topics[0][assigned_to]" class="form-select form-select-solid select2">
                                                <option value="">@lang('dashboard.select_user')</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4 g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('dashboard.discussion')</label>
                                            <textarea name="topics[0][discussion]" class="form-control form-control-solid" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('dashboard.action_items')</label>
                                            <textarea name="topics[0][action_items]" class="form-control form-control-solid" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-light-danger remove-topic">
                                        <i class="fas fa-trash me-2"></i>@lang('dashboard.remove_topic')
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button type="button" class="btn btn-sm btn-light-primary" id="add-topic">
                        <i class="fas fa-plus me-2"></i>@lang('dashboard.add_topic')
                    </button>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        {{ __('dashboard.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#attendees-select').select2({
                placeholder: "@lang('dashboard.select_attendees_placeholder')",
                allowClear: true,
                width: '100%',
                closeOnSelect: true
            });

            // Topic counter
            let topicCount = {{ isset($meeting) ? $meeting->topics->count() : 1 }};

            // Add topic
            $('#add-topic').click(function() {
                const html = `
                <div class="topic-row card card-bordered mb-5">
                    <div class="card-body">
                        <div class="row mb-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('dashboard.topic')</label>
                                <input type="text" name="topics[${topicCount}][topic]" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">@lang('dashboard.assigned_to')</label>
                                <select name="topics[${topicCount}][assigned_to]" class="form-select form-select-solid select2">
                                    <option value="">@lang('dashboard.select_user')</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4 g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('dashboard.discussion')</label>
                                <textarea name="topics[0][discussion]" class="form-control form-control-solid" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">@lang('dashboard.action_items')</label>
                                <textarea name="topics[0][action_items]" class="form-control form-control-solid" rows="2"></textarea>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-light-danger remove-topic">
                            <i class="fas fa-trash me-2"></i>@lang('dashboard.remove_topic')
                        </button>
                    </div>
                </div>
                `;
                $('#topics-container').append(html);
                topicCount++;
            });

            $(document).on('click', '.remove-topic', function() {
                $(this).closest('.topic-row').remove();
            });
        });
    </script>
@endpush

