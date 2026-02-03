<div class="modal fade" id="addPaymentModal" tabindex="-100">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.payments.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">New Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="event_registration_id" value="{{ $eventData->id ?? '' }}">

                    <div class="mb-3">
                        <label class="form-label">Amount <span class="required">*</span></label>
                        <input type="number" class="form-control" min="0" name="amount" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Method <span class="required">*</span></label>
                        <select name="method" id="method" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" maxlength="100" class="form-control" name="remarks">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Handled By</label>
                        <input type="text" class="form-control" name="handled_by" value="{{ auth()->user()->name }}" disabled>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>