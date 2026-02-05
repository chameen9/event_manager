<div class="modal fade" id="addPhotosModal" tabindex="-10">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.photos.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="event_registration_id" value="{{ $eventData->id ?? '' }}">

                    @if ($eventPhotos && $eventPhotos->isNotEmpty())
                        @foreach ($eventPhotos as $photo)
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $photo->name }}
                                    <small class="text-muted">
                                        (LKR {{ number_format($photo->price, 2) }})
                                    </small>
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="photos[{{ $photo->photo_package_id }}]"
                                    min="0"
                                    value="{{ $photo->quantity }}"
                                    required
                                >
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning mb-3">
                            No photo packages found. You can add new ones below.
                        </div>

                        @foreach ($photoPackages as $package)
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $package->name }}
                                    <small class="text-muted">
                                        (LKR {{ number_format($package->price, 2) }})
                                    </small>
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="photos[{{ $package->id }}]"
                                    min="0"
                                    value="0"
                                    required
                                >
                            </div>
                        @endforeach
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Handled By</label>
                        <input type="text" class="form-control" name="handled_by" value="{{ auth()->user()->name }}" disabled>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Photos</button>
                </div>
            </form>
        </div>
    </div>
</div>