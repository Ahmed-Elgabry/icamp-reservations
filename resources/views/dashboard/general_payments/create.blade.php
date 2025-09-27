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
                    action="{{ isset($payment) ? route('general_payments.update', $payment->id) : route('general_payments.store') }}"
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
                                    <input step="0.01" type="number" name="price" id="amount"
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
                                <input type="hidden" name="source" id="source"
                                        value="general_revenue_deposit"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" >
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
                                    @foreach(App\Models\Order::all() as $order)
                                        @if ($order->status == "approved" || $order->status == "delayed" || $order->status == "completed")
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

        <!--begin::Recent General Payments-->
        @if(isset($recentGeneralPayments) && $recentGeneralPayments->count() > 0)
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{ __('dashboard.recent_general_payments') }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Content-->
            <div class="card-body border-top p-9">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="">{{ __('dashboard.statement') }}</th>
                            <th>{{ __('dashboard.price') }}</th>
                            <th class="">{{ __('dashboard.payment_method') }}</th>
                            <th class="">{{ __('dashboard.bank_account') }}</th>
                            <th class="">{{ __('dashboard.verified') }}</th>
                            <th class="">{{ __('dashboard.attached') }}</th>
                            <th class="">{{ __('dashboard.notes') }}</th>
                            <th class="">{{ __('dashboard.created_at') }}</th>
                            <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($recentGeneralPayments as $payment)
                            <!--begin::Table row-->
                            <tr data-id="{{$payment->id}}">
                                <!--begin::Checkbox-->
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$payment->id}}"/>
                                    </div>
                                </td>
                                <!--begin::Statement-->
                                <td>{{ $payment->statement ? __('dashboard.'. $payment->statement) : '-' }}</td>
                                <!--begin::Price-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">{{$payment->price}}</a>
                                        </div>
                                    </div>
                                </td>
                                <!--begin::Payment Method-->
                                <td>{{ $payment->payment_method ? __('dashboard.'. $payment->payment_method) : '-' }}</td>
                                <!--begin::Bank Account-->
                                <td>{{$payment->account->name ?? '-'}}</td>
                                <!--begin::Verified-->
                                <td>
                                    {{ $payment->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                    @can('general_payments.approve')
                                    @if($payment->verified)
                                        <a href="{{ route('order.verified', ['Id' => $payment->id, 'type' => 'general_revenue_deposit']) }}" class="btn btn-sm btn-danger">{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                    @else
                                        <a href="{{ route('order.verified', ['Id' => $payment->id, 'type' => 'general_revenue_deposit']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                    @endif
                                    @endcan
                                </td>
                                <!--begin::Attached-->
                                <td>
                                    @if($payment->image_path)
                                    @can('general_payments.show')
                                        <button type="button" class="btn btn-sm btn-primary" onclick="previewImage('{{ asset('storage/' . $payment->image_path) }}', '{{ $payment->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @endcan
                                    @else
                                        <span class="text-muted">{{ __('dashboard.no_data') }}</span>
                                    @endif
                                </td>
                                <!--begin::Notes-->
                                <td data-kt-ecommerce-category-filter="category_name">
                                    {{$payment->notes ?? '-'}}
                                </td>
                                <!--begin::Created At-->
                                <td>
                                    {{$payment->created_at->diffForHumans() }}
                                </td>
                                <!--begin::Action-->
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    {{ __('dashboard.actions') }}
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        @can('general_payments.edit')
                                        <div class="menu-item px-3">
                                            <a href="#"
                                               class="menu-link px-3 btn-edit-general-payment"
                                               data-id="{{ $payment->id }}"
                                               data-price="{{ $payment->price }}"
                                               data-date="{{ $payment->date ?? optional($payment->created_at)->format('Y-m-d') }}"
                                               data-account-id="{{ $payment->account_id }}"
                                               data-order-id="{{ $payment->order_id }}"
                                               data-description="{{ $payment->notes }}"
                                               data-payment-method="{{ $payment->payment_method }}"
                                               data-statement="{{ $payment->statement }}">
                                                {{ __('actions.edit') }}
                                            </a>
                                        </div>
                                        @endcan
                                        @can('general_payments.destroy')
                                        <div class="menu-item px-6 py-0">
                                            <form class="d-inline store px-3" method="POST" action="{{ route('general_payments.destroy', $payment->id) }}" data-kt-redirect="{{ url()->full() }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="menu-link px-3 btn btn-link p-0 m-0 align-baseline" onclick="return confirm('@lang('dashboard.confirm_delete')')">@lang('dashboard.delete')</button>
                                            </form>
                                        </div>
                                        @endcan
                                    </div>
                                    <!--end::Menu-->
                                </td>
                                <!--end::Action-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Recent General Payments-->
        @endif
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
    <!-- Edit General Payment Modal -->
    <div class="modal fade" id="editGeneralPaymentModal" tabindex="-1" aria-labelledby="editGeneralPaymentLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGeneralPaymentLabel">@lang('actions.edit')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editGeneralPaymentForm" class="form d-flex flex-column  store" method="POST" enctype="multipart/form-data" data-kt-redirect="{{ url()->full() }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.amount') }}</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.date') }}</label>
                                <input type="date" name="date" class="form-control" required>
                                <input type="hidden" name="source" value="general_revenue_deposit">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">{{ __('dashboard.bank_account') }}</label>
                                <select name="account_id" class="form-select" required>
                                    <option value="">{{ __('dashboard.choose_bank_account') }}</option>
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.order_number') }} <span class="text-muted">(optional)</span></label>
                                <select name="order_id" class="form-select">
                                    <option value="">{{ __('dashboard.select_order') }}</option>
                                    @foreach(App\Models\Order::all() as $order)
                                        @if ($order->status == "approved" || $order->status == "delayed" || $order->status == "completed")
                                            <option value="{{ $order->id }}">{{ __('dashboard.order') }} #{{$order->id }} - {{ optional($order->customer)->first_name ?? '' }} {{ optional($order->customer)->last_name ?? '' }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('dashboard.upload_or_take_image') }}</label>
                                <input type="file" name="image" class="form-control" accept="image/*" capture="environment">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('dashboard.notes') }}</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

            // Edit in modal: wire up click to populate and open the modal
            $(document).on('click', '.btn-edit-general-payment', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var price = $(this).data('price');
                var date = $(this).data('date');
                var accountId = $(this).data('account-id');
                var orderId = $(this).data('order-id');
                var description = $(this).data('description');

                var form = $('#editGeneralPaymentForm');
                // Use named route for update to avoid hardcoded paths
                var action = "{{ route('general_payments.update', ':id') }}".replace(':id', id);
                form.attr('action', action);
                form.find('input[name="price"]').val(price);
                form.find('input[name="date"]').val(date);
                form.find('select[name="account_id"]').val(accountId).trigger('change');
                form.find('select[name="order_id"]').val(orderId).trigger('change');
                form.find('textarea[name="description"]').val(description);

                var modal = new bootstrap.Modal(document.getElementById('editGeneralPaymentModal'));
                modal.show();
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
