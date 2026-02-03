<div class="modal fade" id="addSeatModal" tabindex="-10">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            @if($additionalSeatsAvailable || $eventData->seat)
                <form method="POST" action="{{ route('admin.additionalSeats.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Additional Seats</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="event_registration_id" value="{{ $eventData->id ?? '' }}">

                        <div class="mb-3">
                            <label class="form-label">Current Seats</label>
                            <input type="number" class="form-control" name="new_additional_seat_count" value="{{ $eventData->seat->additional_seat_count }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Handled By</label>
                            <input type="text" class="form-control" name="handled_by" value="{{ auth()->user()->name }}" disabled>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Seats</button>
                    </div>
                </form>
            @else
                <div class="modal-header">
                        <h5 class="modal-title">Additional Seats</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-warning">
                            No Additional Seats Available
                        </div>
                    </div>
            @endif
        </div>
    </div>
</div>