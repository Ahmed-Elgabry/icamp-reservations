@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.sign_in'))
@section('content')

<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        @include('dashboard.orders.nav')
        
        @if ($order->video_note)
            <div class="modal fade" id="deleteVideoNoteModal" tabindex="-1" aria-labelledby="deleteVideoNoteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteVideoNoteModalLabel">
                                {{ __('dashboard.delete_video_note') }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ __('dashboard.confirm_video_note_delete') }}
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('orders.updatesignin', $order->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="delete_video_note" value="1">
                                <button type="submit" class="btn btn-danger">{{ __('dashboard.delete') }}</button>
                            </form>
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($order->voice_note)
            <div class="modal fade" id="deleteVoiceNoteModal" tabindex="-1" aria-labelledby="deleteVoiceNoteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteVoiceNoteModalLabel">
                                {{ __('dashboard.delete_voice_note') }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ __('dashboard.confirm_voice_note_delete') }}
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('orders.updatesignin', $order->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="delete_voice_note" value="1">
                                <button type="submit" class="btn btn-danger">{{ __('dashboard.delete') }}</button>
                            </form>
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!--begin::Card-->
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
                <h3 class="card-title">{{ __('dashboard.edit_time_and_image') }}</h3>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form id="kt_ecommerce_add_product_form" action="{{ route('orders.updatesignin', $order->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="uploaded_images" id="uploaded_images">

                    <div class="mb-3">
                        <label for="time_of_receipt" class="form-label">{{ __('dashboard.time_of_receipt') }}</label>
                        <input type="time" name="time_of_receipt" id="time_of_receipt"
                            class="form-control @error('time_of_receipt') is-invalid @enderror"
                            value="{{ old('time_of_receipt', $order->time_of_receipt) }}">
                        @error('time_of_receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="time_of_receipt_notes"
                            class="form-label">{{ __('dashboard.time_of_receipt_notes') }}</label>
                        <textarea name="time_of_receipt_notes" id="time_of_receipt_notes"
                            class="form-control @error('time_of_receipt_notes') is-invalid @enderror">{{ old('time_of_receipt_notes', $order->time_of_receipt_notes) }}</textarea>
                        @error('time_of_receipt_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Upload Section -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('dashboard.pre_login_image') }}</label>
                        <div class="dropzone dropzone-previews" id="preLoginImageDropzone"></div>
                        <input type="file" accept="image/*" capture="camera" id="cameraInput" style="display:none;">
                        <button type="button" class="btn btn-secondary mt-3" id="openCamera">{{ __('dashboard.capture_photo') }}</button>
                    </div>

                    <!-- Video Upload Section -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-video"></i> {{ __('dashboard.video_note') }}
                        </label>
                        <div class="media-upload-container" data-type="video">
                            @if($order->video_note)
                                <div class="existing-media-container mb-2">
                                    <video controls class="w-100" style="max-height: 300px;">
                                        <source src="{{ asset('storage/' . $order->video_note) }}" type="video/mp4">
                                    </video>
                                    <button type="button" class="btn btn-sm btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#deleteVideoNoteModal">
                                        <i class="fas fa-trash"></i> {{ __('dashboard.delete_video_note') }}
                                    </button>
                                </div>
                                <input type="hidden" name="existing_video_note" value="{{ $order->video_note }}">
                            @else
                                <div class="preview-video-container" style="display: none; margin-top: 10px;">
                                    <video class="preview-video w-100" controls style="max-height: 300px;">
                                        <source src="">
                                        {{ __('dashboard.your_browser_does_not_support_video_tag') }}
                                    </video>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                        <i class="fas fa-trash"></i> {{ __('dashboard.delete') }}
                                    </button>
                                </div>
                                
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="video">
                                            <i class="fas fa-video me-2"></i> {{ __('dashboard.record') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary upload-media">
                                            <i class="fas fa-upload me-2"></i> {{ __('dashboard.upload') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info switch-camera d-none" data-media-type="video">
                                            <i class="fas fa-sync-alt me-2"></i> {{ __('dashboard.switch_camera') }}
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="recording-timer text-danger d-none" style="font-weight: bold;">
                                    <i class="fas fa-circle me-2"></i><span>00:00</span>
                                </div>
                                
                                <input type="file" name="video_note" class="media-input d-none" accept="video/*">
                                <input type="hidden" name="video_note_data" class="media-data">
                            @endif
                        </div>
                    </div>

                    <!-- Audio Upload Section -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-microphone"></i> {{ __('dashboard.voice_note') }}
                        </label>
                        <div class="media-upload-container" data-type="audio">
                            @if($order->voice_note)
                                <div class="existing-media-container mb-2">
                                    <audio controls class="w-100">
                                        <source src="{{ asset('storage/' . $order->voice_note) }}" type="audio/mpeg">
                                    </audio>
                                    <button type="button" class="btn btn-sm btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#deleteVoiceNoteModal">
                                        <i class="fas fa-trash"></i> {{ __('dashboard.delete_voice_note') }}
                                    </button>
                                </div>
                                <input type="hidden" name="existing_voice_note" value="{{ $order->voice_note }}">
                            @else
                                <div class="preview-audio-container" style="display: none; margin-top: 10px;">
                                    <audio class="preview-audio w-100" controls>
                                        <source src="">
                                        {{ __('dashboard.your_browser_does_not_support_audio_tag') }}
                                    </audio>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                        <i class="fas fa-trash"></i> {{ __('dashboard.delete') }}
                                    </button>
                                </div>
                                
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="audio">
                                            <i class="fas fa-microphone me-2"></i> {{ __('dashboard.record') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary upload-media">
                                            <i class="fas fa-upload me-2"></i> {{ __('dashboard.upload') }}
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="recording-timer text-danger d-none" style="font-weight: bold;">
                                    <i class="fas fa-circle me-2"></i><span>00:00</span>
                                </div>
                                
                                <input type="file" name="voice_note" class="media-input d-none" accept="audio/*">
                                <input type="hidden" name="voice_note_data" class="media-data">
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" id="kt_ecommerce_add_product_submit"
                            class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->

@endsection

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    .dropzone {
        border: 2px dashed #007bff;
        padding: 20px;
        text-align: center;
        background-color: #f9f9f9;
    }

    .dropzone .dz-message {
        font-size: 18px;
        color: #007bff;
    }

    .dropzone .dz-preview .dz-image img {
        width: 100px;
        height: 100px;
    }

    .media-upload-container {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        background-color: #f8f9fa;
    }

    .recording-timer {
        font-size: 16px;
        text-align: center;
        margin: 10px 0;
    }

    .live-preview {
        border-radius: 5px;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Dropzone
    Dropzone.autoDiscover = false;
    var uploadedImages = [];

    var myDropzone = new Dropzone("#preLoginImageDropzone", {
        url: "{{ route('orders.uploadTemporaryImage') }}",
        paramName: "pre_login_image",
        maxFiles: 5,
        acceptedFiles: 'image/*',
        addRemoveLinks: true,
        parallelUploads: 5,
        uploadMultiple: true,
        previewsContainer: "#preLoginImageDropzone",
        dictDefaultMessage: "{{ __('dashboard.pre_login_image') }}",
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        init: function () {
            var myDropzone = this;

            // Add existing images to dropzone
            @if($order->preLoginImages)
                @foreach($order->preLoginImages as $image)
                    var mockFile = { name: "{{ $image->image }}", size: 12345, serverId: "{{ $image->id }}" };
                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("thumbnail", mockFile, "{{ asset($image->image) }}");
                    myDropzone.emit("complete", mockFile);
                    myDropzone.files.push(mockFile);
                    uploadedImages.push({ path: "{{ $image->image }}", id: "{{ $image->id }}" });
                @endforeach
            @endif

            this.on("sending", function (file, xhr, formData) {
                formData.append("order_id", "{{ $order->id }}");
            });
        },
        success: function (file, response) {
            file.serverId = response[0].id;
            uploadedImages.push({ path: response[0].filePath, id: response[0].id });
            document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);
        },
        error: function (file, response) {
            console.log('Error uploading: ', response);
        },
        removedfile: function (file) {
            var fileId = file.serverId;

            $.ajax({
                url: "{{ route('orders.removeImage', ['id' => 'fileId']) }}".replace('fileId', fileId),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {
                    uploadedImages = uploadedImages.filter(function (image) {
                        return image.id != fileId;
                    });
                    document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);

                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                },
                error: function (response) {
                    console.log('Error removing file:', response);
                }
            });
        }
    });

    // Camera capture functionality
    document.getElementById('openCamera').addEventListener('click', function() {
        document.getElementById('cameraInput').click();
    });

    document.getElementById('cameraInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            myDropzone.addFile(file);
        }
    });

    // Media recording variables
    let mediaRecorder;
    let mediaStream;
    let recordedChunks = [];
    let currentDeviceId = null;
    let devices = [];
    let recordingStartTime;
    let recordingTimer;

    // Initialize media upload handlers
    initializeMediaHandlers(document.body);

    function initializeMediaHandlers(container) {
        // Handle upload media button click
        container.querySelectorAll('.upload-media').forEach(button => {
            button.addEventListener('click', function() {
                const mediaContainer = this.closest('.media-upload-container');
                const input = mediaContainer.querySelector('.media-input');
                if (input) {
                    input.click();
                }
            });
        });

        // Handle capture/record media button click
        container.querySelectorAll('.capture-media').forEach(button => {
            button.addEventListener('click', async function() {
                const mediaContainer = this.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                
                mediaContainer.captureBtn = this;
                
                if (this.classList.contains('recording')) {
                    // Stop recording
                    stopMediaRecording(mediaType, mediaContainer);
                    this.classList.remove('recording');
                    this.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> {{ __('dashboard.record') }}`;
                } else {
                    // Start recording
                    await startMediaRecording(mediaType, mediaContainer);
                    this.classList.add('recording');
                    this.innerHTML = '<i class="fas fa-stop me-2"></i> {{ __("dashboard.stop") }}';
                }
            });
        });
        
        // Handle switch camera button click
        container.querySelectorAll('.switch-camera').forEach(button => {
            button.addEventListener('click', async function() {
                const mediaContainer = this.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                await switchCamera(mediaType, mediaContainer);
            });
        });

        // Handle file input change
        container.querySelectorAll('.media-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = this.files[0];
                if (!file) return;

                const mediaContainer = this.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                const previewContainer = mediaContainer.querySelector(`.preview-${mediaType}-container`);
                const previewElement = mediaContainer.querySelector(`.preview-${mediaType}`);
                const dataInput = mediaContainer.querySelector('.media-data');

                if (previewElement) {
                    const url = URL.createObjectURL(file);
                    
                    // Clear previous sources
                    while (previewElement.firstChild) {
                        previewElement.removeChild(previewElement.firstChild);
                    }
                    
                    const source = document.createElement('source');
                    source.src = url;
                    source.type = file.type;
                    previewElement.appendChild(source);
                    previewContainer.style.display = 'block';
                    
                    previewElement.load();
                    
                    // Store file data
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        dataInput.value = e.target.result.split(',')[1];
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Handle remove media button click
        container.querySelectorAll('.remove-media').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const mediaContainer = this.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                const previewContainer = mediaContainer.querySelector(`.preview-${mediaType}-container`);
                const fileInput = mediaContainer.querySelector('.media-input');
                const dataInput = mediaContainer.querySelector('.media-data');
                
                // Reset inputs
                if (fileInput) fileInput.value = '';
                if (dataInput) dataInput.value = '';
                
                // Hide preview
                if (previewContainer) previewContainer.style.display = 'none';
                
                // Stop any ongoing recording
                if (mediaContainer.captureBtn && mediaContainer.captureBtn.classList.contains('recording')) {
                    stopMediaRecording(mediaType, mediaContainer);
                    mediaContainer.captureBtn.classList.remove('recording');
                    mediaContainer.captureBtn.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> {{ __('dashboard.record') }}`;
                }
            });
        });
    }

    // Get available video devices
    async function getVideoDevices() {
        try {
            const deviceInfos = await navigator.mediaDevices.enumerateDevices();
            return deviceInfos.filter(device => device.kind === 'videoinput');
        } catch (error) {
            console.error('Error getting video devices:', error);
            return [];
        }
    }

    // Switch camera
    async function switchCamera(mediaType, container) {
        if (!devices || devices.length < 2) return;
        
        const currentIndex = currentDeviceId ? 
            devices.findIndex(device => device.deviceId === currentDeviceId) : 0;
        const nextIndex = (currentIndex + 1) % devices.length;
        currentDeviceId = devices[nextIndex].deviceId;
        
        // Restart recording with new device if currently recording
        if (container.captureBtn && container.captureBtn.classList.contains('recording')) {
            stopMediaRecording(mediaType, container);
            setTimeout(() => {
                startMediaRecording(mediaType, container);
            }, 500);
        }
    }

    // Update recording timer
    function updateTimer(container) {
        if (!recordingStartTime) return;
        
        const now = new Date();
        const elapsed = Math.floor((now - recordingStartTime) / 1000);
        const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
        const seconds = (elapsed % 60).toString().padStart(2, '0');
        
        const timerElement = container.querySelector('.recording-timer');
        if (timerElement) {
            timerElement.querySelector('span').textContent = `${minutes}:${seconds}`;
        }
    }

    // Start media recording
    async function startMediaRecording(mediaType, container) {
        try {
            recordedChunks = [];
            
            // Get video devices if recording video
            if (mediaType === 'video') {
                devices = await getVideoDevices();
                const switchBtn = container.querySelector('.switch-camera');
                if (devices.length > 1 && switchBtn) {
                    switchBtn.classList.remove('d-none');
                }
            }

            const constraints = {
                audio: true,
                video: mediaType === 'video' ? {
                    ...(currentDeviceId ? { deviceId: { exact: currentDeviceId } } : { facingMode: 'environment' }),
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } : false
            };

            mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
            
            // For video, show live preview
            if (mediaType === 'video') {
                const videoPreview = document.createElement('video');
                videoPreview.srcObject = mediaStream;
                videoPreview.autoplay = true;
                videoPreview.muted = true;
                videoPreview.controls = false;
                videoPreview.style.width = '100%';
                videoPreview.style.maxHeight = '300px';
                videoPreview.classList.add('live-preview');

                const previewContainer = container.querySelector('.preview-video-container');
                // Clear existing content but keep the remove button
                const removeBtn = previewContainer.querySelector('.remove-media');
                previewContainer.innerHTML = '';
                previewContainer.appendChild(videoPreview);
                if (removeBtn) previewContainer.appendChild(removeBtn);
                previewContainer.style.display = 'block';
                container.livePreview = videoPreview;
            }

            const options = {
                audioBitsPerSecond: 128000,
                videoBitsPerSecond: mediaType === 'video' ? 2500000 : 0,
                mimeType: mediaType === 'video' ? 'video/webm' : 'audio/webm'
            };

            try {
                mediaRecorder = new MediaRecorder(mediaStream, options);
            } catch (e) {
                console.warn('Using default media recorder due to:', e);
                mediaRecorder = new MediaRecorder(mediaStream);
            }

            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    recordedChunks.push(event.data);
                }
            };

            mediaRecorder.onstop = () => {
                const blob = new Blob(recordedChunks, {
                    type: mediaRecorder.mimeType || 
                        (mediaType === 'video' ? 'video/webm' : 'audio/webm')
                });

                // Clean up live preview
                if (container.livePreview) {
                    container.livePreview.remove();
                    container.livePreview = null;
                }
                
                // Stop all tracks
                if (mediaStream) {
                    mediaStream.getTracks().forEach(track => track.stop());
                }

                // Create file
                const file = new File([blob], `${mediaType}_${Date.now()}.webm`, {
                    type: blob.type || (mediaType === 'video' ? 'video/webm' : 'audio/webm')
                });

                // Set file input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                const fileInput = container.querySelector('.media-input');
                fileInput.files = dataTransfer.files;

                // Update preview
                const previewElement = container.querySelector(`.preview-${mediaType}`);
                const previewContainer = container.querySelector(`.preview-${mediaType}-container`);
                
                // Clear previous content but keep remove button
                const removeBtn = previewContainer.querySelector('.remove-media');
                previewElement.innerHTML = '';
                
                // Add new source
                const source = document.createElement('source');
                source.src = URL.createObjectURL(blob);
                source.type = blob.type || (mediaType === 'video' ? 'video/webm' : 'audio/webm');
                previewElement.appendChild(source);
                
                // Show preview
                previewContainer.style.display = 'block';
                previewElement.load();
                
                // Store file data
                const reader = new FileReader();
                reader.onload = (e) => {
                    const dataInput = container.querySelector('.media-data');
                    dataInput.value = e.target.result.split(',')[1];
                };
                reader.readAsDataURL(blob);
            };

            mediaRecorder.start(100);
            
            // Start timer
            recordingStartTime = new Date();
            container.timerInterval = setInterval(() => updateTimer(container), 1000);
            container.querySelector('.recording-timer')?.classList.remove('d-none');
            
            // Auto-stop after 5 minutes
            container.recordingTimeout = setTimeout(() => {
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                    const button = container.querySelector('.capture-media');
                    if (button) {
                        button.classList.remove('recording');
                        button.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> {{ __('dashboard.record') }}`;
                    }
                }
            }, 5 * 60 * 1000);

        } catch (error) {
            console.error('Error accessing media devices:', error);
            alert(`Error accessing ${mediaType === 'audio' ? 'microphone' : 'camera'}. Please check permissions and try again.`);
            
            const button = container.querySelector('.capture-media');
            if (button) {
                button.classList.remove('recording');
                button.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> {{ __('dashboard.record') }}`;
            }
        }
    }

    // Stop media recording
    function stopMediaRecording(mediaType, container) {
        // Clear timer
        if (container.timerInterval) {
            clearInterval(container.timerInterval);
        }
        container.querySelector('.recording-timer')?.classList.add('d-none');
        
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            if (container.recordingTimeout) {
                clearTimeout(container.recordingTimeout);
            }
        }
        
        // Stop tracks
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
        }
    }
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
        }
    });
});
</script>
@endsection