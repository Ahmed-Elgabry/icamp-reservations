@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.general_payments'))
@section('content')
<!-------------->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                data-bs-target="#kt_account_profile_details" aria-expanded="true"
                aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{ isset($payment) ? $payment->notes : __('dashboard.general_revenue_deposit')}}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_ecommerce_add_product_form"
                    data-kt-redirect="{{  isset($payment) ? route('general_payments.edit', $payment->id) : route('general_payments.create') }}"
                    action="{{ isset($payment) ? route('accounts.update', $payment->id) : route('accounts.store') }}"
                    method="post" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row store">
                    @csrf
                    @if(isset($payment)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
                        <!-- Input group for Total amount -->
                        <div class="row mb-5">
                            <div class="col-6">
                                <label
                                    class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.amount') }}</label>
                                <div class="col-lg-12">
                                    <input step="0.01" type="number" name="amount" id="amount"
                                        value="{{ isset($payment) ? $payment->amount : '' }}"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label
                                    class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.date') }}</label>
                                <div class="col-lg-12">
                                    <input type="date" name="date" id="date"
                                        value="{{ isset($payment) ? $payment->date : date('Y-m-d') }}"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" required>
                                </div>
                                <input type="hidden" name="source" value="add_funds_page">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-6">
                                <label
                                    class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.bank_account') }}</label>
                                <div class="col-lg-12">
                                    <select name="account_id" id="account_id"
                                        class="form-select form-select-lg form-select-solid mb-3 mb-lg-0">
                                        <option value="">{{ __('dashboard.choose_bank_account') }}</option>
                                        @foreach($bankAccounts as $bankAccount)
                                                <option value="{{ $bankAccount->id }}"
                                                data-currency="{{ $bankAccount->currency }}" {{ isset($payment) &&
                                            $payment->account_id == $bankAccount->id ? 'selected' : '' }}>{{
                                            $bankAccount->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <label
                                    class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.order_number') }}</label>
                                <div class="col-lg-12">
                                <select name="order_id" id="order_id"
                                class="form-select form-select-lg form-select-solid mb-3 mb-lg-0">
                                    <option value="">{{ __('dashboard.select_order') }}</option>
                                    @foreach($orders as $order)
                                        @if ($order->status == "approved")
                                            <option value="{{ $order->id }}" 
                                            {{ isset($payment) && $payment->order_id == $order->id ? 'selected' : '' }}>
                                            {{ __('dashboard.order') }} #{{$order->id }} - {{ optional($order->customer)->first_name ?? '' }} {{ optional($order->customer)->last_name ?? '' }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            
                            <!-- Image upload field -->
                            <div class="col-12 mb-6">
                                <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.upload_or_take_image') }}</label>
                                <div class="col-lg-12">
                                    <input type="file" name="image" id="image"
                                        class="form-control form-control-lg form-control-solid"
                                        accept="image/*"
                                        capture="environment">
                                    @if(isset($payment) && $payment->image_path)
                                        <div class="mt-2">
                                            <p class="text-muted">{{ __('dashboard.existing_attachments') }}:</p>
                                            <img src="{{ asset('storage/' . $payment->image_path) }}" alt="{{ __('dashboard.attached') }}" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                            <br>
                                            <button type="button" class="btn btn-sm btn-primary mt-2" onclick="previewImage('{{ asset('storage/' . $payment->image_path) }}')">
                                                <i class="fas fa-eye"></i> {{ __('dashboard.view') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Notes input -->
                            <div class="col-12 mb-6">
                                <label
                                    class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.notes') }}</label>
                                <div class="col-lg-12">
                                    <textarea name="description" rows="5" class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __('dashboard.notes') }}">{{ isset($payment) ? $payment->description : old('description') }}</textarea>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">{{ __('dashboard.save_changes') }}</span>
                                <span class="indicator-progress">{{ __('dashboard.please_wait') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                        <!--end::Actions-->
                    </div>
                </form>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->

  
    </div>
    <!--end::Container-->
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">{{ __('dashboard.attached') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="{{ __('dashboard.attached') }}" class="img-fluid" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.close') }}</button>
                <a id="downloadImageBtn" href="" download class="btn btn-primary">
                    <i class="fas fa-download"></i> {{ __('dashboard.save_changes') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for receiver dropdown
            $('#receiver_id').select2({
                width: '100%',
                placeholder: "{{ __('dashboard.choose_receiver') }}"
            });
            
            // Initialize Select2 for account dropdown
            $('#account_id').select2({
                width: '100%',
                placeholder: "{{ __('dashboard.choose_bank_account') }}"
            });
            
            // Initialize Select2 for order dropdown with search functionality
            $('#order_id').select2({
                width: '100%',
                placeholder: "{{ __('dashboard.select_order') }}",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "{{ __('dashboard.no_data_found') }}";
                    },
                    searching: function() {
                        return "{{ __('dashboard.search') }}...";
                    }
                }
            });
        });

        // Image preview function
        function previewImage(imageSrc, paymentId = null) {
            document.getElementById('previewImage').src = imageSrc;
            document.getElementById('downloadImageBtn').href = imageSrc;
            
            // If paymentId is provided, update download link to use the controller route
            if (paymentId) {
                const downloadUrl = "{{ route('general_payments.download', ':id') }}".replace(':id', paymentId);
                document.getElementById('downloadImageBtn').href = downloadUrl;
                document.getElementById('downloadImageBtn').removeAttribute('download');
            } else {
                document.getElementById('downloadImageBtn').setAttribute('download', '');
            }
            
            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            modal.show();
        }
    </script>
@endpush