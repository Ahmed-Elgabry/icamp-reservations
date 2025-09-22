@extends('dashboard.layouts.app')

@section('pageTitle' , __('dashboard.addons'))
@section('content')

<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

        @include('dashboard.orders.nav')

        <!--begin::Category-->
        <div class="card card-flush">
            <!-- customer information -->
            <div class="pt-5 px-9 gap-2 gap-md-5">
                <div class="row g-3 small">
                    <div class="col-md-1 text-center">
                        <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                        <div class="fw-bold">{{ $order->id }}</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="fw-semibold text-muted">{{ __('dashboard.customer_name') }}</div>
                        <div class="fw-bold">{{ $order->customer->name }}</div>
                    </div>
                </div>
            </div>
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="@lang('dashboard.search_title', ['page_title' => __('dashboard.addons')])" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    @can('bookings.addons.create')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAddonModal">
                        @lang('dashboard.create_title', ['page_title' => __('dashboard.addons')])
                    </button>
                    @endcan
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <thead>
                        <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                            <th class="fw-bolder">{{ __('dashboard.addon_type') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.addon_price') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.quantity') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.total_price') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.payment_method') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.bank_account') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.verified') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.notes') }}</th>
                            <th class="fw-bolder">{{ __('dashboard.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($order->addons as $orderAddon)
                        <tr data-id="{{ $orderAddon->id }}">
                            <td data-kt-ecommerce-category-filter="category_name">{{ $orderAddon->name }}</td>
                            <td>{{ $orderAddon->price }}</td>
                            <td>{{ $orderAddon->pivot->count }}</td>
                            <td>{{ $orderAddon->pivot->price }}</td>
                            <td>{{__('dashboard.'. $orderAddon->pivot->payment_method )}}</td>
                            <td>{{ $orderAddon->pivot->account->name }}</td>
                            <td>
                                {{ $orderAddon->pivot->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                @can('bookings.addons.approve')
                                @if($orderAddon->pivot->verified)
                                <a href="{{ route('order.verified' , [$orderAddon->pivot->id , 'addon']) }}" class="btn btn-sm btn-danger">{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                @else
                                <a href="{{ route('order.verified' , [$orderAddon->pivot->id , 'addon']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                @endif
                                @endcan
                            </td>
                            <td>{{ $orderAddon->pivot->description }}</td>


                            <!--begin::Action=-->
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    {{ __('dashboard.actions') }}
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon--></a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        @can('bookings.addons.reciept')
                                        <a href="#"
                                            class="menu-link px-3 receipt-link"
                                            data-verified="{{ $orderAddon->pivot->verified == 1 ? '1' : '0' }}"
                                            data-url="{{ route('addons.receipt', ['order' => $order->id, 'addon' => $orderAddon->pivot->id]) }}">
                                            {{ __('dashboard.receipt') }}
                                        </a>
                                        @endcan
                                    </div>
                                    <div class="menu-item px-3">
                                        @can('bookings.removeAddon')
                                        <form action="{{ route('bookings.removeAddon', $orderAddon->pivot->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" onclick="return confirm('@lang('dashboard.confirm_delete')')">
                                                {{ __('dashboard.delete') }}
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        @can('bookings.addons.edit')
                                        <button class="btn" data-toggle="modal" data-target="#editAddonModal-{{ $orderAddon->pivot->id }}">
                                            {{ __('dashboard.edit') }}
                                        </button>
                                        @endcan
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                            <!--end::Action=-->
                        </tr>

                        <!-- Modal for editing an addon -->
                        <div class="modal fade" id="editAddonModal-{{ $orderAddon->pivot->id }}" tabindex="-1" role="dialog" aria-labelledby="editAddonModalLabel-{{ $orderAddon->pivot->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editAddonModalLabel-{{ $orderAddon->pivot->id }}">{{ __('dashboard.edit') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editAddonForm-{{ $orderAddon->pivot->id }}" action="{{ route('bookingsUpdate.addons', $orderAddon->pivot->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="source" value="reservation_addon">
                                            <div class="form-group">
                                                <label for="addon_id">{{ __('dashboard.addon_type') }} <span class="text-danger">*</span></label>
                                                <select name="addon_id" id="edit_addon_id_{{ $orderAddon->pivot->id }}" class="form-control select2 " required>
                                                    <option value="" data-price="0">{{ __('dashboard.choose') }} {{ __('dashboard.addon') }}</option>
                                                    @foreach ($addons as $addon)
                                                    <option value="{{ $addon->id }}" data-price="{{ $addon->price }}" {{ $orderAddon->id == $addon->id ? 'selected' : '' }}>{{ $addon->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="edit_service_price_{{ $orderAddon->pivot->id }}">{{ __('dashboard.addon_price') }}</label>
                                                <input type="number" step="0.01" name="service_price" id="edit_service_price_{{ $orderAddon->pivot->id }}" class="form-control" value="{{ $orderAddon->price }}">
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="edit_count_{{ $orderAddon->id }}">{{ __('dashboard.quantity') }}</label>
                                                <input type="number" name="count" id="edit_count_{{ $orderAddon->pivot->id }}" class="form-control" value="{{ $orderAddon->pivot->count }}">
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="edit_price_{{ $orderAddon->pivot->id }}">{{ __('dashboard.total_price') }}</label>
                                                <input type="number" step="0.01" name="price" id="edit_price_{{ $orderAddon->pivot->id }}" class="form-control" value="{{ $orderAddon->pivot->price }}">
                                            </div>
                                            <div class="mb-5 fv-row col-md-12">
                                                <label class="required form-label">{{ __('dashboard.payment_method') }}</label>
                                                <select name="payment_method" id="edit_payment_method_{{ $orderAddon->pivot->id }}" class="form-select " required>
                                                    <option value="">{{__('dashboard.select_payment_method')}}</option>
                                                    @foreach(paymentMethod() as $paymentSelect)
                                                    <option @selected($orderAddon->pivot->payment_method == $paymentSelect) value="{{$paymentSelect}}">{{__('dashboard.'. $paymentSelect )}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-5 fv-row col-md-12">
                                                <label class="required form-label">{{ __('dashboard.bank_account') }}</label>
                                                <select name="account_id" id="edit_account_id_{{ $orderAddon->pivot->id }}" class="form-select " required>
                                                    <option value="">{{__('dashboard.select_bank_account')}}</option>
                                                    @foreach($bankAccounts as $bankAccount)
                                                    <option @selected($orderAddon->pivot->account_id === $bankAccount->id) value="{{$bankAccount->id}}">{{ $bankAccount->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="edit_description_{{ $orderAddon->pivot->id }}">{{ __('dashboard.notes') }}</label>
                                                <textarea name="description" id="edit_description_{{ $orderAddon->pivot->id }}" class="form-control">{{ $orderAddon->pivot->description }}</textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" form="editAddonForm-{{ $orderAddon->pivot->id }}" class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->

<!-- Modal for adding a new addon -->
<div class="modal fade" id="addAddonModal" tabindex="-1" role="dialog" aria-labelledby="addAddonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddonModalLabel">{{ __('dashboard.create_title', ['page_title' => __('dashboard.addons')]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addAddonForm" action="{{ route('bookingsStore.addons', $order->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="addon_id">{{ __('dashboard.addon_type') }} <span class="text-danger">*</span></label>
                        <select name="addon_id" id="addon_id" class="form-control" required>
                            <option value="" data-price="0">{{ __('dashboard.choose') }} {{ __('dashboard.addon') }}</option>
                            @foreach ($addons as $addon)
                            <option value="{{ $addon->id }}" data-price="{{ $addon->price }}">{{ $addon->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="addon_price">{{ __('dashboard.addon_price') }}</label>
                        <input type="number" step="0.01" name="addon_price" id="addon_price" class="form-control" value="0">
                    </div>
                    <div class="form-group mt-3">
                        <label for="count">{{ __('dashboard.quantity') }}</label>
                        <input type="number" name="count" id="count" class="form-control" value="1">
                    </div>
                    <div class="form-group mt-3">
                        <label for="price">{{ __('dashboard.total_price') }}</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" value="0">
                    </div>

                    <div class="mb-5 fv-row col-md-12">
                        <label class="required form-label">{{ __('dashboard.payment_method') }}</label>
                        <select name="payment_method" id="payment_method" class="form-select " required>
                            <option value="">{{__('dashboard.select_payment_method')}}</option>

                            @foreach(paymentMethod() as $paymentSelect)
                            <option value="{{$paymentSelect}}">{{__('dashboard.'. $paymentSelect )}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5 fv-row col-md-12">
                        <label class="required form-label">{{ __('dashboard.bank_account') }}</label>
                        <select name="account_id" id="account_id" class="form-select " required>
                            <option value="">{{__('dashboard.select_bank_account')}}</option>
                            @foreach($bankAccounts as $bankAccount)
                            <option value="{{$bankAccount->id}}">{{ $bankAccount->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="description">{{ __('dashboard.notes') }}</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="addAddonForm" class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Initialize select2
        $('#addon_id').select2();

        // Update service price field when addon is selected
        $('#addon_id').on('change', function() {
            let price = $(this).find(':selected').data('price');
            $('#addon_price').val(price ?? 0); // Display the service price in the correct field
            updateTotalPrice();

            // Clear custom validity when a valid option is selected
            if (this.value) {
                this.setCustomValidity('');
            }
        });

        // Clear custom validity when payment method or account is changed
        $('#payment_method, #account_id').on('change', function() {
            if (this.value) {
                this.setCustomValidity('');
            }
        });

        // Update total price when count or addon price is changed (do not override manual total typing)
        $('#count, #addon_price').on('input', function() {
            updateTotalPrice();
        });

        function updateTotalPrice() {
            let count = parseFloat($('#count').val());
            let servicePrice = parseFloat($('#addon_price').val()); // Get the service price from the correct field
            count = isNaN(count) ? 0 : count;
            servicePrice = isNaN(servicePrice) ? 0 : servicePrice;
            let totalPrice = count * servicePrice;
            $('#price').val(totalPrice.toFixed(2)); // Keep the total price logic
        }

        // Form validation before submission - now handled globally
        // But keep this for custom addon-specific validation if needed
        $('#addAddonForm').on('submit', function(e) {
            // Validate that user has selected an addon (not the default empty option)
            let selectedAddon = $('#addon_id').val();
            if (!selectedAddon || selectedAddon === '') {
                // Set custom validity message and trigger HTML5 validation
                document.getElementById('addon_id').setCustomValidity('{{ __("dashboard.please_select_addon") }}');
                document.getElementById('addon_id').reportValidity();
                e.preventDefault();
                return false;
            } else {
                // Clear custom validity if valid option is selected
                document.getElementById('addon_id').setCustomValidity('');
            }

            // Validate payment method
            let paymentMethod = $('#payment_method').val();
            if (!paymentMethod || paymentMethod === '') {
                document.getElementById('payment_method').setCustomValidity('{{ __("dashboard.please_select_required_fields") }}');
                document.getElementById('payment_method').reportValidity();
                e.preventDefault();
                return false;
            } else {
                document.getElementById('payment_method').setCustomValidity('');
            }

            // Validate bank account
            let accountId = $('#account_id').val();
            if (!accountId || accountId === '') {
                document.getElementById('account_id').setCustomValidity('{{ __("dashboard.please_select_required_fields") }}');
                document.getElementById('account_id').reportValidity();
                e.preventDefault();
                return false;
            } else {
                document.getElementById('account_id').setCustomValidity('');
            }
            return true;
        });
    });

    $(document).ready(function() {
        // Update service price when addon changes
        $(document).on('change', 'select[id^="edit_addon_id_"]', function() {
            let selectedAddon = $(this).find(':selected');
            let price = parseFloat(selectedAddon.data('price')) || 0;
            let addonId = $(this).attr('id').split('_').pop();
            $('#edit_service_price_' + addonId).val(price); // Display the service price in the new field
            if (typeof updateTotalPriceEdit === 'function') {
                updateTotalPriceEdit(addonId);
            }

            // Clear custom validity when a valid option is selected
            if (this.value) {
                this.setCustomValidity('');
            }
        });

        // Clear custom validity for required selects inside edit forms when changed
        $(document).on('change', 'form[id^="editAddonForm-"] select[required]', function() {
            if (this.value) this.setCustomValidity('');
        });
    });

    // Validation for edit forms - now handled globally
    // But keep this for custom addon-specific validation if needed
    $(document).on('submit', 'form[id^="editAddonForm-"]', function(e) {
        // Validate required selects inside the edit form first so browser shows HTML5 messages
        let form = this;
        let invalid = false;
        $(form).find('select[required]').each(function() {
            if (!this.value) {
                this.setCustomValidity('{{ __("dashboard.please_select_required_fields") }}');
                this.reportValidity();
                invalid = true;
                return false; // break each
            } else {
                this.setCustomValidity('');
            }
        });
        if (invalid) {
            e.preventDefault();
            return false;
        }
        // Validate that user has selected an addon (not the default empty option)
        let formId = $(this).attr('id');
        let addonId = formId.split('-')[1];
        let selectedAddon = $('#edit_addon_id_' + addonId).val();

        if (!selectedAddon || selectedAddon === '') {
            // Set custom validity message and trigger HTML5 validation
            document.getElementById('edit_addon_id_' + addonId).setCustomValidity('{{ __("dashboard.please_select_addon") }}');
            document.getElementById('edit_addon_id_' + addonId).reportValidity();
            e.preventDefault();
            return false;
        } else {
            // Clear custom validity if valid option is selected
            document.getElementById('edit_addon_id_' + addonId).setCustomValidity('');
        }
        return true;
    });

    $(document).on('click', '.receipt-link', function(e) {
        e.preventDefault();

        if ($(this).data('verified') == '1') {
            window.open($(this).data('url'), '_blank');
        } else {
            Swal.fire({
                icon: 'warning',
                title: '{{ __("dashboard.error") }}',
                text: '{{ __("dashboard.addon_not_verified_receipt_error") }}',
                confirmButtonText: '{{ __("dashboard.ok") }}'
            });
        }
    });
</script>
@endpush