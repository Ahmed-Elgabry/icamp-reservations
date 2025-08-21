<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
    id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
    <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
        <div class=" menu-active-bg">
            <div class="menu-item">
                <a class="menu-link {{ isActiveRoute('home') }}" href="{{route('home')}}">
                    <span class="menu-bullet">
                        <img src="{{ asset('images/logo.png') }}" style="width:25px;height:25px">
                    </span>
                    <span class="menu-title">
                        @lang('dashboard.home')
                    </span>
                </a>
            </div>
        </div>
    </div>

    <hr style="background:#fff">

    @can('roles.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/roles.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.roles')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('roles.index') }}" class="menu-link py-3  {{ isActiveRoute('roles.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.roles')])</span>
                    </a>
                </div>
                <!--end::Menu item-->


                @can('roles.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('roles.create')}}" class="menu-link py-3 {{ isActiveRoute('roles.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.role')])</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                @endcan
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan

    @can('admins.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['admins.index', 'admins.create', 'admins.edit'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['admins.index', 'admins.create', 'admins.edit'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/admins.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.admins')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('admins.index') }}" class="menu-link py-3  {{ isActiveRoute('admins.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.admins')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

                @can('admins.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('admins.create')}}" class="menu-link py-3 {{ isActiveRoute('admins.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.admin')])</span>
                        </a>
                    </div>
                @endcan
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan
    @can('customers.index')
        <!--begin::Menu item-->

        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['customers.index', 'customers.create', 'customers.edit','notices.index','notice-types.index'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#"
                class="menu-link py-3 {{areActiveRoutes(['customers.index', 'customers.create', 'customers.edit','notices.index','notice-types.index'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/customers.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.customers')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('customers.index') }}" class="menu-link py-3  {{ isActiveRoute('customers.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.customers')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

                @can('customers.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('customers.create')}}" class="menu-link py-3 {{ isActiveRoute('customers.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.customers')])</span>
                        </a>
                    </div>
                @endcan
                <!--end::Menu item-->
                {{--    @can('notices.index')--}}
                <div class="menu-item">
                    <a href="{{ route('notices.index') }}" class="menu-link py-3 {{ isActiveRoute('notices.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.notices')</span>
                    </a>
                </div>
                {{--    @endcan--}}
{{--                @can('notice-types.index')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('notice-types.index') }}" class="menu-link py-3 {{ isActiveRoute('notice-types.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">@lang('dashboard.notice_types')</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan
    @can('surveys.create')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['surveys.create', 'surveys.answer', 'surveys.settings', 'surveys.results', 'surveys.statistics'])}}" data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['surveys.create', 'surveys.answer', 'surveys.results', 'surveys.settings', 'surveys.statistics'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/tasks.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.surveys')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                @can('surveys.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('surveys.create')}}" class="menu-link py-3 {{ isActiveRoute('surveys.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">@lang('dashboard.create_survey')</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                @endcan

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{route('surveys.results', 1)}}" class="menu-link py-3 {{ isActiveRoute('surveys.results') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.survey_results')</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{route('surveys.statistics',1)}}" class="menu-link py-3 {{ isActiveRoute('surveys.statistics') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.survey_statistics')</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{route('surveys.settings')}}" class="menu-link py-3 {{ isActiveRoute('surveys.settings') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.survey_settings')</span>
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan
    @can('stocks.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['stocks.index', 'stocks.show', 'stocks.create', 'stocks.edit', 'stock-quantities.show'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['stocks.index', 'stocks.create', 'stocks.edit'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/stocks.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.stocks')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('stocks.index') }}" class="menu-link py-3  {{ isActiveRoute('stocks.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.stocks')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

                @can('stocks.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('stocks.create')}}" class="menu-link py-3 {{ isActiveRoute('stocks.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.stocks')])</span>
                        </a>
                    </div>
                @endcan
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan
    @can('addons.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['addons.index', 'addons.show', 'addons.create', 'addons.edit', 'stock-quantities.show'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['addons.index', 'addons.create', 'addons.edit'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/addons.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.addons')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('addons.index') }}" class="menu-link py-3  {{ isActiveRoute('addons.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.addons')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

                @can('addons.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('addons.create')}}" class="menu-link py-3 {{ isActiveRoute('addons.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.addons')])</span>
                        </a>
                    </div>
                @endcan
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan

    @can('services.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['services.index', 'services.create', 'services.edit'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['services.index', 'services.create', 'services.edit'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/services.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.services')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('services.index') }}" class="menu-link py-3  {{ isActiveRoute('services.index') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.services')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

                @can('services.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('services.create')}}" class="menu-link py-3 {{ isActiveRoute('services.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.services')])</span>
                        </a>
                    </div>
                @endcan
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan
    @can('orders.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['orders.index', 'orders.reports', 'payments.show', 'expenses.show', 'orders.orders-by-status', 'orders.create', 'orders.edit', 'orders.show'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['orders.index', 'orders.orders-by-status'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/orders.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.orders')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{route('orders.index')}}" class="menu-link py-3 {{isActiveRoute('orders.index')}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.orders')])</span>
                    </a>
                </div>
                @can('orders.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('orders.create')}}" class="menu-link py-3 {{ isActiveRoute('orders.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.orders')])</span>
                        </a>
                    </div>
                @endcan
                <!--end::Menu item-->
                <div class="menu-item">
                    <a href="{{route('orders.rate')}}" class="menu-link py-3 {{ isActiveRoute('orders.rate') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.Questionnaires', ['page_title' => __('dashboard.orders')])</span>
                    </a>
                </div>


                <div class="menu-item">
                    <a class="menu-link {{isActiveURLSegment('pending', 2)}}" title="@lang('dashboard.pending_desc')"
                        href="{{ route('orders.index', ['status' => 'pending']) }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('apis.pending_orders')</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('orders.index', ['status' => 'approved']) }}" title="@lang('dashboard.approved_desc')"
                        class="menu-link py-3 {{isActiveURLSegment('approved', 2)}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.delegate_accept_orders')</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('orders.index', ['status' => 'canceled']) }}" title="@lang('dashboard.canceled_desc')"
                        class="menu-link py-3 {{isActiveURLSegment('canceled', 2)}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('apis.cancelled_orders')</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('orders.index', ['status' => 'delayed']) }}" title="@lang('dashboard.delayed_desc')"
                        class="menu-link py-3 {{isActiveURLSegment('delayed', 2)}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.delayed_orders')</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('orders.index', ['status' => 'completed']) }}"
                        title="@lang('dashboard.completed_desc')"
                        class="menu-link py-3 {{isActiveURLSegment('completed', 2)}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('apis.done_orders')</span>
                    </a>
                </div>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan

    @can('payments.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['payments.index'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['payments.index'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/payments.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.financial_system')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    @can('bank-accounts.index')
                        <!--begin::Menu item-->
                        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['bank-accounts.index', 'bank-accounts.create', 'payments.create', 'payments.edit', 'transactions.index', 'bank-accounts.edit', 'bank-accounts.show'])}}"
                            data-kt-menu-trigger="click">
                            <!--begin::Menu link-->
                            <a href="#"
                                class="menu-link py-3 {{areActiveRoutes(['bank-accounts.index', 'bank-accounts.create', 'bank-accounts.edit'])}}">
                                <span class="menu-icon">
                                    <img src="{{ asset('images/bank-accounts.png') }}" style="width:25px;height:25px">
                                </span>
                                <span class="menu-title">@lang('dashboard.bank-accounts')</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <!--end::Menu link-->
                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route('bank-accounts.index') }}"
                                        class="menu-link py-3  {{ isActiveRoute('bank-accounts.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span
                                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.bank-accounts')])</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                @can('bank-accounts.create')
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="{{route('bank-accounts.create')}}"
                                            class="menu-link py-3 {{ isActiveRoute('bank-accounts.create') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span
                                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.bank-accounts')])</span>
                                        </a>
                                    </div>
                                @endcan
                                @can('payments.create')
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="{{route('payments.create')}}"
                                            class="menu-link py-3 {{ isActiveRoute('payments.create') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">@lang('dashboard.switch_accounts')</span>
                                        </a>
                                    </div>
                                @endcan
                                <!--end::Menu item-->
                                @can('bank-accounts.index')
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="{{route('transactions.index')}}"
                                            class="menu-link py-3 {{ isActiveRoute('transactions.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span
                                                class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.transactions')])</span>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                            <!--end::Menu sub-->
                        </div>
                        <!--end::Menu item-->
                    @endcan
                    <a href="{{route('payments.index')}}" class="menu-link py-3 {{isActiveRoute('payments.index')}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.reservation_revenue')</span>
                    </a>
                    <a href="{{route('general_payments.index')}}"
                        class="menu-link py-3 {{isActiveRoute('general_payments.index')}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">@lang('dashboard.general_payments')</span>
                    </a>
                    @can('expenses.index')
                        <!--begin::Menu item-->
                        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['expenses.index', 'expenses.edit', 'expenses.show', 'expenses.create'])}}"
                            data-kt-menu-trigger="click">
                            <!--begin::Menu link-->
                            <a href="#" class="menu-link py-3 {{areActiveRoutes(['expenses.index'])}}">
                                <span class="menu-icon">
                                    <img src="{{ asset('images/expenses.png') }}" style="width:25px;height:25px">
                                </span>
                                <span class="menu-title">@lang('dashboard.expenses')</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <!--end::Menu link-->

                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{route('expenses.index')}}"
                                        class="menu-link py-3 {{isActiveRoute('expenses.index')}}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span
                                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.expenses')])</span>
                                    </a>
                                </div>
                                @can('expenses.create')
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <a href="{{route('expenses.create')}}"
                                            class="menu-link py-3 {{ isActiveRoute('expenses.create') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span
                                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.expenses')])</span>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                            <!--end::Menu sub-->
                        </div>
                        <!--end::Menu item-->
                    @endcan

                </div>
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan
    @can('expense-items.index')
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['expense-items.index', 'expense-items.create', 'expense-items.edit', 'expense-items.show'])}}"
            data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['expense-items.index'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/expense-items.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.expense-items')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{route('expense-items.index')}}"
                        class="menu-link py-3 {{isActiveRoute('expense-items.index')}}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span
                            class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.expense-items')])</span>
                    </a>
                </div>
                @can('expense-items.create')
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{route('expense-items.create')}}"
                            class="menu-link py-3 {{ isActiveRoute('expense-items.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span
                                class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.expense-items')])</span>
                        </a>
                    </div>
                @endcan
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
    @endcan

{{--    @can('tasks.index')--}}
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion {{areActiveRoutes(['tasks.index', 'tasks.create', 'tasks.edit', 'tasks.reports','employee.tasks'])}}"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['tasks.index', 'tasks.create', 'tasks.edit', 'tasks.reports'])}}">
            <span class="menu-icon">
                <img src="{{ asset('images/tasks.png') }}" style="width:25px;height:25px">
            </span>
                <span class="menu-title">@lang('dashboard.tasks')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('tasks.index') }}" class="menu-link py-3 {{ isActiveRoute('tasks.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.tasks')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

{{--                @can('tasks.create')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('tasks.create') }}" class="menu-link py-3 {{ isActiveRoute('tasks.create') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title">@lang('dashboard.add_title', ['page_title' => __('dashboard.tasks')])</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}

{{--                @can('tasks.reports')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('tasks.reports') }}" class="menu-link py-3 {{ isActiveRoute('tasks.reports') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">@lang('dashboard.task_reports')</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}
{{--                @can('tasks.view')--}}
                    <!-- Employee Tasks -->
                    <div class="menu-item">
                        <a href="{{ route('employee.tasks') }}" class="menu-link py-3 {{ isActiveRoute('employee.tasks') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">@lang('dashboard.my_tasks')</span>
                        </a>
                    </div>
{{--                @endcan--}}
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
{{--    @endcan--}}
{{--    @can('daily-reports.index')--}}
        <div class="menu-item menu-sub-indention menu-accordion {{ areActiveRoutes(['daily-reports.index', 'daily-reports.create', 'daily-reports.edit', 'daily-reports.export']) }}"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{ areActiveRoutes(['daily-reports.index', 'daily-reports.create', 'daily-reports.edit']) }}">
                <span class="menu-icon">
                    <img src="{{ asset('images/daily-reports.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.daily_reports')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('daily-reports.index') }}" class="menu-link py-3 {{ isActiveRoute('daily-reports.index') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                        <span class="menu-title">@lang('dashboard.all_daily_reports')</span>
                    </a>
                </div>
                <!--end::Menu item-->

{{--                @can('daily-reports.create')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('daily-reports.create') }}" class="menu-link py-3 {{ isActiveRoute('daily-reports.create') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">@lang('dashboard.create_daily_report')</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}
            </div>
            <!--end::Menu sub-->
        </div>
{{--    @endcan--}}
{{--    @can('equipment-directories.index')--}}
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion {{areActiveRoutes([
        'equipment-directories.index',
        'equipment-directories.create',
        'equipment-directories.edit',
        'equipment-directories.items.index',
        'equipment-directories.items.create',
        'equipment-directories.items.edit',
    ])}}" data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes([
            'equipment-directories.index',
            'equipment-directories.create',
            'equipment-directories.edit',
            'equipment-directories.items.index',
            'equipment-directories.items.create',
            'equipment-directories.items.edit',
        ])}}">
            <span class="menu-icon">
                <img src="{{ asset('images/equipments.png') }}" style="width:25px;height:25px">
            </span>
                <span class="menu-title">@lang('dashboard.equipment_directories')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('equipment-directories.index') }}" class="menu-link py-3 {{ isActiveRoute('equipment-directories.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title">@lang('dashboard.all_directories')</span>
                    </a>
                </div>
                <!--end::Menu item-->

{{--                @can('equipment-directories.create')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('equipment-directories.create') }}" class="menu-link py-3 {{ isActiveRoute('equipment-directories.create') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title">@lang('dashboard.create_directory')</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
{{--    @endcan--}}
{{--    @can('camp-reports.index')--}}
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion {{areActiveRoutes(['camp-reports.index', 'camp-reports.create', 'camp-reports.edit', 'camp-reports.show'])}}"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{areActiveRoutes(['camp-reports.index', 'camp-reports.create', 'camp-reports.edit', 'camp-reports.show'])}}">
            <span class="menu-icon">
                <img src="{{ asset('images/camps.png') }}" style="width:25px;height:25px">
            </span>
                <span class="menu-title">@lang('dashboard.camp_reports')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('camp-reports.index') }}" class="menu-link py-3 {{ isActiveRoute('camp-reports.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.camp_reports')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

{{--                @can('camp-reports.create')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('camp-reports.create') }}" class="menu-link py-3 {{ isActiveRoute('camp-reports.create') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.camp_report')])</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
{{--    @endcan--}}
{{--    @can('meetings.index')--}}
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion {{ areActiveRoutes(['meetings.index', 'meetings.create', 'meetings.edit', 'meetings.show','meeting-locations.index','meeting-locations.create','meeting-locations.edit']) }}"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{ areActiveRoutes(['meetings.index', 'meetings.create', 'meetings.edit','meeting-locations.index','meeting-locations.create','meeting-locations.edit']) }}">
            <span class="menu-icon">
                <img src="{{ asset('images/meetings.png') }}" style="width:25px;height:25px">
            </span>
                <span class="menu-title">@lang('dashboard.meetings')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('meeting-locations.index') }}" class="menu-link py-3 {{ isActiveRoute('meeting-locations.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.meeting_locations')])</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('meetings.index') }}" class="menu-link py-3 {{ isActiveRoute('meetings.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.meetings')])</span>
                    </a>
                </div>
                <!--end::Menu item-->


{{--                @can('meetings.create')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('meetings.create') }}" class="menu-link py-3 {{ isActiveRoute('meetings.create') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.meeting')])</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
{{--    @endcan--}}
{{--    @can('violations.index')--}}
        <!--begin::Menu item-->
        <div class="menu-item menu-sub-indention menu-accordion {{ areActiveRoutes(['violations.index', 'violations.create', 'violations.edit','violation-types.index', 'violation-types.create', 'violation-types.edit']) }}"
             data-kt-menu-trigger="click">
            <!--begin::Menu link-->
            <a href="#" class="menu-link py-3 {{ areActiveRoutes(['violations.index', 'violations.create', 'violations.edit','violation-types.index', 'violation-types.create', 'violation-types.edit']) }}">
            <span class="menu-icon">
                <img src="{{ asset('images/violations.png') }}" style="width:25px;height:25px">
            </span>
                <span class="menu-title">@lang('dashboard.violations')</span>
                <span class="menu-arrow"></span>
            </a>
            <!--end::Menu link-->

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-accordion pt-3">
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('violation-types.index') }}" class="menu-link py-3 {{ isActiveRoute('violation-types.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.violation_types')])</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a href="{{ route('violations.index') }}" class="menu-link py-3 {{ isActiveRoute('violations.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                        <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.violations')])</span>
                    </a>
                </div>
                <!--end::Menu item-->

{{--                @can('violations.create')--}}
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('violations.create') }}" class="menu-link py-3 {{ isActiveRoute('violations.create') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                            <span class="menu-title">@lang('dashboard.add_title', ['page_title' => __('dashboard.violation')])</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
{{--                @endcan--}}
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->
{{--    @endcan--}}
    @can('reprots')
        <div class="menu-item">
            <!--begin::Menu link-->
            <a href="{{ route('reprots') }}" class="menu-link py-3 {{areActiveRoutes(['reprots'])}}">
                <span class="menu-icon">
                    <img src="{{ asset('images/reprots.png') }}" style="width:25px;height:25px">
                </span>
                <span class="menu-title">@lang('dashboard.financial_reports')</span>
            </a>
            <!--end::Menu link-->
        </div>
    @endcan

    <div class="menu-item">
        <a href="{{ route('statistics.index') }}" class="menu-link py-3" title="@lang('dashboard.statistics')">
            <span class="menu-icon">
                <img src="{{ asset('images/roles.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.statistics')</span>
        </a>
    </div>
    <div class="menu-item">
        <!--begin::Menu link-->
        <a href="{{ route('calender') }}" class="menu-link py-3 {{areActiveRoutes(['calender'])}}">
            <span class="menu-icon">
                <img src="{{ asset('images/calender.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.calender')</span>
        </a>
        <!--end::Menu link-->
    </div>
    <div class="menu-item">
        <!--begin::Menu link-->
        <a href="{{ route('terms_sittngs.create')}}" class="menu-link py-3 ">
            <span class="menu-icon">
                <img src="{{ asset('images/logo.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.terms_setting')</span>
        </a>
        <!--end::Menu link-->
    </div>
</div>
