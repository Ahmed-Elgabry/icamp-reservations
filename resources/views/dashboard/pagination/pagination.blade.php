	<div class="text-muted">
			@if(method_exists($transactions, 'firstItem'))
				{{ __('dashboard.showing') }} {{ $transactions->firstItem() ?? 0 }} {{ __('dashboard.to') }} {{ $transactions->lastItem() ?? 0 }} {{ __('dashboard.of') }} {{ $transactions->total() ?? $transactions->count() }} {{ __('dashboard.entries') }}
			@else
				{{ $transactions->count() }} {{ __('dashboard.entries') }}
			@endif
		</div>
		<div>
			@if(method_exists($transactions, 'links'))
				<nav aria-label="Page navigation">
					<ul class="pagination mb-0">
						{{-- Previous --}}
						@if(method_exists($transactions, 'onFirstPage') && $transactions->onFirstPage())
							<li class="page-item disabled"><span class="page-link">&laquo;</span></li>
						@else
							<li class="page-item"><a class="page-link" href="{{ $transactions->previousPageUrl() }}" rel="prev">&laquo;</a></li>
						@endif

						{{-- Page numbers with compact ellipses --}}
						@php
							$last = method_exists($transactions, 'lastPage') ? $transactions->lastPage() : 1;
							$current = method_exists($transactions, 'currentPage') ? $transactions->currentPage() : 1;
						@endphp
						@for($page = 1; $page <= $last; $page++)
							@if($page == 1 || $page == $last || ($page >= $current-1 && $page <= $current+1) || $page <= 3 || $page > $last-3)
								@if($page == $current)
									<li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
								@else
									<li class="page-item"><a class="page-link" href="{{ $transactions->url($page) }}">{{ $page }}</a></li>
								@endif
							@else
								@php
									$nextVisible = null;
								@endphp
								@if(($page == 4 && $current > 5) || ($page == $last-3 && $current < $last-4))
									<li class="page-item disabled"><span class="page-link">&hellip;</span></li>
								@endif
							@endif
						@endfor

						{{-- Next --}}
						@if(method_exists($transactions, 'hasMorePages') && $transactions->hasMorePages())
							<li class="page-item"><a class="page-link" href="{{ $transactions->nextPageUrl() }}" rel="next">&raquo;</a></li>
						@else
							<li class="page-item disabled"><span class="page-link">&raquo;</span></li>
						@endif
					</ul>
				</nav>
			@endif
		</div>