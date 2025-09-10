@extends('dashboard.layouts.app')
@section('pageTitle', isset($dailyReport) ? __('dashboard.edit_daily_report') : __('dashboard.create_daily_report'))

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header">
            <h3 class="card-title">@lang('dashboard.'.(isset($dailyReport) ? 'edit_daily_report' : 'create_daily_report'))</h3>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ isset($dailyReport) ? route('daily-reports.update', $dailyReport) : route('daily-reports.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($dailyReport)) @method('PUT') @endif

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.title')</label>
                    <div class="col-lg-8">
                        <input type="text" name="title" class="form-control" required
                               value="{{ old('title', $dailyReport->title ?? '') }}">
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.details')</label>
                    <div class="col-lg-8">
                        <textarea name="details" rows="5" class="form-control" required>{{ old('details', $dailyReport->details ?? '') }}</textarea>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                    <div class="col-lg-8">
                        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $dailyReport->notes ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Photo Attachment -->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.photo_attachment')</label>
                    <div class="col-lg-8">
                        <div id="photo-preview-container">
                            @if(isset($dailyReport) && $dailyReport->photo_attachment)
                                <div class="attachment-preview mb-2">
                                    <img src="{{ Storage::url($dailyReport->photo_attachment) }}" class="img-thumbnail" style="max-width: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger ms-2 remove-attachment" data-type="photo">
                                        @lang('dashboard.delete')
                                    </button>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="photo_attachment" class="form-control mt-2" accept="image/*" id="photo-input">
                        <input type="hidden" name="delete_photo" id="delete-photo" value="0">
                        <button type="button" class="btn btn-secondary mt-2" id="capture-photo">
                            @lang('dashboard.capture_photo')
                        </button>
                    </div>
                </div>

                <!-- Audio Attachment -->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.audio_attachment')</label>
                    <div class="col-lg-8">
                        <div id="audio-preview-container">
                            @if(isset($dailyReport) && $dailyReport->audio_attachment)
                                <div class="attachment-preview mb-2">
                                    <audio controls>
                                        <source src="{{ Storage::url($dailyReport->audio_attachment) }}">
                                    </audio>
                                    <button type="button" class="btn btn-sm btn-danger ms-2 remove-attachment" data-type="audio">
                                        @lang('dashboard.delete')
                                    </button>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="audio_attachment" class="form-control mt-2" accept="audio/*" id="audio-input">
                        <input type="hidden" name="delete_audio" id="delete-audio" value="0">
                        <button type="button" class="btn btn-info mt-2" id="record-audio">
                            <i class="fas fa-microphone"></i> @lang('dashboard.record_audio')
                        </button>
                    </div>
                </div>

                <!-- Video Attachment -->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.video_attachment')</label>
                    <div class="col-lg-8">
                        <div id="video-preview-container">
                            @if(isset($dailyReport) && $dailyReport->video_attachment)
                                <div class="attachment-preview mb-2">
                                    <video width="320" controls>
                                        <source src="{{ Storage::url($dailyReport->video_attachment) }}">
                                    </video>
                                    <button type="button" class="btn btn-sm btn-danger ms-2 remove-attachment" data-type="video">
                                        @lang('dashboard.delete')
                                    </button>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="video_attachment" class="form-control mt-2" accept="video/*" id="video-input">
                        <input type="hidden" name="delete_video" id="delete-video" value="0">
                        <div class="mt-2">
                            <button type="button" class="btn btn-info me-2" id="record-video">
                                <i class="fas fa-video"></i> @lang('dashboard.record_video')
                            </button>
                            <button type="button" class="btn btn-warning me-2" id="stop-video" style="display: none;">
                                @lang('dashboard.stop_recording')
                            </button>
                        </div>
                        <video id="live-video-preview" class="mt-2" style="display: none; max-width: 100%;"></video>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary">
                        @lang('dashboard.'.(isset($dailyReport) ? 'update_report' : 'save_report'))
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Media Elements
            const mediaContainers = {
                photo: {
                    input: document.getElementById('photo-input'),
                    preview: document.getElementById('photo-preview-container'),
                    delete: document.getElementById('delete-photo'),
                    captureBtn: document.getElementById('capture-photo')
                },
                audio: {
                    input: document.getElementById('audio-input'),
                    preview: document.getElementById('audio-preview-container'),
                    delete: document.getElementById('delete-audio'),
                    recordBtn: document.getElementById('record-audio')
                },
                video: {
                    input: document.getElementById('video-input'),
                    preview: document.getElementById('video-preview-container'),
                    delete: document.getElementById('delete-video'),
                    recordBtn: document.getElementById('record-video'),
                    stopBtn: document.getElementById('stop-video'),
                    livePreview: document.getElementById('live-video-preview')
                }
            };

            // Initialize media handlers
            initializeMediaHandlers();

            function initializeMediaHandlers() {
                // Photo Handling
                if (mediaContainers.photo.captureBtn) {
                    mediaContainers.photo.captureBtn.addEventListener('click', function() {
                        mediaContainers.photo.input.click();
                    });
                }

                mediaContainers.photo.input.addEventListener('change', function(e) {
                    handleFileUpload(e, 'photo');
                });

                // Audio Recording
                if (mediaContainers.audio.recordBtn) {
                    let audioRecorder;
                    let audioChunks = [];

                    mediaContainers.audio.recordBtn.addEventListener('click', async function() {
                        if (audioRecorder && audioRecorder.state === 'recording') {
                            audioRecorder.stop();
                            updateMediaButton('audio', false);
                        } else {
                            try {
                                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                                audioRecorder = new MediaRecorder(stream);
                                audioChunks = [];

                                updateMediaButton('audio', true);

                                audioRecorder.ondataavailable = event => {
                                    if (event.data.size > 0) {
                                        audioChunks.push(event.data);
                                    }
                                };

                                audioRecorder.onstop = () => {
                                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                                    createMediaPreview(audioBlob, 'audio');
                                    stream.getTracks().forEach(track => track.stop());
                                };

                                audioRecorder.start();
                            } catch (error) {
                                console.error('Error accessing microphone:', error);
                                alert('@lang('dashboard.microphone_error')');
                            }
                        }
                    });
                }

                // Video Recording with Live Preview
                if (mediaContainers.video.recordBtn) {
                    let videoRecorder;
                    let videoChunks = [];
                    let videoStream;

                    mediaContainers.video.recordBtn.addEventListener('click', async function() {
                        if (videoRecorder && videoRecorder.state === 'recording') {
                            videoRecorder.stop();
                            updateMediaButton('video', false);
                            mediaContainers.video.stopBtn.style.display = 'none';
                            mediaContainers.video.livePreview.style.display = 'none';
                        } else {
                            try {
                                videoStream = await navigator.mediaDevices.getUserMedia({
                                    video: {
                                        facingMode: { ideal: 'environment' }
                                    },
                                    audio: true
                                });

                                // Show live preview
                                mediaContainers.video.livePreview.srcObject = videoStream;
                                mediaContainers.video.livePreview.style.display = 'block';
                                mediaContainers.video.livePreview.play();

                                videoRecorder = new MediaRecorder(videoStream, {
                                    mimeType: 'video/webm'
                                });

                                videoChunks = [];

                                updateMediaButton('video', true);
                                mediaContainers.video.stopBtn.style.display = 'inline-block';

                                videoRecorder.ondataavailable = event => {
                                    if (event.data.size > 0) {
                                        videoChunks.push(event.data);
                                    }
                                };

                                videoRecorder.onstop = () => {
                                    const videoBlob = new Blob(videoChunks, { type: 'video/webm' });
                                    createMediaPreview(videoBlob, 'video');

                                    // Stop and clear live preview
                                    mediaContainers.video.livePreview.srcObject = null;
                                    mediaContainers.video.livePreview.style.display = 'none';

                                    if (videoStream) {
                                        videoStream.getTracks().forEach(track => track.stop());
                                    }
                                };

                                videoRecorder.start();
                            } catch (error) {
                                console.error('Error accessing camera:', error);
                                alert('@lang('dashboard.camera_error')');
                            }
                        }
                    });

                    // Stop video button
                    mediaContainers.video.stopBtn.addEventListener('click', function() {
                        if (videoRecorder && videoRecorder.state === 'recording') {
                            videoRecorder.stop();
                            updateMediaButton('video', false);
                            mediaContainers.video.stopBtn.style.display = 'none';
                            mediaContainers.video.livePreview.style.display = 'none';
                        }
                    });
                }

                // Handle file inputs for all media types
                Object.keys(mediaContainers).forEach(type => {
                    if (mediaContainers[type].input) {
                        mediaContainers[type].input.addEventListener('change', function(e) {
                            if (e.target.files.length) {
                                createMediaPreview(e.target.files[0], type);
                            }
                        });
                    }
                });

                // Handle existing attachment deletions
                document.querySelectorAll('.remove-attachment').forEach(button => {
                    button.addEventListener('click', function() {
                        const type = this.dataset.type;
                        mediaContainers[type].preview.innerHTML = '';
                        mediaContainers[type].delete.value = '1';
                    });
                });
            }

            function updateMediaButton(type, isRecording) {
                const btn = mediaContainers[type].recordBtn;
                if (type === 'audio') {
                    btn.innerHTML = isRecording
                        ? '<i class="fas fa-stop"></i> @lang('dashboard.stop_recording')'
                        : '<i class="fas fa-microphone"></i> @lang('dashboard.record_audio')';
                    btn.classList.toggle('btn-danger', isRecording);
                    btn.classList.toggle('btn-info', !isRecording);
                } else if (type === 'video') {
                    btn.innerHTML = isRecording
                        ? '<i class="fas fa-stop"></i> @lang('dashboard.stop_recording')'
                        : '<i class="fas fa-video"></i> @lang('dashboard.record_video')';
                    btn.classList.toggle('btn-danger', isRecording);
                    btn.classList.toggle('btn-info', !isRecording);
                }
            }

            function createMediaPreview(fileOrBlob, type) {
                const container = mediaContainers[type].preview;
                const deleteInput = mediaContainers[type].delete;

                // Clear any existing preview
                container.innerHTML = '';
                deleteInput.value = '0';

                // Create preview element based on media type
                let previewElement;
                const url = fileOrBlob instanceof Blob ? URL.createObjectURL(fileOrBlob) : URL.createObjectURL(new Blob([fileOrBlob]));

                if (type === 'photo') {
                    previewElement = `<img src="${url}" class="img-thumbnail" style="max-width: 200px;">`;
                } else if (type === 'audio') {
                    previewElement = `<audio controls><source src="${url}"></audio>`;
                } else if (type === 'video') {
                    previewElement = `<video width="320" controls><source src="${url}"></video>`;
                }

                // Add delete button
                previewElement += `
            <button type="button" class="btn btn-sm btn-danger ms-2 remove-attachment" data-type="${type}">
                @lang('dashboard.delete')
                </button>
`;

                // Insert into container
                const div = document.createElement('div');
                div.className = 'attachment-preview mb-2';
                div.innerHTML = previewElement;
                container.appendChild(div);

                // Set up delete handler
                div.querySelector('.remove-attachment').addEventListener('click', function() {
                    container.innerHTML = '';
                    deleteInput.value = '1';
                });

                // Create file and assign to input if it's a Blob (from recording)
                if (fileOrBlob instanceof Blob) {
                    const file = new File([fileOrBlob], `${type}-recording.${type === 'audio' ? 'wav' : 'webm'}`, {
                        type: fileOrBlob.type
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    mediaContainers[type].input.files = dataTransfer.files;
                }
            }

            function handleFileUpload(event, type) {
                if (event.target.files.length) {
                    const file = event.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        createMediaPreview(file, type);
                    };

                    if (type === 'photo') {
                        reader.readAsDataURL(file);
                    } else {
                        // For audio/video files, we'll let the native controls handle playback
                        createMediaPreview(file, type);
                    }
                }
            }
        });
    </script>
@endpush
