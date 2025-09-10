@extends('dashboard.layouts.app')
@section('pageTitle', isset($item) ? __('dashboard.edit_item') : __('dashboard.add_item'))

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header">
            <h3 class="card-title">
                @isset($item)
                    @lang('dashboard.edit_item') - {{ $equipmentDirectory->name }}
                @else
                    @lang('dashboard.add_item') - {{ $equipmentDirectory->name }}
                @endisset
            </h3>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ isset($item) ? route('equipment-directories.items.update', [$equipmentDirectory, $item]) : route('equipment-directories.items.store', $equipmentDirectory) }}"
                  enctype="multipart/form-data">
                @csrf
                @isset($item) @method('PUT') @endisset

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.item_type')</label>
                    <div class="col-lg-8">
                        <input type="text" name="type" class="form-control"
                               value="{{ old('type', $item->type ?? '') }}" required>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.item_name')</label>
                    <div class="col-lg-8">
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $item->name ?? '') }}" required>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.location')</label>
                    <div class="col-lg-8">
                        <input type="text" name="location" class="form-control"
                               value="{{ old('location', $item->location ?? '') }}" required>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.quantity')</label>
                    <div class="col-lg-8">
                        <input type="number" name="quantity" class="form-control no-arrows" min="1"
                               value="{{ old('quantity', $item->quantity ?? 1) }}" required>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                    <div class="col-lg-8">
                        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $item->notes ?? '') }}</textarea>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.media')</label>
                    <div class="col-lg-8">
                        <input type="file" name="media[]" class="form-control" multiple accept="image/*,video/*">
                        <small class="text-muted">@lang('dashboard.upload_images_or_videos')</small>

                        @isset($item)
                            @if($item->media->count() > 0)
                                <div class="mt-3">
                                    <h6>@lang('dashboard.existing_media')</h6>
                                    <div class="row">
                                        @foreach($item->media as $media)
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    @if($media->file_type === 'image')
                                                        <img src="{{ $media->file_url }}" class="card-img-top" alt="">
                                                    @else
                                                        <video controls class="w-100">
                                                            <source src="{{ $media->file_url }}" type="video/mp4">
                                                        </video>
                                                    @endif
                                                    <div class="card-footer p-2">
                                                        <button type="button" class="btn btn-sm btn-danger w-100 delete-media"
                                                                data-media-id="{{ $media->id }}">
                                                            <i class="fas fa-trash"></i> @lang('dashboard.delete')
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.status')</label>
                    <div class="col-lg-8">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="is_active" @checked(old('is_active', $item->is_active ?? true))>
                            <label class="form-check-label" for="is_active">
                                @lang('dashboard.active')
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary">
                        @lang('dashboard.save_changes')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Chrome, Safari, Edge, Opera */
        .no-arrows::-webkit-inner-spin-button,
        .no-arrows::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .no-arrows[type=number] {
            -moz-appearance: textfield;
        }

        .card-img-top {
            height: 120px;
            object-fit: cover;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle media deletion
            document.querySelectorAll('.delete-media').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('@lang('dashboard.confirm_delete_media')')) {
                        const mediaId = this.dataset.mediaId;
                        fetch(`/equipment-directories/media/${mediaId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.closest('.col-md-3').remove();
                                }
                            });
                    }
                });
            });
        });
    </script>
@endpush
