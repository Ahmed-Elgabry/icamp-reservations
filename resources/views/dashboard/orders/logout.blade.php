@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.logout'))
@section('content')
@include('dashboard.orders.nav')

<div class="card mb-5 mb-xl-10">
    <form method="POST" id="logout-media-form" action="{{ route('orders.updatesignout', $order->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            {{ __('dashboard.edit_time_and_media') }}
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <div class="form-group mb-4">
                <label for="delivery_time">{{ __('dashboard.signout_time') }}</label>
                <input type="time" name="delivery_time" id="delivery_time" class="form-control"
                       value="{{ old('delivery_time', $order->delivery_time) }}">
            </div>

            <div class="form-group mb-5">
                <label for="delivery_time_notes">{{ __('dashboard.delivery_time_notes') }}</label>
                <textarea name="delivery_time_notes" id="delivery_time_notes" class="form-control"
                >{{ old('delivery_time_notes', $order->delivery_time_notes) }}</textarea>
            </div>

            <div class="row g-3">
                <!-- PHOTO (after delivery) -->
                <div class="col-md-4">
                    <label class="form-label">📷 @lang('dashboard.photo_attachment')</label>
                    <div class="media-upload-container" data-type="photo">
                        <div class="preview-image-container mb-2" style="{{ $order->image_after_delivery_url ? '' : 'display:none;' }}">
                            @if($order->image_after_delivery_url)
                                <img src="{{ $order->image_after_delivery_url }}" class="preview-image img-thumbnail" style="max-width: 100%;">
                            @endif
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media w-100">
                                <i class="bi bi-trash"></i> {{ __('dashboard.remove') }}
                            </button>
                        </div>
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="photo">
                                <i class="bi bi-camera"></i> {{ __('dashboard.capture_photo') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary upload-media">
                                <i class="bi bi-upload"></i> {{ __('dashboard.upload') }}
                            </button>
                        </div>
                        <input type="file" name="image_after_delivery" class="media-input d-none" accept="image/*">
                        <input type="hidden" name="remove_photo_logout" value="0" class="remove-flag">
                    </div>
                </div>

                <!-- AUDIO LOGOUT -->
                <div class="col-md-4">
                    <label class="form-label">🎵 @lang('dashboard.audio_attachment')</label>
                    <div class="media-upload-container" data-type="audio">
                        <div class="preview-audio-container mb-2" style="{{ $order->voice_note_logout_url ? '' : 'display:none;' }}">
                            @if($order->voice_note_logout_url)
                                <audio controls class="preview-audio w-100">
                                    <source src="{{ $order->voice_note_logout_url }}">
                                </audio>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media w-100">
                                <i class="bi bi-trash"></i> {{ __('dashboard.remove') }}
                            </button>
                        </div>
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="audio">
                                <i class="bi bi-mic"></i> {{ __('dashboard.record') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary upload-media">
                                <i class="bi bi-upload"></i> {{ __('dashboard.upload') }}
                            </button>
                        </div>
                        <input type="file" name="voice_note_logout" class="media-input d-none" accept="audio/*">
                        <input type="hidden" name="remove_audio_logout" value="0" class="remove-flag">
                    </div>
                </div>

                <!-- VIDEO LOGOUT -->
                <div class="col-md-4">
                    <label class="form-label">🎬 @lang('dashboard.video_attachment')</label>
                    <div class="media-upload-container" data-type="video">
                        <div class="preview-video-container mb-2" style="{{ $order->video_note_logout_url ? '' : 'display:none;' }}">
                            @if($order->video_note_logout_url)
                                <video controls class="preview-video w-100" height="240">
                                    <source src="{{ $order->video_note_logout_url }}">
                                </video>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media w-100">
                                <i class="bi bi-trash"></i> {{ __('dashboard.remove') }}
                            </button>
                        </div>
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="video">
                                <i class="bi bi-camera-video"></i> {{ __('dashboard.record_video') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary upload-media">
                                <i class="bi bi-upload"></i> {{ __('dashboard.upload') }}
                            </button>
                        </div>
                        <input type="file" name="video_note_logout" class="media-input d-none" accept="video/*">
                        <input type="hidden" name="remove_video_logout" value="0" class="remove-flag">
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card body-->

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-light me-2">⬅ {{ __('dashboard.cancel') }}</a>
            <button type="submit" id="submit-btn" class="btn btn-success">💾 {{ __('dashboard.save_changes') }}</button>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let isSubmitting = false;

    const mediaContainers = {
        photo: {
            input: document.querySelector('[name="image_after_delivery"]'),
            previewContainer: document.querySelector('[data-type="photo"] .preview-image-container'),
            captureBtn: document.querySelector('[data-type="photo"] .capture-media'),
            uploadBtn: document.querySelector('[data-type="photo"] .upload-media'),
            removeFlag: document.querySelector('[name="remove_photo_logout"]'),
            container: document.querySelector('[data-type="photo"]')
        },
        audio: {
            input: document.querySelector('[name="voice_note_logout"]'),
            previewContainer: document.querySelector('[data-type="audio"] .preview-audio-container'),
            captureBtn: document.querySelector('[data-type="audio"] .capture-media'),
            uploadBtn: document.querySelector('[data-type="audio"] .upload-media'),
            removeFlag: document.querySelector('[name="remove_audio_logout"]'),
            container: document.querySelector('[data-type="audio"]')
        },
        video: {
            input: document.querySelector('[name="video_note_logout"]'),
            previewContainer: document.querySelector('[data-type="video"] .preview-video-container'),
            captureBtn: document.querySelector('[data-type="video"] .capture-media'),
            uploadBtn: document.querySelector('[data-type="video"] .upload-media'),
            removeFlag: document.querySelector('[name="remove_video_logout"]'),
            container: document.querySelector('[data-type="video"]')
        }
    };

    Object.keys(mediaContainers).forEach(type => {
        const container = mediaContainers[type];
        if (!container.input) return;

        if (container.uploadBtn) container.uploadBtn.addEventListener('click', () => container.input.click());
        container.input.addEventListener('change', e => handleFileUpload(e, type, container));

        if (container.captureBtn) {
            container.captureBtn.addEventListener('click', function() {
                if (type === 'photo') capturePhoto(container);
                else toggleMediaRecording(type, container);
            });
        }

        const existingRemoveBtn = container.previewContainer.querySelector('.remove-media');
        if (existingRemoveBtn) existingRemoveBtn.addEventListener('click', () => clearMediaPreview(type, container));
    });

    document.getElementById('logout-media-form').addEventListener('submit', function() {
        if (isSubmitting) return false;
        isSubmitting = true;
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    });

    function toggleMediaRecording(type, container) {
        if (container.captureBtn.classList.contains('recording')) {
            stopMediaRecording(type, container);
            container.captureBtn.classList.remove('recording', 'btn-danger');
            container.captureBtn.classList.add('btn-primary');
            container.captureBtn.innerHTML = `<i class="bi bi-${type === 'audio' ? 'mic' : 'camera-video'}"></i> ${type === 'audio' ? 'Record' : 'Record Video'}`;
        } else {
            startMediaRecording(type, container);
            container.captureBtn.classList.add('recording', 'btn-danger');
            container.captureBtn.classList.remove('btn-primary');
            container.captureBtn.innerHTML = `<i class="bi bi-stop"></i> Stop`;
        }
    }

    function startMediaRecording(type, container) {
        const constraints = {
            audio: true,
            video: type === 'video' ? { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } } : false
        };

        navigator.mediaDevices.getUserMedia(constraints).then(stream => {
            container.mediaStream = stream;

            if (type === 'video') {
                const videoPreview = document.createElement('video');
                videoPreview.srcObject = stream;
                videoPreview.autoplay = true;
                videoPreview.muted = true;
                videoPreview.controls = false;
                videoPreview.style.width = '100%';

                container.previewContainer.innerHTML = '';
                container.previewContainer.appendChild(videoPreview);
                container.previewContainer.style.display = 'block';
                container.livePreview = videoPreview;
            }

            container.mediaRecorder = new MediaRecorder(stream);
            container.recordedChunks = [];

            container.mediaRecorder.ondataavailable = e => {
                if (e.data.size > 0) container.recordedChunks.push(e.data);
            };

            container.mediaRecorder.onstop = () => {
                const blob = new Blob(container.recordedChunks, { type: container.mediaRecorder.mimeType });
                if (container.livePreview) container.livePreview.srcObject = null;
                stream.getTracks().forEach(track => track.stop());
                createMediaPreview(blob, type, container);
            };

            container.mediaRecorder.start();
        }).catch(err => {
            console.error('Error accessing media devices:', err);
            alert('Error: ' + err.message);
        });
    }

    function stopMediaRecording(type, container) {
        if (container.mediaRecorder && container.mediaRecorder.state !== 'inactive') container.mediaRecorder.stop();
        if (container.mediaStream) container.mediaStream.getTracks().forEach(track => track.stop());
        if (container.livePreview) container.livePreview.srcObject = null;
    }

    function capturePhoto(container) {
        container.input.click();
    }

    function handleFileUpload(event, type, container) {
        const file = event.target.files[0];
        if (file) createMediaPreview(file, type, container);
    }

    function createMediaPreview(blob, type, container) {
        container.previewContainer.innerHTML = '';
        container.removeFlag.value = '0';

        const url = URL.createObjectURL(blob);
        let mediaElement;

        if (type === 'photo') {
            mediaElement = document.createElement('img');
            mediaElement.src = url;
            mediaElement.className = 'preview-image img-thumbnail';
            mediaElement.style.maxWidth = '100%';
        } else {
            mediaElement = document.createElement(type);
            mediaElement.controls = true;
            mediaElement.className = `preview-${type} w-100`;
            mediaElement.src = url;
        }

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-sm btn-danger mt-2 remove-media';
        removeBtn.innerHTML = '<i class="bi bi-trash"></i> Remove';
        removeBtn.addEventListener('click', () => clearMediaPreview(type, container));

        container.previewContainer.appendChild(mediaElement);
        container.previewContainer.appendChild(removeBtn);
        container.previewContainer.style.display = 'block';

        const dataTransfer = new DataTransfer();
        if (blob instanceof File) {
            dataTransfer.items.add(blob);
        } else {
            const fileName = `${type}-${Date.now()}.webm`;
            const file = new File([blob], fileName, { type: blob.type || (type === 'audio' ? 'audio/webm' : 'video/webm') });
            dataTransfer.items.add(file);
        }
        container.input.files = dataTransfer.files;
    }

    function clearMediaPreview(type, container) {
        container.previewContainer.innerHTML = '';
        container.previewContainer.style.display = 'none';
        container.removeFlag.value = '1';
        container.input.value = '';
        if (container.mediaRecorder) stopMediaRecording(type, container);
    }
});
</script>
@endpush

@push('css')
<style>
.media-upload-container{border:1px dashed #ddd;border-radius:4px;padding:10px;background-color:#f9f9f9}
.remove-media{width:100%}
.spinner-border-sm{width:1rem;height:1rem}
</style>
@endpush
