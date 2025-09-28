@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.signin'))
@section('content')
@include('dashboard.orders.nav')

    <div class="card mb-5 mb-xl-10">

            <form method="POST" id ="kt_ecommerce_add_product_form" action="{{ route('orders.updatesignin', $order->id)}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                
                <div class="mb-8">
                      <!--begin::Category-->
            <div class="card card-flush">
                <div class="pt-5 px-9 gap-2 gap-md-5">
                    <div class="row g-3 small">
                        <div class="col-md-1 text-center">
                            <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                            <div class="fw-bold">{{ $order->id }}</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="fw-semibold text-muted">{{ __('dashboard.customer_name') }}</div>
                            <div class="fw-bold">{{ $order->customer->name }}</div>
                        </div>
                    </div>
                </div>
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    {{ __('dashboard.edit_time_and_image') }}
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                        <div class="form-group">
                            <label for="delivery_time">{{ __('dashboard.delivery_time') }}</label>
                            <input type="time" name="delivery_time" id="delivery_time" class="form-control"
                                value="{{ old('delivery_time', $order->delivery_time) }}">
                        </div>
                        
                        <div class="form-group mt-5">
                            <label for="delivery_time_notes">{{ __('dashboard.delivery_time_notes') }}</label>
                            <textarea name="delivery_time_notes" id="delivery_time_notes"
                                class="form-control">{{ old('delivery_time_notes', $order->delivery_time_notes) }}</textarea>
                        </div>
                </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">@lang('dashboard.photo_attachment')</label>
                            <div class="media-upload-container" data-type="photo">
                                <div class="delete-flags-container"></div>
                                <div class="preview-image-container mb-2" style="display: none;">
                                    <img src="" class="preview-image img-thumbnail" style="max-width: 100%;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="btn-group w-100">
                                    <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="photo">
                                        <i class="bi bi-camera"></i> Capture
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary upload-media">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                </div>
                                <input type="file" name="photo" class="media-input d-none" accept="image/*">
                                <input type="hidden" name="photo_data" class="media-data">
                                <input type="hidden" name="remove_photo" value="0" class="remove-flag">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">@lang('dashboard.audio_attachment')</label>
                            <div class="media-upload-container" data-type="audio">
                                <div class="delete-flags-container"></div>
                                <div class="preview-audio-container mb-2" style="display: none;">
                                    <audio controls class="preview-audio w-100">
                                        <source src="">
                                    </audio>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="btn-group w-100">
                                    <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="audio">
                                        <i class="bi bi-mic"></i> Record
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary upload-media">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                </div>
                                <input type="file" name="audio" class="media-input d-none" accept="audio/*">
                                <input type="hidden" name="audio_data" class="media-data">
                                <input type="hidden" name="remove_audio" value="0" class="remove-flag">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">@lang('dashboard.video_attachment')</label>
                            <div class="media-upload-container" data-type="video">
                                <div class="delete-flags-container"></div>
                                <div class="preview-video-container mb-2" style="display: none;">
                                    <video controls class="preview-video w-100">
                                        <source src="">
                                    </video>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="btn-group w-100">
                                    <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="video">
                                        <i class="bi bi-camera-video"></i> Record
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary upload-media">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                </div>
                                <input type="file" name="video" class="media-input d-none" accept="video/*">
                                <input type="hidden" name="video_data" class="media-data">
                                <input type="hidden" name="remove_video" value="0" class="remove-flag">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-light me-2">
                        @lang('dashboard.cancel')
                    </a>
                            <button type="submit" id="kt_ecommerce_add_product_submit"
                                class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                </div>
            </form>
    </div>

@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function initializeMediaHandlers() {
                const mediaContainers = {
                    photo: {
                        input: document.querySelector('[data-type="photo"] .media-input'),
                        previewContainer: document.querySelector('[data-type="photo"] .preview-image-container'),
                        preview: document.querySelector('[data-type="photo"] .preview-image'),
                        captureBtn: document.querySelector('[data-type="photo"] .capture-media'),
                        uploadBtn: document.querySelector('[data-type="photo"] .upload-media'),
                        dataInput: document.querySelector('[data-type="photo"] .media-data'),
                        removeFlag: document.querySelector('[data-type="photo"] .remove-flag')
                    },
                    audio: {
                        input: document.querySelector('[data-type="audio"] .media-input'),
                        previewContainer: document.querySelector('[data-type="audio"] .preview-audio-container'),
                        preview: document.querySelector('[data-type="audio"] .preview-audio source'),
                        captureBtn: document.querySelector('[data-type="audio"] .capture-media'),
                        uploadBtn: document.querySelector('[data-type="audio"] .upload-media'),
                        dataInput: document.querySelector('[data-type="audio"] .media-data'),
                        removeFlag: document.querySelector('[data-type="audio"] .remove-flag')
                    },
                    video: {
                        input: document.querySelector('[data-type="video"] .media-input'),
                        previewContainer: document.querySelector('[data-type="video"] .preview-video-container'),
                        preview: document.querySelector('[data-type="video"] .preview-video source'),
                        captureBtn: document.querySelector('[data-type="video"] .capture-media'),
                        uploadBtn: document.querySelector('[data-type="video"] .upload-media'),
                        dataInput: document.querySelector('[data-type="video"] .media-data'),
                        removeFlag: document.querySelector('[data-type="video"] .remove-flag')
                    }
                };

                // Initialize all media types
                Object.keys(mediaContainers).forEach(type => {
                    const container = mediaContainers[type];

                    // Handle upload button click
                    if (container.uploadBtn) {
                        container.uploadBtn.addEventListener('click', function() {
                            container.input.click();
                        });
                    }

                    // Handle file selection
                    if (container.input) {
                        container.input.addEventListener('change', function(e) {
                            handleFileUpload(e, type, container);
                        });
                    }

                    // Handle capture/record button click
                    if (container.captureBtn) {
                        container.captureBtn.addEventListener('click', function() {
                            if (type === 'photo') {
                                capturePhoto(container);
                            } else {
                                toggleMediaRecording(type, container);
                            }
                        });
                    }

                    // Handle remove media button
                    const removeBtn = container.previewContainer.querySelector('.remove-media');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            clearMediaPreview(type, container);
                        });
                    }
                });
            }

            initializeMediaHandlers();

            function toggleMediaRecording(type, container) {
                if (container.captureBtn.classList.contains('recording')) {
                    // Stop recording
                    stopMediaRecording(type, container);
                    container.captureBtn.classList.remove('recording');
                    container.captureBtn.innerHTML = `<i class="bi bi-${type === 'audio' ? 'mic' : 'camera-video'}"></i> ${type === 'audio' ? 'Record' : 'Record Video'}`;
                    container.captureBtn.classList.remove('btn-danger');
                    container.captureBtn.classList.add('btn-primary');
                } else {
                    // Start recording
                    startMediaRecording(type, container);
                    container.captureBtn.classList.add('recording');
                    container.captureBtn.innerHTML = `<i class="bi bi-stop"></i> Stop`;
                    container.captureBtn.classList.remove('btn-primary');
                    container.captureBtn.classList.add('btn-danger');
                }
            }

            function startMediaRecording(type, container) {
                const constraints = {
                    audio: true,
                    video: type === 'video' ? {
                        facingMode: 'environment',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } : false
                };

                navigator.mediaDevices.getUserMedia(constraints)
                    .then(function(stream) {
                        container.mediaStream = stream;

                        // For video, show live preview
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

                        const options = {
                            audioBitsPerSecond: 128000,
                            videoBitsPerSecond: type === 'video' ? 2500000 : 0,
                            mimeType: type === 'video' ? 'video/webm' : 'audio/webm'
                        };

                        try {
                            container.mediaRecorder = new MediaRecorder(stream, options);
                        } catch (e) {
                            console.warn('Using default media recorder due to:', e);
                            container.mediaRecorder = new MediaRecorder(stream);
                        }

                        container.recordedChunks = [];

                        container.mediaRecorder.ondataavailable = function(event) {
                            if (event.data.size > 0) {
                                container.recordedChunks.push(event.data);
                            }
                        };

                        container.mediaRecorder.onstop = function() {
                            const blob = new Blob(container.recordedChunks, {
                                type: container.mediaRecorder.mimeType ||
                                    (type === 'video' ? 'video/webm' : 'audio/webm')
                            });

                            // Clean up
                            if (container.livePreview) {
                                container.livePreview.srcObject = null;
                            }
                            stream.getTracks().forEach(track => track.stop());

                            createMediaPreview(blob, type, container);
                        };

                        container.mediaRecorder.start(100); // Collect data every 100ms
                    })
                    .catch(function(err) {
                        console.error('Error accessing media devices:', err);
                        alert('Could not access media devices: ' + err.message);
                        toggleMediaRecording(type, container);
                    });
            }

            function stopMediaRecording(type, container) {
                if (container.mediaRecorder && container.mediaRecorder.state !== 'inactive') {
                    container.mediaRecorder.stop();
                }
                if (container.mediaStream) {
                    container.mediaStream.getTracks().forEach(track => track.stop());
                }
                if (container.livePreview) {
                    container.livePreview.srcObject = null;
                }
            }

            function capturePhoto(container) {
                container.input.click();
            }

            function handleFileUpload(event, type, container) {
                const file = event.target.files[0];
                if (!file) return;

                if (type === 'photo') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        createMediaPreview(file, type, container);
                    };
                    reader.readAsDataURL(file);
                } else {
                    createMediaPreview(file, type, container);
                }
            }

            function createMediaPreview(blob, type, container) {
                // Clear previous preview and any delete flags
                container.previewContainer.innerHTML = '';
                const flagsContainer = container.previewContainer.closest('.media-upload-container')
                    .querySelector('.delete-flags-container');
                flagsContainer.innerHTML = '';

                // Reset remove flag
                container.removeFlag.value = '0';

                const url = URL.createObjectURL(blob);
                let mediaElement;

                if (type === 'photo') {
                    mediaElement = document.createElement('img');
                    mediaElement.src = url;
                    mediaElement.className = 'preview-image img-thumbnail';
                    mediaElement.style.maxWidth = '100%';
                } else {
                    // For audio/video, create proper element
                    mediaElement = document.createElement(type);
                    mediaElement.controls = true;
                    mediaElement.className = `preview-${type} w-100`;
                    const source = document.createElement('source');
                    source.src = url;
                    source.type = blob.type || (type === 'audio' ? 'audio/webm' : 'video/webm');
                    mediaElement.appendChild(source);
                }

                // Create remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger mt-2 remove-media';
                removeBtn.innerHTML = '<i class="bi bi-trash"></i> Remove';
                removeBtn.addEventListener('click', function() {
                    clearMediaPreview(type, container);
                });

                container.previewContainer.appendChild(mediaElement);
                container.previewContainer.appendChild(removeBtn);
                container.previewContainer.style.display = 'block';

                // Convert to blob for form submission
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create a new Blob with the correct MIME type
                    const recordedBlob = new Blob([blob], { 
                        type: blob.type || (type === 'audio' ? 'audio/webm' : 'video/webm') 
                    });

                    // Create a File object with proper name and type
                    const fileName = `${type}-recording-${Date.now()}.webm`;
                    const file = new File([recordedBlob], fileName, {
                        type: recordedBlob.type,
                        lastModified: Date.now()
                    });

                    // Create a new DataTransfer and add the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);

                    // Assign the file to the file input
                    container.input.files = dataTransfer.files;

                    // Store the file data for form submission
                    container.dataInput.value = e.target.result;
                };
                reader.readAsDataURL(blob);
            }

            function clearMediaPreview(type, container) {
                container.previewContainer.style.display = 'none';
                container.previewContainer.innerHTML = '';

                // Set remove flag to 1
                container.removeFlag.value = '1';

                // Clear all media inputs
                container.input.value = '';
                container.dataInput.value = '';

                // Stop any ongoing recording
                if (container.mediaRecorder && container.mediaRecorder.state !== 'inactive') {
                    container.mediaRecorder.stop();
                }
                if (container.mediaStream) {
                    container.mediaStream.getTracks().forEach(track => track.stop());
                }
            }
        });
    </script>
@endpush

@push('css')
    <style>
        .media-upload-container {
            border: 1px dashed #ddd;
            border-radius: 4px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .remove-media {
            width: 100%;
        }
    </style>
@endpush