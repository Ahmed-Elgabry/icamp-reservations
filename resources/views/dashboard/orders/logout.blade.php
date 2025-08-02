@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.edit_time_and_image'))
@section('content')

<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

        @include('dashboard.orders.nav')

        <!--begin::Category-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                {{ __('dashboard.edit_time_and_image') }}
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form id="kt_ecommerce_add_product_form" action="{{ route('orders.updatesignin', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="uploaded_images" id="uploaded_images">

                    <div class="form-group">
                        <label for="delivery_time">{{ __('dashboard.delivery_time') }}</label>
                        <input type="time" name="delivery_time" id="delivery_time" class="form-control" value="{{ old('delivery_time', $order->delivery_time) }}">
                    </div>
                    <div class="form-group mt-5">
                        <label for="delivery_time_notes">{{ __('dashboard.delivery_time_notes') }}</label>
                        <textarea name="delivery_time_notes" id="delivery_time_notes" class="form-control">{{ old('delivery_time_notes', $order->delivery_time_notes) }}</textarea>
                    </div>
                    <div class="form-group mt-5">
                        <label for="pre_logout_image">{{ __('dashboard.pre_logout_image') }}</label>
                        <div class="dropzone dropzone-previews" id="preLoginImageDropzone"></div>
                        <!-- Input field to capture photo from camera -->
                        <input type="file" accept="image/*" capture="camera" id="cameraInput" style="display:none;">
                        <button type="button" class="btn btn-secondary mt-3" id="openCamera">{{ __('dashboard.capture_photo') }}</button>
                    </div>

                    <hr>

                    <div class="form-group mt-5">
                        <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
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

$(document).ready(function() {
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
        init: function() {
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

            this.on("sending", function(file, xhr, formData) {
                formData.append("order_id", "{{ $order->id }}");
                formData.append("type", "logout");
            });
        },
        success: function(file, response) {
            file.serverId = response[0].id;
            uploadedImages.push({ path: response[0].filePath, id: response[0].id });
            document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);
        },
        error: function(file, response) {
            console.log('Error uploading: ', response);
        },
        removedfile: function(file) {
            var fileId = file.serverId;

            $.ajax({
                url: "{{ route('orders.removeImage', ['id' => 'fileId']) }}?type=logout".replace('fileId', fileId),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    uploadedImages = uploadedImages.filter(function(image) {
                        return image.id != fileId;
                    });
                    document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);

                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                },
                error: function(response) {
                    console.log('Error removing file:', response);
                }
            });
        }
    });

    $("form#kt_ecommerce_add_product_form").on("submit", function(e) {
        document.querySelector("#uploaded_images").value = JSON.stringify(uploadedImages);
    });

   

    // Open camera to take photo
    $("#openCamera").on('click', function() {
        $("#cameraInput").click();
    });

    // Handle the camera input change
    $("#cameraInput").on('change', function(event) {
        var files = event.target.files;
        if(files.length > 0) {
            myDropzone.addFile(files[0]);
        }
    });
});
</script>
@endsection
