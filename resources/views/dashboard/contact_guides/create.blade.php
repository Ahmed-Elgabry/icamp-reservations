@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.add_title', ['page_title' => __('dashboard.contacts')]))

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
  <div id="kt_content_container" class="container-xxl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ isset($contact) ? __('dashboard.edit_title', ['page_title' => __('dashboard.contact')]) : __('dashboard.add_title', ['page_title' => __('dashboard.contact')]) }}</h3>
      </div>
      <form id="kt_ecommerce_add_product_form" data-kt-redirect="{{ route('contact-guides.index') }}" action="{{ isset($contact) ? route('contact-guides.update', $contact->id) : route('contact-guides.store') }}" method="POST" enctype="multipart/form-data" class="form d-flex flex-column store" novalidate>
        @csrf
        @if(isset($contact))
          @method('PUT')
        @endif
        <div class="card-body row g-6">
          <div class="col-md-6">
            <label class="form-label">@lang('dashboard.entity_name') <span class="text-danger">*</span></label>
            <input type="text" name="entity_name" class="form-control" value="{{ old('entity_name', $contact->entity_name ?? '') }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">@lang('dashboard.contact_person_name')</label>
            <input type="text" name="contact_person_name" class="form-control" value="{{ old('contact_person_name', $contact->contact_person_name ?? '') }}" required>
          </div>
          <!-- Primary Phone -->
          <div class="col-md-4">
            <label class="form-label">@lang('dashboard.primary_phone') <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="tel" name="primary_phone" class="form-control" value="{{ $contact->primary_phone ?? '' }}" data-dial="{{ isset($contact) && $contact->primary_phone ? substr($contact->primary_phone, 0, 4) : '971' }}" required>
            </div>
          </div>

          <!-- Secondary Phone -->
          <div class="col-md-4">
            <label class="form-label">@lang('dashboard.secondary_phone')</label>
            <div class="input-group">
              <input type="tel" name="secondary_phone" class="form-control" value="{{ $contact->secondary_phone ?? '' }}" data-dial="{{ isset($contact) && $contact->secondary_phone ? substr($contact->secondary_phone, 0, 4) : '971' }}">
            </div>
          </div>

          <!-- Fixed Phone -->
          <div class="col-md-4">
            <label class="form-label">@lang('dashboard.fixed_phone')</label>
            <div class="input-group">
              <input type="tel" name="fixed_phone" class="form-control" value="{{ $contact->fixed_phone ?? '' }}" data-dial="{{ isset($contact) && $contact->fixed_phone ? substr($contact->fixed_phone, 0, 4) : '971' }}">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">@lang('dashboard.email')</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $contact->email ?? '') }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">@lang('dashboard.photo')</label>
            <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*" capture="environment">
            <div class="mt-2" id="photoPreviewWrapper" style="display: {{ !empty($contact->photo) ? 'block' : 'none' }};">
              <img id="photoPreview" src="{{ !empty($contact->photo) ? Storage::url($contact->photo) : '' }}" alt="photo" style="height: 64px; border-radius: 6px;">
            </div>
          </div>
          <div class="col-12">
            <label class="form-label">@lang('dashboard.notes')</label>
            <textarea name="notes" rows="4" class="form-control">{{ old('notes', $contact->notes ?? '') }}</textarea>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
          <button id="kt_ecommerce_add_product_submit" class="btn btn-primary" type="submit">
            <span class="indicator-label">@lang('dashboard.save')</span>
            <span class="indicator-progress" style="display:none;">
              @lang('dashboard.please_wait')
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
