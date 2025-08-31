@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.edit_time_and_image'))
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
                        <div class="col-md-1">
                            <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                            <div class="fw-bold">{{ $order->id }}</div>
                        </div>
                        <div class="col-md-3">
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
                            <!-- Input field to capture photo from camera -->
                            <input type="file" accept="image/*" capture="camera" id="cameraInput" style="display:none;">
                            <button type="button" class="btn btn-secondary mt-3"
                                id="openCamera">{{ __('dashboard.capture_photo') }}</button>
                        </div>

                        <div class="mb-3">
                            <label for="voice_note_logout" class="form-label m-3">
                                <i class="fas fa-microphone-alt"></i> {{ __('dashboard.voice_note') }} :
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-12 d-flex align-items-center">
                                    <button type="button" id="recordButton" class="btn btn-info col-3 me-2"
                                        aria-label="{{ __('dashboard.start_recording') }}">
                                        <i class="fas fa-circle"></i> {{ __('dashboard.start_recording') }}
                                    </button>
                                    @if ($order->voice_note_logout)
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteVoiceNoteModal">
                                                {{ __('dashboard.delete_voice_note') }}
                                            </button>
                                        </div>
                                    @endif
                                    <input type="file" accept="audio/*" capture="microphone" id="voiceInput"
                                        name="voice_note_logout" class="form-control" style="display:none;">
                                    <audio id="audioPlayback" class="col-5 m-3" controls></audio>

                                    <small class="form-text text-muted ms-2">
                                        {{ __('dashboard.record_voice_note_instruction') }}
                                        <br>
                                        <i class="fas fa-exclamation-circle"></i> {{ __('dashboard.audio_files_only') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="video_note_logout" class="form-label m-3">
                                <i class="fas fa-video"></i> {{ __('dashboard.video_note') }} :
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-12 d-flex align-items-center">
                                    <button type="button" id="recordVideoButton" class="btn btn-info col-3 me-2">
                                        <i class="fas fa-circle"></i> {{ __('dashboard.start_recording_video') }}
                                    </button>
                                    <button type="button" id="stopVideoButton" class="btn btn-warning col-3 me-2"
                                        style="display: none;">
                                        {{ __('dashboard.stop_recording_video') }}
                                    </button>
                                    @if ($order->video_note_logout)
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteVideoNoteModal">
                                            {{ __('dashboard.delete_video_note') }}
                                        </button>

                                        <video class="col-5 m-3" controls>
                                            <source src="{{ asset('storage/' . $order->video_note_logout) }}">
                                            {{ __('dashboard.your_browser_does_not_support_video_tag') }}
                                        </video>
                                    @endif
                                    <input type="file" accept="video/*" capture="camcorder" id="videoInput"
                                        name="video_note_logout" class="form-control" style="display:none;">
                                    <video id="videoPlayback" class="col-5 m-3" controls style="display:none;"></video>
                                    <button type="button" id="saveVideoButton" class="btn btn-success col-3"
                                        style="display: none;">
                                        {{ __('dashboard.save_video') }}
                                    </button>
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
            let mediaRecorder;
            let recordedChunks = [];
            let videoStream;

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
                        const file = new File([blob], 'video_note_logout.webm', { type: 'video/webm' });
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
            let recordButton = document.getElementById('recordButton');
            let voiceInput = document.getElementById('voiceInput');
            let audioPlayback = document.getElementById('audioPlayback');

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
