@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.create_title', ['page_title' => __('dashboard.expense-items')]))

@section('content')


    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
       
    
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">

            <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
                            <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#product_details"> بيانات البند </a>
                </li>
              
            <!--end:::Tab item-->
            </ul>
                <hr>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="product_details" role="tab-panel">
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_product_form" action="{{ isset($expenseItem) ?  route('expense-items.update',$expenseItem->id) : route('expense-items.store') }}" method="POST"
                        class="form d-flex flex-column flex-lg-row store" data-kt-redirect="{{ isset($expenseItem) ?  route('expense-items.edit',$expenseItem->id) : route('expense-items.create') }}" enctype='multipart/form-data'>
                        @csrf

                        @if(isset($expenseItem)) @method('PUT') @endif
                      
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
                                                <!--begin::Input group-->
                                                <div class="mb-5 fv-row col-md-12">
                                                    <label class="required form-label">أسم البند</label>
                                                    <input type="text" name="name" value="{{ isset($expenseItem) ? $expenseItem->name :  old('name') }}"
                                                        class="form-control mb-2" required  />
                                                </div>
                                                <!--end::Input group-->
                                              
                                            
                                                <!--begin::Input group-->
                                                <div class="mb-5 fv-row col-md-12">
                                                    <label class=" form-label">ملاحظات</label>
                                                    <input type="text" name="description" value="{{ isset($expenseItem) ? $expenseItem->notes :  old('notes') }}"
                                                        class="form-control mb-2" placeholder="" />
                                                </div>
                                                <!--end::Input group-->
                                        
                                                
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
                                <a href="{{ isset($expenseItem) ? route('expense-items.edit',$expenseItem->id) : route('expense-items.create') }}" id="kt_ecommerce_add_product_cancel"
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

    </script>
@endpush