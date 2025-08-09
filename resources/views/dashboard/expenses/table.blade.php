 

 <div class="row justify-content-center">
     <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                        <form action="?" method="GET" class="form-inline row">
                            <div class="form-group mb-2 col-6">
                                <label for="start_date" class="text-right d-block">@lang('dashboard.date_from')</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="@lang('dashboard.date_from')" value="{{ request('start_date') }}">
                            </div>
                            <div class="form-group mb-2 col-6">
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
 {{$expenses->links()}}
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
                            <th class="">@lang('dashboard.source')</th>
                            <th class="">@lang('dashboard.price')</th>
                            <th class="">@lang('dashboard.date')</th>
                            <th class="">@lang('dashboard.notes')</th>
                            <th class=" min-w-70px">@lang('dashboard.actions')</th>
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
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="{{ $expense->expense_item_id ? route('expense-items.show', $expense->expense_item_id) : '#'}}" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1" data-kt-ecommerce-category-filter="category_name">
                                                {{$expense->expenseItem ? $expense->expenseItem->name : ''}}
                                            </a>
                                            <div class="text-muted fs-7 fw-bolder">{{$expense->notes}}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$expense->price}} </td>
                                <td>{{$expense->date }} </td>
                                <td>{{$expense->notes }} </td>
                           
                                <!--begin::Action=-->
                                <td class="">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">@lang('dashboard.actions')
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon--></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        @can('expenses.edit')
                                        <div class="menu-item px-3">
                                            <a href="{{route('expenses.edit', $expense->id)}}" class="menu-link px-3">@lang('dashboard.edit')</a>
                                        </div>
                                        @endcan 
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        @can('expenses.destroy')
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-url="{{route('expenses.destroy', $expense->id)}}" data-id="{{$expense->id}}">@lang('dashboard.delete')</a>
                                        </div>
                                        @endcan 
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                                <!--end::Action=-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>