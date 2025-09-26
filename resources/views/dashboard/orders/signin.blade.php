@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.sign_in'))
@section('content')

<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        @include('dashboard.orders.nav')
        
        @if ($order->video_note)
            <div class="modal fade" id="deleteVideoNoteModal" tabindex="-1" aria-labelledby="deleteVideoNoteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                         <!-- customer information -->
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
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <h3 class="card-title">{{ __('dashboard.edit_time_and_image') }}</h3>
            </div>
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
                    <div class="mb-3">
                        <div class="dropzone dropzone-previews" id="preLoginImageDropzone"></div>
                        <input type="file" accept="image/*" capture="camera" id="cameraInput" style="display:none;">
                        <button type="button" class="btn btn-secondary mt-3"
                            id="openCamera">{{ __('dashboard.capture_photo') }}</button>
                    </div>
                    <!-- Audio Recording Section -->


                    <!-- Video Recording Section -->
                    <div class="mb-3">
                        <label class="form-label m-3">
                            <i class="fas fa-video"></i> {{ __('dashboard.video_note') }}
                        </label>
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <!-- Video Preview -->
                                <video id="videoPreview" class="w-100 mb-3" style="max-width: 100%; background: #000;" controls></video>
                                
                                <!-- Video Controls -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <button type="button" id="recordButton" class="btn btn-primary">
                                        <i class="fas fa-video me-2"></i>{{ __('dashboard.start_recording') }}
                                    </button>
                                    <button type="button" id="stopButton" class="btn btn-danger" disabled>
                                        <i class="fas fa-stop me-2"></i>{{ __('dashboard.stop_recording') }}
                                    </button>
                                    <input type="file" id="videoInput" name="video_note" accept="video/*" class="d-none">
                                    
                                    @if ($order->video_note)
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteVideoNoteModal">
                                            <i class="fas fa-trash me-2"></i>{{ __('dashboard.delete_video_note') }}
                                        </button>
                                        <!-- Show existing video if it exists -->
                                        <div class="w-100 mt-3">
                                            <p class="mb-2">{{ __('dashboard.existing_video') }}:</p>
                                            <video class="w-100" controls>
                                                <source src="{{ asset('storage/' . $order->video_note) }}">
                                                {{ __('dashboard.your_browser_does_not_support_video_tag') }}
                                            </video>
                                        </div>
                                    @endif
                                </div>
                                @endif
                                <input type="file" accept="video/*" capture="camcorder" id="videoInput"
                                    name="video_note" class="form-control" style="display:none;">
                                <video id="videoPlayback" class="col-5 m-3" controls style="display:none;"></video>
                                <button type="button" id="saveVideoButton" class="btn btn-success col-3"
                                    style="display: none;">
                                    {{ __('dashboard.save_video') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" id="kt_ecommerce_add_product_submit"
                            class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
{{ ... }}
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
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let videoRecorder;
        let videoStream;
        let videoPreview = document.getElementById('videoPreview');
        let recordButton = document.getElementById('recordButton');
        let stopButton = document.getElementById('stopButton');
        let videoInput = document.getElementById('videoInput');
        let isRecording = false;
        
        // Initialize RecordRTC for video recording
        async function startVideoRecording() {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: true
                });
                
                videoPreview.srcObject = videoStream;
                videoPreview.muted = true;
                videoPreview.play();
                
                const options = {
                    mimeType: 'video/webm;codecs=vp8,opus',
                    audioBitsPerSecond: 128000,
                    videoBitsPerSecond: 2500000,
                    bitsPerSecond: 2628000
                };
                
                videoRecorder = RecordRTC(videoStream, {
                    type: 'video',
                    mimeType: 'video/webm',
                    recorderType: RecordRTC.MediaStreamRecorder,
                    timeSlice: 1000,
                    ondataavailable: function(blob) {
                        console.log('Video chunk available');
                    }
                });
                
                videoRecorder.startRecording();
                isRecording = true;
                recordButton.disabled = true;
                stopButton.disabled = false;
                
            } catch (error) {
                console.error('Error accessing media devices:', error);
                alert('Error accessing camera/microphone. Please ensure you have granted the necessary permissions.');
            }
        }
        
        async function stopVideoRecording() {
            return new Promise((resolve) => {
                videoRecorder.stopRecording(async function() {
                    const blob = videoRecorder.getBlob();
                    
                    // Stop all tracks in the stream
                    videoStream.getTracks().forEach(track => track.stop());
                    
                    // Create a file from the blob
                    const file = new File([blob], 'video_note.webm', { type: 'video/webm' });
                    
                    // Create a data URL for preview
                    const videoURL = URL.createObjectURL(blob);
                    videoPreview.srcObject = null;
                    videoPreview.src = videoURL;
                    videoPreview.controls = true;
                    
                    // Create a file input element and set the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    videoInput.files = dataTransfer.files;
                    
                    isRecording = false;
                    recordButton.disabled = false;
                    stopButton.disabled = true;
                    
                    resolve();
                });
            });
        }
        
        // Event listeners
        if (recordButton) {
            recordButton.addEventListener('click', startVideoRecording);
        }
        
        if (stopButton) {
            stopButton.addEventListener('click', stopVideoRecording);
        }
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }
            if (videoRecorder && isRecording) {
                videoRecorder.stopRecording();
            }
        });

        // عناصر HTML
        const recordButton = document.getElementById('recordVideoButton');
        const videoInput = document.getElementById('videoInput');
        const videoPlayback = document.getElementById('videoPlayback');
        const deleteVideoButton = document.querySelector('#deleteVideoNoteModal button[type="submit"]');

        // وظيفة بدء التسجيل
        async function startRecording() {
            try {
                // الحصول على تدفق الفيديو والصوت
                videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                videoPlayback.srcObject = videoStream; // عرض الفيديو المباشر في عنصر الفيديو
                videoPlayback.style.display = 'block';

                mediaRecorder = new MediaRecorder(videoStream, { mimeType: 'video/webm' });
                recordedChunks = [];

                // تخزين البيانات المسجلة
                mediaRecorder.ondataavailable = event => {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };

                // عند انتهاء التسجيل
                mediaRecorder.onstop = () => {
                    const blob = new Blob(recordedChunks, { type: 'video/webm' });
                    videoPlayback.srcObject = null; // إيقاف عرض الكاميرا المباشرة
                    videoPlayback.src = URL.createObjectURL(blob); // عرض الفيديو المسجل

                    // إعداد الفيديو للرفع
                    const file = new File([blob], 'video_note.webm', { type: 'video/webm' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    videoInput.files = dataTransfer.files;
                };

                mediaRecorder.start();
                recordButton.textContent = 'إيقاف التسجيل';
            } catch (error) {
                console.error('Error accessing camera:', error);
            }
        }

        // وظيفة إيقاف التسجيل
        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
                videoStream.getTracks().forEach(track => track.stop()); // إيقاف الكاميرا والصوت
                recordButton.textContent = 'بدء التسجيل';
            }
        }

        // التعامل مع الضغط على زر بدء/إيقاف التسجيل
        recordButton.addEventListener('click', () => {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                stopRecording();
            } else {
                startRecording();
            }
        });

        // وظيفة حذف الفيديو
        deleteVideoButton.addEventListener('click', () => {
            videoPlayback.src = '';
            videoPlayback.srcObject = null;
            videoPlayback.style.display = 'none';
            videoInput.value = ''; // حذف الفيديو من الـ input
        });

        // عرض زر حذف الفيديو إذا كان الفيديو موجودًا مسبقًا
        if (videoPlayback.src) {
            videoPlayback.style.display = 'block';
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let recordButton = document.getElementById('recordAudioButton');
        let stopButton = document.getElementById('stopAudioButton');
        let voiceInput = document.getElementById('voiceInput');
        let audioPlayback = document.getElementById('audioPlayback');
        let audioRecorder;
        let audioStream;
        let isRecording = false;
        
        // Initialize RecordRTC for audio recording
        async function startAudioRecording() {
            try {
                audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                
                audioRecorder = RecordRTC(audioStream, {
                    type: 'audio',
                    mimeType: 'audio/webm',
                    recorderType: RecordRTC.StereoAudioRecorder,
                    desiredSampRate: 16000,
                    numberOfAudioChannels: 1,
                    timeSlice: 1000,
                    ondataavailable: function(blob) {
                        console.log('Audio chunk available');
                    }
                });
                
                audioRecorder.startRecording();
                isRecording = true;
                recordButton.disabled = true;
                stopButton.disabled = false;
                
            } catch (error) {
                console.error('Error accessing microphone:', error);
                alert('Error accessing microphone. Please ensure you have granted the necessary permissions.');
            }
        }
        
        async function stopAudioRecording() {
            return new Promise((resolve) => {
                audioRecorder.stopRecording(async function() {
                    const blob = audioRecorder.getBlob();
                    
                    // Stop all tracks in the stream
                    audioStream.getTracks().forEach(track => track.stop());
                    
                    // Create a file from the blob
                    const file = new File([blob], 'audio_note.webm', { type: 'audio/webm' });
                    
                    // Create an audio URL for playback
                    const audioURL = URL.createObjectURL(blob);
                    audioPlayback.src = audioURL;
                    audioPlayback.controls = true;
                    
                    // Create a file input element and set the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    voiceInput.files = dataTransfer.files;
                    
                    isRecording = false;
                    recordButton.disabled = false;
                    stopButton.disabled = true;
                    
                    resolve();
                });
            });
        }
        
        // Event listeners
        if (recordButton) {
            recordButton.addEventListener('click', startAudioRecording);
        }
        
        if (stopButton) {
            stopButton.addEventListener('click', stopAudioRecording);
        }
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (audioStream) {
                audioStream.getTracks().forEach(track => track.stop());
            }
            if (audioRecorder && isRecording) {
                audioRecorder.stopRecording();
            }
        });

        let mediaRecorder;
        let audioChunks = [];

        recordButton.addEventListener('click', function () {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                recordButton.innerHTML = '<i class="fas fa-circle"></i> {{ __("dashboard.start_recording") }}';
            } else {
                navigator.mediaDevices.getUserMedia({ audio: true })
                    .then(stream => {
                        mediaRecorder = new MediaRecorder(stream);
                        mediaRecorder.start();

                        recordButton.innerHTML = '<i class="fas fa-stop"></i> {{ __("dashboard.stop_recording") }}';

                        mediaRecorder.ondataavailable = event => {
                            audioChunks.push(event.data);
                        };

                        mediaRecorder.onstop = () => {
                            let audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                            let audioUrl = URL.createObjectURL(audioBlob);
                            audioPlayback.src = audioUrl;
                            audioPlayback.style.display = 'block';
                            voiceInput.files = createFileList(audioBlob);
                            audioChunks = [];

                            // Convert the Blob to a Base64 string and save to localStorage
                            saveAudioToLocalStorage(audioBlob);
                        };
                    });
            }
        });

        function createFileList(blob) {
            let file = new File([blob], "recorded-audio.wav", { type: "audio/wav" });
            let dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            return dataTransfer.files;
        }

        function saveAudioToLocalStorage(blob) {
            const reader = new FileReader();
            reader.onloadend = () => {
                localStorage.setItem('recordedAudio', reader.result);
            };
            reader.readAsDataURL(blob);
        }

        // Load saved audio from localStorage
        const savedAudio = localStorage.getItem('recordedAudio');
        if (savedAudio) {
            audioPlayback.src = savedAudio;
            audioPlayback.style.display = 'block';
        }
    });
</script>

@endsection
