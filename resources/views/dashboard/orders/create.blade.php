@extends('dashboard.layouts.app')
@props(['order' => null])
@section('pageTitle', __('dashboard.order_data'))

@section('content')

<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
            @if (isset($order))
                @include('dashboard.orders.nav')
            @endif
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
                            <input type="hidden" name="show_price_notes" id="show_price_notes" value="{{ isset($order) ? $order->show_price_notes : '' }}">
                            <input type="hidden" name="order_data_notes" id="order_data_notes" value="{{ isset($order) ? $order->order_data_notes : '' }}">
                            <input type="hidden" name="invoice_notes" id="invoice_notes" value="{{ isset($order) ? $order->invoice_notes : '' }}">
                            <input type="hidden" name="receipt_notes" id="receipt_notes" value="{{ isset($order) ? $order->receipt_notes : '' }}">

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">
                                    @lang('dashboard.customer_first_and_last_name')
                                </label>
                                <div class="col-lg-8">
                                    <select name="customer_id"
                                            class="js-select2 form-select form-select-lg form-select-solid"
                                            required>
                                        <option value="" @unless ($customers) selected @endunless disabled>{{ __('dashboard.choose') }}</option>
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

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">
                                    @lang('dashboard.camp_price')
                                </label>
                                <div class="col-lg-8">
                                    <input type="number" name="price" id="price"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->price : old('price') }}" required>
                                </div>
                            </div>

                            @if (isset($order))
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.addons')</label>
                                    <div class="col-lg-8">
                                        <input type="number" class="form-control form-control-lg form-control-solid"
                                               value="{{ $addonsPrice ?? 0 }}" >
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.deposit')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="deposit" id="deposit"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->deposit : old('deposit') }}" required>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.insurance_amount')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="insurance_amount" id="insurance_amount"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->insurance_amount : old('insurance_amount') }}"
                                           required>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.internal_notes')</label>
                                <div class="col-lg-8">
                                <textarea name="notes" class="form-control form-control-lg form-control-solid"
                                          placeholder="@lang('dashboard.notes')">{{ isset($order) ? $order->notes : old('notes', request('notes')) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.date')</label>
                                <div class="col-lg-8">
                                    <input type="date" name="date"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->date : old('date', request('date')) }}">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_from')</label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_from"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->time_from : old('time_from', request('time_from')) }}">
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_to')</label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_to"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->time_to : old('time_to', request('time_to')) }}">
                                </div>
                            </div>

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

                            @php
                                $orderStatus = isset($order)
                                  ? ($order->status != 'pending_and_show_price' && $order->status != 'pending_and_Initial_reservation')
                                  : null;
                            @endphp

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
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">رد المبالغ المدفوعه ؟ <span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <select name="refunds" class="form-select form-select-lg form-select-solid" required>
                                            <option value="">{{ __('dashboard.choose') }} {{ __('dashboard.refund_option') }}</option>
                                            <option value="1" {{ isset($order) && $order->refunds == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ isset($order) && $order->refunds == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.refunds_notes')</label>
                                    <div class="col-lg-8">
                                    <textarea name="refunds_notes" class="form-control form-control-lg form-control-solid"
                                            placeholder="@lang('dashboard.refunds_notes')">{{ isset($order) ? $order->refunds_notes : old('refunds_notes') }}</textarea>
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

                            <div class="d-flex justify-content-end gap-2">
                                @if(isset($order))
                                    <!--begin::Email Button-->
                                    <button type="button" id="send-email-btn" class="btn btn-secondary d-flex align-items-center gap-2">
                                        <img src="{{ asset('imgs/gmail.png') }}" alt="Email Icon" width="20" height="20">
                                        <span class="indicator-label">@lang('dashboard.send_email')</span>
                                    </button>
                                    <!--end::Email Button-->
                                @endif
                                <!--begin::Additional Notes Button-->
                                <button type="button" id="additional-notes-btn" class="btn btn-secondary">
                                    <span class="indicator-label">@lang('dashboard.additional_notes')</span>
                                </button>
                                <!--end::Additional Notes Button-->
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

    <!--begin::Modal - Send Email-->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bolder">@lang('dashboard.send_email_to_customer')</h2>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-15">
                    <form id="sendEmailForm" class="form">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ isset($order) ? $order->id : '' }}">
                        <div class="row mb-8">
                            <label class="col-form-label fw-bold fs-6">@lang('dashboard.select_documents_to_send')</label>
                            <div class="checkbox-list">
                                <label class="checkbox">
                                    <input type="checkbox" name="documents[]" value="show_price">
                                    <span></span>
                                    @lang('dashboard.show_price_pdf')
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="documents[]" value="reservation_data">
                                    <span></span>
                                    @lang('dashboard.reservation_data_pdf')
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="documents[]" value="invoice">
                                    <span></span>
                                    @lang('dashboard.invoice_pdf')
                                </label>

                                <!-- Addon Receipts -->
                                @if(isset($order) && $order->addons->where('pivot.verified', true)->count() > 0)
                                    <div class="mt-3">
                                        <h6 class="fw-bolder">@lang('dashboard.addon_receipts')</h6>
                                        @foreach($order->addons->where('pivot.verified', true) as $addon)
                                            <label class="checkbox">
                                                <input type="checkbox" name="receipts[addon][]" value="{{ $addon->pivot->id }}">
                                                <span></span>
                                                @lang('dashboard.addon_receipt'): {{ $addon->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Payment Receipts -->
                                @if(isset($order) && $order->payments->where('verified', true)->count() > 0)
                                    <div class="mt-3">
                                        <h6 class="fw-bolder">@lang('dashboard.payment_receipts')</h6>
                                        @foreach($order->payments->where('verified', true) as $payment)
                                            <label class="checkbox">
                                                <input type="checkbox" name="receipts[payment][]" value="{{ $payment->id }}">
                                                <span></span>
                                                @lang('dashboard.payment_receipt'): {{ $payment->price }} - {{ __('dashboard.'.$payment->payment_method) }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Warehouse Receipts -->
                                @if(isset($order) && $order->items->where('verified', true)->count() > 0)
                                    <div class="mt-3">
                                        <h6 class="fw-bolder">@lang('dashboard.warehouse_receipts')</h6>
                                        @foreach($order->items->where('verified', true) as $item)
                                            <label class="checkbox">
                                                <input type="checkbox" name="receipts[warehouse][]" value="{{ $item->id }}">
                                                <span></span>
                                                @lang('dashboard.warehouse_receipt'): {{ $item->stock->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-8">
                            <label class="col-form-label fw-bold fs-6">@lang('dashboard.customer_email')</label>
                            <div class="">
                                <input type="email" class="form-control" value="{{ isset($order) && $order->customer ? $order->customer->email : '' }}" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('dashboard.cancel')</button>
                    <button type="button" id="sendEmailSubmit" class="btn btn-primary">@lang('dashboard.send')</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Send Email-->
    <!--begin::Modal - Additional Notes-->
    <div class="modal fade" id="additionalNotesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bolder">@lang('dashboard.additional_notes')</h2>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-15">
                    <form id="additionalNotesForm" class="form">
                        <!-- Show Price Notes -->
                        <label class="col-form-label fw-bold fs-6">@lang('dashboard.show_price_notes')</label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="showPriceEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>

                        <!-- Order Data Notes -->
                        <label class="col-form-label fw-bold fs-6">@lang('dashboard.order_data_notes')</label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="orderDataEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>

                        <!-- Invoice Notes -->
                        <label class="col-form-label fw-bold fs-6">@lang('dashboard.invoice_notes')</label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="invoiceEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>

                        <!-- Receipt Notes -->
                        <label class="col-form-label fw-bold fs-6">@lang('dashboard.receipt_notes')</label>
                        <div class="row mb-8">
                            <div class="">
                                <textarea id="receiptEditor" class="form-control notes-editor" rows="6"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('dashboard.cancel')</button>
                    <button type="button" id="saveAdditionalNotes" class="btn btn-primary">@lang('dashboard.save')</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Additional Notes-->
@endsection

@section('scripts')
    <script src="https://cdn.tiny.cloud/1/m181ycw0urzvmmzinvpzqn3nv10wxttgo7gvv77hf6ce6z89/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        (function(){
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
                $('#service_id option:selected').each(function(){
                    total += parseFloat($(this).data('price')) || 0;
                });
                $('#price').val(total.toFixed(2));
            }
            // Recalculate only on user-initiated changes (ignore programmatic .trigger('change'))
            $('#service_id').on('change', function(e){
                if (!e.originalEvent) return; // skip programmatic changes
                recalcPrice();
            });
            // Recalculate only on user-initiated changes (ignore programmatic .trigger('change'))
            $('#service_id').on('change', function(e){
                if (!e.originalEvent) return; // skip programmatic changes
                recalcPrice();
            });
            $('#btnRetrieveRf').on('click', ()=>{
                const el = document.getElementById('retrieveRfModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
            });

            const $modal = $('#retrieveRfModal');
            $modal.on('shown.bs.modal', function () {
                const $sel = $('#retrieveRfSelect');
                if ($sel.data('select2')) $sel.select2('destroy');

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
                        processResults: data => {
                            const results = Array.isArray(data) ? data : (data.results || []);
                            return { results };
                        },
                        cache: true
                    }
                });
            });

            $('#btnDoRetrieve').on('click', function(){
                const id = $('#retrieveRfSelect').val();
                if (!id) {
                    return window.Swal
                        ? Swal.fire({ icon: 'warning', title: @json(__('dashboard.select_required')) })
                        : alert(@json(__('dashboard.select_required')));
                }

                const ask = () => $.get(@json(route('orders.registeration-forms.fetch', ['id' => '___ID___'])).replace('___ID___', id))
                    .done(fillFormFromPayload)
                    .fail((xhr)=>{
                        window.Swal
                            ? Swal.fire({ icon:'error', title:'Error', text: (xhr.responseJSON && xhr.responseJSON.message) || 'Failed to fetch data.' })
                            : alert('Failed to fetch data.');
                    });

                if (window.Swal) {
                    Swal.fire({
                        title: @json(__('dashboard.retrieve_confirm_title')),
                        text:  @json(__('dashboard.retrieve_confirm_body')),
                        icon:  'question',
                        showCancelButton: true,
                        confirmButtonText: @json(__('dashboard.retrieve')),
                        cancelButtonText:  @json(__('dashboard.cancel'))
                    }).then((res)=>{ if(res.isConfirmed){ ask(); } });
                } else {
                    if (confirm(@json(__('dashboard.retrieve_confirm_body')))) ask();
                }
            });

            function fillFormFromPayload(payload){
                $('#rf_id').val(payload.rf_id || '');
                $('input[name="people_count"]').val(payload.people_count || '');

                // Set price from database if it exists, otherwise calculate from service IDs
                if (payload.price) {
                    $('#price').val(payload.price);
                } else {
                    $('#service_id').val(payload.service_ids.map(String)).trigger('change');
                    // Recalc after programmatic selection from retrieved form
                    recalcPrice();
                    recalcPrice();
                }

                if (payload.date)      $('input[name="date"]').val(payload.date);
                if (payload.time_from) $('input[name="time_from"]').val(payload.time_from);
                if (payload.time_to)   $('input[name="time_to"]').val(payload.time_to);
                if (payload.notes)     $('textarea[name="client_notes"]').val(payload.notes);

                const $cust = $('select[name="customer_id"]');
                const c = payload.customer;
                if (c && c.id) {
                    const exists = $cust.find(`option[value="${c.id}"]`).length > 0;
                    if (!exists) {
                        const opt = new Option(c.name || (c.email || c.phone || `#${c.id}`), c.id, true, true);
                        $(opt).attr('data-phone', c.phone || '').attr('data-email', c.email || '');
                        $cust.append(opt);
                    }
                    $cust.val(String(c.id)).trigger('change');
                }

                $('#service_id').trigger('change');

                if (window.Swal) Swal.fire({ icon: 'success', title: @json(__('dashboard.loaded_ok')) });
                const m = bootstrap.Modal.getInstance(document.getElementById('retrieveRfModal'));
                m && m.hide();
            }

            // Add event listener to recalculate price on service ID change
            $('#service_id').on('change', function () {
                recalcPrice();
            });

            (function linkPrefill(){
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

                const isEdit = @json(isset($order));
                if (!hasPrefill || isEdit) return;

                if (url.searchParams.has('rf_id') && "{{ Route::has('orders.customers.check') ? '1' : '0' }}" === "1") {
                    $.ajax({
                        url: "{{ route('orders.customers.check') }}",
                        method: "GET",
                        dataType: "json",
                        data: { id: url.searchParams.get('rf_id') }
                    }).done(function (c) {
                        if (!c || !c.customer || !c.customer.id) return;
                        const $cust = $('select[name="customer_id"]');
                        const exists = $cust.find('option[value="'+c.customer.id+'"]').length > 0;
                        if (!exists) {
                            const opt = new Option(c.customer.name || (c.customer.email || c.customer.phone || ('#'+c.customer.id)),
                                c.customer.id, true, true);
                            $(opt).attr('data-phone', c.customer.phone || '').attr('data-email', c.customer.email || '');
                            $cust.append(opt);
                        }
                        $cust.val(String(c.customer.id)).trigger('change');
                    });
                }

                const rfId = url.searchParams.get('rf_id');
                if (window.Swal && rfId) {
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

            /////////////// Start:samuel work ///////////////
            // Initialize TinyMCE
            const editorConfig = {
                plugins: 'link lists code',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link',
                menubar: false,
                height: '50vh',
                skin: 'oxide',
                content_css: 'default',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            };

            // Initialize editors
            tinymce.init({ ...editorConfig, selector: '#showPriceEditor' });
            tinymce.init({ ...editorConfig, selector: '#orderDataEditor' });
            tinymce.init({ ...editorConfig, selector: '#invoiceEditor' });
            tinymce.init({ ...editorConfig, selector: '#receiptEditor' });

            // Additional notes modal functionality
            const additionalNotesModal = new bootstrap.Modal(document.getElementById('additionalNotesModal'));

            let additionalNotesData = {
                notes: '',
                show_price: false,
                order_data: false,
                invoice: false,
                receipt: false
            };

            // Load existing data if any
            function loadExistingData() {
                tinymce.get('showPriceEditor').setContent($('#show_price_notes').val() || '');
                tinymce.get('orderDataEditor').setContent($('#order_data_notes').val() || '');
                tinymce.get('invoiceEditor').setContent($('#invoice_notes').val() || '');
                tinymce.get('receiptEditor').setContent($('#receipt_notes').val() || '');
            }

            // Open modal
            $('#additional-notes-btn').click(function() {
                loadExistingData();
                additionalNotesModal.show();
            });

            // Save additional notes
            $('#saveAdditionalNotes').click(function() {
                // Update hidden fields with editor content
                $('#show_price_notes').val(tinymce.get('showPriceEditor').getContent());
                $('#order_data_notes').val(tinymce.get('orderDataEditor').getContent());
                $('#invoice_notes').val(tinymce.get('invoiceEditor').getContent());
                $('#receipt_notes').val(tinymce.get('receiptEditor').getContent());

                additionalNotesModal.hide();

                // Show success message
                Swal.fire({
                    text: "{{ __('dashboard.additional_notes_saved') }}",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "{{ __('dashboard.ok') }}",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            });

            // Customer change event to check for notices
            $('select[name="customer_id"]').change(function() {
                const customerId = $(this).val();
                if (customerId) {
                    $.get('/orders/check-customer-notices/' + customerId, function(response) {
                        if (response.hasNotices) {
                            // Detect current language direction
                            const textAlign = isRTL ? 'right' : 'left';

                            let noticeContent = `<div style="text-align: ${textAlign}; font-size: 14px; line-height: 1.6;">`;
                            response.notices.forEach(notice => {
                                noticeContent += `
                        <div style="margin-bottom: 15px; padding: 12px; background: #fdfdfd; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <p style="margin: 0 0 6px 0; font-weight: bold; color: #333;">
                                {{ __('dashboard.notice_label') }}:
                            </p>
                            <p style="margin: 0 0 8px 0; color: #555;">${notice.content}</p>
                            <p style="margin: 0; font-size: 12px; color: #888;">
                                {{ __('dashboard.created_by_at', ['name' => '${notice.created_by}', 'date' => '${notice.created_at}']) }}
                                </p>
                            </div>
`;
                            });
                            noticeContent += '</div>';

                            Swal.fire({
                                title: "{{ __('dashboard.customer_has_notices') }}",
                                html: noticeContent,
                                icon: 'warning',
                                confirmButtonText: "{{ __('dashboard.ok') }}",
                                width: '700px',
                                customClass: {
                                    popup: isRTL ? 'swal2-rtl' : ''
                                }
                            });
                        }
                    });
                }
            });

            // Email modal functionality
            const sendEmailModal = new bootstrap.Modal(document.getElementById('sendEmailModal'));

            // Open email modal
            $('#send-email-btn').click(function() {
                sendEmailModal.show();
            });

            // Send email
            $('#sendEmailSubmit').click(function() {
                const formData = new FormData();
                const documents = [];
                const receipts = [];

                // Get main documents
                $('input[name="documents[]"]:checked').each(function() {
                    documents.push($(this).val());
                    formData.append('documents[]', $(this).val());
                });

                // Get addon receipts
                $('input[name="receipts[addon][]"]:checked').each(function() {
                    receipts.push({type: 'addon', id: $(this).val()});
                    formData.append('receipts[addon][]', $(this).val());
                });

                // Get payment receipts
                $('input[name="receipts[payment][]"]:checked').each(function() {
                    receipts.push({type: 'payment', id: $(this).val()});
                    formData.append('receipts[payment][]', $(this).val());
                });

                // Get warehouse receipts
                $('input[name="receipts[warehouse][]"]:checked').each(function() {
                    receipts.push({type: 'warehouse', id: $(this).val()});
                    formData.append('receipts[warehouse][]', $(this).val());
                });

                // Check if at least one document is selected
                if (documents.length === 0 && receipts.length === 0) {
                    Swal.fire({
                        text: "{{ __('dashboard.please_select_at_least_one_document') }}",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "{{ __('dashboard.ok') }}",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return;
                }

                const orderId = $('input[name="order_id"]').val();
                formData.append('_token', $('input[name="_token"]').val());

                // Show loading indicator
                $('#sendEmailSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __("dashboard.sending") }}');

                $.ajax({
                    url: "{{ route('orders.sendEmail', isset($order) ? $order->id : '') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        sendEmailModal.hide();
                        $('#sendEmailSubmit').prop('disabled', false).html('{{ __("dashboard.send") }}');

                        Swal.fire({
                            text: response.message,
                            icon: response.success ? "success" : "error",
                            buttonsStyling: false,
                            confirmButtonText: "{{ __('dashboard.ok') }}",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    },
                    error: function(xhr) {
                        $('#sendEmailSubmit').prop('disabled', false).html('{{ __("dashboard.send") }}');

                        let message = "{{ __('dashboard.something_went_wrong') }}";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            text: message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "{{ __('dashboard.ok') }}",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            });
            /////////////// End:samuel work ///////////////
        })();
    </script>
@endsection
