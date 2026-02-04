<div class="modal fade" id="addEventLogModal" tabindex="-100">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.eventlog.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">New Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="event_registration_id" value="{{ $eventData->id ?? '' }}">

                    <div class="mb-3">
                        <label class="form-label">Comment <span class="required">*</span></label>
                        <textarea class="form-control" name="comment" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </div>
            </form>
        </div>
    </div>
</div>