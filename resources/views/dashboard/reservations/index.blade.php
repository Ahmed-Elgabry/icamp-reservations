@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.reservations_board') )

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
  <div id="kt_content_container" class="container-xxl">
    <div class="card mb-5 mb-xl-10">
      <div class="card-header border-0 align-items-center justify-content-between">
        <div class="card-title m-0 d-flex align-items-center gap-3 flex-wrap">
          <h3 class="fw-bolder m-0">@lang('dashboard.reservations_board')</h3>
          <form method="get" action="{{ $formAction ?? route('pages.reservations.board.today') }}" class="d-flex align-items-center gap-2">
            <label class="form-label m-0">@lang('dashboard.from')</label>
            <input type="date" name="from" value="{{ $selectedFrom ?? now()->toDateString() }}" class="form-control form-control-sm" onchange="this.form.submit()" />
            <label class="form-label m-0">@lang('dashboard.to')</label>
            <input type="date" name="to" value="{{ $selectedTo ?? '' }}" class="form-control form-control-sm" onchange="this.form.submit()" />
            @if(!empty($selectedFrom) || !empty($selectedTo))
            <a href="{{ route('pages.reservations.board.today') }}" class="btn btn-sm btn-light">@lang('dashboard.clear')</a>
            @endif
          </form>
        </div>
        <div class="card-toolbar">
          <ul class="nav nav-tabs nav-line-tabs">
            <li class="nav-item">
              <a class="nav-link {{ ($activeTab ?? 'today') === 'today' ? 'active' : '' }}" href="{{ route('pages.reservations.board.today', ['from' => $selectedFrom ?? null, 'to' => $selectedTo ?? null]) }}">@lang('dashboard.today')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($activeTab ?? 'today') === 'upcoming' ? 'active' : '' }}" href="{{ route('pages.reservations.board.upcoming', ['from' => $selectedFrom ?? null, 'to' => $selectedTo ?? null]) }}">@lang('dashboard.upcoming')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($activeTab ?? 'today') === 'tasks' ? 'active' : '' }}" href="#tab_tasks" data-bs-toggle="tab">@lang('dashboard.tasks')</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="card-body pt-0">
        <div class="tab-content" id="reservationsTabs">
          <!-- Today -->
          <div class="tab-pane fade {{ ($activeTab ?? 'today') === 'today' ? 'show active' : '' }}" id="tab_today" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-row-dashed align-middle">
                <thead>
                  <tr class="text-muted fw-bold">
                    <th>@lang('dashboard.reservation_date')</th>
                    <th>@lang('dashboard.service')</th>
                    <th>@lang('dashboard.reservation') #</th>
                    <th>@lang('dashboard.customer')</th>
                    <th>@lang('dashboard.time')</th>
                    <th>@lang('dashboard.payment_status')</th>
                    <th>@lang('dashboard.login')</th>
                    <th>@lang('dashboard.logout')</th>
                    <th>@lang('dashboard.notes')</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($todayOrders as $order)
                  @php
                  $serviceNames = $order->services->pluck('name')->join(', ');
                  $timeFrom = $order->time_from ?? null;
                  $timeTo = $order->time_to ?? null;
                  $signinAt = $order->time_of_receipt ?? null;
                  $logoutAt = $order->delivery_time ?? null;
                  $dateStr = $order->date ?? null;
                  // payment sum best-effort
                  $verifiedPayments = $order->payments ? $order->payments->where('verified', true) : collect();
                  $paidAmount = (float) $verifiedPayments->sum(function($p){ return isset($p->amount) ? $p->amount : (isset($p->price) ? $p->price : 0); });
                  $isPaid = $order->price ? ($paidAmount >= (float)$order->price) : false;
                  @endphp
                  <tr>
                    <td>{{ $order->date }}</td>
                    <td>{{ $serviceNames }}</td>
                    <td><a href="{{ route('orders.show', $order->id) }}">#{{ $order->id }}</a></td>
                    <td>{{ optional($order->customer)->name }}</td>
                    <td>
                      @if($timeFrom || $timeTo)
                      <span class="badge bg-light text-dark">{{ $timeFrom }} - {{ $timeTo }}</span>
                      @endif
                    </td>
                    <td>
                      @if($isPaid)
                      <span class="badge bg-success">@lang('dashboard.paid')</span>
                      @else
                      <span class="badge bg-warning">@lang('dashboard.unpaid')</span>
                      <div class="text-muted small">{{ number_format($paidAmount,2) }} / {{ number_format((float)$order->price,2) }}</div>
                      @endif
                    </td>
                    <td>
                      @if($signinAt)
                      <span class="badge bg-success">@lang('dashboard.logged_in')</span>
                      <div class="text-muted small">{{ $signinAt }}</div>
                      @else
                      @if($dateStr && $timeFrom)
                      <span class="badge border border-danger text-danger countdown"
                        data-target="{{ \Carbon\Carbon::parse($dateStr.' '.$timeFrom)->format('Y-m-d H:i:s') }}"
                        data-mode="signin">
                        --:--:--
                      </span>
                      @endif
                      @endif
                    </td>
                    <td>
                      @if($logoutAt)
                      <span class="badge bg-success">@lang('dashboard.logged_out')</span>
                      <div class="text-muted small">{{ $logoutAt }}</div>
                      @else
                      @if($dateStr && $timeTo)
                      <span class="badge border border-danger text-danger countdown"
                        data-target="{{ \Carbon\Carbon::parse($dateStr.' '.$timeTo)->format('Y-m-d H:i:s') }}"
                        data-mode="logout">
                        --:--:--
                      </span>
                      @endif
                      @endif
                    </td>
                    <td>
                      @if(!empty($order->notes))
                      <button type="button" class="btn btn-sm btn-light-primary btn-notes" data-notes="{{ e($order->notes) }}">
                        <i class="bi bi-eye"></i>
                      </button>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-light">@lang('dashboard.edit')</a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="10" class="text-center text-muted">@lang('dashboard.no_data')</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Upcoming -->
          <div class="tab-pane fade {{ ($activeTab ?? 'today') === 'upcoming' ? 'show active' : '' }}" id="tab_upcoming" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-row-dashed align-middle">
                <thead>
                  <tr class="text-muted fw-bold">
                    <th>@lang('dashboard.reservation_date')</th>
                    <th>@lang('dashboard.service')</th>
                    <th>@lang('dashboard.reservation') #</th>
                    <th>@lang('dashboard.customer')</th>
                    <th>@lang('dashboard.time')</th>
                    <th>@lang('dashboard.payment_status')</th>
                    <th>@lang('dashboard.notes')</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($upcomingOrders as $order)
                  @php
                  $serviceNames = $order->services->pluck('name')->join(', ');
                  $verifiedPayments = $order->payments ? $order->payments->where('verified', true) : collect();
                  $paidAmount = (float) $verifiedPayments->sum(function($p){ return isset($p->amount) ? $p->amount : (isset($p->price) ? $p->price : 0); });
                  $isPaid = $order->price ? ($paidAmount >= (float)$order->price) : false;
                  @endphp
                  <tr>
                    <td>{{ $order->date }}</td>
                    <td>{{ $serviceNames }}</td>
                    <td><a href="{{ route('orders.show', $order->id) }}">#{{ $order->id }}</a></td>
                    <td>{{ optional($order->customer)->name }}</td>
                    <td>
                      @if($order->time_from || $order->time_to)
                      <span class="badge bg-light text-dark">{{ $order->time_from }} - {{ $order->time_to }}</span>
                      @endif
                    </td>
                    <td>
                      @if($isPaid)
                      <span class="badge bg-success">@lang('dashboard.paid')</span>
                      @else
                      <span class="badge bg-warning">@lang('dashboard.unpaid')</span>
                      <div class="text-muted small">{{ number_format($paidAmount,2) }} / {{ number_format((float)$order->price,2) }}</div>
                      @endif
                    </td>
                    <td>
                      @if(!empty($order->notes))
                      <button type="button" class="btn btn-sm btn-light-primary btn-notes" data-notes="{{ e($order->notes) }}">
                        <i class="bi bi-eye"></i>
                      </button>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-light">@lang('dashboard.edit')</a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted">@lang('dashboard.no_data')</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Tasks placeholder -->
          <div class="tab-pane fade" id="tab_tasks" role="tabpanel">
            <div class="alert alert-info mb-0">@lang('dashboard.coming_soon')</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">@lang('dashboard.notes')</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="notesContent" class="fs-6"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('dashboard.close')</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  (function() {
    // Notes modal
    const notesModalEl = document.getElementById('notesModal');
    const notesModal = notesModalEl ? new bootstrap.Modal(notesModalEl) : null;
    document.querySelectorAll('.btn-notes').forEach(btn => {
      btn.addEventListener('click', function() {
        const html = this.getAttribute('data-notes') || '';
        document.getElementById('notesContent').innerHTML = html;
        notesModal && notesModal.show();
      });
    });

    // Countdown timers
    function pad(n) {
      return String(n).padStart(2, '0');
    }

    function updateCountdown(el) {
      const target = el.getAttribute('data-target');
      if (!target) return;
      const t = new Date(target.replace(' ', 'T'));
      const now = new Date();
      let diff = Math.floor((t.getTime() - now.getTime()) / 1000);
      let prefix = '';
      if (diff < 0) {
        prefix = '-';
        diff = Math.abs(diff);
        el.classList.add('bg-danger', 'text-white');
      }
      const h = Math.floor(diff / 3600),
        m = Math.floor((diff % 3600) / 60),
        s = diff % 60;
      el.textContent = prefix + pad(h) + ":" + pad(m) + ":" + pad(s);
    }

    function tick() {
      document.querySelectorAll('.countdown').forEach(updateCountdown);
    }
    tick();
    setInterval(tick, 1000);
  })();
</script>
@endsection