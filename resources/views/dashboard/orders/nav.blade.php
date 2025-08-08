


 <!--begin::Card header-->
 <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
            <!--begin:::Tab item-->
        <li class="nav-item">
            <a href="{{  isset($order) ? route('orders.edit',$order->id) : route('orders.create') }}" class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.edit') }}">
                {{ isset($order) ? $order->customer->name : __('dashboard.create_title', ['page_title' => __('dashboard.orders')]) }}
                </a>
        </li>
        @if(isset($order))


            @can('orders.addons')
                <li class="nav-item">
                    <a href="{{ route('orders.addons',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.addons') }}">
                     {{ __('dashboard.addons') }} <span class="badge badge-primary">{{$order->addons->count() }}</span>
                    </a>
                </li>
            @endcan
            @can('payments.show')
                <li class="nav-item">
                    <a href="{{ route('payments.show',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('payments.show') }}">
                     {{ __('dashboard.payments') }} <span class="badge badge-primary">{{$order->payments->sum('price')}}</span>
                    </a>
                </li>
            @endcan
            @can('expenses.show')
                <li class="nav-item">
                    <a href="{{ route('expenses.show',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('expenses.show') }}">
                     {{ __('dashboard.expenses') }} <span class="badge badge-primary">{{$order->expenses->sum('price')}}</span>
                    </a>
                </li>
            @endcan
            @can('orders.insurance')
                <li class="nav-item">
                    <a href="{{ route('orders.insurance',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.insurance') }}">
                     {{ __('dashboard.Insurance forfeiture refund') }}
                    </a>
                </li>
            @endcan
            @can('orders.reports')
                <li class="nav-item">
                    <a href="{{ route('orders.reports',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.reports') }}">
                        {{__('dashboard.reports')}}
                    </a>
                </li>
            @endcan
            @can('orders.signin')
                <li class="nav-item">
                    <a href="{{ route('orders.signin',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.signin') }}">
                        {{__('dashboard.signin')}}
                    </a>
                </li>
            @endcan
            @can('orders.logout')
                <li class="nav-item">
                    <a href="{{ route('orders.logout',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.logout') }}">
                        {{__('dashboard.logout')}}
                    </a>
                </li>
            @endcan


            <li class="nav-item">
                <a href="{{ route('orders.quote',$order->id) }}"
                class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.quote') }}">
                    {{__('dashboard.Offer Price')}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('orders.invoice',$order->id) }}"
                class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.invoice') }}">
                    {{__('dashboard.invoice')}}
                </a>
            </li>

        @endif
    <!--end:::Tab item-->
    </ul>
    <hr>
