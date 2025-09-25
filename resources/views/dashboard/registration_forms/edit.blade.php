{{-- resources/views/dashboard/registration_forms/edit.blade.php --}}
@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.registration_forms'))

@section('content')
@php
    // Fallback in case controller didn’t pass $services (not ideal, but pragmatic)
    $services = $services ?? \App\Models\Service::orderBy('name')->get();

    // convenience vars
    $rf = $registration_form;
    $isAr = app()->getLocale() === 'ar';
@endphp

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 d-flex align-items-center justify-content-between">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            {{ __('dashboard.edit', ['page_title' => __('dashboard.registration_forms')]) }}
                        </h3>
                        <div class="text-muted mt-1">
                            {{ __('dashboard.form_no') }}:
                            <span class="fw-bold">#{{ $rf->id }}</span>
                            @if($rf->request_code)
                                <span class="badge bg-light text-dark ms-2">{{ $rf->request_code }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="collapse show">
                    <form
                        action="{{ route('bookings.registeration-forms.update', $rf->id) }}"
                        method="post"
                        class="form"
                        id="rfEditForm"
                        novalidate
                    >
                        @csrf
                        @method('PUT')

                        <div class="card-body border-top p-9">

                            {{-- Service --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">
                                    {{ __('dashboard.service') }}
                                </label>
                                <div class="col-lg-8">
                                    <select
                                        name="service_id" id="service_id"
                                        class="form-select form-select-lg form-select-solid @error('service_id') is-invalid @enderror"
                                        data-placeholder="{{ __('dashboard.service') }}"
                                        required
                                    >
                                        <option value="">{{ __('booking.camp_placeholder') }}</option>
                                        @foreach($services as $s)
                                            <option value="{{ $s->id }}"
                                                    data-hour-from="{{ $s->hour_from }}"
                                                    data-hour-to="{{ $s->hour_to }}"
                                                    @selected(old('service_id', $rf->service_id)==$s->id)
                                            >
                                                {{ $s->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small id="serviceHoursHint" class="text-muted d-block mt-2"></small>
                                </div>
                            </div>

                            {{-- Booking date --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">
                                    {{ __('dashboard.booking_date') }}
                                </label>
                                <div class="col-lg-8">
                                    <input
                                        type="text"
                                        name="booking_date" id="booking_date"
                                        class="form-control form-control-lg form-control-solid @error('booking_date') is-invalid @enderror"
                                        value="{{ old('booking_date', optional($rf->booking_date)->format('Y-m-d')) }}"
                                        placeholder="YYYY-MM-DD"
                                        autocomplete="off"
                                        required
                                    >
                                    @error('booking_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Time slot --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">
                                    {{ __('dashboard.time_slot') }}
                                </label>
                                <div class="col-lg-8">
                                    @php $slotOld = old('time_slot', $rf->time_slot); @endphp
                                    <div class="d-flex flex-wrap gap-2" id="slotGroup">
                                        @foreach(['4-12','5-1','other'] as $val)
                                            <label class="btn btn-outline-primary btn-sm slot-pill {{ $slotOld===$val ? 'active' : '' }}">
                                                <input type="radio" name="time_slot" value="{{ $val }}" {{ $slotOld===$val ? 'checked' : '' }} required>
                                                @if($val==='4-12') <i class="bi bi-moon-stars me-1"></i> @endif
                                                @if($val==='5-1')  <i class="bi bi-alarm me-1"></i> @endif
                                                @if($val==='other')<i class="bi bi-sliders me-1"></i> @endif
                                                @lang("booking.slots.$val")
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('time_slot') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row mb-6 {{ $slotOld==='other' ? '' : 'd-none' }}" id="otherTimesWrap">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('dashboard.custom_times') }}</label>
                                <div class="col-lg-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input
                                                    type="text" id="checkin_time" name="checkin_time"
                                                    class="form-control @error('checkin_time') is-invalid @enderror"
                                                    value="{{ old('checkin_time', $rf->checkin_time) }}"
                                                    placeholder="HH:mm"
                                                    autocomplete="off"
                                                >
                                                <label for="checkin_time">{{ __('booking.checkin_label') }}</label>
                                                @error('checkin_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input
                                                    type="text" id="checkout_time" name="checkout_time"
                                                    class="form-control @error('checkout_time') is-invalid @enderror"
                                                    value="{{ old('checkout_time', $rf->checkout_time) }}"
                                                    placeholder="HH:mm"
                                                    autocomplete="off"
                                                >
                                                <label for="checkout_time">{{ __('booking.checkout_label') }}</label>
                                                @error('checkout_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">{{ __('booking.time_note') }}</small>
                                </div>
                            </div>

                            {{-- Persons --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">
                                    {{ __('dashboard.persons') }}
                                </label>
                                <div class="col-lg-8">
                                    <input
                                        type="number" min="1" name="persons"
                                        class="form-control form-control-lg form-control-solid @error('persons') is-invalid @enderror"
                                        value="{{ old('persons', $rf->persons) }}"
                                        required
                                    >
                                    @error('persons') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Names --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('dashboard.first_name') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" name="first_name"
                                           class="form-control form-control-lg form-control-solid @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name', $rf->first_name) }}" required>
                                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('dashboard.last_name') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" name="last_name"
                                           class="form-control form-control-lg form-control-solid @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name', $rf->last_name) }}" required>
                                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('dashboard.mobile_phone') }}</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" name="mobile_phone"
                                               class="form-control form-control-lg form-control-solid @error('mobile_phone') is-invalid @enderror"
                                               value="{{ old('mobile_phone', $rf->mobile_phone) }}"
                                               placeholder="{{ __('booking.phone_ph') }}" required>
                                        @error('mobile_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('dashboard.email') }}</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email"
                                               class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                                               value="{{ old('email', $rf->email) }}" required>
                                    </div>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('dashboard.notes') }}</label>
                                <div class="col-lg-8">
                                    <textarea
                                        name="notes"
                                        class="form-control form-control-lg form-control-solid @error('notes') is-invalid @enderror"
                                        rows="3"
                                        placeholder="{{ __('dashboard.notes') }}"
                                    >{{ old('notes', $rf->notes) }}</textarea>
                                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('bookings.registeration-forms.index') }}" class="btn btn-light">
                                {{ __('dashboard.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ __('dashboard.save_changes') }}
                            </button>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <script>
        (function(){
            const isRTL = @json($isAr);

            if (window.jQuery && $.fn.select2) {
                $('#service_id').select2({
                    width: '100%',
                    dir: isRTL ? 'rtl' : 'ltr',
                    placeholder: $('#service_id').data('placeholder') || ''
                });
            }

            const sel = document.getElementById('service_id');
            const hint = document.getElementById('serviceHoursHint');
            const HINT = {
                ar: "ساعات الخدمة الافتراضية: من {from} إلى {to}",
                en: "Default service hours: {from} to {to}"
            };
            function updateHint(){
                const lang = document.documentElement.lang || (isRTL ? 'ar' : 'en');
                const opt = sel.options[sel.selectedIndex];
                const from = opt?.dataset?.hourFrom || '';
                const to   = opt?.dataset?.hourTo || '';
                hint.textContent = (from && to) ? HINT[lang].replace('{from}', from).replace('{to}', to) : '';
            }
            sel.addEventListener('change', updateHint); updateHint();

            function syncSlots(){
                document.querySelectorAll('#slotGroup .slot-pill').forEach(lbl=>{
                    const input = lbl.querySelector('input');
                    lbl.classList.toggle('active', input.checked);
                });
                const selected = document.querySelector('#slotGroup input[name="time_slot"]:checked');
                document.getElementById('otherTimesWrap').classList.toggle('d-none', !(selected && selected.value==='other'));
            }
            document.querySelectorAll('#slotGroup input[name="time_slot"]').forEach(r=>r.addEventListener('change', syncSlots));
            syncSlots();

            flatpickr("#booking_date", {
                dateFormat: "Y-m-d",
                locale: isRTL ? 'ar' : 'default',
                allowInput: true
            });

            const timeOpts = {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                locale: isRTL ? 'ar' : 'default',
                allowInput: true
            };
            flatpickr("#checkin_time", timeOpts);
            flatpickr("#checkout_time", timeOpts);
        })();
    </script>
@endsection
