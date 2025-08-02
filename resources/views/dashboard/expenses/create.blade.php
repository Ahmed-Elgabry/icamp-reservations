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
                        class="form d-flex flex-column flex-lg-row store" data-kt-redirect="{{route('expenses.index')}}" enctype='multipart/form-data'>
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
                                                        <label for="expense_item_id" class="required">بند المصاريف</label>
                                                        <select name="expense_item_id" id="expense_item_id" class="form-control" required>
                                                            <option value="">اختر بند المصاريف</option>
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


                                                    <div class="form-group col-6 mt-5">
                                                        <label for="price" class="required">المبلغ</label>
                                                        <input type="number" step="any" name="price" id="price" class="form-control" required value="{{ isset($expense) ? $expense->price : old('price') }}">
                                                    </div>
                                                
                                                    <div class="form-group col-6 mt-5">
                                                        <label for="date" class="required">تاريخ المصاريف</label>
                                                        <input type="date" name="date" id="date" class="form-control" value="{{ isset($expense) ? $expense->date : (old('date') ? old('date') : date('Y-m-d')) }}" required>
                                                    </div>

                                                    <div class="form-group col-12 mt-5">
                                                        <label for="notes">ملاحظات</label>
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
            </div>
            
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->


@endsection
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
    </script>
@endpush