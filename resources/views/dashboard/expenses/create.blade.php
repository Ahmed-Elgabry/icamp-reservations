@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.create_title', ['page_title' => __('dashboard.expenses')]))

@section('content')


    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">


        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">

            <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
                            <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#product_details"> بيانات المصاريف </a>
                </li>

            <!--end:::Tab item-->
            </ul>
                <hr>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="product_details" role="tab-panel">
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_product_form" action="{{ isset($expense) ?  route('expenses.update',$expense->id) : route('expenses.store') }}" method="POST"
                        class="form d-flex flex-column flex-lg-row store" data-kt-redirect="{{ url()->full() }}" enctype='multipart/form-data'>
                        @csrf

                        @if(isset($expense)) @method('PUT') @endif

                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::Tab content-->
                            <div class="tab-content">
                                <!--begin::Tab pane-->
                                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_ar" role="tab-panel">
                                    <div class="d-flex flex-column gap-7 gap-lg-10">
                                        <!--begin::General options-->
                                        <div class="card card-flush py-4">

                                            <!--begin::Card body-->
                                            <div class="card-body pt-10 row">

                                                <div class="row">

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="account_id" class="required">{{ __('dashboard.bank_account') }}</label>
                                                        <select name="account_id" id="account_id" class="form-control" required>
                                                            <option value="">{{ __('dashboard.choose_bank_account') }}</option>
                                                            @foreach($bankAccounts as $bank)
                                                                <option
                                                                        @if(isset($expense) && $expense->account_id)
                                                                            {{ $expense->account_id == $bank->id ? 'selected' : ''}}
                                                                        @else
                                                                            {{ old('account_id') == $bank->id ? 'selected' : '' }}
                                                                        @endif
                                                                value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                        
                                                    <input type="hidden" name="source" id="source" value="general_expenses">
                                                    <div class="form-group col-6 mt-5">
                                                        <label for="expense_item_id" class="required">{{ __('dashboard.expense_item') }}</label>
                                                        <select name="expense_item_id" id="expense_item_id" class="form-control" required>
                                                            <option selected disabled>{{ __('dashboard.select') }}</option>
                                                            @foreach($expenseItems as $expenseItem)
                                                                <option
                                                                        @if(isset($expense) && $expense->expense_item_id)
                                                                            {{ $expense->expense_item_id == $expenseItem->id ? 'selected' : ''}}
                                                                        @elseif(request()->has('expenseItem') && request()->get('expenseItem') == $expenseItem->id)
                                                                            selected
                                                                        @else
                                                                            {{ old('expense_item_id') == $expenseItem->id ? 'selected' : '' }}
                                                                        @endif
                                                                value="{{ $expenseItem->id }}">{{ $expenseItem->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="payment_method" class="required">{{ __('dashboard.payment_method') }}</label>
                                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                                            <option value="">{{ __('dashboard.choose_payment_method') }}</option>
                                                            @foreach(paymentMethod() as $paymentSelect)
                                                                <option
                                                                        @if(isset($expense) && $expense->payment_method)
                                                                            {{ $expense->payment_method == $paymentSelect ? 'selected' : ''}}
                                                                        @else
                                                                            {{ old('payment_method') == $paymentSelect ? 'selected' : '' }}
                                                                        @endif
                                                                value="{{ $paymentSelect }}">{{__('dashboard.'. $paymentSelect )}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-6 mt-5">
                                                        <label for="date" class="required">{{ __('dashboard.expense_date') }}</label>
                                                        <input type="date" name="date" id="date" class="form-control" value="{{ isset($expense) ? $expense->date : (old('date') ? old('date') : date('Y-m-d')) }}" required>
                                                    </div>

                                    <div class="form-group col-6 mt-5">
                                        <label for="image">@lang('dashboard.upload_or_take_image')</label>
                                        <input type="file" name="image" id="image"
                                            class="form-control"
                                            accept="image/*"
                                            capture="environment">
                                        @if(isset($expense) && ($expense->image_path || $expense->image))
                                            <div class="mt-2">
                                                <p class="text-muted">@lang('dashboard.existing_attachments'):</p>
                                                @php
                                                    $imagePath = $expense->image_path ?? $expense->image;
                                                @endphp
                                                <img src="{{ asset('storage/' . $imagePath) }}" alt="@lang('dashboard.attached')" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                                <br>
                                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="previewImage('{{ asset('storage/' . $imagePath) }}', '{{ $expense->id }}')">
                                                    <i class="fas fa-eye"></i> @lang('dashboard.view')
                                                </button>
                                                <a href="{{ route('expenses.download_image', $expense->id) }}" class="btn btn-sm btn-success mt-2" target="_blank">
                                                    <i class="fas fa-download"></i> @lang('dashboard.download')
                                                </a>
                                            </div>
                                        @endif
                                    </div>                                                    <div class="form-group col-6 mt-5">
                                                        <label for="price" class="required">{{ __('dashboard.amount') }}</label>
                                                        <input type="number" step="any" name="price" id="price" class="form-control" required value="{{ isset($expense) ? $expense->price : old('price') }}">
                                                    </div>

                                                    <div class="form-group col-12 mt-5">
                                                        <label for="notes">{{ __('dashboard.notes') }}</label>
                                                        <textarea name="notes" id="notes" class="form-control">{{ isset($expense) ? $expense->notes : old('notes') }}</textarea>
                                                    </div>
                                                </div>


                                            </div>
                                            <!--end::Card header-->
                                        </div>
                                        <!--end::General options-->
                                    </div>
                                </div>
                                <!--end::Tab pane-->

                            </div>
                            <!--end::Tab content-->
                            <div class="d-flex justify-content-end">
                                <!--begin::Button-->
                                <a href="{{ isset($expense) ? route('expenses.edit',$expense->id) : route('expenses.index') }}" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-light me-5">@lang('dashboard.cancel')</a>
                                <!--end::Button-->

                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                    <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                    <span class="indicator-progress">@lang('dashboard.please_wait')
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->

                            </div>
                        </div>
                        <!--end::Main column-->
                    </form>
                    <!--end::Form-->
                </div>

            </div>

            <!-- Recent Expenses Table -->
            @if(isset($recentExpenses) && count($recentExpenses) > 0)
            <div class="card mt-5">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3 class="fw-bolder">{{ __('dashboard.recent_expenses') }}</h3>
                    </div>
                </div>
                <div class="card-body p-0">
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
                                <th class="">{{ __('dashboard.expense_item') }}</th>
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
                            @foreach ($recentExpenses as $expense)
                                <!--begin::Table row-->
                                <tr data-id="{{$expense->id}}">
                                    <!--begin::Checkbox-->
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$expense->id}}"/>
                                        </div>
                                    </td>
                                    <!--begin::Expense Item-->
                                    <td>{{$expense->expenseItem->name ?? '-'}}</td>
                                    <td>
                                        <div class="d-flex">
                                            <!--end::Thumbnail-->
                                            <div class="ms-5">
                                                <!--begin::Title-->
                                                <a href="#" data-kt-ecommerce-category-filter="search" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">{{$expense->price}}</a>
                                                <!--end::Title-->
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$expense->payment_method ? __('dashboard.' . $expense->payment_method) : __('dashboard.not_specified')}}</td>
                                    <td>{{$expense->account->name ?? '-'}}</td>
                                    <td>
                                        {{ $expense->verified ? __('dashboard.yes') : __('dashboard.no') }} <br>
                                        @if($expense->verified)
                                            <a href="{{ route('order.verified' , [$expense->id , 'expense']) }}" class="btn btn-sm btn-danger">{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                        @else
                                            <a href="{{ route('order.verified' , [$expense->id , 'expense']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($expense->image_path || $expense->image)
                                            @php
                                                $imagePath = $expense->image_path ?? $expense->image;
                                            @endphp
                                            <button type="button" class="btn btn-sm btn-primary" onclick="previewImage('{{ asset('storage/' . $imagePath) }}', '{{ $expense->id }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">{{ __('dashboard.no_data') }}</span>
                                        @endif
                                    </td>
                                    <td data-kt-ecommerce-category-filter="category_name">
                                        {{$expense->notes}}
                                    </td>
                                    <td>
                                        {{$expense->created_at->diffForHumans() }}
                                    </td>
                                    <!--end::Expense Item-->
                                    <!--begin::Action-->
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
                                            @can('expenses.edit')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" onclick="openEditModal({{ $expense->id }}, '{{ $expense->account_id }}', '{{ $expense->expense_item_id }}', '{{ $expense->payment_method }}', '{{ $expense->date }}', '{{ $expense->price }}', '{{ addslashes($expense->notes) }}')">{{ __('actions.edit') }}</a>
                                            </div>
                                            @endcan
                                            @can('expenses.destroy')
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-url="{{route('expenses.destroy', $expense->id)}}" data-id="{{$expense->id}}"> @lang('dashboard.delete')</a>
                                            </div>
                                            @endcan
                                        <!--end::Menu item-->
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
                <!--end::Card body-->
            </div>
            <!--end::Expenses Table-->
            @endif

            </div>

            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">@lang('dashboard.attached')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="@lang('dashboard.attached')" class="img-fluid" style="max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('dashboard.close')</button>
                <a id="downloadImageBtn" href="" download class="btn btn-primary">
                    <i class="fas fa-download"></i> @lang('dashboard.save_changes')
                </a>
            </div>
        </div>
    </div>
</div>


@endsection
<!-- Edit Expense Modal -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editExpenseModalLabel">{{ __('actions.edit') }} {{ __('dashboard.expenses') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExpenseForm" class="custom-modal-form store" method="POST" enctype="multipart/form-data" data-kt-redirect="{{ url()->full() }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.bank_account') }}</label>
                        <div class="col-lg-12">
                            <select name="account_id" id="editAccountId" class="form-select form-select-lg form-select-solid" required>
                                <option value="" disabled>{{ __('dashboard.choose_bank_account') }}</option>
                                @if(isset($bankAccounts))
                                    @foreach($bankAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.expense_item') }}</label>
                        <div class="col-lg-12">
                            <select name="expense_item_id" id="editExpenseItemId" class="form-select form-select-lg form-select-solid" required>
                                <option value="" disabled>{{ __('dashboard.select') }}</option>
                                @if(isset($expenseItems))
                                    @foreach($expenseItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.payment_method') }}</label>
                            <div class="col-lg-12">
                                <select name="payment_method" id="editPaymentMethod" class="form-select form-select-lg form-select-solid" required>
                                    <option value="" disabled>{{ __('dashboard.choose_payment_method') }}</option>
                                    @foreach(paymentMethod() as $paymentSelect)
                                        <option value="{{ $paymentSelect }}">{{ __('dashboard.'. $paymentSelect) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.expense_date') }}</label>
                            <div class="col-lg-12">
                                <input type="date" name="date" id="editDate" class="form-control form-control-lg form-control-solid" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6 required">{{ __('dashboard.amount') }}</label>
                        <div class="col-lg-12">
                            <input type="number" step="any" name="price" id="editPrice" class="form-control form-control-lg form-control-solid" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.upload_or_take_image') }}</label>
                        <div class="col-lg-12">
                            <input type="file" name="image" id="editImage" class="form-control form-control-lg form-control-solid" accept="image/*" capture="environment">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('dashboard.notes') }}</label>
                        <div class="col-lg-12">
                            <textarea name="notes" id="editNotes" class="form-control form-control-lg form-control-solid" placeholder="{{ __('dashboard.notes') }}"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="source" value="general_expenses">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">{{ __('dashboard.save_changes') }}</span>
                        <span class="indicator-progress d-none">{{ __('dashboard.please_wait') }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div>
@push('css')
    <style>
        .nav-line-tabs
        {
            margin-bottom:20px !important;
        }



    </style>
@endpush
@push('js')
    <script>
        $("#select2").select2();

        $(document).ready(function() {
        // when load page check the value
        toggleSubProductFields();

        // when change type render toggle function
        $('#type').change(function() {
            toggleSubProductFields();
        });

        function toggleSubProductFields() {
            if ($('#type').val() === 'main') {
                $('#sub-product-fields').removeClass('d-none');
                $('#select2').prop('required', true);
                $('#sub_count').prop('required', true);
            } else {
                $('#sub-product-fields').addClass('d-none');
                $('#select2').prop('required', false);
                $('#sub_count').prop('required', false);
            }
        }
    });


    $("#expense_item_id").select2();

    // Image preview function
    function previewImage(imageSrc, expenseId) {
        document.getElementById('previewImage').src = imageSrc;
        document.getElementById('downloadImageBtn').href = imageSrc;
        
        // Show the modal
        var modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        modal.show();
    }

    // Open Edit Modal and populate fields
    window.openEditModal = function(expenseId, accountId, expenseItemId, paymentMethod, date, price, notes) {
        const form = document.getElementById('editExpenseForm');
        form.action = "{{ route('expenses.update', ':id') }}".replace(':id', expenseId);

        document.getElementById('editAccountId').value = accountId;
        document.getElementById('editExpenseItemId').value = expenseItemId;
        document.getElementById('editPaymentMethod').value = paymentMethod;
        document.getElementById('editDate').value = date;
        document.getElementById('editPrice').value = price;
        document.getElementById('editNotes').value = notes || '';

        const modal = new bootstrap.Modal(document.getElementById('editExpenseModal'));
        modal.show();
    }
    </script>
@endpush
