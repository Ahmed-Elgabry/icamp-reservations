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
                            <div class="dropzone dropzone-previews" id="preLoginImageDropzone"></div>
                        <div class="mb-3">

                        <!-- Video Recording Section for Logout -->
                        <div class="mb-3">
                            <label class="form-label m-3">
                                <i class="fas fa-video"></i> {{ __('dashboard.video_note') }}
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <!-- Video Preview -->
                                    <video id="videoPreviewLogout" class="w-100 mb-3" style="max-width: 100%; background: #000;" controls></video>
                                    
                                    <!-- Video Controls -->
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <button type="button" id="recordButtonLogout" class="btn btn-primary">
                                            <i class="fas fa-video me-2"></i>{{ __('dashboard.start_recording') }}
                                        </button>
                                        <button type="button" id="stopButtonLogout" class="btn btn-danger" disabled>
                                            <i class="fas fa-stop me-2"></i>{{ __('dashboard.stop_recording') }}
                                        </button>
                                        <input type="file" id="videoInputLogout" name="video_note_logout" accept="video/*" class="d-none">
                                        
                                        @if ($order->video_note_logout)
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteVideoNoteModal">
                                                <i class="fas fa-trash me-2"></i>{{ __('dashboard.delete_video_note') }}
                                            </button>
                                            <!-- Show existing video if it exists -->
                                            <div class="w-100 mt-3">
                                                <p class="mb-2">{{ __('dashboard.existing_video') }}:</p>
                                                <video class="w-100" controls>
                                                    <source src="{{ asset('storage/' . $order->video_note_logout) }}">
                                                    {{ __('dashboard.your_browser_does_not_support_video_tag') }}
                                                </video>
                                            </div>
                                        @endif
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
    </style>
@endpush

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/recordrtc@5.6.2/dist/RecordRTC.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        $(document).ready(function () {
            var uploadedImages = [];

            var myDropzone = new Dropzone("#preLoginImageDropzone", {
                url: "{{ route('orders.uploadTemporaryImage') }}",
                paramName: "pre_login_image",
                maxFiles: 5,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                parallelUploads: 5,
                uploadMultiple: true,
                previewsContainer: ".dropzone-previews",
                dictDefaultMessage: "{{ __('dashboard.pre_logout_image') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                init: function () {
                    var myDropzone = this;

                    // Add existing images to dropzone
                    @if($order->PreLogoutImages)
                        @foreach($order->PreLogoutImages as $image)
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
                        formData.append("type", "logout");
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
                        url: "{{ route('orders.removeImage', ['id' => 'fileId']) }}?type=logout".replace('fileId', fileId),
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

            $("form#kt_ecommerce_add_product_form").on("submit", function (e) {
                document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);
            });



            // Open camera to take photo
            $("#openCamera").on('click', function () {
                $("#cameraInput").click();
            });

            // Handle the camera input change
            $("#cameraInput").on('change', function (event) {
                var files = event.target.files;
                if (files.length > 0) {
                    myDropzone.addFile(files[0]);
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Video Recording Variables
            let videoRecorder;
            let videoStream;
            
            // Video Elements
            const videoPreview = document.getElementById('videoPreviewLogout');
            const recordButton = document.getElementById('recordButtonLogout');
            const stopButton = document.getElementById('stopButtonLogout');
            const videoInput = document.getElementById('videoInputLogout');
            
            // Start/Stop button event listeners
            if (recordButton && stopButton) {
                recordButton.addEventListener('click', startVideoRecording);
                stopButton.addEventListener('click', stopVideoRecording);
            }
            
            // Video file input handler
            if (videoInput) {
                videoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const videoURL = URL.createObjectURL(file);
                        videoPreview.src = videoURL;
                        videoPreview.controls = true;
                    }
                });
            }
            
            // Start Video Recording Function
            async function startVideoRecording() {
                try {
                    videoStream = await navigator.mediaDevices.getUserMedia({ 
                        video: true, 
                        audio: true 
                    });
                    
                    videoPreview.srcObject = videoStream;
                    videoPreview.muted = true; // Mute to avoid echo
                    videoPreview.play();
                    
                    videoRecorder = new RecordRTC.MediaStreamRecorder(videoStream, {
                        type: 'video',
                        mimeType: 'video/webm;codecs=vp9',
                        timeSlice: 1000, // Save every 1 second
                        ondataavailable: function(blob) {
                            // Handle data if needed
                        }
                    });
                    
                    videoRecorder.startRecording();
                    
                    // Update UI
                    recordButton.disabled = true;
                    stopButton.disabled = false;
                    
                } catch (error) {
                    console.error('Error accessing camera:', error);
                    alert('Error accessing camera. Please check permissions and try again.');
                }
            }
            
            // Stop Video Recording Function
            function stopVideoRecording() {
                if (!videoRecorder) return;
                
                videoRecorder.stopRecording(function() {
                    // Get the recorded blob
                    const blob = videoRecorder.getBlob();
                    
                    // Create a file from the blob
                    const file = new File([blob], 'video_note_' + Date.now() + '.webm', { 
                        type: 'video/webm' 
                    });
                    
                    // Create a DataTransfer object and add the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    
                    // Set the file input files to the recorded file
                    videoInput.files = dataTransfer.files;
                    
                    // Create a URL for the recorded video
                    const videoURL = URL.createObjectURL(blob);
                    
                    // Update the video preview
                    videoPreview.srcObject = null;
                    videoPreview.src = videoURL;
                    videoPreview.muted = false;
                    videoPreview.controls = true;
                    
                    // Stop all tracks in the stream
                    if (videoStream) {
                        videoStream.getTracks().forEach(track => track.stop());
                    }
                    
                    // Update UI
                    recordButton.disabled = false;
                    stopButton.disabled = true;
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Audio Recording Variables
            let audioRecorder;
            let audioStream;
            
            // Audio Elements
            const audioPlayback = document.getElementById('audioPlaybackLogout');
            const recordAudioButton = document.getElementById('recordAudioButtonLogout');
            const stopAudioButton = document.getElementById('stopAudioButtonLogout');
            const voiceInput = document.getElementById('voiceInputLogout');
            
            // Only proceed if we're on a page with audio recording
            if (recordAudioButton && stopAudioButton && audioPlayback && voiceInput) {
                // Start/Stop button event listeners for audio
                recordAudioButton.addEventListener('click', startAudioRecording);
                stopAudioButton.addEventListener('click', stopAudioRecording);
                
                // Audio file input handler
                voiceInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const audioURL = URL.createObjectURL(file);
                        audioPlayback.src = audioURL;
                        audioPlayback.controls = true;
                    }
                });
                
                // Start Audio Recording Function
                async function startAudioRecording() {
                    try {
                        audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        
                        audioRecorder = new RecordRTC(audioStream, {
                            type: 'audio',
                            mimeType: 'audio/webm',
                            recorderType: RecordRTC.MediaStreamRecorder,
                            timeSlice: 1000, // Save every 1 second
                            ondataavailable: function(blob) {
                                // Handle data if needed
                            }
                        });
                        
                        audioRecorder.startRecording();
                        
                        // Update UI
                        recordAudioButton.disabled = true;
                        stopAudioButton.disabled = false;
                        
                    } catch (error) {
                        console.error('Error accessing microphone:', error);
                        alert('Error accessing microphone. Please check permissions and try again.');
                    }
                }
                
                // Stop Audio Recording Function
                function stopAudioRecording() {
                    if (!audioRecorder) return;
                    
                    audioRecorder.stopRecording(function() {
                        // Get the recorded blob
                        const blob = audioRecorder.getBlob();
                        
                        // Create a file from the blob
                        const file = new File([blob], 'voice_note_' + Date.now() + '.webm', { 
                            type: 'audio/webm' 
                        });
                        
                        // Create a DataTransfer object and add the file
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        
                        // Set the file input files to the recorded file
                        voiceInput.files = dataTransfer.files;
                        
                        // Create a URL for the recorded audio
                        const audioURL = URL.createObjectURL(blob);
                        
                        // Update the audio playback
                        audioPlayback.src = audioURL;
                        audioPlayback.controls = true;
                        
                        // Stop all tracks in the stream
                        if (audioStream) {
                            audioStream.getTracks().forEach(track => track.stop());
                        }
                        
                        // Update UI
                        recordAudioButton.disabled = false;
                        stopAudioButton.disabled = true;
                    });
                }
            }
        });
    </script>

@endsection
