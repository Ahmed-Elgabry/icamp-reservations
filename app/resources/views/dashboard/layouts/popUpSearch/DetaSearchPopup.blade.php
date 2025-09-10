<!-- نافذة البحث -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">بحث عن المصروفات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('expenses.index') }}" method="GET">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">تاريخ البداية</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">تاريخ النهاية</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="mb-3 text-end">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
