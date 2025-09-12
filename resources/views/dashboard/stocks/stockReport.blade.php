@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.stock_report_title'))

@section('content')
<div class="container">
	<h1 class="mb-4 text-center">{{ __('dashboard.stock_report_title') }}</h1>
	<!-- Stock Card -->
	<div class="card mb-4" style="max-width: 420px; margin: auto;">
		<img src="{{ $stock->image ? asset('storage/' . $stock->image) : asset('images/default-stock.png') }}" class="card-img-top" alt="{{ $stock->name }}" style="max-height: 220px; object-fit: contain;">
		<div class="card-body text-center">
			<h5 class="card-title">{{ $stock->name }}</h5>
			<p class="card-text mb-1"><strong>{{ __('dashboard.created_at') }}:</strong> {{ $stock->created_at }}</p>
			<p class="card-text mb-1"><strong>{{ __('dashboard.total_price') }}:</strong> {{ $stock->price ?? '-' }}</p>
			<p class="card-text mb-1"><strong>{{ __('dashboard.price') }}:</strong> {{ $stock->selling_price ?? '-' }}</p>
			<p class="card-text mb-1"><strong>{{ __('dashboard.quantity') }}:</strong> {{ $stock->quantity ?? '-' }}</p>
		</div>
		<div class="d-flex justify-content-center mt-3">
			{!! $transactions->links() !!}
		</div>
	</div>

	<!-- Transaction Table -->
	<div class="card">
		<div class="card-header">{{ __('dashboard.transactions') }}</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-bordered mb-0">
					<thead class="thead-light">
						<tr>
							<th>{{ __('dashboard.date_time') }}</th>
							<th>{{ __('dashboard.type') }}</th>
							<th>{{ __('dashboard.source') }}</th>
							<th>{{ __('dashboard.available_quantity') }}</th>
							<th>{{ __('dashboard.issued_quantity') }}/{{ __('dashboard.returned_quantity') }}</th>
							<th>{{ __('dashboard.quantity') }} ({{ __('dashboard.after') }})</th>
							<th>{{ __('dashboard.reason') }}</th>
							<th>{{ __('dashboard.note') }}</th>
							<th>{{ __('dashboard.image') }}</th>
							<th>{{ __('dashboard.by') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($transactions as $tx)
						<tr>
							<td>{{ $tx->date_time }}</td>
							<td>{{ $tx->type == "item_increment" ? __("dashboard.item_return") : __("dashboard.item_issued") }}</td>
							<td>{{ $tx->order_id ?? __('dashboard.reservations') - $tx->order_id : "manual"}}</td>
							<td>{{ $tx->available_quantity_before ?? '-' }}</td>
							<td>{{ $tx->type === "item_decrement" ? $tx->issued_quantity : $tx->returned_quantity }}</td>
							<td>{{$tx->type === "item_decrement" ? $tx->available_quantity_before - $tx->quantity : $tx->available_quantity_before + $tx->quantity}}</td>
							@if($tx->order_id)
                            <td>{{ __('dashboard.reservations') - $tx->order_id }}</td>
                            @elseif($tx->reason === 'else' && $tx->custom_reason)
                            <td>{{__("dashboard.manual_item_withdrawal_and_return.reason_options.else") $tx->custom_reason }}</td>
							<td>{{ $tx->note ?? '-' }}</td>
							<td>
								@if($tx->image)
									<a href="#" data-toggle="modal" data-target="#imageModal-{{ $tx->id }}">
										<img src="{{ asset('storage/' . $tx->image) }}" alt="Image" style="max-width:40px;max-height:40px;object-fit:cover;">
									</a>
									<!-- Modal -->
									<div class="modal fade" id="imageModal-{{ $tx->id }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel-{{ $tx->id }}" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="imageModalLabel-{{ $tx->id }}">{{ __('dashboard.image') }}</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body text-center">
													<img src="{{ asset('storage/' . $tx->image) }}" alt="Image" class="img-fluid" style="max-width:100%;height:auto;">
												</div>
											</div>
										</div>
									</div>
								@else
									-
								@endif
							</td>
							<td>{{ $tx->by ?? '-' }}</td>
						</tr>
						@empty
						<tr><td colspan="10" class="text-center">{{ __('dashboard.no_data') }}</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
