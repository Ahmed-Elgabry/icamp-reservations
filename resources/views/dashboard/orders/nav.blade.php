<!--begin::Card header-->
<ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
    <!--begin:::Tab item-->
    <li class="nav-item">
        <a href="{{  route('orders.edit', $order->id) }}" class="nav-link bg-transparent border-0 text-active-primary pb-4 {{ isActiveRoute('orders.edit') }}">
            <i class="fa fa-home text-primary me-1"></i>
            {{ __('dashboard.Reservation_information') }}
        </a>
    </li>
    @if(isset($order))
    @can('bookings.addons')
    <li class="nav-item">
        <a href="{{ route('bookings.addons',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('bookings.addons') }}">
            {{ __('dashboard.addons') }} <span class="badge badge-primary">{{$order->addons->count() }}</span>
        </a>
    </li>
    @endcan
    @can('payments.show')
    <li class="nav-item">
        <a href="{{ route('payments.show',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('payments.show') }}">
            {{ __('dashboard.payments') }} <span class="badge badge-primary" id="payment-amount">{{$order->payments->sum('price')}}</span>
        </a>
    </li>
    @endcan

    @can('bookings.payment-links.create')
    <li class="nav-item">
        <a href="{{ route('bookings.payment-links.create') }}?order_id={{ $order->id }}"
            class="nav-link text-active-primary pb-4 {{ isActiveroute('bookings.payment-links.*') }}">
            {{ __('dashboard.payment-links') }} <span class="badge badge-success">+</span>
        </a>
    </li>
    @endcan

    @can('bookings.payment-links.index')
    <li class="nav-item">
        <a href="{{ route('bookings.payment-links.index') }}?order_id={{ $order->id }}"
            class="nav-link text-active-primary pb-4">
            {{ __('dashboard.view_payment_links') }} <span class="badge badge-info">{{ $order->paymentLinks->count() ?? 0 }}</span>
        </a>
    </li>
    @endcan

    @can('bookings.warehouse-sales.show')
    <li class="nav-item">
        <a href="{{ route('warehouse_sales.show',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('warehouse_sales.show') }}">
            {{ __('dashboard.warehouse_sales') }} <span class="badge badge-primary">{{$order->items->count()}}</span>
        </a>
    </li>
    @endcan
    @can('expenses.show')
    <li class="nav-item">
        <a href="{{ route('expenses.show',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('expenses.show') }}">
            {{ __('dashboard.expenses') }} <span class="badge badge-primary " id="expense-amount">{{$order->expenses->sum('price')}}</span>
        </a>
    </li>
    @endcan
    @can('bookings.insurance')
    <li class="nav-item">
        <a href="{{ route('bookings.insurance',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('bookings.insurance') }}">
            {{ __('dashboard.Insurance forfeiture refund') }}
        </a>
    </li>
    @endcan
    @can('bookings.reports')
    <li class="nav-item">
        <a href="{{ route('bookings.reports',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('bookings.reports') }}">
            {{__('dashboard.reports')}}
        </a>
    </li>
    @endcan
    @can('bookings.signin')
    <li class="nav-item">
        <a href="{{ route('bookings.signin',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('bookings.signin') }}">
            {{__('dashboard.signin')}}
        </a>
    </li>
    @endcan
    @can('bookings.logout')
    <li class="nav-item">
        <a href="{{ route('bookings.logout',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('bookings.logout') }}">
            {{__('dashboard.logout')}}
        </a>
    </li>
    @endcan

    @if($order->status == 'approved')
    @can('bookings.view')
    <li class="nav-item">
        <a href="{{ route('orders.client-pdf', $order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.client-pdf') }}"
            target="_blank">
            {{ __('dashboard.client_pdf') }}
        </a>
    </li>
    @endcan
    @endif

    @can('bookings.offer.show')
    <li class="nav-item">
        <a href="{{ route('orders.quote',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.quote') }}" target="_blank">
            {{__('dashboard.Offer Price')}}
        </a>
    </li>
    @endcan

    @can('bookings.invoice.show')
    <li class="nav-item">
        <a href="{{ route('orders.invoice',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('orders.invoice') }}" target="_blank">
            {{__('dashboard.invoice')}}
        </a>
    </li>
    @endcan

    @can('bookings.accept-terms.index')
    <li class="nav-item">
        <a href="{{ route('bookings.accept-terms.index',$order->id) }}"
            class="nav-link text-active-primary pb-4 {{ isActiveRoute('bookings.accept-terms.index') }}">
            {{__('dashboard.accept_terms')}}
        </a>
    </li>
    @endcan

    @endif
    <!--end:::Tab item-->
</ul>
<hr>
