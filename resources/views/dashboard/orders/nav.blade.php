


 <!--begin::Card header-->
 <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
            <!--begin:::Tab item-->
        <li class="nav-item">
            <a href="{{  isset($order) ? route('orders.edit',$order->id) : route('orders.create') }}" class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.edit') }}">
                {{-- {{ isset($order) ? $order->customer->name : __('dashboard.create_title', ['page_title' => __('dashboard.orders')]) }} --}}
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

            <!-- روابط الدفع -->
            <li class="nav-item">
                <a href="{{ route('payment-links.create') }}?order_id={{ $order->id }}"
                class="nav-link text-active-primary pb-4 {{ isActiveRoute('payment-links.*') }}">
                 {{ __('dashboard.payment-links') }} <span class="badge badge-success">+</span>
                </a>
            </li>

            <!-- عرض روابط الدفع المرتبطة بالطلب -->
            <li class="nav-item">
                <a href="{{ route('payment-links.index') }}?order_id={{ $order->id }}"
                class="nav-link text-active-primary pb-4">
                 {{ __('dashboard.view_payment_links') }} <span class="badge badge-info">{{ $order->paymentLinks->count() ?? 0 }}</span>
                </a>
            </li>

            <li class="nav-item">
                    <a href="{{ route('warehouse_sales.show',$order->id) }}"
                    class="nav-link text-active-primary pb-4 {{ isActiveRoute('warehouse_sales.show') }}">
                     {{ __('dashboard.warehouse_sales') }} <span class="badge badge-primary">{{$order->items->count()}}</span>
                    </a>
                </li>
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

            @if($order->status == 'approved')
                <li class="nav-item">
                    <a href="{{ route('orders.client-pdf', $order->id) }}"
                       class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.client-pdf') }}"
                       target="_blank">
                        {{ __('dashboard.client_pdf') }}
                    </a>
                </li>
            @endif

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

            <li class="nav-item">
                <a href="{{ route('orders.accept_terms',$order->id) }}"
                class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.accept_terms') }}">
                    {{__('dashboard.accept_terms')}}
                </a>
            </li>


        @endif
    <!--end:::Tab item-->
    </ul>
    <hr>
