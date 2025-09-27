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
                        <div class="image-upload-zone">
                            <div class="upload-area" id="imageUploadArea">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>{{ __('dashboard.drag_drop_images_or_click_to_browse') }}</p>
                                <input type="file" id="imageInput" name="pre_login_images[]" multiple accept="image/*" style="display: none;">
                            </div>
                            <div class="image-previews" id="imagePreviews"></div>
                        </div>
                        <input type="file" accept="image/*" capture="camera" id="cameraInput">
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
<style>
    /* Custom Image Upload Styles */
    .image-upload-zone {
        border: 2px dashed #007bff;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .upload-area {
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .upload-area:hover {
        background-color: #e3f2fd;
    }

    .upload-area.dragover {
        background-color: #bbdefb;
        border-color: #1976d2;
    }

    .upload-area i {
        font-size: 48px;
        color: #007bff;
        margin-bottom: 16px;
    }

    .upload-area p {
        font-size: 16px;
        color: #6c757d;
        margin: 0;
    }

    .image-previews {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 15px;
        max-height: 300px;
        overflow-y: auto;
    }

    .image-preview {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }

    .image-preview:hover {
        transform: scale(1.05);
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: background-color 0.2s ease;
    }

    .image-preview .remove-btn:hover {
        background: rgba(220, 53, 69, 1);
    }

    /* Media Upload Container Styles */
    .media-upload-container {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    /* Modal Styling Fixes */
    #imageModal .modal-dialog {
        max-width: 90%;
        max-height: 90vh;
        margin: 1.75rem auto;
    }

    #imageModal .modal-content {
        background-color: white;
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    #imageModal .modal-body {
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: white;
    }

    #imageModal .modal-body img {
        max-width: 100%;
        max-height: 80vh;
        width: auto;
        height: auto;
        object-fit: contain;
    }

    #imageModal .modal-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }

    #imageModal .modal-footer {
        border-top: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }

    .recording-timer {
        font-size: 18px;
        text-align: center;
        margin: 15px 0;
        padding: 10px;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 4px;
    }

    .live-preview {
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Button Styles */
    .btn-group .btn {
        flex: 1;
    }

    .capture-media.recording {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .image-preview {
            width: 100px;
            height: 100px;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 5px;
        }
    }
</style>
@endpush

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" class="img-fluid" src="" alt="Preview">
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image upload functionality (Custom implementation without Dropzone)
    let uploadedImages = [];
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreviewsContainer = document.getElementById('imagePreviews');

    // Load existing images
    @if($order->preLoginImages)
        @foreach($order->preLoginImages as $image)
            uploadedImages.push({ 
                path: "{{ $image->image }}", 
                id: "{{ $image->id }}",
                isExisting: true 
            });
            addImagePreview("{{ asset($image->image) }}", "{{ $image->id }}", true);
        @endforeach
        updateUploadedImagesInput();
    @endif

    // Click to browse
    imageUploadArea.addEventListener('click', () => {
        imageInput.click();
    });

    // Drag and drop functionality
    imageUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageUploadArea.classList.add('dragover');
    });

    imageUploadArea.addEventListener('dragleave', () => {
        imageUploadArea.classList.remove('dragover');
    });

    imageUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        imageUploadArea.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    // File input change
    imageInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    // Handle file uploads
    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                uploadImage(file);
            }
        });
    }

    // Upload image to server
    function uploadImage(file) {
        const formData = new FormData();
        formData.append('pre_login_image[]', file);
        formData.append('order_id', '{{ $order->id }}');
        formData.append('_token', '{{ csrf_token() }}');

        // Show loading preview
        const tempId = 'temp_' + Date.now();
        const reader = new FileReader();
        reader.onload = (e) => {
            addImagePreview(e.target.result, tempId, false, true);
        };
        reader.readAsDataURL(file);

        // Upload to server using jQuery AJAX
        $.ajax({
            url: '{{ route("orders.uploadTemporaryImage") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response && response.length > 0) {
                    // Remove temp preview
                    const tempPreview = document.querySelector(`[data-image-id="${tempId}"]`);
                    if (tempPreview) {
                        tempPreview.remove();
                    }

                    // Handle each uploaded file in the response
                    response.forEach(uploadedFile => {
                        if (uploadedFile && uploadedFile.filePath) {
                            uploadedImages.push({ 
                                path: uploadedFile.filePath, 
                                id: uploadedFile.id,
                                isExisting: false 
                            });
                            addImagePreview(uploadedFile.filePath, uploadedFile.id, false);
                        }
                    });
                    updateUploadedImagesInput();
                }
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', error);
                const tempPreview = document.querySelector(`[data-image-id="${tempId}"]`);
                if (tempPreview) {
                    tempPreview.remove();
                }
                
                if (xhr.status === 419) {
                    alert('Your session has expired. Please refresh the page and try again.');
                    window.location.reload();
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert('Error: ' + xhr.responseJSON.message);
                } else {
                    alert('Error uploading image. Please try again.');
                }
            }
        });
    }

    // Add image preview
    function addImagePreview(src, imageId, isExisting = false, isLoading = false) {
        const previewDiv = document.createElement('div');
        previewDiv.className = 'image-preview';
        previewDiv.setAttribute('data-image-id', imageId);
        
        const img = document.createElement('img');
        img.src = src;
        img.alt = 'Preview';
        img.className = 'img-fluid';
        img.style.cursor = 'pointer';
        
        img.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            const modalImg = document.getElementById('modalImage');
            modalImg.src = this.src;
            modal.show();
        });
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = isLoading ? '<i class="fas fa-spinner fa-spin"></i>' : '<i class="fas fa-times"></i>';
        removeBtn.type = 'button';
        
        if (!isLoading) {
            removeBtn.addEventListener('click', () => removeImage(imageId, isExisting));
        }
        
        previewDiv.appendChild(img);
        previewDiv.appendChild(removeBtn);
        imagePreviewsContainer.appendChild(previewDiv);
    }

    // Remove image
    function removeImage(imageId, isExisting) {
        const preview = document.querySelector(`[data-image-id="${imageId}"]`);
        if (preview) {
            preview.remove();
        }

        if (isExisting) {
            fetch(`{{ url('dashboard/orders/remove-image') }}/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Image removed from server');
            })
            .catch(error => {
                console.error('Error removing image:', error);
            });
        }

        uploadedImages = uploadedImages.filter(img => img.id !== imageId);
        updateUploadedImagesInput();
    }

    // Update hidden input
    function updateUploadedImagesInput() {
        document.getElementById('uploaded_images').value = JSON.stringify(uploadedImages);
    }

    // Camera capture functionality
    const openCameraBtn = document.getElementById('openCamera');
    const cameraInput = document.getElementById('cameraInput');
    
    if (openCameraBtn) {
        openCameraBtn.addEventListener('click', function() {
            if (cameraInput) {
                cameraInput.click();
            }
        });
    }

    if (cameraInput) {
        cameraInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                uploadImage(file);
            }
        });
    }

    // Media recording variables and state management
    let mediaRecorders = {
        video: null,
        audio: null
    };
    let mediaStreams = {
        video: null,
        audio: null
    };
    let recordedChunks = {
        video: [],
        audio: []
    };
    let currentDeviceId = null;
    let videoDevices = [];
    let recordingStartTime = {
        video: null,
        audio: null
    };
    let recordingTimers = {
        video: null,
        audio: null
    };
    let currentlyRecording = null;

    // Initialize media upload handlers
    initializeMediaHandlers();

    async function initializeMediaHandlers() {
        // Get video devices and show switch camera button always
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            videoDevices = devices.filter(device => device.kind === 'videoinput');
            
            // Show switch camera button if multiple devices available
            const switchCameraBtn = document.querySelector('.switch-camera');
            if (switchCameraBtn && videoDevices.length > 1) {
                switchCameraBtn.classList.remove('d-none');
            }
        } catch (error) {
            console.warn('Could not enumerate devices:', error);
        }

        // Handle upload media button clicks
        document.querySelectorAll('.upload-media').forEach(button => {
            button.addEventListener('click', function() {
                const mediaContainer = this.closest('.media-upload-container');
                const input = mediaContainer.querySelector('.media-input');
                if (input) {
                    input.click();
                }
            });
        });

        // Handle capture/record media button clicks
        document.querySelectorAll('.capture-media').forEach(button => {
            button.addEventListener('click', async function() {
                const mediaContainer = this.closest('.media-upload-container');
                const mediaType = this.getAttribute('data-media-type');
                
                if (this.classList.contains('recording')) {
                    // Stop current recording
                    await stopMediaRecording(mediaType);
                } else {
                    // Stop any other recording first
                    if (currentlyRecording && currentlyRecording !== mediaType) {
                        await stopMediaRecording(currentlyRecording);
                    }
                    // Start new recording
                    await startMediaRecording(mediaType, mediaContainer);
                }
            });
        });
        
        // Handle switch camera button clicks
        document.querySelectorAll('.switch-camera').forEach(button => {
            button.addEventListener('click', async function() {
                await switchCamera();
            });
        });

        // Handle file input changes
        document.querySelectorAll('.media-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = this.files[0];
                if (!file) return;

                const mediaContainer = this.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                
                displayMediaPreview(file, mediaType, mediaContainer);
            });
        });

        // Handle remove media button clicks
        document.querySelectorAll('.remove-media').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const mediaContainer = this.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                
                removeMediaPreview(mediaType, mediaContainer);
            });
        });
    }

    // Display media preview
    function displayMediaPreview(file, mediaType, container) {
        const previewContainer = container.querySelector(`.preview-${mediaType}-container`);
        const previewElement = container.querySelector(`.preview-${mediaType}`);
        const dataInput = container.querySelector('.media-data');

        if (previewElement) {
            const url = URL.createObjectURL(file);
            
            // Clear previous sources
            previewElement.innerHTML = '';
            
            const source = document.createElement('source');
            source.src = url;
            source.type = file.type;
            previewElement.appendChild(source);
            
            previewContainer.style.display = 'block';
            previewElement.load();
            
            // Store file data for form submission
            const reader = new FileReader();
            reader.onload = function(e) {
                dataInput.value = e.target.result.split(',')[1];
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove media preview
    function removeMediaPreview(mediaType, container) {
        const previewContainer = container.querySelector(`.preview-${mediaType}-container`);
        const fileInput = container.querySelector('.media-input');
        const dataInput = container.querySelector('.media-data');
        
        // Reset inputs
        if (fileInput) fileInput.value = '';
        if (dataInput) dataInput.value = '';
        
        // Hide preview
        if (previewContainer) previewContainer.style.display = 'none';
        
        // Stop recording if active
        if (currentlyRecording === mediaType) {
            stopMediaRecording(mediaType);
        }
    }

    // Switch camera function
    async function switchCamera() {
        if (videoDevices.length < 2) return;
        
        const currentIndex = currentDeviceId ? 
            videoDevices.findIndex(device => device.deviceId === currentDeviceId) : 0;
        const nextIndex = (currentIndex + 1) % videoDevices.length;
        currentDeviceId = videoDevices[nextIndex].deviceId;
        
        // If video is currently recording, restart with new camera
        if (currentlyRecording === 'video') {
            const videoContainer = document.querySelector('[data-type="video"]');
            await stopMediaRecording('video');
            // Small delay to ensure cleanup
            setTimeout(() => {
                startMediaRecording('video', videoContainer);
            }, 500);
        }
    }

    // Start media recording
    async function startMediaRecording(mediaType, container) {
        try {
            // Reset recorded chunks
            recordedChunks[mediaType] = [];
            
            // Set up constraints
            const constraints = {
                audio: true,
                video: mediaType === 'video' ? {
                    ...(currentDeviceId ? { deviceId: { exact: currentDeviceId } } : { facingMode: 'environment' }),
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } : false
            };

            // Get media stream
            mediaStreams[mediaType] = await navigator.mediaDevices.getUserMedia(constraints);
            
            // Show live preview for video
            if (mediaType === 'video') {
                showLiveVideoPreview(container, mediaStreams[mediaType]);
            }

            // Set up media recorder
            const options = {
                audioBitsPerSecond: 128000,
                videoBitsPerSecond: mediaType === 'video' ? 2500000 : 0,
                mimeType: getSupportedMimeType(mediaType)
            };

            try {
                mediaRecorders[mediaType] = new MediaRecorder(mediaStreams[mediaType], options);
            } catch (e) {
                mediaRecorders[mediaType] = new MediaRecorder(mediaStreams[mediaType]);
            }

            // Handle data available
            mediaRecorders[mediaType].ondataavailable = (event) => {
                if (event.data.size > 0) {
                    recordedChunks[mediaType].push(event.data);
                }
            };

            // Handle recording stop
            mediaRecorders[mediaType].onstop = () => {
                handleRecordingStop(mediaType, container);
            };

            // Start recording
            mediaRecorders[mediaType].start(100);
            currentlyRecording = mediaType;
            
            // Update UI
            updateRecordingUI(mediaType, true);
            
            // Start timer
            startRecordingTimer(mediaType, container);
            
            // Auto-stop after 5 minutes
            setTimeout(() => {
                if (currentlyRecording === mediaType) {
                    stopMediaRecording(mediaType);
                }
            }, 5 * 60 * 1000);

        } catch (error) {
            console.error(`Error starting ${mediaType} recording:`, error);
            alert(`Error accessing ${mediaType === 'audio' ? 'microphone' : 'camera'}. Please check permissions and try again.`);
            updateRecordingUI(mediaType, false);
        }
    }

    // Stop media recording
    async function stopMediaRecording(mediaType) {
        if (mediaRecorders[mediaType] && mediaRecorders[mediaType].state === 'recording') {
            mediaRecorders[mediaType].stop();
        }
        
        // Clean up stream
        if (mediaStreams[mediaType]) {
            mediaStreams[mediaType].getTracks().forEach(track => track.stop());
            mediaStreams[mediaType] = null;
        }
        
        // Clean up timer
        if (recordingTimers[mediaType]) {
            clearInterval(recordingTimers[mediaType]);
            recordingTimers[mediaType] = null;
        }
        
        // Hide timer
        const container = document.querySelector(`[data-type="${mediaType}"]`);
        const timerElement = container?.querySelector('.recording-timer');
        if (timerElement) {
            timerElement.classList.add('d-none');
        }
        
        // Update UI
        updateRecordingUI(mediaType, false);
        
        // Clear current recording state
        if (currentlyRecording === mediaType) {
            currentlyRecording = null;
        }
    }

    // Handle recording stop
    function handleRecordingStop(mediaType, container) {
        // Remove live preview for video
        if (mediaType === 'video') {
            removeLiveVideoPreview(container);
        }
        
        // Create blob from recorded chunks
        const mimeType = mediaRecorders[mediaType].mimeType || getSupportedMimeType(mediaType);
        const blob = new Blob(recordedChunks[mediaType], { type: mimeType });
        
        // Create file
        const extension = mimeType.includes('webm') ? 'webm' : (mimeType.includes('mp4') ? 'mp4' : 'wav');
        const file = new File([blob], `${mediaType}_${Date.now()}.${extension}`, { type: mimeType });
        
        // Set file input
        const fileInput = container.querySelector('.media-input');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        
        // Show preview
        displayRecordedMediaPreview(blob, mediaType, container);
        
        // Store data for form submission
        const dataInput = container.querySelector('.media-data');
        const reader = new FileReader();
        reader.onload = (e) => {
            dataInput.value = e.target.result.split(',')[1];
        };
        reader.readAsDataURL(blob);
    }

    // Show live video preview
    function showLiveVideoPreview(container, stream) {
        const previewContainer = container.querySelector('.preview-video-container');
        
        // Remove existing content but keep remove button
        const removeBtn = previewContainer.querySelector('.remove-media');
        previewContainer.innerHTML = '';
        
        // Create live preview video element
        const liveVideo = document.createElement('video');
        liveVideo.srcObject = stream;
        liveVideo.autoplay = true;
        liveVideo.muted = true;
        liveVideo.controls = false;
        liveVideo.style.width = '100%';
        liveVideo.style.maxHeight = '300px';
        liveVideo.classList.add('live-preview');
        
        previewContainer.appendChild(liveVideo);
        if (removeBtn) {
            previewContainer.appendChild(removeBtn);
        }
        
        previewContainer.style.display = 'block';
    }

    // Remove live video preview
    function removeLiveVideoPreview(container) {
        const livePreview = container.querySelector('.live-preview');
        if (livePreview) {
            livePreview.remove();
        }
    }

    // Display recorded media preview
    function displayRecordedMediaPreview(blob, mediaType, container) {
        const previewContainer = container.querySelector(`.preview-${mediaType}-container`);
        const previewElement = container.querySelector(`.preview-${mediaType}`);
        
        // Clear existing content but preserve remove button
        const removeBtn = previewContainer.querySelector('.remove-media');
        previewElement.innerHTML = '';
        
        // Create source element
        const source = document.createElement('source');
        source.src = URL.createObjectURL(blob);
        source.type = blob.type;
        previewElement.appendChild(source);
        
        // Show preview
        previewContainer.style.display = 'block';
        previewElement.load();
        
        // Ensure remove button is still there
        if (removeBtn && !previewContainer.contains(removeBtn)) {
            previewContainer.appendChild(removeBtn);
        }
    }

    // Update recording UI
    function updateRecordingUI(mediaType, isRecording) {
        const button = document.querySelector(`.capture-media[data-media-type="${mediaType}"]`);
        if (!button) return;
        
        if (isRecording) {
            button.classList.add('recording');
            button.innerHTML = '<i class="fas fa-stop me-2"></i> {{ __("dashboard.stop") }}';
        } else {
            button.classList.remove('recording');
            const icon = mediaType === 'audio' ? 'microphone' : 'video';
            button.innerHTML = `<i class="fas fa-${icon} me-2"></i> {{ __('dashboard.record') }}`;
        }
    }

    // Start recording timer
    function startRecordingTimer(mediaType, container) {
        const timerElement = container.querySelector('.recording-timer');
        if (!timerElement) return;
        
        recordingStartTime[mediaType] = new Date();
        timerElement.classList.remove('d-none');
        
        recordingTimers[mediaType] = setInterval(() => {
            const elapsed = Math.floor((new Date() - recordingStartTime[mediaType]) / 1000);
            const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
            const seconds = (elapsed % 60).toString().padStart(2, '0');
            
            const timerSpan = timerElement.querySelector('span');
            if (timerSpan) {
                timerSpan.textContent = `${minutes}:${seconds}`;
            }
        }, 1000);
    }

    // Get supported MIME type
    function getSupportedMimeType(mediaType) {
        const types = mediaType === 'video' 
            ? ['video/webm;codecs=vp9', 'video/webm;codecs=vp8', 'video/webm', 'video/mp4']
            : ['audio/webm;codecs=opus', 'audio/webm', 'audio/mp4', 'audio/wav'];
            
        for (const type of types) {
            if (MediaRecorder.isTypeSupported(type)) {
                return type;
            }
        }
        
        return mediaType === 'video' ? 'video/webm' : 'audio/webm';
    }
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        ['video', 'audio'].forEach(mediaType => {
            if (mediaRecorders[mediaType] && mediaRecorders[mediaType].state === 'recording') {
                mediaRecorders[mediaType].stop();
            }
            if (mediaStreams[mediaType]) {
                mediaStreams[mediaType].getTracks().forEach(track => track.stop());
            }
            if (recordingTimers[mediaType]) {
                clearInterval(recordingTimers[mediaType]);
            }
        });
    });

    // Form validation before submit
    document.getElementById('kt_ecommerce_add_product_form').addEventListener('submit', function(e) {
        console.log('Form submitted with uploaded images:', uploadedImages);
    });
});
</script>
@endsection