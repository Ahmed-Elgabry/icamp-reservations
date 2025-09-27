@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.logout'))
@section('content')

    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            @include('dashboard.orders.nav')
            
            @if ($order->video_note_logout)
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
                                    <input type="hidden" name="delete_video_note_logout" value="1">
                                    <button type="submit" class="btn btn-danger">{{ __('dashboard.delete') }}</button>
                                </form>
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($order->voice_note_logout)
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
                                    <input type="hidden" name="delete_voice_note_logout" value="1">
                                    <button type="submit" class="btn btn-danger">{{ __('dashboard.delete') }}</button>
                                </form>
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                    <form id="kt_ecommerce_add_product_form" action="{{ route('orders.updatesignin', $order->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="uploaded_images" id="uploaded_images">

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
                        
                        <!-- Image Upload Section -->
                        <div class="form-group mt-5">
                            <label class="form-label">{{ __('dashboard.pre_logout_image') }}</label>
                            <div class="image-upload-zone">
                                <div class="upload-area" id="imageUploadArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>{{ __('dashboard.drag_drop_images_or_click_to_browse') }}</p>
                                    <input type="file" id="imageInput" name="pre_logout_images[]" multiple accept="image/*" style="display: none;">
                                </div>
                                <div class="image-previews" id="imagePreviews">
                                    @if($order->preLogoutImages->isNotEmpty())
                                        @foreach($order->preLogoutImages as $image)
                                            <div class="image-preview" data-image-id="existing-{{ $image->id }}">
                                                <img src="{{ asset($image->image) }}" alt="Preview">
                                                <button type="button" class="remove-btn" data-id="{{ $image->id }}" data-type="existing">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Video Upload Section -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-video"></i> {{ __('dashboard.video_note') }}
                            </label>
                            <div class="media-upload-container" data-type="video">
                                <input type="file" class="media-input d-none" name="video_note_logout" accept="video/*">
                                <input type="hidden" name="video_note_logout_data" class="media-data">
                                
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="video">
                                            <i class="fas fa-video me-2"></i> Record
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary upload-media">
                                            <i class="fas fa-upload me-2"></i> Upload
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info switch-camera d-none" data-media-type="video">
                                            <i class="fas fa-sync-alt me-2"></i> Switch Camera
                                        </button>
                                    </div>
                                    
                                    @if ($order->video_note_logout)
                                        <button type="button" class="btn btn-danger remove-media" data-bs-toggle="modal" data-bs-target="#deleteVideoNoteModal">
                                            <i class="fas fa-trash me-2"></i>{{ __('dashboard.delete_video_note') }}
                                        </button>
                                    @endif
                                </div>
                                
                                <div class="recording-timer text-danger d-none" style="font-weight: bold;">
                                    <i class="fas fa-circle me-2"></i><span>00:00</span>
                                </div>
                                    
                                <div class="preview-video-container" style="display: {{ $order->video_note_logout ? 'block' : 'none' }}; margin-top: 10px;">
                                    @if ($order->video_note_logout)
                                        <p class="mb-2">{{ __('dashboard.existing_video') }}:</p>
                                        <video class="preview-video w-100" controls>
                                            <source src="{{ asset('storage/' . $order->video_note_logout) }}">
                                            {{ __('dashboard.your_browser_does_not_support_video_tag') }}
                                        </video>
                                    @else
                                        <video class="preview-video w-100" controls style="display: none;">
                                            {{ __('dashboard.no_video_selected') }}
                                        </video>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Audio Upload Section -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-microphone"></i> {{ __('dashboard.voice_note') }}
                            </label>
                            <div class="media-upload-container" data-type="audio">
                                <input type="file" class="media-input d-none" name="voice_note_logout" accept="audio/*">
                                <input type="hidden" name="voice_note_logout_data" class="media-data">
                                
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="audio">
                                            <i class="fas fa-microphone me-2"></i> Record
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary upload-media">
                                            <i class="fas fa-upload me-2"></i> Upload
                                        </button>
                                    </div>
                                    
                                    @if ($order->voice_note_logout)
                                        <button type="button" class="btn btn-danger remove-media" data-bs-toggle="modal" data-bs-target="#deleteVoiceNoteModal">
                                            <i class="fas fa-trash me-2"></i>{{ __('dashboard.delete_voice_note') }}
                                        </button>
                                    @endif
                                </div>
                                
                                <div class="recording-timer text-danger d-none" style="font-weight: bold;">
                                    <i class="fas fa-circle me-2"></i><span>00:00</span>
                                </div>
                                
                                <div class="preview-audio-container" style="display: {{ $order->voice_note_logout ? 'block' : 'none' }}; margin-top: 10px;">
                                    @if ($order->voice_note_logout)
                                        <p class="mb-2">{{ __('dashboard.existing_audio') }}:</p>
                                        <audio class="preview-audio w-100" controls>
                                            <source src="{{ asset('storage/' . $order->voice_note_logout) }}">
                                            {{ __('dashboard.your_browser_does_not_support_audio_tag') }}
                                        </audio>
                                    @else
                                        <audio class="preview-audio w-100" controls style="display: none;">
                                            {{ __('dashboard.no_audio_selected') }}
                                        </audio>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mt-5">
                            <button type="submit" id="kt_ecommerce_add_product_submit"
                                class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                        </div>
                    </form>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->

@endsection

@push('css')
    <style>
        /* Image Upload Zone */
        .image-upload-zone {
            border: 2px dashed #007bff;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
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
        
        .image-upload-zone .upload-area {
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-upload-zone .upload-area i {
            font-size: 2.5rem;
            color: #6c757d;
            margin-bottom: 1rem;
            display: block;
        }
        
        .image-upload-zone .upload-area p {
            margin: 0;
            color: #6c757d;
        }
        
        .image-upload-zone.dragover {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .image-previews {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px;
        }
        
        .image-preview {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .image-preview img:hover {
            transform: scale(1.05);
        }
        
        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .image-preview:hover .remove-btn {
            opacity: 1;
        }
        
        .capture-media.recording {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        .media-upload-container {
            margin-bottom: 1.5rem;
            padding: 1rem;
            border: 1px solid #e4e6ef;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        
        .preview-video-container,
        .preview-audio-container {
            margin-top: 1rem;
        }
        
        .preview-video,
        .preview-audio {
            max-width: 100%;
            margin-top: 0.5rem;
            max-height: 300px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border-radius: 6px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .recording-timer {
            margin-top: 8px;
            font-size: 0.9em;
            font-weight: 500;
        }
        
        .recording-timer .fas.fa-circle {
            color: #dc3545;
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.3; }
            100% { opacity: 1; }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .btn-group .btn {
                font-size: 0.875rem;
            }
            
            .image-preview {
                width: 100px;
                height: 100px;
            }
        }
        
        @media (max-width: 576px) {
            .btn-group {
                flex-wrap: wrap;
            }
            
            .btn-group .btn {
                flex: 1 0 calc(50% - 5px);
                margin-bottom: 5px;
            }
            
            .image-preview {
                width: 80px;
                height: 80px;
            }
        }
    </style>
@endpush

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
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
class OrderLogoutManager {
    constructor() {
        this.uploadedImages = [];
        this.mediaRecorder = null;
        this.mediaStream = null;
        this.recordedChunks = [];
        this.currentDeviceId = null;
        this.devices = [];
        this.recordingStartTime = null;
        this.recordingTimers = new Map();
        this.init();
    }

    init() {
        this.loadExistingImages();
        this.initializeImageUpload();
        this.initializeExistingImageButtons();
        this.initializeMediaHandlers();
        this.initializeFormSubmission();
        this.updateUploadedImagesInput();
    }

    // Load existing images from server
    loadExistingImages() {
        @if($order->preLogoutImages->isNotEmpty())
            @foreach($order->preLogoutImages as $image)
                this.uploadedImages.push({ 
                    path: "{{ $image->image }}", 
                    id: "existing-{{ $image->id }}",
                    isExisting: true
                });
            @endforeach
        @endif
    }

    // Initialize event listeners for existing image buttons
    initializeExistingImageButtons() {
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const imageId = button.getAttribute('data-id');
                const imageType = button.getAttribute('data-type');
                const isExisting = imageType === 'existing';
                
                // For existing images, the imageId needs to be prefixed
                const fullImageId = isExisting ? `existing-${imageId}` : imageId;
                
                this.removeImage(fullImageId, isExisting);
            });
        });
    }

    // Initialize image upload functionality
    initializeImageUpload() {
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imageInput = document.getElementById('imageInput');

        if (!imageUploadArea || !imageInput) return;

        // Click handler
        imageUploadArea.addEventListener('click', () => imageInput.click());

        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            imageUploadArea.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            imageUploadArea.addEventListener(eventName, () => {
                imageUploadArea.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            imageUploadArea.addEventListener(eventName, () => {
                imageUploadArea.classList.remove('dragover');
            }, false);
        });

        imageUploadArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            this.handleFiles(files);
        }, false);

        // File input change handler
        imageInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (files && files.length > 0) {
                this.handleFiles(files);
                e.target.value = ''; // Reset input
            }
        });

        // Image preview click handlers
        document.addEventListener('click', (e) => {
            const img = e.target.closest('.image-preview img');
            if (img) {
                this.openImageModal(img.src);
            }
        });
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    handleFiles(files) {
        if (!files || files.length === 0) return;
        
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                this.uploadImage(file);
            }
        });
    }

    async uploadImage(file) {
        try {
            // Validate file type and size
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
            const maxSize = 20 * 1024 * 1024; // 20MB to match backend validation
            
            if (!validImageTypes.includes(file.type)) {
                throw new Error('Invalid file type. Please upload an image (JPEG, PNG, GIF, WebP, or SVG).');
            }
            
            if (file.size > maxSize) {
                throw new Error('File is too large. Maximum size is 20MB.');
            }
            
            const formData = new FormData();
            formData.append('pre_login_image[]', file);
            formData.append('order_id', '{{ $order->id }}');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('type', 'logout');

            // Show loading preview
            const tempId = 'temp_' + Date.now();
            const reader = new FileReader();
            reader.onload = (e) => {
                this.addImagePreview(e.target.result, tempId, false, true);
            };
            reader.readAsDataURL(file);

            const response = await fetch('{{ route("orders.uploadTemporaryImage") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            // Remove temp preview
            const tempPreview = document.querySelector(`[data-image-id="${tempId}"]`);
            if (tempPreview) {
                tempPreview.remove();
            }

            if (response.ok && data && data.length > 0) {
                data.forEach(uploadedFile => {
                    if (uploadedFile && uploadedFile.filePath) {
                        this.uploadedImages.push({ 
                            path: uploadedFile.filePath, 
                            id: uploadedFile.id,
                            isExisting: false 
                        });
                        this.addImagePreview(uploadedFile.filePath, uploadedFile.id, false);
                    }
                });
                this.updateUploadedImagesInput();
            } else {
                throw new Error(data.message || 'Upload failed');
            }
        } catch (error) {
            console.error('Upload error:', error);
            // Remove temp preview on error
            const tempPreview = document.querySelector(`[data-image-id="${tempId}"]`);
            if (tempPreview) {
                tempPreview.remove();
            }
            alert('Error uploading image: ' + error.message);
        }
    }

    addImagePreview(src, imageId, isExisting = false, isLoading = false) {
        const imagePreviewsContainer = document.getElementById('imagePreviews');
        if (!imagePreviewsContainer) return;

        const previewDiv = document.createElement('div');
        previewDiv.className = 'image-preview';
        previewDiv.setAttribute('data-image-id', imageId);
        
        const img = document.createElement('img');
        img.src = src.startsWith('data:') ? src : `${src}`;
        img.alt = 'Preview';
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = isLoading ? '<i class="fas fa-spinner fa-spin"></i>' : '<i class="fas fa-times"></i>';
        removeBtn.type = 'button';
        
        if (!isLoading) {
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.removeImage(imageId, isExisting);
            });
        }
        
        previewDiv.appendChild(img);
        previewDiv.appendChild(removeBtn);
        imagePreviewsContainer.appendChild(previewDiv);
    }

    async removeImage(imageId, isExisting) {
        const preview = document.querySelector(`[data-image-id="${imageId}"]`);
        if (preview) {
            preview.remove();
        }

        if (isExisting) {
            const id = imageId.replace('existing-', '');
            try {
                const response = await fetch(`{{ route('orders.removeImage', ['id' => '__ID__']) }}`.replace('__ID__', id) + '?type=logout', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    console.error('Failed to remove image from server');
                }
            } catch (error) {
                console.error('Error removing image:', error);
            }
        }

        this.uploadedImages = this.uploadedImages.filter(img => img.id !== imageId);
        this.updateUploadedImagesInput();
    }

    updateUploadedImagesInput() {
        const uploadedImagesInput = document.getElementById('uploaded_images');
        if (uploadedImagesInput) {
            uploadedImagesInput.value = JSON.stringify(this.uploadedImages);
        }
    }

    openImageModal(src) {
        const modalEl = document.getElementById('imageModal');
        if (!modalEl) return;
        
        const modal = new bootstrap.Modal(modalEl);
        const modalImg = document.getElementById('modalImage');
        if (modalImg) {
            modalImg.src = src;
            modal.show();
        }
    }

    // Initialize media recording handlers
    initializeMediaHandlers() {
        // Upload media buttons
        document.querySelectorAll('.upload-media').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const mediaContainer = button.closest('.media-upload-container');
                const mediaType = mediaContainer.dataset.type;
                const fileInput = mediaContainer.querySelector('.media-input');
                
                if (fileInput) {
                    fileInput.value = '';
                    fileInput.click();
                    
                    fileInput.onchange = (e) => {
                        const files = e.target.files;
                        if (!files || files.length === 0) return;
                        
                        const file = files[0];
                        const previewContainer = mediaContainer.querySelector(`.preview-${mediaType}-container`);
                        const previewElement = mediaContainer.querySelector(`.preview-${mediaType}`);
                        
                        if (previewElement) {
                            const url = URL.createObjectURL(file);
                            
                            if (mediaType === 'audio') {
                                previewElement.src = url;
                                previewElement.controls = true;
                            } else if (mediaType === 'video') {
                                previewElement.src = url;
                                previewElement.controls = true;
                            }
                            
                            if (previewContainer) {
                                previewContainer.style.display = 'block';
                            }
                            
                            // Store the file in the form data
                            const dataInput = mediaContainer.querySelector('.media-data');
                            if (dataInput) {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    dataInput.value = e.target.result;
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    };
                }
            });
        });

        // Capture/Record media buttons
        document.querySelectorAll('.capture-media').forEach(button => {
            button.addEventListener('click', async () => {
                const mediaContainer = button.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                
                if (button.classList.contains('recording')) {
                    await this.stopMediaRecording(mediaType, mediaContainer);
                    button.classList.remove('recording');
                    button.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> Record`;
                    
                    if (mediaType === 'video') {
                        const switchBtn = mediaContainer.querySelector('.switch-camera');
                        if (switchBtn) {
                            switchBtn.classList.add('d-none');
                        }
                    }
                } else {
                    const success = await this.startMediaRecording(mediaType, mediaContainer);
                    if (success) {
                        button.classList.add('recording');
                        button.innerHTML = '<i class="fas fa-stop me-2"></i> Stop';
                    }
                }
            });
        });

        // Switch camera buttons
        document.querySelectorAll('.switch-camera').forEach(button => {
            button.addEventListener('click', async () => {
                const mediaContainer = button.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                await this.switchCamera(mediaType, mediaContainer);
            });
        });

        // File input change handlers
        document.querySelectorAll('.media-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const mediaContainer = input.closest('.media-upload-container');
                const mediaType = mediaContainer.getAttribute('data-type');
                this.handleMediaFileSelect(file, mediaType, mediaContainer);
            });
        });
    }

    handleMediaFileSelect(file, mediaType, mediaContainer) {
        const previewContainer = mediaContainer.querySelector(`.preview-${mediaType}-container`);
        const previewElement = mediaContainer.querySelector(`.preview-${mediaType}`);
        const dataInput = mediaContainer.querySelector('.media-data');

        if (previewElement && previewContainer) {
            const url = URL.createObjectURL(file);
            
            previewElement.innerHTML = '';
            const source = document.createElement('source');
            source.src = url;
            source.type = file.type;
            previewElement.appendChild(source);
            
            previewElement.style.display = 'block';
            previewContainer.style.display = 'block';
            previewElement.load();
            
            // Store file data
            const reader = new FileReader();
            reader.onload = (e) => {
                if (dataInput) {
                    dataInput.value = e.target.result.split(',')[1];
                }
            };
            reader.readAsDataURL(file);
        }
    }

    async getVideoDevices() {
        try {
            const deviceInfos = await navigator.mediaDevices.enumerateDevices();
            return deviceInfos.filter(device => device.kind === 'videoinput');
        } catch (error) {
            console.error('Error getting video devices:', error);
            return [];
        }
    }

    async switchCamera(mediaType, container) {
        if (!this.devices || this.devices.length < 2) return;
        
        const currentIndex = this.currentDeviceId ? 
            this.devices.findIndex(device => device.deviceId === this.currentDeviceId) : 0;
        const nextIndex = (currentIndex + 1) % this.devices.length;
        this.currentDeviceId = this.devices[nextIndex].deviceId;
        
        if (this.mediaStream) {
            this.mediaStream.getTracks().forEach(track => track.stop());
        }
        
        const captureBtn = container.querySelector('.capture-media');
        if (captureBtn && captureBtn.classList.contains('recording')) {
            const constraints = {
                audio: true,
                video: mediaType === 'video' ? {
                    deviceId: { exact: this.currentDeviceId },
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } : false
            };

            try {
                this.mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
                
                if (container.livePreview) {
                    container.livePreview.srcObject = this.mediaStream;
                }
            } catch (error) {
                console.error('Error switching camera:', error);
            }
        }
    }

    updateTimer(container) {
        if (!this.recordingStartTime) return;
        
        const now = new Date();
        const elapsed = Math.floor((now - this.recordingStartTime) / 1000);
        const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
        const seconds = (elapsed % 60).toString().padStart(2, '0');
        
        const timerElement = container.querySelector('.recording-timer span');
        if (timerElement) {
            timerElement.textContent = `${minutes}:${seconds}`;
        }
    }

    async startMediaRecording(mediaType, container) {
        try {
            this.recordedChunks = [];
            
            // Check if we have video devices if this is a video recording
            if (mediaType === 'video') {
                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    
                    if (videoDevices.length === 0) {
                        throw new Error('No video devices found');
                    }
                    
                    this.devices = videoDevices;
                    
                    const switchBtn = container.querySelector('.switch-camera');
                    if (this.devices.length > 1 && switchBtn) {
                        switchBtn.classList.remove('d-none');
                    }
                } catch (error) {
                    console.error('Error checking video devices:', error);
                    throw new Error('Could not access camera. Please check your device permissions.');
                }
            }

            const constraints = {
                audio: true,
                video: mediaType === 'video' ? {
                    ...(this.currentDeviceId ? { deviceId: { exact: this.currentDeviceId } } : { facingMode: 'environment' }),
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } : false
            };

            this.mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
            
            // For video, show live preview
            if (mediaType === 'video') {
                const previewContainer = container.querySelector('.preview-video-container');
                const existingVideo = container.querySelector('.preview-video');
                
                let videoPreview = container.livePreview;
                if (!videoPreview) {
                    videoPreview = document.createElement('video');
                    videoPreview.className = 'preview-video w-100';
                    videoPreview.autoplay = true;
                    videoPreview.muted = true;
                    videoPreview.controls = false;
                    videoPreview.style.maxHeight = '300px';
                    container.livePreview = videoPreview;
                    
                    if (existingVideo) {
                        existingVideo.parentNode.replaceChild(videoPreview, existingVideo);
                    } else {
                        previewContainer.appendChild(videoPreview);
                    }
                }
                
                videoPreview.srcObject = this.mediaStream;
                previewContainer.style.display = 'block';
            }

            // Setup MediaRecorder
            let options = {};
            const possibleTypes = [
                mediaType === 'video' ? 'video/webm;codecs=vp9' : 'audio/webm;codecs=opus',
                mediaType === 'video' ? 'video/webm;codecs=vp8' : 'audio/webm',
                mediaType === 'video' ? 'video/webm' : 'audio/webm',
                mediaType === 'video' ? 'video/mp4' : 'audio/mp4'
            ];

            for (const type of possibleTypes) {
                if (MediaRecorder.isTypeSupported(type)) {
                    options.mimeType = type;
                    break;
                }
            }

            try {
                this.mediaRecorder = new MediaRecorder(this.mediaStream, options);
            } catch (e) {
                console.warn('Using default MediaRecorder options:', e);
                this.mediaRecorder = new MediaRecorder(this.mediaStream);
            }

            this.mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    this.recordedChunks.push(event.data);
                }
            };

            this.mediaRecorder.onstop = () => {
                this.handleRecordingComplete(mediaType, container);
            };

            this.mediaRecorder.onerror = (event) => {
                console.error('MediaRecorder error:', event.error);
            };

            this.mediaRecorder.start(100);
            
            // Start recording timer
            this.recordingStartTime = new Date();
            const timerElement = container.querySelector('.recording-timer');
            if (timerElement) {
                timerElement.classList.remove('d-none');
                const timerId = setInterval(() => this.updateTimer(container), 1000);
                this.recordingTimers.set(container, timerId);
            }
            
            // Auto-stop after 5 minutes
            container.recordingTimeout = setTimeout(() => {
                if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
                    this.stopMediaRecording(mediaType, container);
                    const button = container.querySelector('.capture-media');
                    if (button) {
                        button.classList.remove('recording');
                        button.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> Record`;
                    }
                }
            }, 5 * 60 * 1000);

            return true;

        } catch (error) {
            console.error('Error accessing media devices:', error);
            alert(`Error accessing ${mediaType === 'audio' ? 'microphone' : 'camera'}. Please check permissions and try again.`);
            
            const button = container.querySelector('.capture-media');
            if (button) {
                button.classList.remove('recording');
                button.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> Record`;
            }
            
            return false;
        }
    }

    handleRecordingComplete(mediaType, container) {
        const blob = new Blob(this.recordedChunks, {
            type: this.mediaRecorder.mimeType || (mediaType === 'video' ? 'video/webm' : 'audio/webm')
        });

        // Clean up live preview
        if (container.livePreview && mediaType === 'video') {
            container.livePreview.srcObject = null;
        }
        
        if (this.mediaStream) {
            this.mediaStream.getTracks().forEach(track => track.stop());
        }

        // Create file from blob
        const timestamp = Date.now();
        const file = new File([blob], `${mediaType}_${timestamp}.webm`, {
            type: blob.type
        });

        // Set file input
        const fileInput = container.querySelector('.media-input');
        if (fileInput) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }

        // Update preview
        const previewElement = container.querySelector(`.preview-${mediaType}`);
        const previewContainer = container.querySelector(`.preview-${mediaType}-container`);
        
        if (previewElement && previewContainer) {
            previewElement.innerHTML = '';
            
            const source = document.createElement('source');
            source.src = URL.createObjectURL(blob);
            source.type = blob.type;
            previewElement.appendChild(source);
            
            previewElement.controls = true;
            previewElement.style.display = 'block';
            previewContainer.style.display = 'block';
            previewElement.load();
            
            if (mediaType === 'video' && container.livePreview) {
                container.livePreview.parentNode.replaceChild(previewElement, container.livePreview);
                container.livePreview = null;
            }
        }
        
        // Store file data
        const reader = new FileReader();
        reader.onload = (e) => {
            const dataInput = container.querySelector('.media-data');
            if (dataInput) {
                dataInput.value = e.target.result.split(',')[1];
            }
        };
        reader.readAsDataURL(blob);
    }

    async stopMediaRecording(mediaType, container) {
        // Clear timer
        const timerId = this.recordingTimers.get(container);
        if (timerId) {
            clearInterval(timerId);
            this.recordingTimers.delete(container);
        }
        
        const timerElement = container.querySelector('.recording-timer');
        if (timerElement) {
            timerElement.classList.add('d-none');
        }
        
        // Clear timeout
        if (container.recordingTimeout) {
            clearTimeout(container.recordingTimeout);
            container.recordingTimeout = null;
        }
        
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.stop();
        }
    }

    initializeFormSubmission() {
        const form = document.getElementById('kt_ecommerce_add_product_form');
        if (form) {
            form.addEventListener('submit', () => {
                this.updateUploadedImagesInput();
            });
        }
    }

    // Cleanup on page unload
    cleanup() {
        if (this.mediaStream) {
            this.mediaStream.getTracks().forEach(track => track.stop());
        }
        
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.stop();
        }
        
        // Clear all timers
        this.recordingTimers.forEach(timerId => clearInterval(timerId));
        this.recordingTimers.clear();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to all AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        const csrfToken = token.getAttribute('content');
        // Set default headers for fetch requests
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            if (typeof args[1] === 'object' && args[1].headers) {
                args[1].headers['X-CSRF-TOKEN'] = csrfToken;
            }
            return originalFetch.apply(this, args);
        };
    }

    // Initialize the main manager
    window.orderLogoutManager = new OrderLogoutManager();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.orderLogoutManager) {
        window.orderLogoutManager.cleanup();
    }
});
</script>
@endsection