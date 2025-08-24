@extends('dashboard.layouts.app')
@props(['order' => null])
@section('pageTitle', __('dashboard.orders'))
@section('content')
    <!-------------->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            @include('dashboard.orders.nav')
            <!--begin::Basic info-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     data-bs-target="#kt_account_profile_details" aria-expanded="true"
                     aria-controls="kt_account_profile_details">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            {{ isset($order) ? $order->customer->name : __('dashboard.create_title', ['page_title' => __('dashboard.orders')]) }}
                        </h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div class="collapse show">
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_product_form"
                          data-kt-redirect="{{ isset($order) ? route('orders.edit', $order->id) : route('orders.create') }}"
                          action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}"
                          method="post" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row store">
                        @csrf
                        @if (isset($order))
                            @method('PUT')
                        @endif

                        <div class="card-body border-top p-9">
                            <!-- Customer Select -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.customer_first_and_last_name')</label>
                                <div class="col-lg-8">
                                    <select name="customer_id"
                                            class="form-select form-select-lg form-select-solid select2-hidden-accessible"
                                            required>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
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
                                           value="{{ isset($order) ? $order->people_count : old('people_count') }}"
                                           required>
                                </div>
                            </div>

                            <input type="hidden" name="created_by" value="{{ Auth::id() }}">

                            <!-- Service Select -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">@lang('dashboard.service')</label>
                                <div class="col-lg-8">
                                    <select name="service_ids[]" id="service_id" placeholder="@lang('dashboard.service')"
                                            class="form-select form-select-lg form-select-solid select2-hidden-accessible"
                                            multiple="multiple" required>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                                                {{ isset($order) && in_array($service->id, $order->services->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.service_price')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="price" id="price"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->price : old('price') }}" required>
                                </div>
                            </div>

                            <!-- addons Price -->
                            @if (isset($order))
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.addons')</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="price" id="price"
                                               class="form-control form-control-lg form-control-solid"
                                               value="{{ isset($order) ? $addonsPrice : 0 }}" readonly>
                                    </div>
                                </div>
                            @endif

                            <!-- deposit -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">@lang('dashboard.deposit')</label>
                                <div class="col-lg-8">
                                    <input type="number" name="deposit" id="deposit"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->deposit : old('deposit') }}" required>
                                </div>
                            </div>

                            <!-- insurance_amount -->
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
                                    <textarea name="notes" class="form-control form-control-lg form-control-solid" placeholder="@lang('dashboard.notes')">{{ isset($order) ? $order->notes : old('notes') }}</textarea>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.date')</label>
                                <div class="col-lg-8">
                                    <input type="date" name="date"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ request('date', isset($order) ? $order->date : old('date')) }}">
                                </div>
                            </div>


                            <!-- Time From -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_from')</label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_from"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->time_from : old('time_from') }}">
                                </div>
                            </div>

                            <!-- Time To -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.time_to')</label>
                                <div class="col-lg-8">
                                    <input type="time" name="time_to"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->time_to : old('time_to') }}">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">@lang('dashboard.status')</label>
                                <div class="col-lg-8">
                                    <select name="status" class="form-select form-select-lg form-select-solid" id="status">
                                        <option value="pending_and_show_price" title="@lang('dashboard.pending_and_show_price_desc')" {{ isset($order) && $order->status == 'pending_and_show_price' ? 'selected' : '' }}>
                                            @lang('dashboard.pending_and_show_price_desc')
                                        </option>
                                        <option value="pending_and_Initial_reservation" title="@lang('dashboard.pending_and_Initial_reservation_desc')" {{ isset($order) && $order->status == 'pending_and_Initial_reservation' ? 'selected' : '' }}>
                                            @lang('dashboard.pending_and_Initial_reservation')
                                        </option>
                                        <option value="approved" title="@lang('dashboard.approved_desc')" {{ isset($order) && $order->status == 'approved' ? 'selected' : '' }}>
                                            @lang('dashboard.approved')
                                        </option>
                                        <option value="canceled" title="@lang('dashboard.canceled_desc')" {{ isset($order) && $order->status == 'canceled' ? 'selected' : '' }}>
                                            @lang('dashboard.canceled')
                                        </option>
                                        <option value="delayed" title="@lang('dashboard.delayed')"
                                            {{ isset($order) && $order->status == 'delayed' ? 'selected' : '' }}>
                                            @lang('dashboard.delayed')</option>
                                        <option value="completed" title="@lang('dashboard.completed_desc')"
                                            {{ isset($order) && $order->status == 'completed' ? 'selected' : '' }}>
                                            @lang('dashboard.completed')</option>
                                    </select>
                                </div>
                            </div>

                            {{-- delayed_reson --}}
                            <div @class(['row mb-6' , 'd-none' => (!$order || $order->status != 'delayed') ]) id="delayed_reson">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.delayed_reson')</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control @error('delayed_reson') is-invalid @enderror" name="delayed_reson" value="{{ old('delayed_reson' , isset($order) ? $order->delayed_reson : '') }}">
                                    @error('delayed_reson')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @php $orderStatus = isset($order) ? ($order->status != 'pending_and_show_price' && $order->status != 'pending_and_Initial_reservation') : null  @endphp
                            <div @class(['row mb-6' , 'd-none' => $orderStatus ]) id="expired_price_offer">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">@lang('dashboard.expired_price_offer') <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="date" name="expired_price_offer"
                                           placeholder="@lang('dashboard.expired_price_offer')"
                                           id="expired_price_offer"
                                           class="form-control form-control-lg form-control-solid"
                                           value="{{ isset($order) ? $order->expired_price_offer : old('expired_price_offer') }}">
                                </div>
                            </div>

                            <div class='row mb-6'>
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    @lang('dashboard.client_notes')
                                </label>
                                <div class="col-lg-8">
                                    <textarea name="client_notes" class="form-control">
                                           {{ isset($order) ? $order->client_notes : old('client_notes') }}
                                    </textarea>
                                </div>
                            </div>

                            @if (isset($order) && $order->status == 'canceled')
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label  fw-bold fs-6">رد المبالغ المدفوعه ؟</label>
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
                                <!-- Time From -->
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
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        @lang('dashboard.Customer_Signature')
                                    </label>

                                    <div class="col-lg-8 d-flex flex-column gap-3">
                                        @if(isset($order->signature_path))
                                            <div class="text-success fw-bold">
                                                {{ $order?->signature }}
                                            </div>
                                            <img src="{{ Storage::url($order->signature_path) }}" alt="Signature" style="max-height:80px;">
                                        @else
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control"
                                                        value="{{ route('signature.show', $order) }}"
                                                        readonly
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

                            <!-- Hidden fields for additional notes and options -->
                            <input type="hidden" name="show_price_notes" id="show_price_notes" value="{{ isset($order) ? $order->show_price_notes : '' }}">
                            <input type="hidden" name="order_data_notes" id="order_data_notes" value="{{ isset($order) ? $order->order_data_notes : '' }}">
                            <input type="hidden" name="invoice_notes" id="invoice_notes" value="{{ isset($order) ? $order->invoice_notes : '' }}">
                            <input type="hidden" name="receipt_notes" id="receipt_notes" value="{{ isset($order) ? $order->receipt_notes : '' }}">

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end gap-2">
                                <!--begin::Additional Notes Button-->
                                <button type="button" id="additional-notes-btn" class="btn btn-secondary">
                                    <span class="indicator-label">@lang('dashboard.additional_notes')</span>
                                </button>
                                <!--end::Additional Notes Button-->

                                <!--begin::Submit Button-->
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                    <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                    <span class="indicator-progress">@lang('dashboard.please_wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Submit Button-->
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->

                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Basic info-->

        </div>
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
        <!--end::Container-->
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.tiny.cloud/1/m181ycw0urzvmmzinvpzqn3nv10wxttgo7gvv77hf6ce6z89/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
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

            $('#status').change(function() {
                if ($(this).val() === 'pending_and_show_price' || $(this).val() === 'pending_and_Initial_reservation' ) {
                    $('#expired_price_offer').removeClass('d-none');
                } else if($(this).val() === 'delayed') {
                    $('#delayed_reson').removeClass('d-none');
                } else {
                    $('#expired_price_offer,#delayed_reson').addClass('d-none');
                }
            });


            $('.select2-hidden-accessible').select2(); // Initialize Select2

            $('#service_id').change(function() {
                var totalSelectedPrice = 0;
                // Loop through all selected options and sum their prices
                $(this).find('option:selected').each(function() {
                    var servicePrice = $(this).data('price');
                    totalSelectedPrice += parseFloat(servicePrice);
                });

                // Update the price input with the total price
                $('#price').val(totalSelectedPrice.toFixed(
                    2)); // .toFixed(2) ensures the price is formatted as a decimal with two digits
            });

            // Customer change event to check for notices
            $('select[name="customer_id"]').change(function() {
                const customerId = $(this).val();
                if (customerId) {
                    $.get('/orders/check-customer-notices/' + customerId, function(response) {
                        if (response.hasNotices) {
                            // Detect current language direction
                            const isRTL = "{{ app()->getLocale() }}" === "ar";
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
        });
    </script>
@endsection
