@extends('dashboard.layouts.app')
@section('pageTitle', isset($campReport) ? __('dashboard.edit_camp_report') : __('dashboard.create_camp_report'))

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header">
            <h3 class="card-title">@lang('dashboard.'.(isset($campReport) ? 'edit_camp_report' : 'create_camp_report'))</h3>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ isset($campReport) ? route('camp-reports.update', $campReport) : route('camp-reports.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($campReport)) @method('PUT') @endif

                <div class="row g-5 mb-8">
                    <div class="col-md-6">
                        <div class="mb-6">
                            <label class="form-label required">@lang('dashboard.report_date')</label>
                            <input type="date" name="report_date" class="form-control" required
                                   value="{{ old('report_date', isset($campReport) ? $campReport->report_date->format('Y-m-d') : '') }}">
                        </div>

                        <div class="mb-6">
                            <label class="form-label ">@lang('dashboard.service')</label>
                            <select name="service_id" class="form-select" >
                                <option value="">@lang('dashboard.select_service')</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', isset($campReport) ? $campReport->service_id : '') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-6">
                            <label class="form-label ">@lang('dashboard.camp_name')</label>
                            <input type="text" name="camp_name" class="form-control"
                                   value="{{ old('camp_name', $campReport->camp_name ?? '') }}">
                        </div>

                        <div class="mb-6">
                            <label class="form-label">@lang('dashboard.general_notes')</label>
                            <textarea name="general_notes" rows="1" class="form-control">{{ old('general_notes', $campReport->general_notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="mb-4">@lang('dashboard.report_items')</h4>
                    <div id="items-container">
                        @if(old('items', null) || isset($campReport))
                            @foreach(old('items', isset($campReport) ? $campReport->items : []) as $index => $item)
                                <div class="card mb-4 item-row" data-index="{{ $index }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Item #{{ $index + 1 }}</h5>
                                        <button type="button" class="btn btn-sm btn-icon btn-danger remove-item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @if(isset($item['id']))
                                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item['id'] }}">
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">@lang('dashboard.item_name')</label>
                                                <input type="text" name="items[{{ $index }}][item_name]" class="form-control" required
                                                       value="{{ $item['item_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('dashboard.notes')</label>
                                                <textarea name="items[{{ $index }}][notes]" rows="1" class="form-control">{{ $item['notes'] ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">@lang('dashboard.photo_attachment')</label>
                                                <div class="media-upload-container" data-type="photo">
                                                    <div class="delete-flags-container"></div>
                                                    @if(isset($item['photo_path']))
                                                        <div class="preview-image-container mb-2">
                                                            <img src="{{ Storage::url($item['photo_path']) }}" class="preview-image img-thumbnail" style="max-width: 100%;">
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                                                <i class="bi bi-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="items[{{ $index }}][existing_photo]" value="{{ $item['photo_path'] }}">
                                                    @else
                                                        <div class="preview-image-container mb-2" style="display: none;">
                                                            <img src="" class="preview-image img-thumbnail" style="max-width: 100%;">
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                                                <i class="bi bi-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    @endif
                                                    <div class="btn-group w-100">
                                                        <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="photo">
                                                            <i class="bi bi-camera"></i> Capture
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary upload-media">
                                                            <i class="bi bi-upload"></i> Upload
                                                        </button>
                                                    </div>
                                                    <input type="file" name="items[{{ $index }}][photo]" class="media-input d-none" accept="image/*">
                                                    <input type="hidden" name="items[{{ $index }}][photo_data]" class="media-data">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">@lang('dashboard.audio_attachment')</label>
                                                <div class="media-upload-container" data-type="audio">
                                                    <div class="delete-flags-container"></div>
                                                    @if(isset($item['audio_path']))
                                                        <div class="preview-audio-container mb-2">
                                                            <audio controls class="preview-audio w-100">
                                                                <source src="{{ Storage::url($item['audio_path']) }}">
                                                            </audio>
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                                                <i class="bi bi-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="items[{{ $index }}][existing_audio]" value="{{ $item['audio_path'] }}">
                                                    @else
                                                        <div class="preview-audio-container mb-2" style="display: none;">
                                                            <audio controls class="preview-audio w-100">
                                                                <source src="">
                                                            </audio>
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                                                <i class="bi bi-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    @endif
                                                    <div class="btn-group w-100">
                                                        <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="audio">
                                                            <i class="bi bi-mic"></i> Record
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary upload-media">
                                                            <i class="bi bi-upload"></i> Upload
                                                        </button>
                                                    </div>
                                                    <input type="file" name="items[{{ $index }}][audio]" class="media-input d-none" accept="audio/*">
                                                    <input type="hidden" name="items[{{ $index }}][audio_data]" class="media-data">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">@lang('dashboard.video_attachment')</label>
                                                <div class="media-upload-container" data-type="video">
                                                    <div class="delete-flags-container"></div>
                                                    @if(isset($item['video_path']))
                                                        <div class="preview-video-container mb-2">
                                                            <video controls class="preview-video w-100">
                                                                <source src="{{ Storage::url($item['video_path']) }}">
                                                            </video>
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                                                <i class="bi bi-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="items[{{ $index }}][existing_video]" value="{{ $item['video_path'] }}">
                                                    @else
                                                        <div class="preview-video-container mb-2" style="display: none;">
                                                            <video controls class="preview-video w-100">
                                                                <source src="">
                                                            </video>
                                                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media">
                                                                <i class="bi bi-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    @endif
                                                    <div class="btn-group w-100">
                                                        <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="video">
                                                            <i class="bi bi-camera-video"></i> Record
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary upload-media">
                                                            <i class="bi bi-upload"></i> Upload
                                                        </button>
                                                    </div>
                                                    <input type="file" name="items[{{ $index }}][video]" class="media-input d-none" accept="video/*">
                                                    <input type="hidden" name="items[{{ $index }}][video_data]" class="media-data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">@lang('dashboard.no_items_added')</div>
                        @endif
                    </div>
                    <button type="button" id="add-item" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> @lang('dashboard.add_item')
                    </button>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary">
                        @lang('dashboard.'.(isset($campReport) ? 'update_report' : 'save_report'))
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemsContainer = document.getElementById('items-container');
            const addItemBtn = document.getElementById('add-item');
            let itemIndex = {{ isset($campReport) ? $campReport->items->count() : 0 }};

            // Add new item
            addItemBtn.addEventListener('click', function() {
                const template = `
                    <div class="card mb-4 item-row" data-index="${itemIndex}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Item #${itemIndex + 1}</h5>
                            <button type="button" class="btn btn-sm btn-icon btn-danger remove-item">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">@lang('dashboard.item_name')</label>
                                    <input type="text" name="items[${itemIndex}][item_name]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">@lang('dashboard.notes')</label>
                                    <textarea name="items[${itemIndex}][notes]" rows="1" class="form-control"></textarea>
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
                                        <input type="file" name="items[${itemIndex}][photo]" class="media-input d-none" accept="image/*">
                                        <input type="hidden" name="items[${itemIndex}][photo_data]" class="media-data">
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
                                        <input type="file" name="items[${itemIndex}][audio]" class="media-input d-none" accept="audio/*">
                                        <input type="hidden" name="items[${itemIndex}][audio_data]" class="media-data">
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
                                        <input type="file" name="items[${itemIndex}][video]" class="media-input d-none" accept="video/*">
                                        <input type="hidden" name="items[${itemIndex}][video_data]" class="media-data">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                itemsContainer.insertAdjacentHTML('beforeend', template);
                itemIndex++;

                // Initialize media handlers for the new item
                initializeMediaHandlers(itemsContainer.lastElementChild);
            });

            // Remove item
            itemsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-item')) {
                    const itemRow = e.target.closest('.item-row');
                    const itemId = itemRow.querySelector('input[name$="[id]"]')?.value;

                    if (itemId) {
                        // For existing items, add to deleted_items array
                        const deletedItemsInput = document.createElement('input');
                        deletedItemsInput.type = 'hidden';
                        deletedItemsInput.name = 'deleted_items[]';
                        deletedItemsInput.value = itemId;
                        document.querySelector('form').appendChild(deletedItemsInput);
                    }

                    itemRow.remove();
                }
            });

            // Initialize media handlers for existing items
            document.querySelectorAll('.item-row').forEach(item => {
                initializeMediaHandlers(item);
            });

            function initializeMediaHandlers(itemElement) {
                const mediaContainers = {
                    photo: {
                        input: itemElement.querySelector('[data-type="photo"] .media-input'),
                        previewContainer: itemElement.querySelector('[data-type="photo"] .preview-image-container'),
                        preview: itemElement.querySelector('[data-type="photo"] .preview-image'),
                        captureBtn: itemElement.querySelector('[data-type="photo"] .capture-media'),
                        uploadBtn: itemElement.querySelector('[data-type="photo"] .upload-media'),
                        dataInput: itemElement.querySelector('[data-type="photo"] .media-data')
                    },
                    audio: {
                        input: itemElement.querySelector('[data-type="audio"] .media-input'),
                        previewContainer: itemElement.querySelector('[data-type="audio"] .preview-audio-container'),
                        preview: itemElement.querySelector('[data-type="audio"] .preview-audio source'),
                        captureBtn: itemElement.querySelector('[data-type="audio"] .capture-media'),
                        uploadBtn: itemElement.querySelector('[data-type="audio"] .upload-media'),
                        dataInput: itemElement.querySelector('[data-type="audio"] .media-data')
                    },
                    video: {
                        input: itemElement.querySelector('[data-type="video"] .media-input'),
                        previewContainer: itemElement.querySelector('[data-type="video"] .preview-video-container'),
                        preview: itemElement.querySelector('[data-type="video"] .preview-video source'),
                        captureBtn: itemElement.querySelector('[data-type="video"] .capture-media'),
                        uploadBtn: itemElement.querySelector('[data-type="video"] .upload-media'),
                        dataInput: itemElement.querySelector('[data-type="video"] .media-data')
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
                    const removeBtn = itemElement.querySelector(`[data-type="${type}"] .remove-media`);
                    removeBtn.addEventListener('click', function() {
                        // Special handling for existing files
                        const existingInput = itemElement.querySelector(`input[name$="[existing_${type}]"]`);
                        if (existingInput) {
                            const deleteFlag = document.createElement('input');
                            deleteFlag.type = 'hidden';
                            deleteFlag.name = existingInput.name.replace('existing_', 'remove_');
                            deleteFlag.value = '1';
                            existingInput.closest('.media-upload-container').appendChild(deleteFlag);
                        }
                        clearMediaPreview(type, container);
                    });
                });
            }

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
                const input = container.input;
                input.click();
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
                    source.type = blob.type || (type === 'audio' ? 'audio/webm' : 'video/webm'); // Default MIME type if not set
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
                    const recordedBlob = new Blob([blob], { type: blob.type || (type === 'audio' ? 'audio/webm' : 'video/webm') });

                    // Create a File object with proper name and type
                    const fileName = `${type}-recording-${Date.now()}.${type === 'audio' ? 'webm' : 'webm'}`;
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
                container.previewContainer.innerHTML = ''; // Clear all preview content

                // Create hidden input to flag deletion to backend
                const deleteFlagInput = document.createElement('input');
                deleteFlagInput.type = 'hidden';
                deleteFlagInput.name = container.dataInput.name.replace('_data', `_remove_${type}`);
                deleteFlagInput.value = '1';
                container.previewContainer.closest('.media-upload-container').appendChild(deleteFlagInput);

                // Clear all media inputs
                container.input.value = '';
                container.dataInput.value = '';

                // Clear existing media reference if present
                const existingMediaInput = container.previewContainer.closest('.media-upload-container')
                    .querySelector(`input[name$="[existing_${type}]"]`);
                if (existingMediaInput) {
                    existingMediaInput.remove();
                }

                // Stop any ongoing recording
                if (container.mediaRecorder && container.mediaRecorder.state !== 'inactive') {
                    container.mediaRecorder.stop();
                }
                if (container.mediaStream) {
                    container.mediaStream.getTracks().forEach(track => track.stop());
                }
            }        });
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
        .item-row {
            transition: all 0.3s ease;
        }
        .item-row:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .remove-media {
            width: 100%;
        }
    </style>
@endpush
