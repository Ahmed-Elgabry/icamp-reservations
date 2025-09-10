 <!--begin::Card header-->
 <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3">
             <!--begin:::Tab item-->
            <li class="nav-item">
                <a href="{{  isset($stock) ? route('stocks.edit',$stock->id) : route('stocks.create') }}" class="nav-link text-active-primary pb-4 {{ isActiveRoute('stocks.edit') }}"> 
                    {{ isset($stock) ? $stock->name : __('dashboard.create_title', ['page_title' => __('dashboard.stocks')]) }} 
                    </a>
            </li>
            @can('stock-quantities.show')
                @if(isset($stock))
                    <li class="nav-item">
                        <a href="{{ route('stock-quantities.show',$stock->id) }}" 
                        class="nav-link text-active-primary pb-4 {{ isActiveRoute('stock-quantities.show') }}"> 
                            {{ __('dashboard.amounts') }} 
                            <span class="badge badge-success ms-2">{{$stock->quantity }}</span>
                        </a>
                    </li>
                @endif 
            @endcan 
        <!--end:::Tab item-->
        </ul>
        <hr>