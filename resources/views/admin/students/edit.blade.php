@extends('admin.layouts.admin')

@section('content')


    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Edit Student : {{ $studentData['student']->student_id }}</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.students.index')}}">Students</a></li>
                <li class="breadcrumb-item active"><a href="">Edit Student</a></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9 col-xxl-9 col-sm-12">
            <div class="card">
                <form action="#" method="post">
                    <div class="card-header">
                        <h5 class="card-title">Basic Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="student_id">Student ID</label>
                                    <input id="student_id" name="student_id" type="text" class="form-control" value="{{ $studentData['student']->student_id }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="full_name">Full Name</label>
                                    <input id="full_name" name="full_name" type="text" class="form-control" value="{{ $studentData['student']->full_name }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <input id="email" type="email" name="email" class="form-control" value="{{ $studentData['student']->email }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone">Phone</label>
                                    <input id="phone" type="tel" name="phone" class="form-control" value="{{ $studentData['student']->phone }}" required>
                                </div>
                            </div>                  
                        </div>
                    </div>

                    <div class="card-header">
                        <h5 class="card-title">Event Registration
                            @if($eventPayment)
                                @if($eventPayment->status == "paid")
                                    <span class="badge bg-success">Paid</span>
                                @elseif($eventPayment->status == "pending")
                                    <span class="badge light badge-warning">Pending</span>
                                @elseif($eventPayment->status == "partial")
                                    <span class="badge light badge-success">Partial</span>
                                @else
                                    <span class="badge badge-light">{{$eventPayment->status}}</span>
                                @endif
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                @if(isset($eventData) && $eventData->seat)
                                    <h5>Registration Details</h5>

                                    <ul class="list-group">

                                        {{-- Seat number --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Seat Number</span>
                                            <span><strong>{{ $eventData->seat->seat_number }}</strong></span>
                                        </li>

                                        {{-- Event package --}}
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Event Package</span>
                                            <span>LKR {{ number_format(6500, 2) }}</span>
                                        </li>

                                        {{-- Additional seats --}}
                                        @if($eventData->seat->additional_seat_count > 0)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    Additional Seats
                                                    <span class="badge badge-circle badge-sm badge-primary ms-2">
                                                        {{ $eventData->seat->additional_seat_count }}
                                                    </span>
                                                </span>
                                                <span>
                                                    LKR {{ number_format($eventData->seat->price, 2) }}
                                                </span>
                                            </li>
                                        @endif

                                        {{-- Shuttle seats --}}
                                        @if($eventData->shuttleSeats && $eventData->shuttleSeats->shuttle_seat_count > 0)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    Shuttle Seats
                                                    <span class="badge badge-circle badge-sm badge-primary ms-2">
                                                        {{ $eventData->shuttleSeats->shuttle_seat_count }}
                                                    </span>
                                                </span>
                                                <span>
                                                    LKR {{ number_format($eventData->shuttleSeats->price, 2) }}
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                    <br>
                                @else
                                    <p>No event registration found.</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row">
                            @if ($eventPhotos)
                                <div class="col-sm-12">
                                    <h5>Photos ordered</h5>
                                    <ul class="list-group">
                                        @foreach ($eventPhotos as $item)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    {{ $item->name }}
                                                    <span class="badge badge-circle badge-sm badge-primary ms-2">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </span>
                                                <span>LKR {{ number_format($item->price,2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <br>
                                </div>
                            @else
                                <p>No photo packages found.</p>
                            @endif
                            
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                @if($eventPayment)
                                    <h5>Payments</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                Total
                                            </span>
                                            <span>
                                                LKR {{ number_format($eventPayment->amount, 2) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                Paid
                                            </span>
                                            <span>
                                                LKR {{ number_format($eventPayment->paid,2) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                Total Due
                                            </span>
                                            <span class="fw-bold">
                                                LKR {{ number_format($eventPayment->amount - $eventPayment->paid,2) }}
                                            </span>
                                        </li>
                                    </ul>
                                @else
                                    <p>No Payment found.</p>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="card-header">
                        <h5 class="card-title">Course Completion Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($studentData['programs'] as $program)
                                <div class="col-sm-12">
                                    <h4>{{ $program['program_name'] }}</h4>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Module</th>
                                                <th>Status</th>
                                                <th>Mark Complete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($program['modules'] as $module)
                                                <tr>
                                                    <td>{{ $module['module_order'] }}</td>
                                                    <td>{{ $module['module_name'] }}</td>
                                                    <td>
                                                        @if ($module['completed'])
                                                            <span class="badge bg-success">Completed</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!$module['completed'])
                                                            <input
                                                                type="checkbox"
                                                                name="modules[]"
                                                                value="{{ $module['module_id'] }}"
                                                            >
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                            
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="submit" class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Event History</h5>
                </div>
                <div class="card-body">
                    <div class="col-sm-12">
                        @if(isset($eventData) && $eventData->logs->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach ($eventData->logs as $log)
                                    <li class="list-group-item">
                                        <div class="fw-bold">{{ $log->action }}</div>
                                        <div class="text-muted small">
                                            {{ $log->description }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $log->created_at->format('d M Y, h:i A') }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No Event Data Found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection