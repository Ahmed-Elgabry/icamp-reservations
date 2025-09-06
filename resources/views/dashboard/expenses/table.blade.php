

 <div class="row justify-content-center">
     <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                        <form action="?" method="GET" class="form-inline row">
                            <div class="col-md-4 mb-3">
                                <label for="expense_item_id" class="form-label text-right d-block">
                                     {{ __('dashboard.expense-item') }}
                                </label>
                                <select name="expense_item_id" id="expense_item_id" class="form-select select2-search">
                                    <option value="">{{ __('dashboard.select') }} {{ __('dashboard.expense-items') }}</option>
                                    @if(isset($expenseItems))
                                        @foreach($expenseItems as $expenseItem)
                                            <option value="{{ $expenseItem->id }}" {{ request('expense_item_id') == $expenseItem->id ? 'selected' : '' }}>
                                                {{ $expenseItem->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group mb-2 col-md-4">
                                <label for="start_date" class="text-right d-block">@lang('dashboard.date_from')</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="@lang('dashboard.date_from')" value="{{ request('start_date') }}">
                            </div>
                            <div class="form-group mb-2 col-md-4">
                                <label for="end_date" class="text-right d-block">@lang('dashboard.date_to')</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="إلى تاريخ" value="{{ request('end_date') }}">
                            </div>
                            <button type="submit" class="mt-5 btn btn-primary mb-2" style="width:20%">
                            @lang('dashboard.search') <i class="fa fa-search"></i>
                            </button>
                            @if(count(request()->all()))
                                <a href="?">@lang('dashboard.showall')</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

  <!--begin::Card header-->
  <div class="card-header align-items-center py-5 gap-2 gap-md-5 p-0">



 <!--begin::Card body-->
 <div class="card-body p-0">
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
                            <th class="">@lang('dashboard.date')</th>
                            <th class="">@lang('dashboard.price')</th>
                            <th class="">@lang('dashboard.source')</th>
                            <th class="">@lang('dashboard.payment_method')</th>
                            @isset($pageTitle) 
                               @if($pageTitle == 'add_expenses')
                                    <th class="">@lang('dashboard.verified')</th>
                                    <th class="">@lang('dashboard.attached')</th>
                               @endif
                            @endisset
                            <th class="">@lang('dashboard.notes')</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($expenses as $expense)
                        <!--begin::Table row-->
                        <tr data-id="{{$expense->id}}">
                            <!--begin::Checkbox-->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$expense->id}}"/>
                                </div>
                            </td>
                            <td>{{$expense->date }} </td>
                            <td>{{$expense->price}} </td>
                            @if($expense->source === 'reservation_expenses')
                                <td>{{ __('dashboard.reservations') }}-{{ optional($expense->order)->id  }} </td>
                            @elseif($expense->source === 'general_expenses')
                                <td>{{ __('dashboard.general') }}</td>
                            @elseif($expense->source === 'other_expenses')
                                <td>{{ __('dashboard.other') }}</td>
                            @endif
                                <td>{{$expense->payment_method ? __('dashboard.' . $expense->payment_method) : __('dashboard.not_specified')}} </td>
                            @isset($pageTitle)
                                @if($pageTitle == 'add_expenses')
                                <td>
                                    {{ $expense->verified ? __('dashboard.yes') : __('dashboard.no') }}
                                    @if(($expense->source ?? null) === 'reservation_expenses')
                                        <br>
                                        @if($expense->verified)
                                            <a href="{{ route('order.verified' , [$expense->id , 'expense']) }}" class="btn btn-sm btn-danger">{{ __('dashboard.mark') }} {{ __('dashboard.unverifyed') }}</a>
                                        @else
                                            <a href="{{ route('order.verified' , [$expense->id , 'expense']) }}" class="btn btn-sm btn-success">{{ __('dashboard.mark') }} {{ __('dashboard.verified') }}</a>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($expense->image)
                                    <button type="button" class="btn btn-sm btn-primary" onclick="previewImage('{{ asset('storage/' . $expense->image) }}', '{{ $expense->id }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @else
                                        <span class="text-muted">@lang('dashboard.no_data')</span>
                                        @endif
                                    </td>
                                @endif
                            @endisset
                                    <td>{{$expense->notes }} </td>

                                <!--begin::Action=-->
                                <!--end::Action=-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
                <div class="mt-4">{{$expenses->onEachSide(1)->links('pagination::bootstrap-5')}}</div>
            </div>

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

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Make pagination next/prev icons smaller */
.pagination .page-link svg { width: 14px; height: 14px; }
.pagination .page-link { padding: .35rem .6rem; }

.select2-container .select2-selection--single {
    height: 38px !important;
    padding: 6px 12px;
    border: 1px solid #d1d3e2;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
}

@if(app()->getLocale() == 'ar' || app()->getLocale() == 'ps')
.select2-container--default .select2-selection--single {
    text-align: right;
    direction: rtl;
}

.select2-dropdown {
    direction: rtl;
}

.select2-results__option {
    text-align: right;
}
@endif
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for expense items dropdown
    $('#expense_item_id').select2({
        placeholder: "{{ __('dashboard.select') }} {{ __('dashboard.expense-items') }}",
        allowClear: true,
        width: '100%',
        language: "{{ app()->getLocale() }}",
        dir: "{{ app()->getLocale() == 'ar' || app()->getLocale() == 'ps' ? 'rtl' : 'ltr' }}"
    });

    // Custom styling for RTL languages
    @if(app()->getLocale() == 'ar' || app()->getLocale() == 'ps')
    $('body').on('select2:open', '#expense_item_id', function() {
        $('.select2-dropdown').css('direction', 'rtl');
        $('.select2-search__field').css('text-align', 'right');
    });
    @endif
});

// Image preview function
function previewImage(imageSrc, expenseId) {
    document.getElementById('previewImage').src = imageSrc;
    document.getElementById('downloadImageBtn').href = imageSrc;
    
    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    modal.show();
}
</script>
@endpush
