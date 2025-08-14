@extends('dashboard.layouts.app')
@section('pageTitle', isset($equipmentDirectory) ? __('dashboard.edit_directory') : __('dashboard.create_directory'))

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header">
            <h3 class="card-title">
                {{ isset($equipmentDirectory) ? __('dashboard.edit_directory') : __('dashboard.create_directory') }}
            </h3>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ isset($equipmentDirectory) ? route('equipment-directories.update', $equipmentDirectory) : route('equipment-directories.store') }}">
                @csrf
                @if(isset($equipmentDirectory))
                    @method('PUT')
                @endif

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">
                        @lang('dashboard.directory_name')
                    </label>
                    <div class="col-lg-8">
                        <input type="text" name="name"
                               class="form-control"
                               value="{{ old('name', $equipmentDirectory->name ?? '') }}"
                               required>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                        @lang('dashboard.directory_status')
                    </label>
                    <div class="col-lg-8">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                {{ old('is_active', $equipmentDirectory->is_active ?? 1) ? 'checked' : '' }}>
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
