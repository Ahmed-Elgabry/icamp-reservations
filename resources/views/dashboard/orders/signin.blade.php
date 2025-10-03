@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.signin'))
@section('content')
@include('dashboard.orders.nav')

<div class="card mb-5 mb-xl-10">
    <form method="POST" id="media-form" action="{{ route('orders.updatesignin', $order->id) }}" enctype="multipart/form-data">
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
                <label for="time_of_receipt">{{ __('dashboard.signin_time') }}</label>
                <input type="time" name="time_of_receipt" id="time_of_receipt" class="form-control"
                       value="{{ old('time_of_receipt', $order->time_of_receipt) }}">
            </div>

            <div class="form-group mb-5">
                <label for="time_of_receipt_notes">{{ __('dashboard.receiving_time_notes') }}</label>
                <textarea name="time_of_receipt_notes" id="time_of_receipt_notes" class="form-control"
                >{{ old('time_of_receipt_notes', $order->time_of_receipt_notes) }}</textarea>
            </div>

            <div class="row g-3">
                <!-- PHOTO (before receiving) -->
                <div class="col-md-4">
                    <label class="form-label">📷 @lang('dashboard.photo_attachment')</label>
                    <div class="media-upload-container" data-type="photo">
                        <div class="preview-image-container mb-2" style="{{ $order->image_before_receiving_url ? '' : 'display:none;' }}">
                            @if($order->image_before_receiving_url)
                                <img src="{{ $order->image_before_receiving_url }}" class="preview-image img-thumbnail" style="max-width: 100%;">
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
                        <input type="file" name="image_before_receiving" class="media-input d-none" accept="image/*">
                        <input type="hidden" name="remove_photo" value="0" class="remove-flag">
                    </div>
                </div>

                <!-- AUDIO (signin) -->
                <div class="col-md-4">
                    <label class="form-label">🎵 @lang('dashboard.audio_attachment')</label>
                    <div class="media-upload-container" data-type="audio">
                        <div class="preview-audio-container mb-2" style="{{ $order->voice_note_url ? '' : 'display:none;' }}">
                            @if($order->voice_note_url)
                                <audio controls class="preview-audio w-100">
                                    <source src="{{ $order->voice_note_url }}">
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
                        <input type="file" name="voice_note" class="media-input d-none" accept="audio/*">
                        <input type="hidden" name="remove_audio" value="0" class="remove-flag">
                    </div>
                </div>

                <!-- VIDEO (signin) -->
                <div class="col-md-4">
                    <label class="form-label">🎬 @lang('dashboard.video_attachment')</label>
                    <div class="media-upload-container" data-type="video">
                        <div class="preview-video-container mb-2" style="{{ $order->video_note_url ? '' : 'display:none;' }}">
                            @if($order->video_note_url)
                                <video controls class="preview-video w-100" height="240">
                                    <source src="{{ $order->video_note_url }}">
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
                        <input type="file" name="video_note" class="media-input d-none" accept="video/*">
                        <input type="hidden" name="remove_video" value="0" class="remove-flag">
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

    function initializeMediaHandlers() {
        const mediaContainers = {
            photo: {
                input: document.querySelector('[data-type="photo"] .media-input'),
                previewContainer: document.querySelector('[data-type="photo"] .preview-image-container'),
                captureBtn: document.querySelector('[data-type="photo"] .capture-media'),
                uploadBtn: document.querySelector('[data-type="photo"] .upload-media'),
                removeFlag: document.querySelector('[data-type="photo"] .remove-flag'),
                container: document.querySelector('[data-type="photo"]')
            },
            audio: {
                input: document.querySelector('[data-type="audio"] .media-input'),
                previewContainer: document.querySelector('[data-type="audio"] .preview-audio-container'),
                captureBtn: document.querySelector('[data-type="audio"] .capture-media'),
                uploadBtn: document.querySelector('[data-type="audio"] .upload-media'),
                removeFlag: document.querySelector('[data-type="audio"] .remove-flag'),
                container: document.querySelector('[data-type="audio"]')
            },
            video: {
                input: document.querySelector('[data-type="video"] .media-input'),
                previewContainer: document.querySelector('[data-type="video"] .preview-video-container'),
                captureBtn: document.querySelector('[data-type="video"] .capture-media'),
                uploadBtn: document.querySelector('[data-type="video"] .upload-media'),
                removeFlag: document.querySelector('[data-type="video"] .remove-flag'),
                container: document.querySelector('[data-type="video"]')
            }
        };

        Object.keys(mediaContainers).forEach(type => {
            const container = mediaContainers[type];
            if (!container.input) return;

            if (container.uploadBtn) {
                container.uploadBtn.addEventListener('click', () => container.input.click());
            }

            container.input.addEventListener('change', e => handleFileUpload(e, type, container));

            if (container.captureBtn) {
                container.captureBtn.addEventListener('click', function() {
                    if (type === 'photo') startPhotoCapture(container);
                    else toggleMediaRecording(type, container);
                });
            }

            const existingRemoveBtn = container.previewContainer.querySelector('.remove-media');
            if (existingRemoveBtn) {
                existingRemoveBtn.addEventListener('click', () => clearMediaPreview(type, container));
            }
        });
    }

    initializeMediaHandlers();

    document.getElementById('media-form').addEventListener('submit', function() {
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

        navigator.mediaDevices.getUserMedia(constraints)
            .then(stream => {
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
            })
            .catch(err => {
                console.error('Error accessing media devices:', err);
                alert('Error: ' + err.message);
            });
    }

    function stopMediaRecording(type, container) {
        if (container.mediaRecorder && container.mediaRecorder.state !== 'inactive') container.mediaRecorder.stop();
        if (container.mediaStream) container.mediaStream.getTracks().forEach(track => track.stop());
        if (container.livePreview) container.livePreview.srcObject = null;
    }

    // ✅ NEW: Photo Capture using getUserMedia
    function startPhotoCapture(container) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                const video = document.createElement('video');
                video.srcObject = stream;
                video.autoplay = true;
                video.style.width = '100%';

                const snapBtn = document.createElement('button');
                snapBtn.type = 'button';
                snapBtn.className = 'btn btn-sm btn-success mt-2';
                snapBtn.innerHTML = '<i class="bi bi-camera"></i> Snap';

                container.previewContainer.innerHTML = '';
                container.previewContainer.appendChild(video);
                container.previewContainer.appendChild(snapBtn);
                container.previewContainer.style.display = 'block';

                snapBtn.addEventListener('click', function() {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);

                    stream.getTracks().forEach(track => track.stop());

                    canvas.toBlob(function(blob) {
                        createMediaPreview(blob, 'photo', container);
                    }, 'image/png');
                });
            })
            .catch(err => {
                console.error('Error accessing camera:', err);
                alert('Error: ' + err.message);
            });
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
            const fileName = `${type}-${Date.now()}.${type === 'photo' ? 'png' : 'webm'}`;
            const file = new File([blob], fileName, { type: blob.type || (type === 'photo' ? 'image/png' : type === 'audio' ? 'audio/webm' : 'video/webm') });
            dataTransfer.items.add(file);
        }
        container.input.files = dataTransfer.files;
    }

    function clearMediaPreview(type, container) {
        container.previewContainer.style.display = 'none';
        container.previewContainer.innerHTML = '';
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
