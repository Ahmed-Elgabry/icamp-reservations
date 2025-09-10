@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.registration_forms'))

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <form method="GET" action="{{ route('orders.registeration-forms') }}"
                                class="d-flex align-items-center position-relative my-1" role="search">
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    class="form-control form-control-solid w-350px ps-14"
                                    placeholder="{{ __('dashboard.search_rf_placeholder') }}" />
                            </form>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                                <thead>
                                    <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                                        <th class="min-w-120px fw-bolder">{{ __('dashboard.form_no') }}</th>
                                        <th class="min-w-180px fw-bolder">{{ __('dashboard.mobile_phone') }}</th>
                                        <th class="min-w-200px fw-bolder">{{ __('dashboard.full_name') }}</th>
                                        <th class="min-w-210px fw-bolder">{{ __('dashboard.email') }}</th>
                                        <th class="min-w-80px text-center fw-bolder">{{ __('dashboard.view') }}</th>
                                        <th class="min-w-80px text-center fw-bolder">@lang('dashboard.created_date')</th>
                                        <th class="min-w-80px text-center fw-bolder">@lang('dashboard.created_time')</th>
                                        <th class="text-end min-w-180px fw-bolder">{{ __('dashboard.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600" id="rfTableBody">
                                    @forelse ($registration_forms as $rf)
                                        @php
                                            $slotToTimes = [
                                                '4-12' => ['16:00', '00:00'],
                                                '5-1'  => ['17:00', '01:00'],
                                            ];
                                            [$from, $to] = [null, null];

                                            if ($rf->time_slot === 'other') {
                                                $from = $rf->checkin_time;
                                                $to   = $rf->checkout_time;
                                            } elseif (isset($slotToTimes[$rf->time_slot])) {
                                                [$from, $to] = $slotToTimes[$rf->time_slot];
                                            }

                                            $dateStr = $rf->booking_date instanceof \Carbon\Carbon
                                                ? $rf->booking_date->format('Y-m-d')
                                                : (string) $rf->booking_date;

                                            $prefillForOrder = [
                                                'rf_id'         => $rf->id,
                                                'people_count'  => $rf->persons,
                                                'service_ids'   => [$rf->service_id],
                                                'date'          => $dateStr,
                                                'time_from'     => $from,
                                                'time_to'       => $to,
                                                'notes'         => $rf->notes,
                                                'prefill_mobile' => $rf->mobile_phone,
                                                'prefill_email'  => $rf->email,
                                            ];

                                            $prefillQuery = http_build_query(
                                                array_filter($prefillForOrder, fn($v) => $v !== null && $v !== ''),
                                                '', '&', PHP_QUERY_RFC3986
                                            );
                                            $slot = $rf->time_slot ?? null; $slotLabel = $slot === 'other' ? (($rf->checkin_time ?? '-') . ' - ' . ($rf->checkout_time ?? '-')) : (__('booking.slots.' . ($slot ?? 'other')));    $ordersCreateUrl = route('orders.create'); // your Orders@create page
                                            $details = [
                                                __('dashboard.form_no') => '#' . $rf->id,
                                                __('dashboard.service') => $rf->service->name ?? '-',
                                                __('dashboard.booking_date') => $rf->booking_date->format('Y-m-d') ?? '-',
                                                __('dashboard.time_slot') => $slotLabel,
                                                __('dashboard.persons') => (int) ($rf->persons ?? 0),
                                                __('dashboard.notes') => $rf->notes ?? '—',
                                                __('dashboard.mobile_phone') => $rf->mobile_phone ?? '—',
                                                __('dashboard.email') => $rf->email ?? '—',
                                            ];
                                        @endphp

                                        <tr data-id="{{ $rf->id }}">
                                            <td><span class="text-gray-800 text-hover-primary fw-bold">{{ $rf->request_code }}</span></td>
                                            <td><i class="bi bi-telephone me-1"></i>{{ $rf->mobile_phone ?? '—' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px symbol-circle me-3">
                                                        <span class="symbol-label bg-light-primary"><i
                                                                class="bi bi-person text-primary"></i></span>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-800 fw-bolder">{{ $rf->full_name ?: '—' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><i class="bi bi-envelope me-1"></i>{{ $rf->email ?? '—' }}</td>
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn btn-icon btn-light btn-active-light-primary btn-sm rf-view"
                                                    data-details='@json($details)' title="{{ __('dashboard.view_rest') }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                {{ $rf->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="text-center">
                                                {{ $rf->created_at->format('h:i A') }}
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <button type="button"
                                                        class="btn btn-icon btn-light-primary btn-sm rf-book"
                                                        data-href="{{ $ordersCreateUrl . '?' . $prefillQuery }}"
                                                        title="{{ __('dashboard.make_reservation') }}">
                                                    <i class="bi bi-journal-plus"></i>
                                                </button>

                                                    <a href="{{ route('orders.registeration-forms.edit', $rf->id) }}"
                                                        class="btn btn-icon btn-light-warning btn-sm"
                                                        title="{{ __('dashboard.edit') }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('orders.registeration-forms.destroy', $rf->id) }}"
                                                        method="POST" class="d-inline rf-delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-icon btn-light-danger btn-sm rf-delete"
                                                            title="{{ __('dashboard.delete') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-10">{{ __('dashboard.no_data') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="rfDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('dashboard.view_form_data') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="rfDetailsBody" class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('dashboard.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rfConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rfConfirmTitle">{{ __('dashboard.confirm') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="rfConfirmMsg">{{ __('dashboard.confirm_continue') }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="rfConfirmOk">{{ __('dashboard.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            (function () {
                const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
                const $ = (s, r = document) => r.querySelector(s);

                const detailsModal = new bootstrap.Modal('#rfDetailsModal');
                $$('#rfTableBody .rf-view').forEach(btn => {
                    btn.addEventListener('click', () => {
                        let obj = {}; try { obj = JSON.parse(btn.getAttribute('data-details')); } catch (e) { }
                        const tbody = document.querySelector('#rfDetailsBody tbody');
                        tbody.innerHTML = '';
                        Object.entries(obj).forEach(([k, v]) => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `<th class="fw-bolder text-gray-700" style="width: 40%">${k}</th><td>${v ?? '—'}</td>`;
                            tbody.appendChild(tr);
                        });
                        detailsModal.show();
                    });
                });

                const confirmModal = new bootstrap.Modal('#rfConfirmModal');
                let onConfirm = null;
                $('#rfConfirmOk').addEventListener('click', () => { if (onConfirm) { onConfirm(); } confirmModal.hide(); });
                function askConfirm(title, msg, cb) {
                    $('#rfConfirmTitle').textContent = title;
                    $('#rfConfirmMsg').textContent = msg;
                    onConfirm = cb; confirmModal.show();
                }

                $$('#rfTableBody .rf-book').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const href = btn.getAttribute('data-href');
                        askConfirm('{{ __('dashboard.confirm_reservation_title') }}', '{{ __('dashboard.confirm_reservation_body') }}', () => { window.location.href = href; });
                    });
                });

                $$('#rfTableBody .rf-delete').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const form = btn.closest('form.rf-delete-form');
                        askConfirm('{{ __('dashboard.confirm_delete_title') }}', '{{ __('dashboard.confirm_delete_body') }}', () => { form.submit(); });
                    });
                });

            })();
        </script>
    @endpush
@endsection
