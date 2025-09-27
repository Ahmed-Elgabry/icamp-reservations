@section('pageTitle' , __('dashboard.payments'))
@extends('dashboard.layouts.app')
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
                        <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="@lang('dashboard.search_title', ['page_title' => __('dashboard.payments')])" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->


                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Add customer-->
                    @can('payments.create')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewCount">
                            @lang('dashboard.create_title', ['page_title' => __('dashboard.payments')])
                    </button>
                    @endcan
                    <!--end::Add customer-->
                    <span class="w-5px h-2px"></span>

                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">

 <!--begin::Table-->
 <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
            <!--begin::Table head-->
            <thead>
                <!--begin::Table row-->
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2">
                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3" >
                            <input class="form-check-input" id="checkedAll"  type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                        </div>
                    </th>
                    <th class="">{{ __('dashboard.statement') }}</th>
                    <th>{{ __('dashboard.price') }}</th>
                    <th class="">{{ __('dashboard.payment_method') }}</th>
                    <th class="">{{ __('dashboard.bank_account') }}</th>
                    @if($order->insurance_status !== 'returned'  || $order->insurance_approved == "1")
                        <th class="">{{ __('dashboard.verified') }}</th>
                    @endif
                    <th class="">{{ __('dashboard.handled_by') }}</th>
                    <th class="">{{ __('dashboard.notes') }}</th>
                    <th class="">{{ __('dashboard.created_at') }}</th>
                    <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                </tr>
                <!--end::Table row-->
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody class="fw-bold text-gray-600">
                @foreach ($payments as $payment)
                    <!--begin::Table row-->
                    <tr data-id="{{$payment->id}}">
                        <!--begin::Checkbox-->
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$payment->id}}"/>
                            </div>
                        </td>
                        <!--begin::Category=-->
                        <td>{{__('dashboard.'. $payment->statement )}}</td>
                        <td>
                            <div class="d-flex">
                                <!--end::Thumbnail-->
                                <div class="ms-5">
                                    <!--begin::Title-->
                                    @if($payment->insurance_status == 'confiscated_partial')
                                        <a href="#"
                                        data-kt-ecommerce-category-filter="search"
                                        class="text-gray-800  fs-7 fw-bolder mb-1"  >{{__("dashboard.remaining :priceAferConfiscation", ['priceAferConfiscation' => $payment->price - $payment->transaction->amount])}}
                                    {{__("dashboard.from")}} {{$payment->price}}
                                    </a>
                                    @else
                                         <a href="#"
                                        data-kt-ecommerce-category-filter="search"
                                        class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1"
                                         >{{$payment->price}}</a>
                                        <!--end::Title-->
                                    @endif
                                    </div>
                                </div>
                        </td>

                        <td>{{__('dashboard.'. $payment->payment_method )}}</td>
                        @foreach($bankAccounts as $bankAccount)
                            @if($payment->account_id == $bankAccount->id)
                            <td>{{__($bankAccount->name )}}</td>

                            @endif
                        @endforeach
                        @if($order->insurance_status !== 'returned'  || $order->insurance_approved == "1")
                            <td>
                                {{ $payment->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                @can('payments.approve')
                                @if($payment->verified)
                                        <a href="{{ route('order.verified' , [$payment->id , 'payment']) }}" class="btn btn-sm btn-danger" >{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                    @else
                                        <a href="{{ route('order.verified' , [$payment->id , 'payment']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                    @endif
                                    @endcan
                            </td>
                        @endif
                        <td>{{ $payment->handled_by ?? '-' }}</td>
                        <td  data-kt-ecommerce-category-filter="category_name" >
                            {{$payment->notes}}
                                @if($payment->statement == 'the_insurance' && $payment->verified == "1")
                                    @if($order->insurance_status === 'returned')
                                        <br><span class="badge badge-success">{{ __('dashboard.insurance_returned_note') }}</span>
                                    @elseif($order->insurance_status === 'confiscated_full' )
                                        <br><span class="badge badge-dark">{{ __('dashboard.insurance_confiscated_full') }}</span>
                                    @elseif($payment->insurance_status == 'confiscated_partial')
                                        <br><span class="badge badge-warning">{{ __('dashboard.insurance_confiscated_partial') }}</span>
                                    @elseif($order->insurance_status === null && $order->payments()->where('statement', 'the_insurance')->sum("price") < 1)
                                        <br><span class="badge badge-danger">{{ __('dashboard.insurance_null') }}</span>
                                    @elseif($order->insurance_status === null && $order->payments()->where('statement', 'the_insurance')->sum("price") > 1)
                                        <br><span class="badge badge-info">{{ __('dashboard.insurance_not_returned') }}</span>
                                    @endif
                                @endif
                                {{__("dashboard.by")}} {{" " . $payment->insurance_handled_by}}
                        </td>
                        <td>
                           {{$payment->created_at->diffForHumans() }}
                        </td>
                        <!--end::Category=-->
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
                                        @can('payments.print')
                                <div class="menu-item px-3">
                                    <a href="#"
                                       class="menu-link px-3 payment-receipt-link"
                                       data-verified="{{ $payment->verified ? '1' : '0' }}"
                                       data-url="{{ route('payments.receipt', ['order' => $order->id, 'payment' => $payment->id]) }}">
                                        {{ __('dashboard.receipt') }}
                                    </a>
                                </div>
                                @endcan
                                @can('payments.edit')
                                <div class="menu-item px-3">
                                    <a href="#" type="button" data-toggle="modal" data-target="#editCount-{{$payment->id}}" class="menu-link px-3">{{ __('actions.edit') }}</a>
                                </div>
                                @endcan
                                @can('payments.destroy')
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-url="{{route('payments.destroy', $payment->id)}}" data-id="{{$payment->id}}"> @lang('dashboard.delete')</a>
                                </div>
                                @endcan
                            <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </td>
                        <!--end::Action=-->
                    </tr>
                    <!--end::Table row-->

                    <!-- Modal -->
                    <div class="modal fade" id="editCount-{{$payment->id}}" tabindex="-1" role="dialog" aria-labelledby="editCount-{{$payment->id}}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addNewCoutLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('payments.update',$payment->id) }}" id="editCountForm{{$payment->id}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-5 fv-row col-md-12">
                                    <label class="required form-label">{{ __('dashboard.statement') }}</label>
                                    <select name="statement" id="" class="form-select" required>
                                        <option value="">{{ __('dashboard.select') }}</option>
                                        @foreach(statements() as $statement)
                                            <option {{$payment->statement == $statement ? 'selected' : ''}} value="{{$statement}}">{{__('dashboard.'. $statement )}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!--begin::Card body-->
                                 <input type="hidden" value="reservation_payments" name="source">

                                <div class="mb-5 fv-row col-md-12">
                                    <label class="required form-label">{{ __('dashboard.price') }}</label>
                                    <input type="number" name="price" id="price" value="{{   $payment->price }}"
                                        class="form-control mb-2" required
                                        value="" />
                                </div>
                                <div class="mb-5 fv-row col-md-12">
                                    <label class="required form-label">{{ __('dashboard.payment_method') }}</label>
                                    <select name="payment_method" id="edit_payment_method_{{$payment->id}}" class="form-select" required>
                                        <option value="">{{ __('dashboard.select_payment_method') }}</option>
                                        @foreach(paymentMethod() as $paymentSelect)
                                            <option {{$payment->payment_method == $paymentSelect ? 'selected' : ''}} value="{{$paymentSelect}}">{{__('dashboard.'. $paymentSelect )}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                                 <div class="mb-5 fv-row col-md-12">
                                     <label class="required form-label">{{ __('dashboard.bank_account') }}</label>
                                    <select name="account_id" id="edit_account_id_{{$payment->id}}" class="form-select" required>
                                        <option value="">{{ __('dashboard.choose_account') }}</option>
                                        @foreach($bankAccounts as $bankAccount)
                                            <option {{$payment->account_id == $bankAccount->id ? 'selected' : ''}} value="{{$bankAccount->id}}">{{ $bankAccount->name }}</option>
                                        @endforeach
                                    </select>
                                 </div>

                                <!--begin::Input group-->
                                <div class="mb-5 fv-row col-md-12">
                                    <label class="form-label">{{ __('dashboard.notes') }}</label>
                                    <textarea name="notes" id="" class="form-control mb-2">{{  $payment->notes }}</textarea>
                                </div>
                                <!--end::Input group-->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" form="editCountForm{{$payment->id}}" class="btn btn-primary">@lang('dashboard.save_changes')</button>
                        </div>
                        </div>
                    </div>
                    </div>


                @endforeach
            </tbody>
            <!--end::Table body-->
        </table>

        <!-- Modal -->
<div class="modal fade" id="addNewCount" tabindex="-1" role="dialog" aria-labelledby="addNewCountLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewCountLabel"> @lang('dashboard.create_title', ['page_title' => __('dashboard.payments')])</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('payments.store') }}" id="saveCountDetails" method="POST" enctype="multipart/form-data">
            @csrf
            <!--begin::Input group-->
            <input type="hidden" value="{{ $order->id }}" name="order_id">
            <input type="hidden" value="reservation_payments" name="source">
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label">{{ __('dashboard.statement') }}</label>
                <select name="statement" id="" class="form-select" required>

                    <option value="">{{ __('dashboard.select') }}</option>

                    @foreach(statements() as $statement)
                        <option value="{{$statement}}">{{__('dashboard.'. $statement )}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label">{{ __('dashboard.price') }}</label>
                <input type="number" name="price" id="price" value="{{   old('price') }}"
                    class="form-control mb-2" required
                    value="" />
            </div>
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label">{{ __('dashboard.payment_method') }}</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="">{{ __('dashboard.select_payment_method') }}</option>
                    @foreach(paymentMethod() as $paymentSelect)
                        <option value="{{$paymentSelect}}">{{__('dashboard.'. $paymentSelect )}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5 fv-row col-md-12">
                <label class="required form-label">{{ __('dashboard.bank_account') }}</label>
                <select name="account_id" id="account_id" class="form-select" required>
                    <option value="">{{ __('dashboard.choose_account') }}</option>
                    @foreach($bankAccounts as $bankAccount)
                        <option {{old('account_id') == $bankAccount->id ? 'selected' : ''}} value="{{$bankAccount->id}}">{{ $bankAccount->name }}</option>
                    @endforeach
                </select>
            </div>

            <!--begin::Input group-->
            <div class="mb-5 fv-row col-md-12">
                <label class=" form-label">{{ __('dashboard.notes') }}</label>
                <textarea name="notes" id="" class="form-control mb-2">{{  old('notes') }}</textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="saveCountDetails" class="btn btn-primary">@lang('dashboard.save_changes')</button>
      </div>
    </div>
  </div>
</div>


</div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->

@endsection
@push('js')
    <script>
        $(document).on('click', '.payment-receipt-link', function(e) {
            e.preventDefault();

            if ($(this).data('verified') == '1') {
                window.open($(this).data('url'), '_blank');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("dashboard.error") }}',
                    text: '{{ __("dashboard.payment_not_verified_receipt_error") }}',
                    confirmButtonText: '{{ __("dashboard.ok") }}'
                });
            }
        });

        // Add payment form validation
        $('#saveCountDetails').on('submit', function(e) {
            let statementSelect = $(this).find('select[name="statement"]')[0];
            if (!statementSelect.value || statementSelect.value === '') {
                statementSelect.setCustomValidity('{{ __("dashboard.required") }}');
                statementSelect.reportValidity();
                e.preventDefault();
                return false;
            } else {
                statementSelect.setCustomValidity('');
            }
+
+            // Validate payment method
+            let paymentMethodSelect = $(this).find('select[name="payment_method"]')[0];
+            if (!paymentMethodSelect.value || paymentMethodSelect.value === '') {
+                paymentMethodSelect.setCustomValidity('{{ __("dashboard.required") }}');
+                paymentMethodSelect.reportValidity();
+                e.preventDefault();
+                return false;
+            } else {
+                paymentMethodSelect.setCustomValidity('');
+            }
+
+            // Validate bank account
+            let accountSelect = $(this).find('select[name="account_id"]')[0];
+            if (!accountSelect.value || accountSelect.value === '') {
+                accountSelect.setCustomValidity('{{ __("dashboard.required") }}');
+                accountSelect.reportValidity();
+                e.preventDefault();
+                return false;
+            } else {
+                accountSelect.setCustomValidity('');
+            }
         });

         // Edit payment form validation
         $('form[id^="editCountForm"]').on('submit', function(e) {
             let statementSelect = $(this).find('select[name="statement"]')[0];
             if (!statementSelect.value || statementSelect.value === '') {
                 statementSelect.setCustomValidity('{{ __("dashboard.required") }}');
                 statementSelect.reportValidity();
                 e.preventDefault();
                 return false;
             } else {
                 statementSelect.setCustomValidity('');
             }
+
+            // Validate payment method inside edit form
+            let paymentMethodSelect = $(this).find('select[name="payment_method"]')[0];
+            if (!paymentMethodSelect.value || paymentMethodSelect.value === '') {
+                paymentMethodSelect.setCustomValidity('{{ __("dashboard.required") }}');
+                paymentMethodSelect.reportValidity();
+                e.preventDefault();
+                return false;
+            } else {
+                paymentMethodSelect.setCustomValidity('');
+            }
+
+            // Validate account inside edit form
+            let accountSelect = $(this).find('select[name="account_id"]')[0];
+            if (!accountSelect.value || accountSelect.value === '') {
+                accountSelect.setCustomValidity('{{ __("dashboard.required") }}');
+                accountSelect.reportValidity();
+                e.preventDefault();
+                return false;
+            } else {
+                accountSelect.setCustomValidity('');
+            }
         });

         // Clear validation when user selects a valid option
-        $(document).on('change', 'select[name="statement"]', function() {
-            if (this.value && this.value !== '') {
-                this.setCustomValidity('');
-            }
-        });
+        $(document).on('change', 'select[name="statement"], select[name="payment_method"], select[name="account_id"]', function() {
+            if (this.value && this.value !== '') {
+                this.setCustomValidity('');
+            }
+        });

    </script>
@endpush
