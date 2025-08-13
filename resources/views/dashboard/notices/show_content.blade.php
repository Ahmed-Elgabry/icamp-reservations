<div class="row mb-3">
    <div class="col-md-6">
        <strong>@lang('dashboard.customer'):</strong>
        <p>{{ $notice->customer->name }}</p>
    </div>
    <div class="col-md-6">
        <strong>@lang('dashboard.order'):</strong>
        <p>
            @if($notice->order)
                <a href="{{ route('orders.edit', $notice->order_id) }}">#{{ $notice->order_id }}</a>
            @else
                -
            @endif
        </p>
    </div>
</div>

<div class="mb-3">
    <strong>@lang('dashboard.notice'):</strong>
    <p class="p-3 bg-light rounded">{{ $notice->notice }}</p>
</div>

<div class="row">
    <div class="col-md-6">
        <strong>@lang('dashboard.notice_created_by'):</strong>
        <p>{{ $notice->creator->name }}</p>
    </div>
    <div class="col-md-6">
        <strong>@lang('dashboard.created_at'):</strong>
        <p>{{ $notice->created_at->format('Y-m-d H:i') }}</p>
    </div>
</div>
