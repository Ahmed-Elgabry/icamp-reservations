@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.reservations_board') )

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
  <div id="kt_content_container" class="container-xxl">
    <div class="card mb-5 mb-xl-10">
      <div class="card-header border-0 align-items-center justify-content-between">
        <div class="card-title m-0 d-flex align-items-center gap-3 flex-wrap">
          <form method="get" action="{{ $formAction ?? route('reservations.board') }}" class="d-flex align-items-center gap-2">
            <label class="form-label m-0">@lang('dashboard.from')</label>
            <input type="date" name="from" value="{{ $selectedFrom ?? now()->toDateString() }}" class="form-control form-control-sm" onchange="this.form.submit()" />
            <label class="form-label m-0">@lang('dashboard.to')</label>
            <input type="date" name="to" value="{{ $selectedTo ?? '' }}" class="form-control form-control-sm" onchange="this.form.submit()" />
            @if(!empty($selectedFrom) || !empty($selectedTo))
              <a href="{{ route('reservations.board') }}" class="btn btn-sm btn-light">@lang('dashboard.clear')</a>
            @endif
          </form>
        </div>
        <div class="card-toolbar">
          <ul class="nav nav-tabs nav-line-tabs">
            <li class="nav-item">
              <a class="nav-link {{ ($activeTab ?? 'today') === 'today' ? 'active' : '' }}" href="{{ route('reservations.board', ['from' => $selectedFrom ?? null, 'to' => $selectedTo ?? null]) }}">@lang('dashboard.today_reservations')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($activeTab ?? 'today') === 'upcoming' ? 'active' : '' }}" href="{{ route('reservations.board.upcoming', ['from' => $selectedFrom ?? null, 'to' => $selectedTo ?? null]) }}">@lang('dashboard.upcoming_reservations')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ ($activeTab ?? 'today') === 'tasks' ? 'active' : '' }}" href="{{ route('bookings.tasks.index') }}">@lang('dashboard.tasks')</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="card-body pt-0">
        <div class="tab-content" id="reservationsTabs">
          <!-- Today -->
          <div class="tab-pane fade {{ ($activeTab ?? 'today') === 'today' ? 'show active' : '' }}" id="tab_today" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-row-dashed align-middle mx-auto">
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
                    $totalPaid = $order->totalPaidAmount();
                    $totalPrice = $order->price + $order->insurance_amount;
                    $isPaid = $totalPaid >= $totalPrice;
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
                    <td class="payment-status" data-order-id="{{ $order->id }}">
                      @if($isPaid)
                        <span class="badge bg-success">@lang('dashboard.paid_totaly')</span>
                      @elseif($totalPaid < $totalPrice && $totalPaid > 0)
                        <span class="badge bg-warning">@lang('dashboard.paid_partially')</span>
                        <div class="text-muted small paid-amount">{{ number_format($totalPaid,2) }} / {{ number_format($totalPrice,2) }}</div>
                      @elseif($totalPaid == 0)
                        <span class="badge bg-danger">@lang('dashboard.unpaid')</span>
                      @endif
                    </td>
                    <td class="signin-status" data-order-id="{{ $order->id }}">
                      @php
                        $signinTime = $dateStr && $timeFrom ? \Carbon\Carbon::parse($dateStr.' '.$timeFrom) : null;
                        $now = now();
                        $isPastSignin = $signinTime ? $now->gt($signinTime) : false;
                      @endphp
                      @if($signinAt)
                        <span class="badge bg-success">@lang('dashboard.logged_in')</span>
                        <!-- <div class="text-muted small signin-time">{{ $signinAt }}</div> -->
                      @elseif($signinTime)
                        <span class="badge {{ $isPastSignin ? 'bg-success' : 'border border-success text-success' }} countdown"
                              data-target="{{ $signinTime->format('Y-m-d H:i:s') }}"
                              data-order-id="{{ $order->id }}"
                              data-mode="signin">
                          @if($isPastSignin)
                            @lang('dashboard.login_completed')
                          @else
                            --:--:--
                          @endif
                        </span>
                      @endif
                    </td>
                    <td class="logout-status" data-order-id="{{ $order->id }}">
                      @php
                        $logoutTime = $dateStr && $timeTo ? \Carbon\Carbon::parse($dateStr.' '.$timeTo) : null;
                        $isPastLogout = $logoutTime ? $now->gt($logoutTime) : false;
                      @endphp
                      @if($logoutAt)
                        <span class="badge bg-success">@lang('dashboard.logged_out')</span>
                        <!-- <div class="text-muted small logout-time">{{ $logoutAt }}</div> -->
                      @elseif($logoutTime)
                        <span class="badge {{ $isPastLogout ? 'bg-danger' : 'border border-danger text-danger' }} countdown"
                              data-target="{{ $logoutTime->format('Y-m-d H:i:s') }}"
                              data-order-id="{{ $order->id }}"
                              data-mode="logout">
                          @if($isPastLogout)
                            @lang('dashboard.logout_completed')
                          @else
                            --:--:--
                          @endif
                        </span>
                      @endif
                    </td>
                    <td>
                      @if(!empty($order->notes))
                        <button type="button" class="btn btn-sm btn-light-primary btn-notes" data-notes="{!! $order->notes !!}">
                          <i class="bi bi-eye"></i>
                        </button>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="10" class="text-center text-muted">@lang('dashboard.no_data')</td></tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Upcoming -->
          <div class="tab-pane fade {{ ($activeTab ?? 'today') === 'upcoming' ? 'show active' : '' }}" id="tab_upcoming" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-row-dashed align-middle mx-auto">
                <thead>
                  <tr class="text-muted fw-bold">
                    <th>@lang('dashboard.reservation_date')</th>
                    <th>@lang('dashboard.service')</th>
                    <th>@lang('dashboard.order_id') #</th>
                    <th>@lang('dashboard.customer')</th>
                    <th>@lang('dashboard.time')</th>
                    <th>@lang('dashboard.payment_status')</th>
                    <th>@lang('dashboard.notes')</th>
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
                        <button type="button" class="btn btn-sm btn-light-primary btn-notes" data-notes="{!! $order->notes !!}">
                          <i class="bi bi-eye"></i>
                        </button>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="8" class="text-center text-muted">@lang('dashboard.no_data')</td></tr>
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
// Configuration
const POLL_INTERVAL = 1000; // 1 second

// Initialize the application
(function(){
  // Notes modal
  const notesModalEl = document.getElementById('notesModal');
  const notesModal = notesModalEl ? new bootstrap.Modal(notesModalEl) : null;
  document.querySelectorAll('.btn-notes').forEach(btn => {
    btn.addEventListener('click', function(){
      const html = this.getAttribute('data-notes') || '';
      document.getElementById('notesContent').innerHTML = html;
      notesModal && notesModal.show();
    });
  });

  // Countdown timers
  function pad(n){ return String(n).padStart(2,'0'); }
  function updateCountdown(el){
    const target = el.getAttribute('data-target');
    if (!target) return;
    const t = new Date(target.replace(' ', 'T'));
    const now = new Date();
    let diff = Math.floor((t.getTime() - now.getTime())/1000);
    let prefix = '';
    if (diff < 0) { prefix = '-'; diff = Math.abs(diff); el.classList.add('bg-danger','text-white'); }
    const h = Math.floor(diff/3600), m = Math.floor((diff%3600)/60), s = diff%60;
    el.textContent = prefix + pad(h)+":"+pad(m)+":"+pad(s);
  }
  function tick(){
    document.querySelectorAll('.countdown').forEach(updateCountdown);
  }
  tick();
  setInterval(tick, 1000);

  // Start polling for updates
  function pollForUpdates() {
    const orderIds = Array.from(new Set(
      Array.from(document.querySelectorAll('[data-order-id]'))
        .map(el => el.getAttribute('data-order-id'))
        .filter(Boolean)
    ));

    if (orderIds.length === 0) return;

    // Get current status of all orders
    fetch('{{ route("orders.status") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ order_ids: orderIds })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success && data.orders) {
        data.orders.forEach(order => {
          updateOrderStatus(order);
        });
      }
    })
    .catch(error => console.error('Error fetching order statuses:', error));
  }

  // Update order status in the UI
  function updateOrderStatus(order) {
    if (!order.id) return;

    // Update payment status
    if (typeof order.payment_status !== 'undefined') {
      const paymentCell = document.querySelector(`.payment-status[data-order-id="${order.id}"]`);
      if (paymentCell) {
        if (order.payment_status === 'paid') {
          paymentCell.innerHTML = `
            <span class="badge bg-success">@lang('dashboard.paid_totaly')</span>
            ${order.paid_amount ? `<div class="text-muted paid-amount">${parseFloat(order.paid_amount).toFixed(2)} / ${parseFloat(order.total_price).toFixed(2)}</div>` : ''}
          `;
        } else if (order.payment_status === 'partial') {
          paymentCell.innerHTML = `
            <span class="badge bg-warning">@lang('dashboard.paid_partially')</span>
            ${order.paid_amount ? `<div class="text-muted paid-amount">${parseFloat(order.paid_amount).toFixed(2)} / ${parseFloat(order.total_price).toFixed(2)}</div>` : ''}
          `;
        } else {
          paymentCell.innerHTML = `
            <span class="badge bg-danger">@lang('dashboard.unpaid')</span>
            ${order.total_price ? `<div class="text-muted paid-amount">0.00 / ${parseFloat(order.total_price).toFixed(2)}</div>` : ''}
          `;
        }
      }
    }

    // Update sign-in status
    if (typeof order.signin_at !== 'undefined') {
      const signinCell = document.querySelector(`.signin-status[data-order-id="${order.id}"]`);
      if (signinCell) {
        if (order.signin_at) {
          signinCell.innerHTML = `
            <span class="badge bg-success">@lang('dashboard.logged_in')</span>
            <div class="text-muted small signin-time">${formatDateTime(order.signin_at)}</div>
          `;
        }
      }
    }

    // Update logout status
    if (typeof order.logout_at !== 'undefined') {
      const logoutCell = document.querySelector(`.logout-status[data-order-id="${order.id}"]`);
      if (logoutCell) {
        if (order.logout_at) {
          logoutCell.innerHTML = `
            <span class="badge bg-success">@lang('dashboard.logged_out')</span>
            <div class="text-muted small logout-time">${formatDateTime(order.logout_at)}</div>
          `;
        }
      }
    }
  }

  // Helper function to format date time
  function formatDateTime(datetimeString) {
    if (!datetimeString) return '';
    const options = {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    };
    return new Date(datetimeString).toLocaleString('en-US', options);
  }

  // Start polling
  setInterval(pollForUpdates, POLL_INTERVAL);
  // Initial poll after page load
  setTimeout(pollForUpdates, 5000);
})();
</script>
@endsection
