@extends('dashboard.layouts.app')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .fc-event-title.fc-sticky {
        font-size: 11px;
        text-align: right;
        width: 100%;
    }
</style>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: `{{ app()->getLocale() }}`,
            events: [
                @foreach($orders as $order)
                        {
                        id: "{{ $order->id }}",
                        title: `{{ $order->customer->name ?? 'عميل غير محدد' }} `,
                        start: "{{ $order->date }}",
                        end: "{{ $order->date }}",
                        url: `{{ route('orders.edit', $order->id) }}`,
                        extendedProps: {
                            status: "{{ $order->status }}"
                        }
                    },
                @endforeach
            ],
            eventDidMount: function (info) {
                var status = info.event.extendedProps.status;
                var titleElement = info.el.querySelector('.fc-event-title');

                // ضبط لون اسم العميل حسب الحالة
                if (status === 'pending') {
                    info.el.style.backgroundColor = 'blue';
                    info.el.style.borderColor = 'darkblue';
                    titleElement.style.color = 'white';
                } else if (status === 'approved') {
                    info.el.style.backgroundColor = 'green';
                    info.el.style.borderColor = 'darkgreen';
                    titleElement.style.color = 'white';
                } else if (status === 'completed') {
                    info.el.style.backgroundColor = 'gray';
                    info.el.style.borderColor = 'darkgray';
                    titleElement.style.color = 'white';
                } else if (status === 'canceled') {
                    info.el.style.backgroundColor = 'orenge';
                    info.el.style.borderColor = 'darkorenge';
                    titleElement.style.color = 'white';
                } else if (status === 'rejected') {
                    info.el.style.backgroundColor = 'red';
                    info.el.style.borderColor = 'darkred';
                    titleElement.style.color = 'white';
                }
            },
            dateClick: function (info) {
                window.location.href = `{{ route('orders.create') }}?date=${info.dateStr}`;
            }
        });
        calendar.render();
    });
</script>
@endsection