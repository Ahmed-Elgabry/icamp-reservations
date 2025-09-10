<div class="modal fade" id="createEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">@lang('dashboard.customer') *</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">@lang('dashboard.select_customer')</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('dashboard.order')</label>
                            <select name="order_id" class="form-select">
                                <option value="">@lang('dashboard.select_order')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">@lang('dashboard.notice_type')</label>
                            <select name="notice_type_id" class="form-select">
                                <option value="">@lang('dashboard.select_notice_type')</option>
                                @foreach($noticeTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">@lang('dashboard.notice') *</label>
                        <textarea name="notice" class="form-control" rows="5" required
                                  placeholder="@lang('dashboard.notice_placeholder')"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('dashboard.cancel')</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="modal-mode-indicator">@lang('dashboard.save_changes')</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
