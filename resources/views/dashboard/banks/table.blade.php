<!--begin::Table-->
<div class="table-responsive">
<table class="table align-middle table-row-dashed table-hover table-striped fs-6 gy-5" id="kt_ecommerce_category_table">

    <!--begin::Table head-->

    <thead>

        <!--begin::Table row-->

    <tr class="text-start text-dark fw-bolder fs-8 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important; letter-spacing: .3px;">

            <th class="w-10px pe-2 text-center fw-bolder">

                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">

                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true"

                        data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />

                </div>

            </th>
            <th class="text-center fw-bolder">{{ __('dashboard.process') }}</th>

            <th class="text-center fw-bolder">{{ __('dashboard.date') }}</th>
            <th class="text-center fw-bolder">{{ __('dashboard.time') }}</th>
            @isset($pageTitle)
                @if($pageTitle === __('dashboard.all_transactions'))
                      <th class="text-center fw-bolder">{{ __('dashboard.bank_account') }}</th>
                @endif
            @endisset

            <th class="text-center fw-bolder">{{ __('dashboard.transfer_from') }}</th>

            <th class="text-center fw-bolder">{{ __('dashboard.receiver') }}</th>

            <th class="text-center fw-bolder">{{ __('dashboard.price') }}</th>

            <th class="text-center fw-bolder">{{ __('dashboard.source') }}</th>


            <th class="text-center fw-bolder">{{ __('dashboard.orders') }}</th>

            @if(isset($pageTitle))
                    @if($pageTitle ===  __('dashboard.all_transactions'))
                        <th class="text-end min-w-70px fw-bolder">{{ __('dashboard.actions') }}</th>
                    @endif
            @endif


        </tr>

        <!--end::Table row-->

    </thead>

    <!--end::Table head-->



    <!--begin::Table body-->

    <tbody class="fw-bold text-gray-600">

        @foreach ($transactions as $transaction)

        <!--begin::Table row-->

        <tr data-id="{{$transaction->id}}">

            <!--begin::Checkbox-->

            <td class="text-center">

                <div class="form-check form-check-sm form-check-custom form-check-solid ">

                    <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$transaction->id}}" />

                </div>

            </td>

            <!--end::Checkbox-->

            <td class="text-center">
                @if($transaction->type == 'transfer')
                    <span class="badge" style="background-color: orange; color: white;">{{ __("dashboard.transfer") }}</span>


                    @elseif ($transaction->type == 'deposit')
                    <span class="badge badge-success">{{ __("dashboard.moneyDeposit") }}</span>
                @elseif ($transaction->type == 'debit')
                    <span class="badge badge-danger">{{ __("dashboard.debit") }}</span>

                @endif
            </td>

            <!--begin::Date-->
            <td class="text-center"><a href="{{ $transaction->editRoute }}">{{ $transaction->created_at->format('Y-m-d') }}</a></td>
            <!--begin::Time-->
            <td class="text-center">{{ $transaction->created_at->format('h:i A') }}</td>
            @isset($pageTitle)
                @if($pageTitle === __('dashboard.all_transactions'))
                <td class="text-center">

                    @if($transaction->account)
                    <span>
                        <a href="{{ route('bank-accounts.show', $transaction->account->id) }}" >
                            {{$transaction->account ? $transaction->account->name :""}}
                        </a>
                    </span>
                    @elseif($transaction->senderAccount->name)
                    <span>
                        <a href="{{ route('bank-accounts.show', $transaction->senderAccount->id) }}" >
                            {{$transaction->senderAccount->name }}
                        </a>
                    </span>
                    @endif

                    @if(!$transaction->order_id && $transaction->type != 'Payment')
                    @if($transaction->senderAccount)
                        <span >

                            {{__('dashboard.debit')}}.

                        </span>
                    @endif

                    @endif



                    @if($transaction->account && $transaction->account->id && $transaction->order_id || $transaction->type == 'Payment')

                        <span>

                            {{__('dashboard.Deposit')}}

                        </span>

                    @endif



                </td>
                @endif
            @endisset
            <td class="text-center">
                @if($transaction->senderAccount)
                <span class="badge  badge-primary">
                            <a href="{{ $transaction->senderAccount ? route('bank-accounts.show', $transaction->senderAccount->id) : '#' }}" class="text-light">
                                {{$transaction->senderAccount ? $transaction->senderAccount->name : ''}}
                            </a>
                </span>
                @endif
            </td>
            <td class="text-center">

                @if($transaction->receiver)
                <span class="badge  badge-primary">
                    <a href="{{ $transaction->receiver ? route('bank-accounts.show', $transaction->receiver->id) : '#' }}" class="text-light">
                        {{$transaction->receiver ? $transaction->receiver->name : ''}}
                    </a>
                </span>
                @endif



                @if($transaction->receiver)

                    @if(isset($bank) && $transaction->receiver->id == $bank->id)

                        <span class="badge  badge-success"> {{__('dashboard.Deposit')}}</span>

                    @endif

                @endif

            </td>

            <td class="text-center fw-bold" data-kt-ecommerce-category-filter="category_name">{{ $transaction->amount }} </td>

                <td class="text-center">
                    @if($transaction->type == 'transfer')

                            {{__('dashboard.transfer')}}
                    @else
                    @if($transaction->payment && $transaction->payment->statement)
                        {{ __('dashboard.' . $transaction->source) }} - {{ __('dashboard.' . $transaction->payment->statement) }}
                    @elseif($transaction->orderAddon && $transaction->orderAddon->addon && $transaction->orderAddon->addon->name)
                        {{ __('dashboard.' . $transaction->source) }} - {{  $transaction->orderAddon->addon->name }}
                    @elseif($transaction->orderItem && $transaction->orderItem->stock && $transaction->orderItem->stock->name)
                        {{ __("dashboard.orders") }} - {{ __('dashboard.' . $transaction->source) }} - {{ $transaction->orderItem->stock->name }}
                    @elseif($transaction->expense && $transaction->reservation_expenses)
                        {{ __('dashboard.' . $transaction->source) }} - {{ __('dashboard.' . $transaction->reservation_expenses) }}
                    @else
                        {{ __('dashboard.' . $transaction->source) }}
                    @endif
                    @endif

                </td>

            <!-- <td>{{ $transaction->description }}</td> -->

            <td class="text-center">{{ $transaction->order_id ? $transaction->order_id : '' }}</td>
                @if(isset($pageTitle))
                    @if($pageTitle ===  __('dashboard.all_transactions'))
                        <td class="text-end">

                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click"

                                        data-kt-menu-placement="bottom-end">

                                        @lang('dashboard.actions')

                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->

                                        <span class="svg-icon svg-icon-5 m-0">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">

                                                <path

                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"

                                                    fill="currentColor" />

                                            </svg>

                                        </span>

                                        <!--end::Svg Icon--></a>

                                    <!--begin::Menu-->

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"

                                        data-kt-menu="true">

                                        @can('payments.edit')

                                            <div class="menu-item px-3">

                                                <a href="{{ $transaction->editRoute }}" class="menu-link px-3">@lang('dashboard.edit')</a>

                                            </div>

                                        @endcan

                                        @can('transactions.destroy')

                                            <div class="menu-item px-3">

                                                <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row"

                                                    data-url="{{  $transaction->destroyRoute }}"

                                                    data-id="{{$transaction->id}}">@lang('dashboard.delete')</a>

                                            </div>

                                        @endcan

                                    </div>

                                    <!--end::Menu-->

                                </td>
                    @endif
                @endif
            <!--end::Action=-->

        </tr>

        <!--end::Table row-->

        @endforeach

    </tbody>

    <!--end::Table body-->

</table>
</div>
<!--end::Table-->

@push('css')
<style>
    /* Scoped enhancements for the banks table */
    #kt_ecommerce_category_table thead tr {
        background: linear-gradient(180deg, #f9fafb 0%, #f1f3f5 100%);
        border-bottom: 2px solid #e5e7eb;
    }
    #kt_ecommerce_category_table thead th {
        padding: 0.9rem 0.75rem;
        font-size: 1.125rem; /* ~ fs-5 */
        white-space: nowrap;
    }
    #kt_ecommerce_category_table tbody td {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        vertical-align: middle;
    }
    #kt_ecommerce_category_table tbody tr:hover {
        background-color: #f8fafc;
    }
    /* Improve checkbox alignment */
    #kt_ecommerce_category_table .form-check-input {
        width: 1.05rem;
        height: 1.05rem;
    }
    /* Make action column content slightly larger */
    #kt_ecommerce_category_table td.text-end {
        font-size: 1.05rem;
    }
</style>
@endpush
