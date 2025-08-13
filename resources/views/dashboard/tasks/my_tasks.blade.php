@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.my_tasks'))

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3>@lang('dashboard.my_tasks')</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_employee_tasks_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.creator')</th>
                            <th>@lang('dashboard.due_date')</th>
                            <th>@lang('dashboard.status')</th>
                            <th>@lang('dashboard.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->creator->name }}</td>
                                <td>{{ $task->due_date->format('Y-m-d') }}</td>
                                <td>
                                <span class="badge badge-light-primary">
                                    {{ $task->status }}
                                </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-task"
                                            data-task="{{ $task->toJson() }}"
                                            data-notification-id="{{ $task->notifications->first()->id ?? '' }}">
                                        @lang('dashboard.view')
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <p><strong>@lang('dashboard.description'):</strong> <span id="taskDescription"></span></p>
                            <p><strong>@lang('dashboard.due_date'):</strong> <span id="taskDueDate"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>@lang('dashboard.priority'):</strong> <span id="taskPriority"></span></p>
                            <p><strong>@lang('dashboard.created_at'):</strong> <span id="taskCreatedAt"></span></p>
                        </div>
                    </div>

                    <form id="statusForm">
                        @csrf
{{--                        @method('PUT')--}}
                        <input type="hidden" name="task_id" id="taskId">

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label">@lang('dashboard.status')</label>
                                <select name="status" class="form-select" id="taskStatus">
                                    <option value="pending">@lang('dashboard.pending')</option>
                                    <option value="in_progress">@lang('dashboard.in_progress')</option>
                                    <option value="completed">@lang('dashboard.completed')</option>
                                    <option value="failed">@lang('dashboard.failed')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-5" id="failureReasonContainer" style="display:none;">
                            <div class="col-md-12">
                                <label class="form-label">@lang('dashboard.failure_reason')</label>
                                <textarea name="failure_reason" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- Photo Attachment -->
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label class="form-label">@lang('dashboard.photo_attachment')</label>
                                <div id="photoAttachmentContainer"></div>
                                <input type="file" name="photo_attachment" accept="image/*" capture="camera" class="form-control">
                                <button type="button" class="btn btn-secondary mt-2" id="openCamera">
                                    @lang('dashboard.capture_photo')
                                </button>
                                <input type="hidden" name="delete_photo" id="delete_photo" value="0">
                            </div>
                        </div>

                        <!-- Video Attachment -->
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label class="form-label">@lang('dashboard.video_attachment')</label>
                                <div id="videoAttachmentContainer"></div>
                                <input type="file" name="video_attachment" accept="video/*" class="form-control">
                                <div class="mt-2">
                                    <button type="button" id="recordVideoButton" class="btn btn-info me-2">
                                        <i class="fas fa-video"></i> @lang('dashboard.record_video')
                                    </button>
                                    <button type="button" id="stopVideoButton" class="btn btn-warning me-2" style="display: none;">
                                        @lang('dashboard.stop_recording')
                                    </button>
                                    <button type="button" id="saveVideoButton" class="btn btn-success" style="display: none;">
                                        @lang('dashboard.save_video')
                                    </button>
                                </div>
                                <video id="videoPlayback" class="mt-2" controls style="display: none; max-width: 100%;"></video>
                                <input type="hidden" name="delete_video" id="delete_video" value="0">
                            </div>
                        </div>

                        <!-- Audio Attachment -->
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label class="form-label">@lang('dashboard.audio_attachment')</label>
                                <div id="audioAttachmentContainer"></div>
                                <input type="file" name="audio_attachment" accept="audio/*" class="form-control">
                                <button type="button" id="recordAudioButton" class="btn btn-info mt-2">
                                    <i class="fas fa-microphone"></i> @lang('dashboard.record_audio')
                                </button>
                                <audio id="audioPlayback" controls class="mt-2" style="display: none;"></audio>
                                <input type="hidden" name="delete_audio" id="delete_audio" value="0">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('dashboard.cancel')</button>
                            <button type="button" class="btn btn-primary" id="saveStatus">@lang('dashboard.save_changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Existing task modal initialization
            $('.view-task').click(function() {
                const task = $(this).data('task');
                const priorityColors = {
                    low: 'success',
                    medium: 'warning',
                    high: 'danger'
                };
                const formatDate = (dateString) => {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                };
                const notificationId = $(this).data('notification-id');

                if (notificationId) {
                    $.ajax({
                        url: `/notifications/${notificationId}/read`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PATCH'
                        },
                        success: function(response) {
                            // console.log('Notification marked as read');
                        }.bind(this),
                        error: function(xhr) {
                            console.error('Error marking notification as read');
                        }
                    });
                }

                $('#taskTitle').text(task.title);
                $('#taskDescription').text(task.description || 'N/A');
                $('#taskDueDate').text(formatDate(task.due_date));
                $('#taskPriority').html(`
                    <span class="badge bg-${priorityColors[task.priority] || 'secondary'}">
                        ${task.priority.toUpperCase()}
                    </span>
                `);
                $('#taskCreatedAt').text(formatDate(task.created_at));
                $('#taskId').val(task.id);
                $('#taskStatus').val(task.status);

                // Show/hide failure reason
                if(task.status == 'failed') {
                    $('#failureReasonContainer').show();
                    $('textarea[name="failure_reason"]').val(task.failure_reason);
                } else {
                    $('#failureReasonContainer').hide();
                }

                // Reset file inputs and previews
                $('input[type="file"]').val('');
                $('.attachment-preview').remove();
                $('#photoAttachmentContainer').empty();
                $('#videoAttachmentContainer').empty();
                $('#audioAttachmentContainer').empty();

                // Show existing attachments if they exist
                if(task.photo_attachment) {
                    $('#photoAttachmentContainer').append(`
                        <div class="attachment-preview mb-2">
                            <img src="/storage/${task.photo_attachment}" class="img-thumbnail" style="max-width: 200px;">
                            <button type="button" class="btn btn-sm btn-danger ms-2" onclick="document.getElementById('delete_photo').value = '1'; this.parentElement.remove();">
                                @lang('dashboard.delete')
                            </button>
                        </div>
                    `);
                }

                if(task.video_attachment) {
                    $('#videoAttachmentContainer').append(`
                        <div class="attachment-preview mb-2">
                            <video width="200" controls>
                                <source src="/storage/${task.video_attachment}">
                            </video>
                            <button type="button" class="btn btn-sm btn-danger ms-2" onclick="document.getElementById('delete_video').value = '1'; this.parentElement.remove();">
                                @lang('dashboard.delete')
                            </button>
                        </div>
                    `);
                }

                if(task.audio_attachment) {
                    $('#audioAttachmentContainer').append(`
                        <div class="attachment-preview mb-2">
                            <audio controls>
                                <source src="/storage/${task.audio_attachment}">
                            </audio>
                            <button type="button" class="btn btn-sm btn-danger ms-2" onclick="document.getElementById('delete_audio').value = '1'; this.parentElement.remove();">
                                @lang('dashboard.delete')
                            </button>
                        </div>
                    `);
                }

                $('#taskModal').modal('show');
            });

            $('#taskStatus').change(function() {
                if($(this).val() == 'failed') {
                    $('#failureReasonContainer').show();
                } else {
                    $('#failureReasonContainer').hide();
                }
            });

            $('#saveStatus').click(function() {
                const formData = new FormData($('#statusForm')[0]);

                $.ajax({
                    url: `/tasks/${$('#taskId').val()}/status`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#taskModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        // Clear previous errors
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            // Handle audio attachment error
                            if (errors.audio_attachment) {
                                const audioGroup = $('input[name="audio_attachment"]').closest('.mb-3');
                                audioGroup.append(`<div class="invalid-feedback">${errors.audio_attachment[0]}</div>`);
                                $('input[name="audio_attachment"]').addClass('is-invalid');
                            }

                            // Handle video attachment error
                            if (errors.video_attachment) {
                                const videoGroup = $('input[name="video_attachment"]').closest('.mb-3');
                                videoGroup.append(`<div class="invalid-feedback">${errors.video_attachment[0]}</div>`);
                                $('input[name="video_attachment"]').addClass('is-invalid');
                            }

                            // Handle photo attachment error
                            if (errors.photo_attachment) {
                                const photoGroup = $('input[name="photo_attachment"]').closest('.mb-3');
                                photoGroup.append(`<div class="invalid-feedback">${errors.photo_attachment[0]}</div>`);
                                $('input[name="photo_attachment"]').addClass('is-invalid');
                            }

                            // Handle status error
                            if (errors.status) {
                                $('#taskStatus').after(`<div class="invalid-feedback">${errors.status[0]}</div>`);
                                $('#taskStatus').addClass('is-invalid');
                            }

                            // Handle failure_reason error
                            if (errors.failure_reason) {
                                $('textarea[name="failure_reason"]').after(`<div class="invalid-feedback">${errors.failure_reason[0]}</div>`);
                                $('textarea[name="failure_reason"]').addClass('is-invalid');
                            }
                        } else {
                            // Fallback for generic errors
                            alert(xhr.responseJSON.message || 'Error updating task');
                        }
                    }
                });
            });

            // Audio recording functionality
            let audioRecorder;
            let audioChunks = [];
            const recordAudioButton = document.getElementById('recordAudioButton');
            const audioPlayback = document.getElementById('audioPlayback');
            const audioInput = document.querySelector('input[name="audio_attachment"]');

            recordAudioButton.addEventListener('click', function() {
                if (audioRecorder && audioRecorder.state === 'recording') {
                    audioRecorder.stop();
                    recordAudioButton.innerHTML = '<i class="fas fa-microphone"></i> @lang("dashboard.record_audio")';
                    recordAudioButton.classList.remove('btn-danger');
                    recordAudioButton.classList.add('btn-info');
                } else {
                    navigator.mediaDevices.getUserMedia({ audio: true })
                        .then(stream => {
                            audioRecorder = new MediaRecorder(stream);
                            audioRecorder.start();
                            audioChunks = [];

                            recordAudioButton.innerHTML = '<i class="fas fa-stop"></i> @lang("dashboard.stop_recording")';
                            recordAudioButton.classList.remove('btn-info');
                            recordAudioButton.classList.add('btn-danger');

                            audioRecorder.ondataavailable = event => {
                                if (event.data.size > 0) {
                                    audioChunks.push(event.data);
                                }
                            };

                            audioRecorder.onstop = () => {
                                const audioBlob = new Blob(audioChunks, { type: 'wav' });
                                const audioUrl = URL.createObjectURL(audioBlob);
                                audioPlayback.src = audioUrl;
                                audioPlayback.style.display = 'block';

                                // Stop all tracks in the stream
                                stream.getTracks().forEach(track => track.stop());

                                // Create a file and assign it to the audio input
                                const file = new File([audioBlob], 'recording.wav', { type: 'audio/wav' });
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                audioInput.files = dataTransfer.files;
                            };
                        })
                        .catch(error => {
                            console.error('Error accessing microphone:', error);
                            alert('Error accessing microphone. Please check permissions.');
                        });
                }
            });

            // Camera capture functionality
            document.getElementById('openCamera').addEventListener('click', function() {
                document.querySelector('input[name="photo_attachment"]').click();
            });

            // Video recording functionality
            let videoRecorder;
            let videoChunks = [];
            const recordVideoButton = document.getElementById('recordVideoButton');
            const stopVideoButton = document.getElementById('stopVideoButton');
            const saveVideoButton = document.getElementById('saveVideoButton');
            const videoPlayback = document.getElementById('videoPlayback');
            const videoInput = document.querySelector('input[name="video_attachment"]');
            let videoStream;

            recordVideoButton.addEventListener('click', async function() {
                try {
                    videoStream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: true
                    });

                    videoPlayback.srcObject = videoStream;
                    videoPlayback.style.display = 'block';
                    videoPlayback.muted = true; // Mute playback during recording
                    videoPlayback.play();

                    videoRecorder = new MediaRecorder(videoStream, {
                        mimeType: 'video/webm'
                    });
                    videoChunks = [];

                    videoRecorder.ondataavailable = event => {
                        if (event.data.size > 0) {
                            videoChunks.push(event.data);
                        }
                    };

                    videoRecorder.onstop = () => {
                        const videoBlob = new Blob(videoChunks, { type: 'video/webm' });
                        const videoUrl = URL.createObjectURL(videoBlob);
                        videoPlayback.srcObject = null;
                        videoPlayback.src = videoUrl;
                        videoPlayback.muted = false;

                        // Create a file and assign it to the video input
                        const file = new File([videoBlob], 'recording.webm', { type: 'video/webm' });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        videoInput.files = dataTransfer.files;
                    };

                    videoRecorder.start();
                    recordVideoButton.style.display = 'none';
                    stopVideoButton.style.display = 'inline-block';
                    saveVideoButton.style.display = 'none';
                } catch (error) {
                    console.error('Error accessing camera:', error);
                    alert('Error accessing camera. Please check permissions.');
                }
            });

            stopVideoButton.addEventListener('click', function() {
                if (videoRecorder && videoRecorder.state === 'recording') {
                    videoRecorder.stop();
                    videoStream.getTracks().forEach(track => track.stop());

                    stopVideoButton.style.display = 'none';
                    saveVideoButton.style.display = 'inline-block';
                }
            });

            saveVideoButton.addEventListener('click', function() {
                saveVideoButton.style.display = 'none';
                recordVideoButton.style.display = 'inline-block';
            });

            // Handle file input changes to show previews
            $('input[name="photo_attachment"]').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#photoAttachmentContainer').html(`
                            <div class="attachment-preview mb-2">
                                <img src="${event.target.result}" class="img-thumbnail" style="max-width: 200px;">
                                <button type="button" class="btn btn-sm btn-danger ms-2" onclick="document.getElementById('delete_photo').value = '1'; this.parentElement.remove();">
                                    @lang('dashboard.delete')
                                </button>
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('input[name="video_attachment"]').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    $('#videoAttachmentContainer').html(`
                        <div class="attachment-preview mb-2">
                            <video width="200" controls>
                                <source src="${url}">
                            </video>
                            <button type="button" class="btn btn-sm btn-danger ms-2" onclick="document.getElementById('delete_video').value = '1'; this.parentElement.remove();">
                                @lang('dashboard.delete')
                            </button>
                        </div>
                    `);
                }
            });

            $('input[name="audio_attachment"]').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    $('#audioAttachmentContainer').html(`
                        <div class="attachment-preview mb-2">
                            <audio controls>
                                <source src="${url}">
                            </audio>
                            <button type="button" class="btn btn-sm btn-danger ms-2" onclick="document.getElementById('delete_audio').value = '1'; this.parentElement.remove();">
                                @lang('dashboard.delete')
                            </button>
                        </div>
                    `);
                }
            });
        });
    </script>
@endsection
