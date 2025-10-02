@extends('dashboard.layouts.app')
@section('pageTitle' , __('dashboard.calender'))
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card card-header d-flex" style="flex-direction: row;justify-content: center;align-items: center;">
                <div class="pending" style="margin-left: 12px;">
                    <span class="dotted-pending"></span>
                <span style="font-size: 14px;font-weight: 700;padding-right: 6px;">{{ __('dashboard.pending_and_show_price_desc') }}</span>
                </div>
                <div class="pending" style="margin-left: 12px;">
                    <span class="dotted-pending"></span>
                    <span style="font-size: 14px;font-weight: 700;padding-right: 6px;">{{ __('dashboard.pending_and_Initial_reservation') }}</span>
                </div>
                <div class="approved" style="margin-left: 12px;">
                    <span class="dotted-approved"></span>
                    <span style="font-size: 14px;font-weight: 700;padding-right: 6px;">{{ __('dashboard.approved') }}</span>
                </div>
                <div class="canceled" style="margin-left: 12px;">
                    <span class="dotted-canceled"></span>
                    <span style="font-size: 14px;font-weight: 700;padding-right: 6px;">{{ __('dashboard.canceled') }}</span>
                </div>
                <div class="delayed" style="margin-left: 12px;">
                    <span class="dotted-delayed"></span>
                    <span style="font-size: 14px;font-weight: 700;padding-right: 6px;">{{ __('dashboard.delayed') }}</span>
                </div>
                <div class="completed" style="margin-left: 12px;">
                    <span class="dotted-completed"></span>
                    <span style="font-size: 14px;font-weight: 700;padding-right: 6px;">{{ __('dashboard.completed') }}</span>
                </div>
            </div>
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
    .dotted-pending {
        background-color: #009ef7;
        color: white;
        padding:0px 12px;
        border-radius: 12px;
    }

    .dotted-approved {
        background-color: green;
        color: white;
        padding:0px 12px;
        border-radius: 12px;
    }

    .dotted-canceled {
        background-color: red;
        color: white;
        padding:0px 12px;
        border-radius: 12px;
    }

    .dotted-delayed {
        background-color: orange;
        color: white;
        padding:0px 12px;
        border-radius: 12px;
    }

    .dotted-completed {
        background-color: gray;
        color: white;
        padding:0px 12px;
        border-radius: 12px;
    }

    .fc-event-title.fc-sticky {
        font-size: 11px;
        text-align: right;
        width: 100%;
    }
    .fc .fc-col-header-cell .fc-col-header-cell-cushion { 
        color: white !important; 
    } 
    table thead tr { 
        background: linear-gradient(135deg, #ba7321 0%, #b16e17ff 100%) !important ; 
        box-shadow : none !important; 
    }
        table tbody tr:hover {
        background-color: unset  !important;
    }
    table tbody tr {
        background-color: unset  !important;
    }
    /* Hover effect removed - now handled by jQuery */
    table tbody tr:nth-child(even) {
			background-color: unset !important;
		}
</style>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    $(document).ready(function() {
        $('table tbody td').hover(
            function() {
                var date = $(this).data('date');
                if(!date) return;
                $(this).css({
                    'background-color': '#e3f2fd',
                    'transition': 'background-color 0.2s ease-in-out'
                });
            },
            function() {
                var date = $(this).data('date');
                if(!date) return;
                $(this).css('background-color', '');
            }
        );
    });
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
                    @can('scheduling.edit')
                        url: `{{ route('orders.edit', $order->id) }}`,
                        @endcan
                        extendedProps: {
                            status: "{{ $order->status }}"
                        }
                    },
                @endforeach
            ],
            eventDidMount: function (info) {
                var status = info.event.extendedProps.status;
                var titleElement = info.el.querySelector('.fc-event-title');
                console.log(status);

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
                } else if (status === 'delayed') {
                    info.el.style.backgroundColor = 'orange';
                    info.el.style.borderColor = 'darkorenge';
                    titleElement.style.color = 'white';
                } else if (status === 'canceled') {
                    info.el.style.backgroundColor = 'red';
                    info.el.style.borderColor = 'darkred';
                    titleElement.style.color = 'white';
                }
            },
            @can('scheduling.create')
                dateClick: function (info) {
                    window.location.href = `{{ route('orders.create') }}?scheduledDate=${info.dateStr}`;
                }
            @endcan
        });
        calendar.render();
    });
</script>
@endsection
