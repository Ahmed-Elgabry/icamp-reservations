@extends('dashboard.layouts.app')
@props(['order' => null])
@section('pageTitle', __('dashboard.orders'))

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            @include('dashboard.orders.nav')

            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 d-flex align-items-center justify-content-between">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            {{ isset($order) ? $order->customer->name : __('dashboard.create_title', ['page_title' => __('dashboard.orders')]) }}
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-light-primary" id="btnRetrieveRf">
                            <i class="bi bi-cloud-download"></i> {{ __('dashboard.retrieve_data') }}
                        </button>
                    </div>
                </div>

                <div class="collapse show">
                    <form id="kt_ecommerce_add_product_form"
                          data-kt-redirect="{{ isset($order) ? route('orders.edit', $order->id) : route('orders.create') }}"
                          action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}"
                          method="post" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row store">
                        @csrf
                        @if (isset($order)) @method('PUT') @endif

                        <div class="card-body border-top p-9">
                            <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                            <input type="hidden" name="rf_id" id="rf_id" value="">

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">
                                    @lang('dashboard.customer_first_and_last_name')
                                </label>
                                <div class="col-lg-8">
                                    <select name="customer_id"
                                            class="js-select2 form-select form-select-lg form-select-solid"
                                            required>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                    data-phone="{{ $customer->mobile_phone ?? '' }}"
                                                    data-email="{{ $customer->email ?? '' }}"
                                                    {{ isset($order) && $order->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- People count -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">
                                    @lang('dashboard.people_count')
                                </label>
                                <div class="col-lg-8">
                                    <input type="number" name="people_count" id="people_count"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->people_count : old('people_count', request('people_count')) }}"
                                           required>
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.service')</label>
                                <div class="col-lg-8">
                                    @php
                                        $initialServiceIds = isset($order)
                                            ? $order->services->pluck('id')->all()
                                            : collect(old('service_ids', request('service_ids', [])))->map(fn($v)=>(int)$v)->all();
                                    @endphp
                                    <select name="service_ids[]" id="service_id"
                                            class="js-select2 form-select form-select-lg form-select-solid"
                                            multiple="multiple" required>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                    data-price="{{ $service->price }}"
                                                    {{ in_array($service->id, $initialServiceIds) ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">
                                    @lang('dashboard.service_price')
                                </label>
                                <div class="col-lg-8">
                                    <input type="number" name="price" id="price"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->price : old('price') }}" required>
                                </div>
                            </div>

                            <!-- Addons (edit only) -->
                            @if (isset($order))
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.addons')</label>
                                    <div class="col-lg-8">
                                        <input type="number" class="form-control form-control-lg form-control-solid"
                                               value="{{ $addonsPrice ?? 0 }}" readonly>
                                    </div>
                                </div>
                            @endif

                            <!-- Deposit -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.deposit')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="deposit" id="deposit"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->deposit : old('deposit') }}" required>
                                </div>
                            </div>

                            <!-- Insurance -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.insurance_amount')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="insurance_amount" id="insurance_amount"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->insurance_amount : old('insurance_amount') }}"
                                           required>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.notes')</label>
                                <div class="col-lg-8">
                                    <textarea name="notes" class="form-control form-control-lg form-control-solid"
                                              placeholder="@lang('dashboard.notes')">{{ isset($order) ? $order->notes : old('notes', request('notes')) }}</textarea>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.date')</label>
                                <div class="col-lg-8">
                                    <input type="date" name="date"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->date : old('date', request('date')) }}">
                                </div>
                            </div>

                            <!-- Time From -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_from')</label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_from"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->time_from : old('time_from', request('time_from')) }}">
                                </div>
                            </div>

                            <!-- Time To -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_to')</label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_to"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->time_to : old('time_to', request('time_to')) }}">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.status')</label>
                                <div class="col-lg-8">
                                    <select name="status" class="form-select form-select-lg form-select-solid" id="status">
                                        <option value="pending_and_show_price"
                                                title="@lang('dashboard.pending_and_show_price_desc')"
                                                {{ isset($order) && $order->status == 'pending_and_show_price' ? 'selected' : '' }}>
                                            @lang('dashboard.pending_and_show_price_desc')
                                        </option>
                                        <option value="pending_and_Initial_reservation"
                                                title="@lang('dashboard.pending_and_Initial_reservation_desc')"
                                                {{ isset($order) && $order->status == 'pending_and_Initial_reservation' ? 'selected' : '' }}>
                                            @lang('dashboard.pending_and_Initial_reservation')
                                        </option>
                                        <option value="approved" title="@lang('dashboard.approved_desc')"
                                                {{ isset($order) && $order->status == 'approved' ? 'selected' : '' }}>
                                            @lang('dashboard.approved')
                                        </option>
                                        <option value="canceled" title="@lang('dashboard.canceled_desc')"
                                                {{ isset($order) && $order->status == 'canceled' ? 'selected' : '' }}>
                                            @lang('dashboard.canceled')
                                        </option>
                                        <option value="delayed" title="@lang('dashboard.delayed')"
                                                {{ isset($order) && $order->status == 'delayed' ? 'selected' : '' }}>
                                            @lang('dashboard.delayed')
                                        </option>
                                        <option value="completed" title="@lang('dashboard.completed_desc')"
                                                {{ isset($order) && $order->status == 'completed' ? 'selected' : '' }}>
                                            @lang('dashboard.completed')
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @php $orderStatus = isset($order) ? ($order->status != 'pending_and_show_price' && $order->status != 'pending_and_Initial_reservation') : null; @endphp

                            <!-- delayed_reson -->
                            <div @class(['row mb-6', 'd-none' => (!$order || $order->status != 'delayed')]) id="delayed_reson">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.delayed_reson')</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control @error('delayed_reson') is-invalid @enderror"
                                           name="delayed_reson"
                                           value="{{ old('delayed_reson', isset($order) ? $order->delayed_reson : '') }}">
                                    @error('delayed_reson') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div @class(['row mb-6', 'd-none' => $orderStatus]) id="expired_price_offer">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    @lang('dashboard.expired_price_offer') <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <input type="date" name="expired_price_offer"
                                           placeholder="@lang('dashboard.expired_price_offer')"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->expired_price_offer : old('expired_price_offer') }}">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.client_notes')</label>
                                <div class="col-lg-8">
                                    <textarea name="client_notes" class="form-control">{{ isset($order) ? $order->client_notes : old('client_notes') }}</textarea>
                                </div>
                            </div>

                            @if (isset($order) && $order->status == 'canceled')
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">رد المبالغ المدفوعه ؟</label>
                                    <div class="col-lg-8">
                                        <select name="refunds" class="form-select form-select-lg form-select-solid">
                                            <option value="">-- Select --</option>
                                            <option value="1" {{ isset($order) && $order->refunds == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ isset($order) && $order->refunds == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.refunds_notes')</label>
                                    <div class="col-lg-8">
                                        <textarea name="refunds_notes" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.refunds_notes')">{{ isset($order) ? $order->refunds_notes : old('refunds_notes') }}</textarea>
                                    </div>
                                </div>
                            @endif

                            @if (isset($order) && $order->status == 'delayed')
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.delayed_time')</label>
                                    <div class="col-lg-8">
                                        <input type="time" name="delayed_time"
                                               class="form-control form-control-lg form-control-solid"
                                               value="{{ isset($order) ? $order->delayed_time : old('delayed_time') }}">
                                    </div>
                                </div>
                            @endif

                            @isset($order)
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.Customer_Signature')</label>
                                    <div class="col-lg-8 d-flex flex-column gap-3">
                                        @if(isset($order->signature_path))
                                            <div class="text-success fw-bold">{{ $order?->signature }}</div>
                                            <img src="{{ Storage::url($order->signature_path) }}" alt="Signature" style="max-height:80px;">
                                        @else
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                       value="{{ route('signature.show', $order) }}" readonly
                                                       onclick="this.select();document.execCommand('copy');">
                                                <button type="button" class="btn btn-outline-secondary"
                                                        onclick="navigator.clipboard.writeText('{{ route('signature.show', $order) }}')">
                                                    Copy Link
                                                </button>
                                            </div>
                                            <small class="text-muted">@lang('dashboard.desc_Customer_Signature')</small>
                                        @endif
                                    </div>
                                </div>
                            @endisset

                            <div class="d-flex justify-content-end">
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                    <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                    <span class="indicator-progress">@lang('dashboard.please_wait')
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="retrieveRfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('dashboard.retrieve_from_form') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('dashboard.close') }}"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">{{ __('dashboard.retrieve_from_form') }}</label>

                    <select id="retrieveRfSelect" class="form-select" style="width:100%">
                    <option value=""></option>
                    </select>

                    <small class="text-muted d-block mt-2">{{ __('dashboard.retrieve_placeholder') }}</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="btnDoRetrieve">{{ __('dashboard.retrieve') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

<script>
    (function() {
        const isEdit = @json(isset($order));
        if (isEdit) return;

        const url = new URL(window.location.href);
        const hasPrefill =
            url.searchParams.has('rf_id') ||
            url.searchParams.has('people_count') ||
            url.searchParams.has('service_ids') ||
            url.searchParams.has('date') ||
            url.searchParams.has('time_from') ||
            url.searchParams.has('time_to') ||
            url.searchParams.has('notes') ||
            url.searchParams.has('prefill_mobile') ||
            url.searchParams.has('prefill_email');

        if (!hasPrefill) return;

        const prePhone = url.searchParams.get('prefill_mobile') || '';
        const preEmail = (url.searchParams.get('prefill_email') || '').toLowerCase().trim();
        const $cust = $('select[name="customer_id"]');

        if (url.searchParams.has('rf_id')) {
            $.ajax({
                url: "{{ route('orders.customers.check') }}",
                method: "GET",
                dataType: "json",
                data: { id: url.searchParams.get('rf_id') },
                success: function (c) {
                    console.log(c);

                    const exists = $cust.find('option[value="'+c.customer.id+'"]').length > 0;
                    if (!exists) {
                        const opt = new Option(c.customer.name || (c.customer.email || c.phone || ('#'+c.customer.id)), c.customer.id, true, true);
                        $(opt).attr('data-phone', c.customer.phone || '').attr('data-email', c.customer.email || '');
                        $cust.append(opt);
                    }

                    $cust.val(String(c.customer.id)).trigger('change');
                }
            });
        }

        $('#service_id').trigger('change');

        const rfId = url.searchParams.get('rf_id');
        const isRTL = "{{ app()->getLocale() }}" === "ar";
        if (typeof Swal !== 'undefined') {
            Swal.fire({
            title: isRTL ? 'تم سحب البيانات' : 'Prefilled',
            text: rfId
                ? (isRTL ? `تم سحب البيانات من الاستمارة رقم #${rfId}` : `Loaded data from form #${rfId}` )
                : (isRTL ? 'تم سحب البيانات من الاستمارة' : 'Loaded data from form'),
            icon: 'success',
            confirmButtonText: isRTL ? 'حسناً' : 'OK',
            });
        }
    })();

    $(function() {
        const isRTL = "{{ app()->getLocale() }}" === "ar";

        $('.js-select2').each(function(){
            $(this).select2({
                width: '100%',
                dir: isRTL ? 'rtl' : 'ltr',
                dropdownAutoWidth: true
            });
        });

        $('#status').on('change', function() {
            if (this.value === 'pending_and_show_price' || this.value === 'pending_and_Initial_reservation') {
                $('#expired_price_offer').removeClass('d-none');
                $('#delayed_reson').addClass('d-none');
            } else if (this.value === 'delayed') {
                $('#delayed_reson').removeClass('d-none');
                $('#expired_price_offer').addClass('d-none');
            } else {
                $('#expired_price_offer,#delayed_reson').addClass('d-none');
            }
        });

        function recalcPrice() {
            let total = 0;
            $('#service_id').find('option:selected').each(function() {
                total += parseFloat($(this).data('price')) || 0;
            });
            $('#price').val(total.toFixed(2));
        }
        $('#service_id').on('change', recalcPrice);
        recalcPrice();

        $('#btnRetrieveRf').on('click', function () {
        const el = document.getElementById('retrieveRfModal');
        bootstrap.Modal.getOrCreateInstance(el).show();
        });
        const $modal = $('#retrieveRfModal');
        $modal.on('shown.bs.modal', function () {
        const $sel = $('#retrieveRfSelect');

        if ($sel.data('select2')) $sel.select2('destroy');

        const isRTL = "{{ app()->getLocale() }}" === "ar";

        $sel.select2({
            dropdownParent: $modal,
            width: '100%',
            dir: isRTL ? 'rtl' : 'ltr',
            placeholder: @json(__('dashboard.retrieve_placeholder')),
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
            url: @json(route('orders.registeration-forms.search')),
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => Array.isArray(data)
                ? { results: data }
                : { results: data.results || [] },
            cache: true
            },
            templateResult: rf => {
            if (!rf.id || !rf.extra) return rf.text || rf.id;
            const e = rf.extra;
            return $(
                `<div>
                <div class="fw-bold">${rf.text}</div>
                <div class="text-muted small">#${e.id} • ${e.phone || ''} • ${e.email || ''}</div>
                </div>`
            );
            },
            templateSelection: rf => rf.text || rf.id
        });
        });

        $('#btnDoRetrieve').on('click', function(){
            const id = $('#retrieveRfSelect').val();
            if (!id) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: @json(__('dashboard.select_required')) });
                }
                return;
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: @json(__('dashboard.retrieve_confirm_title')),
                    text:  @json(__('dashboard.retrieve_confirm_body')),
                    icon:  'question',
                    showCancelButton: true,
                    confirmButtonText: @json(__('dashboard.retrieve')),
                    cancelButtonText:  @json(__('dashboard.cancel'))
                }).then((res)=>{ if(res.isConfirmed){ doFetch(id); }});
            } else {
                if (confirm(@json(__('dashboard.retrieve_confirm_body')))) doFetch(id);
            }
        });

        function doFetch(id){
            $.get(@json(route('orders.registeration-forms.fetch', ['id' => '___ID___'])).replace('___ID___', id))
            .done(function(payload){

                $('#rf_id').val(payload.rf_id || '');
                $('input[name="people_count"]').val(payload.people_count || '');
                $('textarea[name="client_notes"]').val(payload.notes || '');
                if (Array.isArray(payload.service_ids)) {
                    const vals = payload.service_ids.map(String);
                    $('#service_id').val(vals).trigger('change');
                }

                if (payload.date)      $('input[name="date"]').val(payload.date);
                if (payload.time_from) $('input[name="time_from"]').val(payload.time_from);
                if (payload.time_to)   $('input[name="time_to"]').val(payload.time_to);
                if (payload.notes)     $('textarea[name="notes"]').val(payload.notes);

                const $cust = $('select[name="customer_id"]');
                const c = payload.customer;
                if (c && c.id) {
                    const exists = $cust.find('option[value="'+c.id+'"]').length > 0;

                    if (!exists) {
                        const opt = new Option(c.name || (c.email || c.phone || ('#'+c.id)), c.id, true, true);
                        $(opt).attr('data-phone', c.phone || '').attr('data-email', c.email || '');
                        $cust.append(opt);
                    }

                    $cust.val(String(c.id)).trigger('change');
                }

                $('#service_id').trigger('change');

                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon:'success', title: @json(__('dashboard.loaded_ok')) });
                }
                const m = bootstrap.Modal.getInstance(document.getElementById('retrieveRfModal'));
                m && m.hide();
            })
            .fail(function(xhr){
                if (typeof Swal !== 'undefined') {
                Swal.fire({ icon:'error', title:'Error', text: (xhr.responseJSON && xhr.responseJSON.message) || 'Failed to fetch data.' });
                }
            });
        }
    });
</script>
@endsection
