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
                        
                        <div class="form-group mt-5">
                            <label for="pre_logout_image">{{ __('dashboard.pre_logout_image') }}</label>
                            
                            <!-- Image Upload Dropdown -->
                            <div class="dropdown mb-3">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="imageUploadDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-camera me-2"></i>{{ __('dashboard.add_image') }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="imageUploadDropdown">
                                    <li>
                                        <a class="dropdown-item" href="#" id="uploadImageBtn">
                                            <i class="fas fa-upload me-2"></i>{{ __('dashboard.upload_image') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" id="takePhotoBtn">
                                            <i class="fas fa-camera me-2"></i>{{ __('dashboard.take_photo') }}
                                        </a>
                                    </li>
                                </ul>
                                <input type="file" id="imageInput" class="d-none" accept="image/*">
                                <input type="hidden" id="capturedImageData" name="captured_image">
                            </div>
                            
                            <!-- Image Preview Container -->
                            <div class="row" id="imagePreviewContainer">
                                <!-- Existing images will be shown here -->
                            </div>
                            
                            <!-- Hidden dropzone for file uploads -->
                            <div class="dropzone dropzone-previews d-none" id="preLoginImageDropzone"></div>
                            
                            <!-- Camera Modal -->
                            <div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('dashboard.take_photo') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <div class="camera-container mb-3">
                                                <video id="cameraPreview" class="w-100" autoplay playsinline style="max-height: 400px; background: #000;"></video>
                                                <canvas id="cameraCanvas" class="d-none"></canvas>
                                            </div>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-primary" id="captureBtn">
                                                    <i class="fas fa-camera me-2"></i>{{ __('dashboard.capture') }}
                                                </button>
                                                <button type="button" class="btn btn-secondary" id="switchCameraBtn" style="display: none;">
                                                    <i class="fas fa-sync-alt me-2"></i>{{ __('dashboard.switch_camera') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                {{ __('dashboard.cancel') }}
                                            </button>
                                            <button type="button" class="btn btn-primary" id="usePhotoBtn" disabled>
                                                {{ __('dashboard.use_photo') }}
                                            </button>
                                        </div>
                                    </div>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <style>
        /* Dropzone styles */
        .dropzone {
            border: 2px dashed #007bff;
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .dropzone:hover {
            border-color: #0056b3;
            background-color: #f0f7ff;
        }

        .dropzone .dz-message {
            font-size: 18px;
            color: #007bff;
            margin: 0;
        }

        .dropzone .dz-preview .dz-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
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
        
        .switch-camera {
            display: none;
        }
        
        /* Image upload and camera styles */
        #imagePreviewContainer {
            margin: 15px -5px 0;
            display: flex;
            flex-wrap: wrap;
        }
        
        #imagePreviewContainer .card {
            border: 1px solid #e4e6ef;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin: 0 5px 10px;
            width: calc(25% - 10px);
        }
        
        #imagePreviewContainer .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        #imagePreviewContainer .card-img-top {
            height: 120px;
            width: 100%;
            object-fit: cover;
        }
        
        #imagePreviewContainer .card-body {
            padding: 0.75rem;
            background: #fff;
        }
        
        #imagePreviewContainer .remove-image {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        #imagePreviewContainer .remove-image:hover {
            transform: scale(1.1);
        }
        
        /* Camera modal styles */
        #cameraPreview, #cameraCanvas {
            max-width: 100%;
            height: auto;
            background: #000;
            margin: 0 auto;
            display: block;
            border-radius: 6px;
        }
        
        #cameraCanvas {
            display: none;
        }
        
        .camera-container {
            position: relative;
            margin-bottom: 15px;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            max-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Loading indicator */
        .loading-indicator {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            flex-direction: column;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
            margin-bottom: 1rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            #imagePreviewContainer .card {
                width: calc(33.333% - 10px);
            }
        }
        
        @media (max-width: 768px) {
            #imagePreviewContainer .card {
                width: calc(50% - 10px);
            }
            
            .camera-container {
                max-height: 50vh;
            }
            
            .modal-dialog {
                margin: 0.5rem;
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
            
            .switch-camera {
                flex: 1 0 100% !important;
                margin-top: 5px;
            }
            
            #imagePreviewContainer .card {
                width: calc(50% - 10px);
            }
            
            #imagePreviewContainer .card-img-top {
                height: 100px;
            }
            
            .modal-dialog {
                margin: 0.25rem;
            }
        }
    </style>
@endpush

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize media upload handlers if they exist
        if (document.body) {
            initializeMediaHandlers(document.body);
        }
        
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Image upload and camera functionality
        const imageInput = document.getElementById('imageInput');
        const uploadImageBtn = document.getElementById('uploadImageBtn');
        const takePhotoBtn = document.getElementById('takePhotoBtn');
        const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'));
        const cameraPreview = document.getElementById('cameraPreview');
        const cameraCanvas = document.getElementById('cameraCanvas');
        const captureBtn = document.getElementById('captureBtn');
        const usePhotoBtn = document.getElementById('usePhotoBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const capturedImageData = document.getElementById('capturedImageData');
        
        let stream = null;
        let currentFacingMode = 'environment'; // Start with back camera
        let capturedImage = null;

        // Handle upload image button click
        uploadImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            imageInput.click();
        });

        // Handle file selection
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    addImagePreview(event.target.result, file.name);
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle take photo button click
        takePhotoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            startCamera();
            cameraModal.show();
        });

        // Handle camera modal hide event
        document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function() {
            stopCamera();
            resetCameraUI();
        });

        // Handle capture button click
        captureBtn.addEventListener('click', function() {
            captureImage();
        });

        // Handle use photo button click
        usePhotoBtn.addEventListener('click', function() {
            if (capturedImage) {
                addImagePreview(capturedImage, 'captured_photo_' + new Date().getTime() + '.jpg');
                cameraModal.hide();
            }
        });

        // Handle switch camera button click
        if (switchCameraBtn) {
            switchCameraBtn.addEventListener('click', function() {
                currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                stopCamera();
                startCamera();
            });
        }

        // Start camera function
        async function startCamera() {
            try {
                const constraints = {
                    video: {
                        facingMode: currentFacingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                };

                stream = await navigator.mediaDevices.getUserMedia(constraints);
                cameraPreview.srcObject = stream;
                
                // Show switch camera button if multiple cameras are available
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                if (videoDevices.length > 1) {
                    switchCameraBtn.style.display = 'inline-block';
                }
                
                // Enable capture button once camera is ready
                captureBtn.disabled = false;
                
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Could not access the camera. Please check your permissions.');
            }
        }

        // Stop camera function
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                cameraPreview.srcObject = null;
                stream = null;
            }
        }

        // Capture image from camera
        function captureImage() {
            if (!stream) return;

            const context = cameraCanvas.getContext('2d');
            cameraCanvas.width = cameraPreview.videoWidth;
            cameraCanvas.height = cameraPreview.videoHeight;
            context.drawImage(cameraPreview, 0, 0, cameraCanvas.width, cameraCanvas.height);
            
            // Convert canvas to data URL
            capturedImage = cameraCanvas.toDataURL('image/jpeg');
            
            // Show preview and enable use photo button
            cameraPreview.style.display = 'none';
            cameraCanvas.style.display = 'block';
            captureBtn.style.display = 'none';
            usePhotoBtn.disabled = false;
            
            // Show the captured image
            cameraCanvas.classList.remove('d-none');
        }

        // Reset camera UI
        function resetCameraUI() {
            cameraPreview.style.display = 'block';
            cameraCanvas.style.display = 'none';
            captureBtn.style.display = 'inline-block';
            usePhotoBtn.disabled = true;
            capturedImage = null;
            
            // Clear the canvas
            const context = cameraCanvas.getContext('2d');
            context.clearRect(0, 0, cameraCanvas.width, cameraCanvas.height);
        }

        // Add image preview to the container
        function addImagePreview(imageData, fileName) {
            const previewId = 'preview-' + Math.random().toString(36).substr(2, 9);
            const previewHtml = `
                <div class="col-md-3 mb-3" id="${previewId}">
                    <div class="card">
                        <img src="${imageData}" class="card-img-top" alt="Preview">
                        <div class="card-body p-2 text-center">
                            <small class="text-muted d-block text-truncate">${fileName}</small>
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-image" data-preview-id="${previewId}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            imagePreviewContainer.insertAdjacentHTML('beforeend', previewHtml);
            
            // Add event listener for remove button
            document.querySelector(`#${previewId} .remove-image`).addEventListener('click', function() {
                document.getElementById(previewId).remove();
                // TODO: Handle removal from server if needed
            });
            
            // Store the image data in a hidden field for form submission
            updateHiddenImageData();
        }
        
        // Update hidden field with all image data
        function updateHiddenImageData() {
            const images = [];
            document.querySelectorAll('#imagePreviewContainer img').forEach(img => {
                images.push(img.src);
            });
            capturedImageData.value = JSON.stringify(images);
        }

        // Media recording variables
        let mediaRecorder;
        let mediaStream;
        let recordedChunks = [];
        let currentDeviceId = null;
        let devices = [];
        let recordingStartTime;
        let recordingTimer;

        // Function to handle media uploads and recordings
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
                    
                    // Store button reference on container for easier access
                    mediaContainer.captureBtn = this;
                    
                    if (this.classList.contains('recording')) {
                        // Stop recording
                        await stopMediaRecording(mediaType, mediaContainer);
                        this.classList.remove('recording');
                        this.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> Record`;
                        
                        // Hide switch camera button when not recording
                        if (mediaType === 'video') {
                            const switchBtn = mediaContainer.querySelector('.switch-camera');
                            if (switchBtn) {
                                switchBtn.classList.add('d-none');
                            }
                        }
                    } else {
                        // Start recording
                        const success = await startMediaRecording(mediaType, mediaContainer);
                        if (success) {
                            this.classList.add('recording');
                            this.innerHTML = '<i class="fas fa-stop me-2"></i> Stop';
                        }
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
                        if (mediaType === 'video' || mediaType === 'audio') {
                            // For video/audio, create a preview with controls
                            const url = URL.createObjectURL(file);
                            
                            // Clear previous sources
                            previewElement.innerHTML = '';
                            
                            const source = document.createElement('source');
                            source.src = url;
                            source.type = file.type;
                            previewElement.appendChild(source);
                            
                            // Show preview
                            previewElement.style.display = 'block';
                            previewContainer.style.display = 'block';
                            
                            // Load the media to ensure it plays
                            previewElement.load();
                            
                            // Store file data
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                dataInput.value = e.target.result.split(',')[1]; // Store base64 data
                            };
                            reader.readAsDataURL(file);
                        }
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
                    
                    // Reset file input
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    
                    // Clear data input
                    if (dataInput) {
                        dataInput.value = '';
                    }
                    
                    // Hide preview
                    if (previewContainer) {
                        previewContainer.style.display = 'none';
                    }
                    
                    // Create a hidden input to flag deletion to backend
                    const deleteFlag = document.createElement('input');
                    deleteFlag.type = 'hidden';
                    deleteFlag.name = `delete_${mediaType}_flag`;
                    deleteFlag.value = '1';
                    mediaContainer.appendChild(deleteFlag);
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
            
            // Get current device index and calculate next
            const currentIndex = currentDeviceId ? 
                devices.findIndex(device => device.deviceId === currentDeviceId) : 0;
            const nextIndex = (currentIndex + 1) % devices.length;
            currentDeviceId = devices[nextIndex].deviceId;
            
            // Stop current stream
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
            }
            
            // Restart recording with new device if recording
            if (container.captureBtn && container.captureBtn.classList.contains('recording')) {
                // Get new constraints with the selected device
                const constraints = {
                    audio: true,
                    video: mediaType === 'video' ? {
                        deviceId: { exact: currentDeviceId },
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } : false
                };

                try {
                    mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
                    
                    // Update live preview
                    if (container.livePreview) {
                        container.livePreview.srcObject = mediaStream;
                    }
                } catch (error) {
                    console.error('Error switching camera:', error);
                }
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
                const span = timerElement.querySelector('span');
                if (span) {
                    span.textContent = `${minutes}:${seconds}`;
                }
            }
        }

        // Start media recording
        async function startMediaRecording(mediaType, container) {
            try {
                recordedChunks = [];
                
                // Get video devices if not already loaded
                if (mediaType === 'video' && devices.length === 0) {
                    devices = await getVideoDevices();
                    console.log('Available devices:', devices);
                    
                    // Show switch camera button if multiple devices available
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
                    const previewContainer = container.querySelector('.preview-video-container');
                    const existingVideo = container.querySelector('.preview-video');
                    
                    // Create or update live preview
                    let videoPreview = container.livePreview;
                    if (!videoPreview) {
                        videoPreview = document.createElement('video');
                        videoPreview.className = 'preview-video w-100';
                        videoPreview.autoplay = true;
                        videoPreview.muted = true;
                        videoPreview.controls = false;
                        videoPreview.style.maxHeight = '300px';
                        container.livePreview = videoPreview;
                        
                        // Replace existing video with live preview
                        if (existingVideo) {
                            existingVideo.parentNode.replaceChild(videoPreview, existingVideo);
                        } else {
                            previewContainer.appendChild(videoPreview);
                        }
                    }
                    
                    videoPreview.srcObject = mediaStream;
                    previewContainer.style.display = 'block';
                }

                // Setup MediaRecorder with better options
                let options = {};
                
                // Try different MIME types based on browser support
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

                // Add bitrate if supported
                if (options.mimeType) {
                    if (mediaType === 'video') {
                        options.videoBitsPerSecond = 2500000;
                        options.audioBitsPerSecond = 128000;
                    } else {
                        options.audioBitsPerSecond = 128000;
                    }
                }

                try {
                    mediaRecorder = new MediaRecorder(mediaStream, options);
                } catch (e) {
                    console.warn('Using default MediaRecorder options:', e);
                    mediaRecorder = new MediaRecorder(mediaStream);
                }

                mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = () => {
                    handleRecordingComplete(mediaType, container);
                };

                mediaRecorder.onerror = (event) => {
                    console.error('MediaRecorder error:', event.error);
                };

                // Start recording
                mediaRecorder.start(100); // Collect data every 100ms
                
                // Start recording timer
                recordingStartTime = new Date();
                const timerElement = container.querySelector('.recording-timer');
                if (timerElement) {
                    timerElement.classList.remove('d-none');
                    container.timerInterval = setInterval(() => updateTimer(container), 1000);
                }
                
                // Auto-stop recording after 5 minutes (safety measure)
                container.recordingTimeout = setTimeout(() => {
                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        stopMediaRecording(mediaType, container);
                        const button = container.querySelector('.capture-media');
                        if (button) {
                            button.classList.remove('recording');
                            button.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> Record`;
                        }
                    }
                }, 5 * 60 * 1000); // 5 minutes

                return true;

            } catch (error) {
                console.error('Error accessing media devices:', error);
                alert(`Error accessing ${mediaType === 'audio' ? 'microphone' : 'camera'}. Please check permissions and try again.`);
                
                // Reset the record button
                const button = container.querySelector('.capture-media');
                if (button) {
                    button.classList.remove('recording');
                    button.innerHTML = `<i class="fas fa-${mediaType === 'audio' ? 'microphone' : 'video'} me-2"></i> Record`;
                }
                
                return false;
            }
        }

        // Handle recording completion
        function handleRecordingComplete(mediaType, container) {
            const blob = new Blob(recordedChunks, {
                type: mediaRecorder.mimeType || (mediaType === 'video' ? 'video/webm' : 'audio/webm')
            });

            console.log('Recording completed, blob size:', blob.size);

            // Clean up live preview if it exists
            if (container.livePreview && mediaType === 'video') {
                container.livePreview.srcObject = null;
                // Don't remove the element, just change it back to recorded video
            }
            
            // Stop all tracks in the stream
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
            }

            // Create a file from the blob
            const timestamp = Date.now();
            const file = new File([blob], `${mediaType}_${timestamp}.webm`, {
                type: blob.type
            });

            // Create a data transfer object to simulate file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            // Set the file input files to the recorded file
            const fileInput = container.querySelector('.media-input');
            if (fileInput) {
                fileInput.files = dataTransfer.files;
            }

            // Update the preview
            const previewElement = container.querySelector(`.preview-${mediaType}`);
            const previewContainer = container.querySelector(`.preview-${mediaType}-container`);
            
            if (previewElement && previewContainer) {
                // Clear previous sources
                previewElement.innerHTML = '';
                
                // Create new source
                const source = document.createElement('source');
                source.src = URL.createObjectURL(blob);
                source.type = blob.type;
                previewElement.appendChild(source);
                
                // Ensure preview element has controls and is visible
                previewElement.controls = true;
                previewElement.style.display = 'block';
                previewContainer.style.display = 'block';
                
                // Load the media
                previewElement.load();
                
                // For video, replace live preview with recorded video
                if (mediaType === 'video' && container.livePreview) {
                    container.livePreview.parentNode.replaceChild(previewElement, container.livePreview);
                    container.livePreview = null;
                }
            }
            
            // Store file data in hidden input
            const reader = new FileReader();
            reader.onload = (e) => {
                const dataInput = container.querySelector('.media-data');
                if (dataInput) {
                    dataInput.value = e.target.result.split(',')[1]; // Store base64 data
                }
            };
            reader.readAsDataURL(blob);
        }

        // Stop media recording
        async function stopMediaRecording(mediaType, container) {
            // Clear recording timer
            if (container.timerInterval) {
                clearInterval(container.timerInterval);
                container.timerInterval = null;
            }
            
            const timerElement = container.querySelector('.recording-timer');
            if (timerElement) {
                timerElement.classList.add('d-none');
            }
            
            // Clear auto-stop timeout
            if (container.recordingTimeout) {
                clearTimeout(container.recordingTimeout);
                container.recordingTimeout = null;
            }
            
            // Stop MediaRecorder if it's recording
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
            }
        }
        
        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            // Stop all media streams
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
            }
            
            // Stop recording if active
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
            }
        });

        // Initialize Dropzone for pre-login images
        Dropzone.autoDiscover = false;
        
        // Initialize Dropzone for pre-login image upload if the element exists
        var preLoginDropzone = document.getElementById('preLoginImageDropzone');
        if (preLoginDropzone) {
            window.myDropzone = new Dropzone("#preLoginImageDropzone", {
                url: "{{ route('orders.uploadTemporaryImage') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                paramName: "pre_login_image",
                maxFiles: 5,
                acceptedFiles: 'image/*',
                autoProcessQueue: true,
                addRemoveLinks: true,
                uploadMultiple: false,
                timeout: 0,
                previewsContainer: "#preLoginImageDropzone",
                dictDefaultMessage: "{{ __('dashboard.pre_logout_image') }}",
                init: function() {
                    var dropzone = this;
                    
                    this.on("error", function(file, message) {
                        console.error('Upload error:', message);
                        if (message && typeof message === 'object' && message.message) {
                            alert('Upload error: ' + message.message);
                        } else if (typeof message === 'string' && message.startsWith('<!DOCTYPE')) {
                            // Handle HTML response (likely a login page)
                            alert('Session expired. Please refresh the page and log in again.');
                            window.location.reload();
                        } else {
                            alert('An error occurred during upload. Please try again.');
                        }
                        this.removeFile(file);
                    });
                    
                    this.on("success", function(file, response) {
                        console.log("Upload successful:", response);
                        if (response && response[0]) {
                            file.serverId = response[0].id;
                            uploadedImages.push({ path: response[0].filePath, id: response[0].id });
                            document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);
                        }
                    });
                    
                    this.on("removedfile", function(file) {
                        if (!file.serverId) return;
                        
                        $.ajax({
                            url: "{{ route('orders.removeImage', ['id' => '']) }}/" + file.serverId + "?type=logout",
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                uploadedImages = uploadedImages.filter(function(image) {
                                    return image.id != file.serverId;
                                });
                                document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);
                                console.log('File removed successfully');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error removing file:', error);
                                alert('Error removing file. Please try again.');
                            }
                        });
                    });
                }
            });
        }
                    }
                });
            }
        });

        // Update uploaded images on form submit
        $("form#kt_ecommerce_add_product_form").on("submit", function (e) {
            document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);
        });

        // Camera functionality for dropzone (if needed)
        var openCameraBtn = document.getElementById('openCamera');
        var cameraInput = document.getElementById('cameraInput');
        
        if (openCameraBtn && cameraInput) {
            openCameraBtn.addEventListener('click', function() {
                cameraInput.click();
            });

            // Handle the camera input change (if camera input exists)
            cameraInput.addEventListener('change', function(event) {
                var files = event.target.files;
                if (files.length > 0 && window.myDropzone) {
                    window.myDropzone.addFile(files[0]);
                }
            });
        }
    }); // End of DOMContentLoaded
    </script>

@endsection