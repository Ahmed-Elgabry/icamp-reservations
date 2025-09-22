@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.tasks'))

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
  <div id="kt_content_container" class="container-xxl">
    <div class="card card-flush">
      <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
          <div class="ms-3 d-flex flex-wrap gap-2">
            <a href="{{ route('bookings.tasks.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-light' }}">@lang('dashboard.all')</a>
            <a href="{{ route('bookings.tasks.index', ['status' => 'in_progress']) }}" class="btn btn-sm {{ $status === 'in_progress' ? 'btn-primary' : 'btn-light' }}">@lang('dashboard.in_progress')</a>
            <a href="{{ route('bookings.tasks.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-light' }}">@lang('dashboard.pending')</a>
            <a href="{{ route('bookings.tasks.index', ['status' => 'failed']) }}" class="btn btn-sm {{ $status === 'failed' ? 'btn-primary' : 'btn-light' }}">@lang('dashboard.failed')</a>
          </div>
        </div>
        <div class="card-toolbar">
          @php
            $selectedFrom = request('from');
            $selectedTo = request('to');
            $activeTab = 'tasks';
          @endphp
          <form method="get" action="{{ route('bookings.tasks.index') }}" class="d-flex align-items-center gap-2">
            <label class="form-label m-0">@lang('dashboard.from')</label>
            <input type="date" name="from" value="{{ $selectedFrom ?? now()->toDateString() }}" class="form-control form-control-sm" onchange="this.form.submit()" />
            <label class="form-label m-0">@lang('dashboard.to')</label>
            <input type="date" name="to" value="{{ $selectedTo ?? '' }}" class="form-control form-control-sm" onchange="this.form.submit()" />
            @if(!empty($selectedFrom) || !empty($selectedTo))
              <a href="{{ route('bookings.tasks.index') }}" class="btn btn-sm btn-light">@lang('dashboard.clear')</a>
            @endif
          </form>
          <ul class="nav nav-tabs nav-line-tabs ms-4">
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
        <div class="table-responsive">
          <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
              <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                <th class="min-w-100px fw-bolder">@lang('dashboard.title')</th>
                <th class="">@lang('dashboard.created_date | created_time')</th>
                <th class="min-w-100px fw-bolder">@lang('dashboard.assigned_to')</th>
                <th class="min-w-100px">@lang('dashboard.priority')</th>
                <th class="min-w-100px">@lang('dashboard.due_date')</th>
                <th class="min-w-100px">@lang('dashboard.task_status')</th>
              </tr>
            </thead>
            <tbody class="fw-bold text-gray-600">
              @forelse ($tasks as $task)
                <tr>
                  <td><a class="text-gray-800 text-hover-primary mb-1">{{ $task->title }}</a></td>
                  <td>{{ optional($task->created_at)->format('Y-m-d | h:i A') }}</td>
                  <td>{{ optional($task->assignedUser)->name }}</td>
                  <td>
                    <span class="badge badge-light-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'primary') }}">
                      {{ ucfirst( __('dashboard.' . $task->priority ) ) }}
                    </span>
                  </td>
                  <td>{{ optional($task->due_date)->format('Y-m-d') }}</td>
                  <td>
                    <span class="badge badge-light-{{ $task->status == 'completed' ? 'success' : ($task->status == 'failed' ? 'danger' : ($task->status == 'in_progress' ? 'info' : 'warning')) }}">
                      {{ str_replace('_', ' ', ucfirst( __('dashboard.' . $task->status) )) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">@lang('dashboard.no_data')</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
