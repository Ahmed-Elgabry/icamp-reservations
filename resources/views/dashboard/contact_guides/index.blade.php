@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.contacts'))

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
  <div id="kt_content_container" class="container-xxl">
    <div class="card card-flush">
      <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
          <h3 class="fw-bolder m-0">@lang('dashboard.contacts')</h3>
        </div>
        <div class="card-toolbar">
            @can('contact-guides.create')
          <a href="{{ route('contact-guides.create') }}" class="btn btn-primary me-2">@lang('dashboard.add_title', ['page_title' => __('dashboard.contact')])</a>
          @endcan
            @can('contact-guides.export-pdf')
          <a href="{{ route('contact-guides.export-pdf') }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> @lang('dashboard.export_pdf')
          </a>
          @endcan
        </div>
      </div>

      <div class="card-body pt-0">
        <div class="table-responsive">
          <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
              <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                <th class="min-w-150px">@lang('dashboard.entity_name')</th>
                <th class="min-w-150px">@lang('dashboard.contact_person_name')</th>
                <th class="min-w-150px">@lang('dashboard.primary_phone')</th>
                <th class="min-w-150px">@lang('dashboard.secondary_phone')</th>
                <th class="min-w-150px">@lang('dashboard.fixed_phone')</th>
                <th class="min-w-200px">@lang('dashboard.email')</th>
                <th class="min-w-100px">@lang('dashboard.photo')</th>
                <th class="min-w-200px">@lang('dashboard.notes')</th>
                <th class="min-w-100px text-end">@lang('dashboard.actions')</th>
              </tr>
            </thead>
            <tbody class="fw-bold text-gray-600">
              @forelse ($contacts as $c)
              <tr>
                <td>{{ $c->entity_name }}</td>
                <td>{{ $c->contact_person_name }}</td>
                <td class="text-center">
                  @if($c->primary_phone)
                    <a title="@lang('dashboard.call')" href="tel:{{ $c->primary_phone }}" class="btn btn-sm btn-light me-1">
                      <i class="bi bi-telephone"></i>
                    </a>
                    <a title="WhatsApp" target="_blank" href="https://wa.me/{{ preg_replace('/\D+/', '', $c->primary_phone) }}" class="btn btn-sm btn-success">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                    <div dir="ltr" class="small text-muted mt-1">{{ $c->primary_phone }}</div>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($c->secondary_phone)
                    <a title="@lang('dashboard.call')" href="tel:{{ $c->secondary_phone }}" class="btn btn-sm btn-light me-1">
                      <i class="bi bi-telephone"></i>
                    </a>
                    <a title="WhatsApp" target="_blank" href="https://wa.me/{{ preg_replace('/\D+/', '', $c->secondary_phone) }}" class="btn btn-sm btn-success">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                    <div dir="ltr" class="small text-muted mt-1">{{ $c->secondary_phone }}</div>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($c->fixed_phone)
                    <a title="@lang('dashboard.call')" href="tel:{{ $c->fixed_phone }}" class="btn btn-sm btn-light">
                      <i class="bi bi-telephone"></i>
                    </a>
                    <div dir="ltr" class="small text-muted mt-1">{{ $c->fixed_phone }}</div>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($c->email)

                    <!-- @php $emailRoute = Route::has('emails.compose') ? route('emails.compose', ['to' => $c->email]) : 'mailto:'.$c->email; @endphp -->
                    <a title="@lang('dashboard.send_email')" href="https://ngx343.inmotionhosting.com:2096/" class="btn btn-sm btn-light-primary">
                      <i class="bi bi-envelope"></i>
                    </a>
                    <div class="small text-muted mt-1">{{ $c->email }}</div>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($c->photo)
                    @php
                        $photoUrl = Storage::url($c->photo);
                    @endphp
                    <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo="{{ $photoUrl }}" title="@lang('dashboard.view')">
                      <i class="bi bi-eye"></i>
                    </a>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>{{ Str::limit($c->notes, 60) }}</td>
                <td class="text-end">
            @can('contact-guides.edit')
                  <a href="{{ route('contact-guides.edit', $c->id) }}" class="btn btn-sm btn-light-primary me-1">@lang('dashboard.edit')</a>
                  @endcan
            @can('contact-guides.destroy')
                  <button type="button" class="btn btn-sm btn-light-danger"
                          onclick="confirmDelete('{{ route('contact-guides.destroy', $c->id) }}', '{{ csrf_token() }}')">
                    @lang('dashboard.delete')
                  </button>
                  @endcan
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="text-center text-muted">@lang('dashboard.no_data')</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">@lang('dashboard.photo')</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img src="" id="photoModalImg" class="img-fluid rounded" alt="photo">
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Handle photo modal
  document.addEventListener('DOMContentLoaded', function() {
  const photoModalEl = document.getElementById('photoModal');
  if (photoModalEl) {
    photoModalEl.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const url = button.getAttribute('data-photo');
      document.getElementById('photoModalImg').setAttribute('src', url);
    });
  }
});

  // Delete confirmation is handled globally in app.blade.php
</script>
@endpush
@endsection
