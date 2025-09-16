@extends('dashboard.layouts.app')

@if (isset($issuedStocks))
    @section('pageTitle', __('dashboard.manual_item_withdrawal_show'))
@elseif (isset($returnedStocks))
    @section('pageTitle', __('dashboard.manual_item_return_show'))
@else
    @section('pageTitle', __('dashboard.manual_item_withdrawal_and_return.heading'))
@endif

@section('content')
<div class="container">
    <!-- Table Scroll Arrows -->
    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-light mr-2 scroll-left-btn" title="Scroll Left"><i class="fa fa-arrow-left"></i></button>
        <button type="button" class="btn btn-light scroll-right-btn" title="Scroll Right"><i class="fa fa-arrow-right"></i></button>
    </div>
@push('css')
    <style>
        .select2-container--default .select2-selection--single {
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            border-radius: .25rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered { color: inherit; }
        .select2-available-badge { font-size: .75rem; color: #6c757d; }
          /* ensure form controls can shrink to content width when desired
              avoid forcing display as that can affect table cell layout */
          .w-auto { width: auto !important; max-width: 320px !important; }
          /* helper to constrain select look when using Select2 */
          .select-item { max-width: 420px; }
          .select2-container { width: auto !important; }
          .select2-container--default .select2-selection--single { min-width: 200px; }
        /* helper to set a minimum width on table headers */
        .min-w-200 { min-width: 200px !important; }
        .min-w-400 { min-width: 400px !important; }

        /* label helper for modal form alignment */
        .label-min-w { min-width: 200px; display: inline-block; margin-right: .5rem; }
    /* Modal form sizing & spacing */
    .modal .form-group { margin-bottom: .75rem; }
    .modal .form-control.w-auto { width: 320px !important; max-width: 100% !important; }
    .modal .label-min-w { min-width: 160px; }
    .modal .custom-reason-input, .modal .orders-select { margin-left: .5rem; }

    /* Close icon styling + transition */
    .modal-header .close { font-size: 1.4rem; color: #6c757d; opacity: 1; background: transparent; border: none; padding: .25rem .5rem; transition: transform .15s ease, color .15s ease; }
    .modal-header .close:hover { color: #000; transform: rotate(20deg); }

    /* Select2 selected content transition and badge spacing */
    .select2-container--default .select2-selection--single .select2-selection__rendered { transition: background-color .18s ease, color .18s ease, transform .12s ease; }
    .select2-available-badge { margin-left: .35rem; opacity: .9; }
          /* Input/select sizing helpers: use these classes to pick a sensible width
              - .input-sm  : compact controls (percent, small numbers)
              - .input-md  : regular form controls inside tables
              - .input-lg  : wider controls for long content
          */
          .form-control.w-auto { display: inline-block; width: auto !important; min-width: 140px; max-width: 420px; box-sizing: border-box; }
          .select2-container--default .select2-selection--single { min-width: 140px; max-width: 420px; box-sizing: border-box; }
          .select-item.min-select-item { min-width: 200px; }
          .input-sm { min-width: 90px; max-width: 150px; }
          .input-md { min-width: 140px; max-width: 320px; }
          .input-lg { min-width: 220px; max-width: 540px; }
        /* Make tables scroll only within their container instead of scrolling the whole page */
        .scrollable-table-wrapper {
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            margin: .5rem 0 1rem 0;
            padding: .5rem;
        }
        /* Keep table layout intact while allowing horizontal overflow when needed */
        .scrollable-table-wrapper .table {
            margin-bottom: 0;
        }
    </style>
@endpush
    <!-- Stock Search Input -->
    <div class="mb-3">
        <input type="text" id="stock-search-input" class="form-control  px-4" placeholder="{{ __('dashboard.search_stock') }}">
    </div>
    @if(isset($issuedStocks))
    <h2>{{ __('dashboard.manual_item_withdrawal_history_heading') }}</h2>
    <div class="scrollable-table-wrapper">
    <table class="table table-responsive" id="stock-table">
            <thead>
                <tr>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.item') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.available_quantity') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.quantity_to_discount') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.reason') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.note') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.image') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.by') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.date_time') }}</th>
                    <th class="min-w-200">{{ __('dashboard.actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @if($issuedStocks->total() > 0)
                @foreach($issuedStocks as $adj)
                    <tr data-id="{{ $adj->id }}">
                        <td><a href="{{ route('dashboard.stock.report', $adj->stock->id) }}">{{ $adj->stock->name ?? '-' }}</a></td>
                        <td>{{ isset($adj->stock->percentage) ? $adj->stock->percentage . ' %' : ($adj->stock->quantity ?? '-') }}</td>
                        <td>{{ isset($adj->stock->percentage) ? abs($adj->percentage - $adj->available_percentage_before) . ' %' : abs($adj->available_quantity_after - $adj->available_quantity_before) }}</td>
                        @if($adj->order_id)
                            <td>{{ __("dashboard.manual_item_withdrawal_and_return.reason_options.".$adj->reason) }} {{ " - ".$adj->order_id}}</td>
                        @elseif(isset($adj->custom_reason) && $adj->custom_reason)
                            <td>{{ __("dashboard.manual_item_withwithdrawal_and_return.reason_options.".$adj->reason) }}  {{ " - ".$adj->custom_reason }}</td>
                        @else
                            <td>{{ __("dashboard.manual_item_withdrawal_and_return.reason_options.".$adj->reason) }}</td>
                        @endif
                                                <td>{{ $adj->note }}</td>
                                                <td>
                                                        @if($adj->image)
                                                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#imageModal-{{ $adj->id }}">
                                                                        <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('stock.adjustments.download', $adj->id) }}" class="btn btn-sm btn-success" target="_blank">
                                                                        <i class="fa fa-download"></i>
                                                                </a>
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="imageModal-{{ $adj->id }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel-{{ $adj->id }}" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="imageModalLabel-{{ $adj->id }}">{{ __('dashboard.manual_item_withdrawal_and_return.image') }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <img src="{{ asset('storage/' . $adj->image) }}" alt="Image" class="img-fluid" style="max-width:100%;height:auto;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        @endif
                                                </td>
                        <td>{{ $adj->employee_name }}</td>
                        <td class="min-w-200">{{ $adj->date_time }}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary btn-edit-adjustment" data-id="{{ $adj->id }}">{{ __('dashboard.edit') }}</button>
                            <button class="btn btn-sm btn-danger btn-delete-adjustment" data-id="{{ $adj->id }}">{{ __('dashboard.delete') }}</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">{{ __('dashboard.no_data_found') }}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            @if(method_exists($issuedStocks, 'links'))
                @include('dashboard.pagination.pagination', ['transactions' => $issuedStocks])
            @endif
        </div>
    @elseif(isset($returnedStocks))
    <h3 class="my-2">{{ __('dashboard.manual_item_return_history_heading') }}</h3>
        <div class="scrollable-table-wrapper">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.item') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.available_quantity') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.quantity_returned') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.type') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.reason') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.note') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.image') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.by') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.date_time') }}</th>
                    <th class="min-w-200">{{ __('dashboard.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if( $returnedStocks->total() > 0)
                    @foreach($returnedStocks as $adj)
                        <tr data-id="{{ $adj->id }}">
                            <td><a href="{{ route('dashboard.stock.report', $adj->stock->id) }}">{{ $adj->stock->name ?? '-' }}</a></td>
                            <td>{{$adj->stock->percentage ? $adj->stock->percentage . ' %' : $adj->stock->quantity  }}</td>
                            <td>{{ isset($adj->stock->percentage) ? abs($adj->percentage - $adj->available_percentage_before) . ' %' : abs($adj->available_quantity_after - $adj->available_quantity_before) }}</td>
                            <td>{{ __("dashboard.manual_item_withdrawal_and_return.".$adj->type) }}</td>
                            @if($adj->order_id)
                                <td>{{ __("dashboard.manual_item_withdrawal_and_return.reason_options.".$adj->reason) }} {{ " - ".$adj->order_id}}</td>
                            @elseif(isset($adj->custom_reason) && $adj->custom_reason)
                                <td>{{ __("dashboard.manual_item_withdrawal_and_return.reason_options.".$adj->reason) }}  {{ " - ".$adj->custom_reason}}</td>
                            @else
                                <td>{{ __("dashboard.manual_item_withdrawal_and_return.reason_options.".$adj->reason) }}</td>
                            @endif
                                                    <td>{{ $adj->note }}</td>
                                                    <td>
                                                            @if($adj->image)
                                                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#imageModal-{{ $adj->id }}">
                                                                            <i class="fa fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('stock.adjustments.download', $adj->id) }}" class="btn btn-sm btn-success" target="_blank">
                                                                            <i class="fa fa-download"></i>
                                                                    </a>
                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="imageModal-{{ $adj->id }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel-{{ $adj->id }}" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="imageModalLabel-{{ $adj->id }}">{{ __('dashboard.manual_item_withdrawal_and_return.image') }}</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center">
                                                                                    <img src="{{ asset('storage/' . $adj->image) }}" alt="Image" class="img-fluid" style="max-width:100%;height:auto;">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            @endif
                                                    </td>
                            <td>{{ $adj->employee_name }}</td>
                            <td>{{ $adj->date_time }}</td>
                            <td>
                                <button class="btn btn-sm btn-secondary btn-edit-adjustment" style="padding: 5px 11px !important;border-radius: 4px !important;" data-id="{{ $adj->id }}">{{ __('dashboard.edit') }}</button>
                                <button class="btn btn-sm btn-danger btn-delete-adjustment" data-id="{{ $adj->id }}">{{ __('dashboard.delete') }}</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10" class="text-center">{{ __('dashboard.no_data_found') }}</td>
                    </tr>
                @endif
            </tbody>
    </table>
    </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            @if(method_exists($returnedStocks, 'links'))
                @include('dashboard.pagination.pagination', ['transactions' => $returnedStocks])
            @endif
        </div>
    @else
    <form method="POST" action="{{ route('stock.adjustments.store') }}" enctype="multipart/form-data" class="update" data-success-message="{{ __('dashboard.stock_updated_successfully') }}" data-kt-redirect="{{ url()->current() }}">
        @csrf
        <input type="hidden" name="type" value="item_decrement">
        <div class="scrollable-table-wrapper">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.item') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.available_quantity') }}</th>
                    <th class="min-w-400">{{ __('dashboard.manual_item_withdrawal_and_return.quantity_to_discount_or_to_add') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.type') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.reason') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.note') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.image') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.date_time') }}</th>
                    <th class="min-w-200">{{ __('dashboard.manual_item_withdrawal_and_return.by') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="stock_id" class="form-control select-item min-select-item" required>
                            <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_item') }}</option>
                            @if(isset($stocks) && $stocks->count())
                                @foreach($stocks as $stock)
                                            <option value="{{ $stock->id }}" data-available="{{ $stock->percentage ? ($stock->percentage ) . ' %' : ($stock->quantity ?? '-') }}">{{ $stock->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                            <input type="text" name="available_quantity" class="form-control" value="" readonly>
                    </td>
                    <td class = "d-flex flex-row gap-4">
                            <input type="number" name="quantity_to_discount" class="form-control " placeholder="{{ __('dashboard.manual_item_withdrawal_and_return.quantity_to_discount_or_to_add') }}">
                            <input type="number" name="percentage" id="main-percentage" class="form-control input-sm" placeholder="{{__('dashboard.percentage_placeholder')}}" >
                    </td>
                    <input type="hidden" name="source" value="stockIssueAndReturn">

                    <td>
                        <select name="type" class="form-control " required>
                            <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_type') }}</option>
                            <option value="item_decrement">{{ __('dashboard.manual_item_withdrawal_and_return.type_decrement') }}</option>
                            <option value="item_increment">{{ __('dashboard.manual_item_withdrawal_and_return.type_increment') }}</option>
                        </select>
                    </td>
                    <td>
                        <select name="reason" class="form-control reason-select " required>
                            <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_reason') }}</option>
                            
                        <input type="text" name="custom_reason" class="form-control mt-2 custom-reason-input  d-none" placeholder="{{ __('dashboard.manual_item_withdrawal_and_return.specify_reason') }}"> 
                        <select name="order_id" class="form-control mt-2 orders-select  d-none">
                            <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_order') }}</option>
                                @if(isset($orders) && $orders->count())
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}">{{ $order->id }} - {{ $order->customer->name  }}</option>
                                    @endforeach
                                @endif
                        </select>
                    </td>
                    <td>
                        <textarea name="note" class="form-control"></textarea>
                    </td>
                    <td>
                        <input type="file" name="image" class="form-control" accept="image/*" capture="environment">
                    </td>
                    <td>
                        <input type="datetime-local" name="date_time" class="form-control" required>
                    </td>
                    <td>
                        <input type="text" name="employee_name" class="form-control " value="{{ auth()->user()->name }}" readonly>
                    </td>
                </tr>
            </tbody>
    </table>
    </div>
        <button type="submit" class="btn btn-primary" id="kt_ecommerce_add_product_submit">{{ __('dashboard.manual_item_withdrawal_and_return.submit') }}</button>
    </form>
    @endif

    <!-- Edit Adjustment Modal -->
    <div class="modal fade" id="editAdjustmentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('dashboard.manual_item_withdrawal_and_return.edit_adjustment',['default' => 'Edit Adjustment']) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <form id="edit-adjustment-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="adjustment_id" id="edit-adjustment-id">
                        <div class="form-group d-flex  flex-column">
                            <label class="label-min-w">{{ __('dashboard.manual_item_withdrawal_and_return.available_quantity') }}</label>
                            <input type="text" name="available_quantity" id="edit-available-quantity" class="form-control " readonly>
                        </div>
                        <div class="form-group d-flex flex-row gap-4 flex-column">
                            <label class="label">{{ __('dashboard.manual_item_withdrawal_and_return.quantity_to_discount_or_to_add') }}</label>
                           <div class="flex-column d-flex gap-4 w-80 ">
                               <input type="number" name="quantity_to_discount" id="edit-quantity" class="form-control" >
                               <input type="number" name="percentage" id="edit-percentage" placeholder="{{ __('dashboard.percentage_placeholder') }}" class="form-control ">
                            </div>
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label class="label-min-w" for="edit-type-select">{{ __('dashboard.manual_item_withdrawal_and_return.type') }}</label>
                            <select name="type" id="edit-type-select" class="form-control ml-2" required>
                                <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_type') }}</option>
                                <option value="item_decrement">{{ __('dashboard.manual_item_withdrawal_and_return.type_decrement') }}</option>
                                <option value="item_increment">{{ __('dashboard.manual_item_withdrawal_and_return.type_increment') }}</option>
                            </select>
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label class="label-min-w">{{ __('dashboard.manual_item_withdrawal_and_return.reason') }}</label>
                            <select name="reason" id="edit-reason" class="form-control reason-select ">
                               <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_reason') }}</option>
                            </select>
                            <input type="text" name="custom_reason" id="edit-custom-reason" class="form-control ml-0 custom-reason-input  d-none" placeholder="{{ __('dashboard.manual_item_withdrawal_and_return.specify_reason') }}">
                            <select name="order_id" id="edit-order-id" class="form-control mt-2 orders-select  d-none">
                                <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_order') }}</option>
                                    @foreach(App\Models\Order::all() as $order)
                                        <option value="{{ $order->id }}">{{ $order->id }} - {{ $order->customer?->name  }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label class="label-min-w">{{ __('dashboard.manual_item_withdrawal_and_return.note') }}</label>
                            <textarea name="note" id="edit-note" class="form-control"></textarea>
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label class="label-min-w">{{ __('dashboard.manual_item_withdrawal_and_return.image') }}</label>
                            <input type="file" name="image" id="edit-image" class="form-control ">
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label class="label-min-w">{{ __('dashboard.manual_item_withdrawal_and_return.employee_name') }}</label>
                            <input type="text" name="employee_name" id="edit-employee-name" class="form-control "  value="{{ auth()->user()->name }}" placeholder="{{ __('dashboard.manual_item_withdrawal_and_return.employee_name') }}" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('dashboard.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('js')
<script>
// Stock table search filter
$(document).on('input', '#stock-search-input', function() {
    var query = $(this).val().toLowerCase();
    $('#stock-table tbody tr').each(function() {
        var stockName = $(this).find('td').eq(0).text().toLowerCase();
        var stockId = $(this).data('id') ? String($(this).data('id')).toLowerCase() : '';
        if (stockName.includes(query) || stockId.includes(query)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Table horizontal scroll buttons
$(document).on('click', '.scroll-left-btn', function() {
    var $wrapper = $(this).closest('.container').find('.scrollable-table-wrapper:visible').first();
    $wrapper.animate({ scrollLeft: 0 }, 400);
});
$(document).on('click', '.scroll-right-btn', function() {
    var $wrapper = $(this).closest('.container').find('.scrollable-table-wrapper:visible').first();
    var maxScroll = $wrapper[0].scrollWidth - $wrapper[0].clientWidth;
    $wrapper.animate({ scrollLeft: maxScroll }, 400);
});

// Add SweetAlert2 CDN if not already present
if (!window.Swal) {
    var swalScript = document.createElement('script');
    swalScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    document.head.appendChild(swalScript);
}

    // Load Select2 script and initialize the select-item
    (function initSelect2(){
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        s.onload = function(){
            function formatStock (state) {
                if (!state.id) { return state.text; }
                var available = $(state.element).data('available');
                var $state = $("<span>"+state.text+" <span class='select2-available-badge'>("+available+")</span></span>");
                return $state;
            };
            $('.select-item').each(function(){
                var opts = {
                    templateResult: formatStock,
                    templateSelection: formatStock,
                    width: 'resolve'
                };
                // if the select lives inside a modal, attach dropdown to that modal
                var $modal = $(this).closest('.modal');
                if ($modal.length) { opts.dropdownParent = $modal; }
                $(this).select2(opts);
            });
            // ensure Select2 computes proper widths after rendering
            setTimeout(function(){ $('.select-item').trigger('resize.select2'); }, 100);
        };
        document.head.appendChild(s);
    })();

    // When the stock/item select changes, populate the available quantity input
    // listen for the real select name `stock_id` and for Select2 events on `.select-item`
    $(document).on('change', 'select[name="stock_id"]', function () {
    var available = $(this).find('option:selected').data('available') || '';
    $(this).closest('form').find('input[name="available_quantity"]').val(available);
    });

    // Select2 fires its own events — sync those as well
    $(document).on('select2:select', '.select-item', function () {
    var available = $(this).find('option:selected').data('available') || '';
    $(this).closest('form').find('input[name="available_quantity"]').val(available);
    });
        // Define reason options for each type
        const reasonOptions = {
            item_increment: `
                <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_reason') }}</option>
                <option value="correct_quantity">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.correct_quantity') }}</option>
                <option value="for_employee">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.for_employee') }}</option>
                <option value="employee_mistake">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.employee_mistake') }}</option>
                <option value="non-consumption">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.non-consumption') }}</option>
                <option value="for_orders">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.for_orders') }}</option>
                <option value="else">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.else') }}</option>
            `,
            item_decrement: `
                <option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_reason') }}</option>
                <option value="correct_quantity">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.correct_quantity') }}</option>
                <option value="for_employee">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.for_employee') }}</option>
                <option value="employee_mistake">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.employee_mistake') }}</option>
                <option value="invalid">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.invalid') }}</option>
                <option value="damage">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.damage') }}</option>
                <option value="losing">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.losing') }}</option>
                <option value="for_orders">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.for_orders') }}</option>
                <option value="else">{{ __('dashboard.manual_item_withdrawal_and_return.reason_options.else') }}</option>
            `
        };
    $(document).on('click', '.btn-edit-adjustment', function () {
        var id = $(this).data('id');
        $('#editAdjustmentModal').modal('show');

        $.ajax({
            url: '/manual/stock-adjustments/' + id + '/json',
            method: 'GET',
            success: function(data) {
                $('#edit-adjustment-id').val(data.id);
                $('#edit-available-quantity').val(data.stock ? data.stock.quantity : '');
                $('#edit-quantity').val(Math.abs(data.available_quantity_after-data.available_quantity_before));
                $('#edit-type-select').val(data.type);
                $('#edit-percentage').val(data.available_percentage_after);

                   const selectedType = data.type;
                     const $reasonDropdown = $('select[name="reason"]');


            // Update the reason dropdown options based on the selected type
            if (reasonOptions[selectedType]) {
                $reasonDropdown.html(reasonOptions[selectedType]);

            }
                $('#edit-reason').html(reasonOptions[data.type] || reasonOptions['item_decrement']);
                // Explicitly select the correct reason option
                $('#edit-reason option').each(function() {
                    if ($(this).val() === data.reason) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });
                if (data.reason === 'else') {
                    $('#edit-custom-reason').show().removeClass('d-none').val(data.custom_reason);
                } else if (data.reason === 'for_orders') {
                    $('#edit-order-id').show().removeClass('d-none');
                    $('#edit-order-id').find('option').each(function() {
                        if ($(this).val() == data.order_id) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                }

                $('#edit-note').val(data.note);
                if (data.employee_name) {
                    $('#edit-adjustment-form input[name="employee_name"]').val(data.employee_name);
                }
                if (data.date_time) {
                    $('#edit-adjustment-form input[name="date_time"]').val(data.date_time);
                }
            },
            error: function() {
                alert('Failed to fetch adjustment data');
            }
        });
    });

    $('#edit-adjustment-form').on('submit', function (e) {
        e.preventDefault();
        var id = $('#edit-adjustment-id').val();
        var formData = new FormData(this);
        // ensure type select value is included
        var t = $('#edit-type-select').val();
        if (t) { formData.set('type', t); }

        $.ajax({
            url: '{{ url('') }}/manual/stock-adjustments/' + id,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-HTTP-Method-Override': 'PUT' },
            success: function (res) {
                location.reload();
            },
            error: function (err) { alert('Failed to update'); }
        });
    });

    $(document).on('click', '.btn-delete-adjustment', function () {
        var id = $(this).data('id');
        // Wait for SweetAlert2 to load if not already
        function showDeleteSwal() {
            Swal.fire({
                title: '{{ __('dashboard.are_you_sure') }}',
                text: '{{ __('dashboard.you_wont_be_able_to_revert_this') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('dashboard.yes_delete_it') }}',
                cancelButtonText: '{{ __('dashboard.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('') }}/manual/stock-adjustments/' + id,
                        method: 'POST',
                        data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                        success: function () {
                            Swal.fire({
                                title: '{{ __('dashboard.deleted') }}',
                                text: '{{ __('dashboard.deleted_successfully') }}',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });
                            setTimeout(function(){ location.reload(); }, 1200);
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to delete',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }
        if (window.Swal) {
            showDeleteSwal();
        } else {
            var interval = setInterval(function(){
                if (window.Swal) {
                    clearInterval(interval);
                    showDeleteSwal();
                }
            }, 100);
        }
    });

    // Toggle custom reason / orders select in main form
    $(document).on('change', 'select.reason-select', function () {
        var $form = $(this).closest('form');
        var val = $(this).val();
        var $custom = $form.find('.custom-reason-input');
        var $orders = $form.find('.orders-select');

        function showEl($el) {
            $el.removeClass('d-none').show().prop('required', true);
        }
        function hideEl($el) {
            $el.prop('required', false).hide().addClass('d-none');
        }

        if (val === 'for_orders') {
            showEl($orders);
            hideEl($custom);
        } else if (val === 'else') {
            // only show custom reason when explicitly 'else'
            hideEl($orders);
            showEl($custom);
        } else {
            // hide both for other known reasons
            hideEl($orders);
            hideEl($custom);
        }
    });

    // Initialize visibility on page load
    $(function(){
        $('select.reason-select').each(function(){
            $(this).trigger('change');
        });
    });

    // Set available quantity for any pre-selected stock on load
    $(function(){
        $('select[name="stock_id"]').each(function(){
            var $sel = $(this);
            var available = $sel.find('option:selected').data('available') || '';
            $sel.closest('form').find('input[name="available_quantity"]').val(available);
        });
    });

 

    // Ensure edit modal toggles when opening (in case values are prefilled differently)
    $('#editAdjustmentModal').on('shown.bs.modal', function () {
        var $form = $('#edit-adjustment-form');
        var val = $form.find('select.reason-select').val();
        $form.find('select.reason-select').trigger('change');
        // available_quantity is now set from AJAX response, so no need to sync from select
        // recalc select2 widths when modal opens
        if ($('.select-item').data('select2')) {
            // re-init only selects inside the modal so dropdownParent attaches correctly
            $('#editAdjustmentModal .select-item').each(function(){
                if ($(this).data('select2')) { $(this).select2('destroy'); }
                var $modal = $(this).closest('.modal');
                $(this).select2({ width: 'resolve', dropdownParent: $modal.length ? $modal : null, templateResult: formatStock, templateSelection: formatStock });
            });
        }
    });

    // close button fallback for older bootstrap versions
    $(document).on('click', '#editAdjustmentModal .modal-header .close', function(){
        $('#editAdjustmentModal').modal('hide');
    });

    // jQuery fallback: ensure any cancel/dismiss element inside the modal hides it
    $(document).on('click', '#editAdjustmentModal [data-dismiss="modal"], #editAdjustmentModal [data-bs-dismiss="modal"], #editAdjustmentModal .modal-footer .btn-secondary', function(e){
        e.preventDefault();
        $('#editAdjustmentModal').modal('hide');
    });

    $(document).ready(function() {

        // Listen for changes in the type dropdown
        $('select[name="type"]').on('change', function() {
            const selectedType = $(this).val();
            const $reasonDropdown = $('select[name="reason"]');

            // Update the reason dropdown options based on the selected type
            if (reasonOptions[selectedType]) {
                $reasonDropdown.html(reasonOptions[selectedType]);
            } else {
                $reasonDropdown.html('<option value="">{{ __('dashboard.manual_item_withdrawal_and_return.select_reason') }}</option>');
            }
        });

        // Trigger change event on page load to initialize the reason dropdown
        $('select[name="type"]').trigger('change');
    });
// Unified validation: require at least one of quantity_to_discount or percentage for both main and edit forms
$(document).on('submit', 'form.update, #edit-adjustment-form', function(e) {
    var $form = $(this);
    var qty = $form.find('input[name="quantity_to_discount"]').val();
    var perc = $form.find('input[name="percentage"]').val();
    if ((!qty || qty === "") && (!perc || perc === "")) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ __('dashboard.enter_quantity_or_percentage') }}'
                    });
                } else {
                    alert('{{ __('dashboard.enter_quantity_or_percentage') }}');
                }
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
    }
});
</script>
@endpush
</div>
@endsection