@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.stock_report_title'))

@section('content')
@push("css")
    <style>
        thead tr th { 
			min-width: 200px !important;
		}
	</style>
@endpush
<div class="container">
	<!-- Stock Card -->
	<div class="card mb-4" style="max-width: 420px; margin: auto;">
		<img src="{{asset($stock->image) }}" class="card-img-top" alt="{{ $stock->name }}" style="max-height: 220px; object-fit: contain;">
		<div class="card-body text-center">
			<h5 class="card-title">{{ $stock->name }}</h5>
			<p class="card-text mb-1"><strong>{{ __('dashboard.date_of_creation') }}:</strong> {{ $stock->created_at }}</p>
			<p class="card-text mb-1"><strong>{{ __('dashboard.total_price-first-time') }}:</strong> {{ $stock->price ?? '-' }}</p>
			<p class="card-text mb-1"><strong>{{ __('dashboard.selling_price-first-time') }}:</strong> {{ $stock->selling_price ?? '-' }}</p>
			<p class="card-text mb-1"><strong>{{ __('dashboard.quantity-first-time') }}:</strong> {{ $stock->quantity ?? '-' }}</p>
		</div>
	</div>
	
	<!-- Transaction Table -->
			<div class="table-responsive">
				<table class="table table-bordered mb-0">
					<thead class="thead-light">
						<tr>
							<th>{{ __('dashboard.date_time') }}</th>
							<th>{{ __('dashboard.type') }}</th>
							<th>{{ __('dashboard.source') }}</th>
							<th>{{ __('dashboard.available_quantity_before') }}</th>
							<th>{{ __('dashboard.issued_quantity') }}/{{ __('dashboard.returned_quantity') }}</th>
							<th>{{ __('dashboard.available_quantity_after') }} </th>
							<th>{{ __('dashboard.reason') }}</th>
							<th>{{ __('dashboard.notes') }}</th>
							<th>{{ __('dashboard.image_preview') }}</th>
							<th>{{ __('dashboard.by') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($transactions as $tx)
						<tr>
							<td>{{ $tx->date_time }}</td>
							<td>{{ $tx->type == "item_increment" ? __("dashboard.item_return") : __("dashboard.item_issued") }}</td>
							<td>{{ $tx->order_id ? __('dashboard.reservations') . ' - ' . $tx->order_id : __("dashboard.manual") }}</td>
							
							<td>{{ $tx->available_quantity_before ?? '-' }}</td>
							<!-- when the type is item_increment or item_decrement it is meant this is from manual stock adjustment which the quintity in the stock ajustment is the difference between the available quantity before and after the adjustment -->
							<!-- when the type is stockTaking_increment or stockTaking_decrement it is meant this is from stock taking which the quantity in the stock taking is  quantity after the stock taking -->
							@php
								if($tx->type === "item_decrement" || $tx->type === "item_increment") {
									$quantity_after = $tx->type === "item_decrement" ? $tx->available_quantity_before - $tx->quantity : $tx->available_quantity_before + $tx->quantity;

									$diffQuantity = $tx->quantity;
								}
								else{
									$quantity_after = $tx->quantity;
									$diffQuantity = abs($tx->available_quantity_before - $tx->quantity);
								}
		
							@endphp
							<td>{{ $diffQuantity }}</td>
							<td>{{  $quantity_after  }}</td>
							<td>
								@if($tx->order_id)
									{{ __('dashboard.reservations') . ' - ' . $tx->order_id }}
								@elseif($tx->reason === 'else' && $tx->custom_reason)
									{{__("dashboard.manual_item_withdrawal_and_return.reason_options.else")}} - {{ $tx->custom_reason }}
								@else
									{{__("dashboard.manual_item_withdrawal_and_return.reason_options.$tx->reason") ?? '-' }}
								@endif
							</td>
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
							<td>{{ $tx->employee_name ?? '-' }}</td>
						</tr>

						@empty
						<tr><td colspan="10" class="text-center">{{ __('dashboard.no_data') }}</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

	<div class="d-flex justify-content-between mt-3 mb-4 p-2 align-items-center">
	@include('dashboard.pagination.pagination', ['transactions' => $transactions])
	</div>
@endsection
