@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.signin'))
@section('content')
@include('dashboard.orders.nav')

<div class="card mb-5 mb-xl-10">
    <form method="POST" id="media-form" action="{{ route('orders.updatesignin', $order->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            {{ __('dashboard.edit_time_and_media') }}
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">

            {{-- الوقت والملاحظات --}}
            <div class="form-group mb-4">
                <label for="time_of_receipt">{{ __('dashboard.signin_time') }}</label>
                <input type="time" name="time_of_receipt" id="time_of_receipt" class="form-control"
                       value="{{ old('time_of_receipt', $order->time_of_receipt) }}">
                @error('time_of_receipt') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="form-group mb-5">
                <label for="time_of_receipt_notes">{{ __('dashboard.receiving_time_notes') }}</label>
                <textarea name="time_of_receipt_notes" id="time_of_receipt_notes" class="form-control" rows="3">{{ old('time_of_receipt_notes', $order->time_of_receipt_notes) }}</textarea>
                @error('time_of_receipt_notes') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- المديا --}}
            <div class="row g-3">
                {{-- PHOTO --}}
                <div class="col-12 col-md-4">
                    <label class="form-label d-block">{{ __('dashboard.photo_before') }}</label>
                    <input type="file" id="pond-photo" name="image_before_receiving" accept="image/*">
                    <input type="hidden" name="remove_photo" id="remove_photo_flag" value="0">
                    @error('image_before_receiving') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- AUDIO --}}
                <div class="col-12 col-md-4">
                    <label class="form-label d-block">{{ __('dashboard.voice_note') }}</label>
                    <input type="file" id="pond-audio" name="voice_note" accept="audio/*">
                    <input type="hidden" name="remove_audio" id="remove_audio_flag" value="0">
                    @error('voice_note') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- VIDEO (S3 Multipart) --}}
                <div class="col-12 col-md-4">
                    <label class="form-label d-block">{{ __('dashboard.video_note') }}</label>
                    @if($order->video_note_direct_key)
                        <div class="mt-4">
                            <label class="form-label d-block">📹 {{ __('dashboard.video_note') }}</label>
                            <video controls width="400" style="border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.2)">
                                <source src="{{ Storage::disk('hetzner')->url($order->video_note_direct_key) }}" type="video/mp4">
                                متصفحك لا يدعم تشغيل الفيديو
                            </video>
                            <p class="text-muted small mt-2">
                                {{ $order->video_note_direct_key }}
                            </p>
                        </div>
                    @endif

                    <input type="file" id="pond-video" accept="video/*">
                    <input type="hidden" name="remove_video" id="remove_video_flag" value="0">
                    <input type="hidden" id="video_note_direct_key" name="video_note_direct_key" value="">
                    <input type="hidden" id="video_upload_id" value="">
                    <small class="text-muted d-block mt-1">
                        {{ __('dashboard.allowed_types') }}: mp4, webm, avi, mov, mkv, flv, 3gp, mpeg, mpg, wmv
                    </small>
                    <div id="video-error" class="text-danger small mt-1" style="display:none;"></div>
                </div>
            </div>

        </div>
        <!--end::Card body-->

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-light me-2">⬅ {{ __('dashboard.cancel') }}</a>
            <button type="submit" id="submit-btn" class="btn btn-success">💾 {{ __('dashboard.save_changes') }}</button>
        </div>
    </form>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
<style>
  .filepond--root{margin-top:.5rem}
  .filepond--panel-root{border-radius:.5rem}
  .filepond--drop-label{color:#555;font-size:.95rem}
</style>
@endpush






@push('js')
<!-- مكتبات FilePond -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const CSRF       = '{{ csrf_token() }}';
  const CHUNK_SIZE = 10 * 1024 * 1024; // 10MB (أكبر من 5MB حد S3)
  const ACCEPTED_VIDEOS = [
    'video/mp4','video/webm','video/avi','video/quicktime','video/x-matroska',
    'video/x-flv','video/3gpp','video/mpeg','video/mpg','video/x-ms-wmv'
  ];

  // سجل الإضافات
  if (window.FilePond) {
    FilePond.registerPlugin(
      window.FilePondPluginFileValidateType || (()=>{}),
      window.FilePondPluginFileValidateSize || (()=>{}),
      window.FilePondPluginImagePreview     || (()=>{})
    );
  }

  // PHOTO
  const elPhoto = document.getElementById('pond-photo');
  if (elPhoto) {
    const pondPhoto = FilePond.create(elPhoto, {
      allowMultiple: false,
      credits: false,
      acceptedFileTypes: ['image/*'],
      maxFileSize: '20MB',
      labelIdle: 'اسحب الصورة أو <span class="filepond--label-action">تصفّح</span>'
    });
    pondPhoto.on('removefile', () => document.getElementById('remove_photo_flag').value = '1');
  }

  // AUDIO
  const elAudio = document.getElementById('pond-audio');
  if (elAudio) {
    const pondAudio = FilePond.create(elAudio, {
      allowMultiple: false,
      credits: false,
      acceptedFileTypes: ['audio/*'],
      maxFileSize: '30MB',
      labelIdle: 'اسحب الصوت أو <span class="filepond--label-action">تصفّح</span>'
    });
    pondAudio.on('removefile', () => document.getElementById('remove_audio_flag').value = '1');
  }

  // VIDEO (رفع Multipart إلى S3 عبر الراوتات)
  const elVideo = document.getElementById('pond-video');
  let pondVideo = null;

  if (elVideo) {
    pondVideo = FilePond.create(elVideo, {
      allowMultiple: false,
      credits: false,
      acceptedFileTypes: ACCEPTED_VIDEOS,
      allowFileSizeValidation: false,
      labelIdle: 'اسحب الفيديو أو <span class="filepond--label-action">تصفّح</span>',
      server: {
        process: async (fieldName, file, metadata, load, error, progress, abort) => {
          const prefix = 'uploads/signin/videos';
          let aborted = false;

          abort(async () => {
            aborted = true;
            try {
              if (window.__UPLOAD_ID && window.__UPLOAD_KEY) {
                await fetch(`{{ route('multipart.abort') }}`, {
                  method: 'POST',
                  headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
                  body: JSON.stringify({ key: window.__UPLOAD_KEY, uploadId: window.__UPLOAD_ID })
                });
              }
            } catch (e) {}
          });

          try {
            // 1) create
            const createRes = await fetch(`{{ route('multipart.create') }}`, {
              method: 'POST',
              headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
              body: JSON.stringify({
                fileName: file.name,
                contentType: file.type || 'video/mp4',
                fileSize: file.size,
                prefix
              })
            });
            if (!createRes.ok) return error('createMultipart failed');
            const { uploadId, key } = await createRes.json();
            window.__UPLOAD_ID  = uploadId;
            window.__UPLOAD_KEY = key;

            document.getElementById('video_upload_id').value = uploadId;
            document.getElementById('video_note_direct_key').value = key;

            // 2) parts
            const totalParts = Math.ceil(file.size / CHUNK_SIZE);
            let uploaded = 0;
            window.__PARTS = [];

            for (let partNumber = 1; partNumber <= totalParts; partNumber++) {
              if (aborted) return;
              const start = (partNumber - 1) * CHUNK_SIZE;
              const end   = Math.min(start + CHUNK_SIZE, file.size);
              const blob  = file.slice(start, end);

              const signRes = await fetch(`{{ route('multipart.sign') }}`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
                body: JSON.stringify({ key, uploadId, partNumber })
              });
              if (!signRes.ok) return error('signPart failed');
              const { url } = await signRes.json();

              const putRes = await fetch(url, { method: 'PUT', body: blob });
              if (!putRes.ok) return error(`PUT part ${partNumber} failed`);

              const etag = (putRes.headers.get('ETag') || '').replace(/"/g,'');
              window.__PARTS.push({ ETag: etag, PartNumber: partNumber });

              uploaded += blob.size;
              progress(true, uploaded, file.size);
            }

            if (aborted) return;

            // 3) complete
            const completeRes = await fetch(`{{ route('multipart.complete') }}`, {
              method: 'POST',
              headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
              body: JSON.stringify({ key, uploadId, parts: window.__PARTS })
            });
            if (!completeRes.ok) return error('completeMultipart failed');

            load(key);
          } catch (e) {
            console.error(e);
            document.getElementById('video-error').style.display='block';
            document.getElementById('video-error').textContent = 'فشل رفع الفيديو. حاول من جديد.';
            error('فشل رفع الفيديو');
          }
        },
        revert: async (uniqueFileId, load, error) => {
          try {
            const upId = document.getElementById('video_upload_id')?.value;
            const key  = document.getElementById('video_note_direct_key')?.value;
            if (upId && key) {
              await fetch(`{{ route('multipart.abort') }}`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
                body: JSON.stringify({ key, uploadId: upId })
              });
            }
            document.getElementById('video_upload_id').value = '';
            document.getElementById('video_note_direct_key').value = '';
            window.__UPLOAD_ID = null; window.__UPLOAD_KEY = null; window.__PARTS = [];
            document.getElementById('remove_video_flag').value = '1';
            load();
          } catch (e) {
            console.error(e);
            error('فشل إلغاء الرفع');
          }
        }
      }
    });
  }

  // منع الإرسال قبل اكتمال الفيديو
  const form = document.getElementById('media-form');
  if (form && pondVideo) {
    form.addEventListener('submit', (e) => {
      const videoKey = document.getElementById('video_note_direct_key')?.value;
      const items = pondVideo.getFiles ? pondVideo.getFiles() : [];
      const PROCESSING_COMPLETE = 5, INIT = 1;
      const processing = items.some(i => i.status !== PROCESSING_COMPLETE && i.status !== INIT);
      if (processing && !videoKey) {
        e.preventDefault();
        alert('الرجاء انتظار اكتمال رفع الفيديو.');
      }
    });
  }
});
</script>
@endpush
