@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.notices'))

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <input type="text" data-kt-notice-filter="search"
                                   class="form-control form-control-solid w-250px ps-14"
                                   placeholder="@lang('dashboard.search_notices')" />
                        </div>
                    </div>
                    <div class="card-toolbar">
{{--                        @can('notices.create')--}}
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEditModal" data-mode="create">
                                @lang('dashboard.add_notice')
                            </button>
{{--                        @endcan--}}
                    </div>
                </div>
                <div class="card-body pt-0">
{{--                    @include('dashboard.partials.alerts')--}}
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_notices_table">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>@lang('dashboard.customer')</th>
                                <th>@lang('dashboard.notice_order')</th>
                                <th>@lang('dashboard.notice_type')</th>
                                <th>@lang('dashboard.notice')</th>
                                <th>@lang('dashboard.notice_created_by')</th>
                                <th>@lang('dashboard.date')</th>
                                <th class="text-end">@lang('dashboard.actions')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notices as $notice)
                                <tr>
                                    <td>{{ $notice->customer->name }}</td>
                                    <td>
                                        @if($notice->order)
                                            <a href="{{ route('orders.edit', $notice->order_id) }}">#{{ $notice->order_id }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{  $notice->type ? $notice->type->name : '-'  }}</td>
                                    <td>{{ Str::limit($notice->notice, 70) }}</td>
                                    <td>{{ $notice->creator->name }}</td>
                                    <td>{{ $notice->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light btn-show-notice"
                                                data-notice-id="{{ $notice->id }}"
                                                title="@lang('dashboard.show')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        {{--                                    @can('notices.edit')--}}
                                        <button class="btn btn-sm btn-light btn-edit-notice"
                                                data-notice-id="{{ $notice->id }}"
                                                data-mode="edit"
                                                title="@lang('dashboard.edit')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        {{--                                    @endcan--}}
                                        {{--                                    @can('notices.destroy')--}}
                                        <button class="btn btn-sm btn-danger btn-delete-notice"
                                                data-notice-id="{{ $notice->id }}"
                                                title="@lang('dashboard.delete')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        {{--                                    @endcan--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $notices->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.notices.create')
    @include('dashboard.notices.show')

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for customer and order selects
            $('#createEditModal select[name="customer_id"]').select2({
                placeholder: "@lang('dashboard.select_customer')",
                dropdownParent: $('#createEditModal'),
                width: '100%'
            });

            {{--$('#createEditModal select[name="order_id"]').select2({--}}
            {{--    placeholder: "@lang('dashboard.select_order')",--}}
            {{--    dropdownParent: $('#createEditModal'),--}}
            {{--    width: '100%'--}}
            {{--});--}}

            // Initialize create/edit modal based on mode
            $('#createEditModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const mode = button.data('mode');
                const modal = $(this);

                if (mode === 'create') {
                    modal.find('.modal-title').text('@lang("dashboard.add_notice")');
                    modal.find('form').attr('action', '{{ route("notices.store") }}');
                    modal.find('input[name="_method"]').remove();
                    modal.find('form')[0].reset();
                    modal.find('select[name="order_id"]').html('<option value="">@lang("dashboard.select_order")</option>');
                    modal.find('.modal-mode-indicator').text('@lang("dashboard.save_changes")');
                } else {
                    const noticeId = button.data('notice-id');
                    modal.find('.modal-title').text('@lang("dashboard.edit_notice")');
                    modal.find('form').attr('action', '/notices/' + noticeId);
                    if (!modal.find('input[name="_method"]').length) {
                        modal.find('form').append('@method("PUT")');
                    }
                    modal.find('.modal-mode-indicator').text('@lang("dashboard.save_changes")');

                    // Load notice data via AJAX
                    $.get('/notices/' + noticeId + '/edit', function(data) {
                        modal.find('select[name="customer_id"]').val(data.customer_id);

                        // Update orders dropdown
                        const orderSelect = modal.find('select[name="order_id"]');
                        orderSelect.empty();
                        orderSelect.append('<option value="">@lang("dashboard.select_order")</option>');
                        $.each(data.orders, function(key, order) {
                            orderSelect.append('<option value="' + order.id + '" ' +
                                (order.id == data.order_id ? 'selected' : '') + '>#' + order.id + '</option>');
                        });

                        modal.find('textarea[name="notice"]').val(data.notice);
                    });
                }
            });

            // Show notice button handler
            $('.btn-show-notice').click(function() {
                const noticeId = $(this).data('notice-id');
                $('#showModal .modal-body').load('/notices/' + noticeId, function() {
                    $('#showModal').modal('show');
                });
            });

            // Edit notice button handler
            $('.btn-edit-notice').click(function() {
                const noticeId = $(this).data('notice-id');
                const modal = $('#createEditModal');

                // Show modal immediately
                modal.modal('show');

                // Set modal title and form action
                modal.find('.modal-title').text('@lang("dashboard.edit_notice")');
                modal.find('form').attr('action', '/notices/' + noticeId);
                modal.find('input[name="_method"]').remove();
                modal.find('form').append('@method("PUT")');
                modal.find('.modal-mode-indicator').text('@lang("dashboard.save_changes")');

                // Load notice data via AJAX
                $.get('/notices/' + noticeId + '/edit', function(data) {
                    // Set customer
                    modal.find('select[name="customer_id"]').val(data.notice.customer_id);

                    // Update orders dropdown
                    const orderSelect = modal.find('select[name="order_id"]');
                    orderSelect.empty();
                    orderSelect.append('<option value="">@lang("dashboard.select_order")</option>');

                    $.each(data.orders, function(key, order) {
                        orderSelect.append(
                            $('<option></option>')
                                .val(order.id)
                                .text('#' + order.id)
                                .prop('selected', order.id == data.notice.order_id)
                        );
                    });

                    // Set notice text
                    modal.find('textarea[name="notice"]').val(data.notice.notice);
                }).fail(function() {
                    modal.find('.modal-body').html('<div class="alert alert-danger">Failed to load notice data</div>');
                });
            });

            // Customer change event to load orders
            $(document).on('change', '#createEditModal select[name="customer_id"]', function() {
                const customerId = $(this).val();
                const modal = $('#createEditModal');
                const currentOrderId = modal.find('select[name="order_id"]').val();

                if (customerId) {
                    $.get('/notices/get-customer-orders/' + customerId, function(data) {
                        const orderSelect = modal.find('select[name="order_id"]');
                        orderSelect.empty();
                        orderSelect.append('<option value="">@lang("dashboard.select_order")</option>');

                        $.each(data, function(key, order) {
                            orderSelect.append(
                                $('<option></option>')
                                    .val(order.id)
                                    .text('#' + order.id)
                                    .prop('selected', order.id == currentOrderId)
                            );
                        });
                    });
                } else {
                    modal.find('select[name="order_id"]').empty()
                        .append('<option value="">@lang("dashboard.select_order")</option>');
                }
            });

            // Delete notice
            $('.btn-delete-notice').click(function() {
                if (!confirm('@lang("dashboard.are_you_sure_delete_notice")')) {
                    return false;
                }

                const noticeId = $(this).data('notice-id');
                const url = '/notices/' + noticeId;
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: token
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('@lang("dashboard.delete_failed")');
                    }
                });
            });
        });
    </script>
@endsection
