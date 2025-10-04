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

            <!-- وقت الاستلام + ملاحظات -->
            <div class="form-group mb-4">
                <label for="time_of_receipt">{{ __('dashboard.signin_time') }}</label>
                <input type="time" name="time_of_receipt" id="time_of_receipt" class="form-control"
                       value="{{ old('time_of_receipt', $order->time_of_receipt) }}">
            </div>
            <div class="form-group mb-5">
                <label for="time_of_receipt_notes">{{ __('dashboard.receiving_time_notes') }}</label>
                <textarea name="time_of_receipt_notes" id="time_of_receipt_notes" class="form-control">{{ old('time_of_receipt_notes', $order->time_of_receipt_notes) }}</textarea>
            </div>

            <div class="row g-3">
                <!-- PHOTO -->
                <div class="col-md-4">
                    <label class="form-label">📷 @lang('dashboard.photo_attachment')</label>
                    <div class="media-upload-container" data-type="photo">
                        <div class="preview-image-container mb-2" style="{{ $order->image_before_receiving_url ? '' : 'display:none;' }}">
                            @if($order->image_before_receiving_url)
                                <img src="{{ $order->image_before_receiving_url }}" class="preview-image img-thumbnail" style="max-width: 100%;">
                            @endif
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media w-100">
                                <i class="bi bi-trash"></i> {{ __('dashboard.remove') }}
                            </button>
                        </div>
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="photo">
                                <i class="bi bi-camera"></i> {{ __('dashboard.capture_photo') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary upload-media">
                                <i class="bi bi-upload"></i> {{ __('dashboard.upload') }}
                            </button>
                        </div>
                        <input type="file" name="image_before_receiving" class="media-input d-none" accept="image/*" capture="environment">
                        <input type="hidden" name="remove_photo" value="0" class="remove-flag">
                    </div>
                </div>

                <!-- AUDIO -->
                <div class="col-md-4">
                    <label class="form-label">🎵 @lang('dashboard.audio_attachment')</label>
                    <div class="media-upload-container" data-type="audio">
                        <div class="preview-audio-container mb-2" style="{{ $order->voice_note_url ? '' : 'display:none;' }}">
                            @if($order->voice_note_url)
                                <audio controls class="preview-audio w-100">
                                    <source src="{{ $order->voice_note_url }}">
                                </audio>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-media w-100">
                                <i class="bi bi-trash"></i> {{ __('dashboard.remove') }}
                            </button>
                        </div>
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-sm btn-primary capture-media" data-media-type="audio">
                                <i class="bi bi-mic"></i> {{ __('dashboard.record') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary upload-media">
                                <i class="bi bi-upload"></i> {{ __('dashboard.upload') }}
                            </button>
                        </div>
                        <input type="file" name="voice_note" class="media-input d-none" accept="audio/*">
                        <input type="hidden" name="remove_audio" value="0" class="remove-flag">
                    </div>
                </div>

                <!-- VIDEO (FilePond) -->
                <div class="col-md-4">
                    <label class="form-label">🎬 @lang('dashboard.video_attachment')</label>

                    <div class="preview-video-container mb-2">
                        @if($order->video_note_direct_key)
                            <video controls width="100%" style="border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.2)">
                                <source src="{{ Storage::disk('hetzner')->url($order->video_note_direct_key) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>

                    <div class="btn-group w-100 mb-2">
                        <button type="button" id="capture-video-btn" class="btn btn-sm btn-primary">
                            🎥 {{ __('dashboard.capture_video') }}
                        </button>
                    </div>

                    <input type="file" id="pond-video" accept="video/*">
                    <input type="hidden" name="remove_video" id="remove_video_flag" value="0">
                    <input type="hidden" id="video_note_direct_key" name="video_note_direct_key" value="">
                    <input type="hidden" id="video_upload_id" value="">


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
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
<style>
.media-upload-container{border:1px dashed #ddd;border-radius:4px;padding:10px;background:#f9f9f9}
.remove-media{width:100%}
.preview-audio-container audio,.preview-video-container video{max-width:100%;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.2)}
</style>
@endpush





@push('js')
<!-- FilePond -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const CSRF       = '{{ csrf_token() }}';
  const CHUNK_SIZE = 10 * 1024 * 1024; // 10MB
  const isMobile   = /Mobi|Android|iPhone|iPad/i.test(navigator.userAgent);

  const ACCEPTED_VIDEOS = [
    'video/mp4','video/webm','video/avi','video/quicktime','video/x-matroska',
    'video/x-flv','video/3gpp','video/mpeg','video/mpg','video/x-ms-wmv'
  ];

  // Helpers
  function setInputFile(input, file){
    if (!input) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
  }
  function clearPreview(el){ el.innerHTML=''; el.style.display='none'; }

  // ================== PHOTO ==================
  (function initPhoto(){
    const box = document.querySelector('[data-type="photo"]');
    if (!box) return;
    const input = box.querySelector('.media-input');
    const preview = box.querySelector('.preview-image-container');
    const captureBtn = box.querySelector('.capture-media');
    const uploadBtn = box.querySelector('.upload-media');
    const removeFlag = box.querySelector('.remove-flag');

    uploadBtn?.addEventListener('click', ()=> input?.click());

    captureBtn?.addEventListener('click', ()=>{
      if (isMobile) {
        input?.click();
      } else {
        navigator.mediaDevices.getUserMedia({ video:true })
          .then(stream=>{
            preview.style.display='block';
            preview.innerHTML='';
            const v=document.createElement('video');
            v.autoplay=true; v.muted=true; v.srcObject=stream;
            v.style.width='100%';
            preview.appendChild(v);

            const btn=document.createElement('button');
            btn.type='button'; btn.className='btn btn-sm btn-success mt-2 w-100';
            btn.innerHTML='<i class="bi bi-camera"></i> Snap';
            preview.appendChild(btn);

            btn.addEventListener('click', ()=>{
              const canvas=document.createElement('canvas');
              canvas.width=v.videoWidth||640; canvas.height=v.videoHeight||480;
              canvas.getContext('2d').drawImage(v,0,0);
              stream.getTracks().forEach(t=>t.stop());
              canvas.toBlob(blob=>{
                const file=new File([blob],'photo_'+Date.now()+'.png',{type:'image/png'});
                setInputFile(input, file);
                preview.innerHTML='';
                const img=document.createElement('img');
                img.src=URL.createObjectURL(file);
                img.className='img-thumbnail';
                img.style.maxWidth='100%';
                preview.appendChild(img);
                removeFlag.value='0';
              },'image/png');
            });
          })
          .catch(err=> alert("Camera error: "+err.message));
      }
    });

    input?.addEventListener('change',(e)=>{
      const f=e.target.files?.[0]; if(!f) return;
      preview.style.display='block';
      preview.innerHTML='<img src="'+URL.createObjectURL(f)+'" class="img-thumbnail" style="max-width:100%">';
      removeFlag.value='0';
    });

    // زر الحذف
    const removeBtn = box.querySelector('.remove-media');
    if(removeBtn){
      removeBtn.addEventListener('click', ()=>{
        preview.style.display='none'; input.value=''; removeFlag.value='1';
      });
    }
  })();

  // ================== AUDIO ==================
  (function initAudio(){
    const box = document.querySelector('[data-type="audio"]');
    if (!box) return;
    const input = box.querySelector('.media-input');
    const preview = box.querySelector('.preview-audio-container');
    const captureBtn = box.querySelector('.capture-media');
    const uploadBtn = box.querySelector('.upload-media');
    const removeFlag = box.querySelector('.remove-flag');
    let recorder=null, chunks=[];

    uploadBtn?.addEventListener('click', ()=> input?.click());

    captureBtn?.addEventListener('click', async ()=>{
      if (recorder && recorder.state==='recording'){
        recorder.stop();
        captureBtn.className='btn btn-sm btn-primary';
        captureBtn.innerHTML='<i class="bi bi-mic"></i> {{ __("dashboard.record") }}';
        return;
      }
      try {
        const stream=await navigator.mediaDevices.getUserMedia({audio:true});
        chunks=[];
        recorder=new MediaRecorder(stream);
        recorder.ondataavailable=e=>{ if(e.data.size>0) chunks.push(e.data); };
        recorder.onstop=()=>{
          stream.getTracks().forEach(t=>t.stop());
          const blob=new Blob(chunks,{type:'audio/webm'});
          const file=new File([blob],'audio_'+Date.now()+'.webm',{type:'audio/webm'});
          setInputFile(input,file);
          preview.innerHTML='<audio controls class="w-100" src="'+URL.createObjectURL(file)+'"></audio>';
          removeFlag.value='0';
        };
        recorder.start();
        captureBtn.className='btn btn-sm btn-danger';
        captureBtn.innerHTML='<i class="bi bi-stop"></i> {{ __("dashboard.stop") }}';
      }catch(e){ alert("Mic error: "+e.message); }
    });

    input?.addEventListener('change',(e)=>{
      const f=e.target.files?.[0]; if(!f) return;
      preview.innerHTML='<audio controls class="w-100" src="'+URL.createObjectURL(f)+'"></audio>';
      removeFlag.value='0';
    });

    // زر الحذف
    const removeBtn = box.querySelector('.remove-media');
    if(removeBtn){
      removeBtn.addEventListener('click', ()=>{
        preview.style.display='none'; input.value=''; removeFlag.value='1';
      });
    }
  })();

  // ================== VIDEO (FilePond) ==================
  if (window.FilePond) {
    try {
      FilePond.registerPlugin(
        window.FilePondPluginFileValidateType || (()=>{}),
        window.FilePondPluginFileValidateSize || (()=>{})
      );
    } catch(_) {}
  }

  const elVideo = document.getElementById('pond-video');
  let pondVideo = null;

  if (elVideo && window.FilePond) {
    pondVideo = FilePond.create(elVideo, {
      allowMultiple:false,
      credits:false,
      acceptedFileTypes:ACCEPTED_VIDEOS,
      labelIdle:'🎬 {{ __("dashboard.capture_video") }} أو <span class="filepond--label-action">تصفح</span>',
      server:{
        process:(fieldName,file,metadata,load,error,progress,abort)=>{
          let cancelled=false,currentXhr=null,uploadId='',key='';
          const parts=[]; let uploadedBase=0;
          progress(true,0,file.size);

          (async()=>{
            try{
              const createRes=await fetch(`{{ route('multipart.create') }}`,{
                method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
                body:JSON.stringify({fileName:file.name,contentType:file.type,fileSize:file.size,prefix:'uploads/signin/videos'})
              });
              if(!createRes.ok) throw new Error('createMultipart failed');
              const createJson=await createRes.json();
              uploadId=createJson.uploadId; key=createJson.key;
              document.getElementById('video_upload_id').value=uploadId;
              document.getElementById('video_note_direct_key').value=key;

              const totalParts=Math.ceil(file.size/CHUNK_SIZE);
              for(let partNumber=1;partNumber<=totalParts;partNumber++){
                if(cancelled) return;
                const start=(partNumber-1)*CHUNK_SIZE,end=Math.min(start+CHUNK_SIZE,file.size);
                const blob=file.slice(start,end);
                const signRes=await fetch(`{{ route('multipart.sign') }}`,{
                  method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
                  body:JSON.stringify({key,uploadId,partNumber})
                });
                if(!signRes.ok) throw new Error('signPart failed');
                const {url}=await signRes.json();

                await new Promise((resolve,reject)=>{
                  const xhr=new XMLHttpRequest();
                  currentXhr=xhr; xhr.open('PUT',url,true);
                  xhr.upload.onprogress=(e)=>{ if(e.lengthComputable) progress(true,uploadedBase+e.loaded,file.size); };
                  xhr.onload=()=>{
                    currentXhr=null;
                    if(xhr.status>=200&&xhr.status<300){
                      const etag=(xhr.getResponseHeader('ETag')||'').replace(/"/g,'');
                      if(!etag) return reject(new Error('Missing ETag'));
                      parts.push({ETag:etag,PartNumber:partNumber});
                      uploadedBase+=blob.size; progress(true,uploadedBase,file.size);
                      resolve();
                    } else reject(new Error('PUT failed: '+xhr.status));
                  };
                  xhr.onerror=()=>reject(new Error('Network error'));
                  xhr.onabort=()=>reject(new Error('Aborted'));
                  xhr.send(blob);
                });
              }

              if(cancelled) return;
              const completeRes=await fetch(`{{ route('multipart.complete') }}`,{
                method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
                body:JSON.stringify({key,uploadId,parts})
              });
              if(!completeRes.ok) throw new Error('completeMultipart failed');
              progress(true,file.size,file.size);
              load(key);
            }catch(e){
              if(cancelled) return;
              console.error(e); error(e.message||'upload failed');
              document.getElementById('video-error').style.display='block';
              document.getElementById('video-error').textContent='فشل رفع الفيديو. حاول من جديد.';
            }
          })();

          return{abort:async()=>{cancelled=true;try{if(currentXhr) currentXhr.abort();}catch(_){};abort();}};
        }
      }
    });
  }

  // زر حذف الفيديو
  const videoRemoveBtn = document.querySelector('.remove-video');
  if(videoRemoveBtn){
    videoRemoveBtn.addEventListener('click', ()=>{
      const preview = document.querySelector('.preview-video-container');
      if(preview) preview.style.display='none';
      document.getElementById('remove_video_flag').value = '1';
      document.getElementById('video_note_direct_key').value = '';
      document.getElementById('video_upload_id').value = '';
    });
  }

  // ================== RECORD VIDEO ==================
  (function initVideoRecord(){
    const btn=document.getElementById('capture-video-btn');
    const preview=document.querySelector('.preview-video-container');
    if(!btn||!preview) return;
    let recorder=null,chunks=[],stream=null;

    btn.addEventListener('click',async()=>{
      if(recorder&&recorder.state==='recording'){
        recorder.stop();
        btn.className='btn btn-sm btn-primary w-100';
        btn.innerHTML='🎥 {{ __("dashboard.capture_video") }}';
        return;
      }
      try{
        stream=await navigator.mediaDevices.getUserMedia({video:true,audio:true});
        preview.style.display='block'; preview.innerHTML='';
        const v=document.createElement('video'); v.srcObject=stream;
        v.autoplay=true; v.muted=true; v.style.width='100%'; preview.appendChild(v);

        chunks=[]; recorder=new MediaRecorder(stream,{mimeType:'video/webm'});
        recorder.ondataavailable=e=>{if(e.data.size>0) chunks.push(e.data);};
        recorder.onstop=()=>{
          stream.getTracks().forEach(t=>t.stop());
          const blob=new Blob(chunks,{type:'video/webm'});
          const file=new File([blob],'recorded_'+Date.now()+'.webm',{type:'video/webm'});
          preview.innerHTML='<video controls style="width:100%" src="'+URL.createObjectURL(blob)+'"></video>';
          if(pondVideo) pondVideo.addFile(file);
        };
        recorder.start();
        btn.className='btn btn-sm btn-danger w-100';
        btn.innerHTML='<i class="bi bi-stop"></i> {{ __("dashboard.stop") }}';
      }catch(e){ alert("Camera error: "+e.message); }
    });
  })();
});
</script>
@endpush
