@extends('admin.layouts.admin')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>All Students</h4>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            
            <li class="breadcrumb-item"><a href="{{route('admin.students.index')}}">Students</a></li>
            <li class="breadcrumb-item active"><a href="{{route('admin.students.index')}}">All Student</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="row tab-content">
            <div id="all" class="tab-pane fade active show col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{$studentCount}} Students</h4>
                        <div class="btn-group">
                            <a href="{{route('admin.students.showAdd')}}" class="btn btn-primary">+ Add new</a>
                            <a href="{{route('admin.students.showImport')}}" class="btn btn-dark">Import</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="responsiveTable" class="display responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student ID</th>
                                        <th>Full Name</th>
                                        <th>Program(s)</th>
                                        <th>Batch</th>
                                        <th>Status</th>
                                        <th>Phone</th>
                                        <th>Payments</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsData as $data)
                                        <tr>
                                            <td>{{ $data['student']->id }}</td>
                                            <td>{{ $data['student']->student_id }}</td>
                                            <td>
                                                {!! \Illuminate\Support\Str::limit(strip_tags($data['student']->full_name), 30) !!}
                                                <!-- {{ $data['student']->full_name }} -->
                                            </td>
                                            <td>
                                                @foreach ($data['programs'] as $program)
                                                    <div>
                                                        <strong>{{ $program['program_name'] }}</strong> :
                                                        @if ($program['completed'])
                                                            <span class="text-success">Completed</span>
                                                        @else
                                                            <span class="text-danger">
                                                                {{ $program['completed_modules'] }}/{{ $program['total_modules'] }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($data['batches'] as $batch)
                                                    <span class="badge badge-light">{{ $batch->batch_code }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if ($data['is_registered'])
                                                    <span class="badge bg-success">Registered</span>
                                                @else
                                                    <span class="badge badge-light">Not Registered</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data['student']->phone)
                                                    {{$data['student']->phone}}
                                                @else
                                                    <span class="badge badge-light">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($data['payment_status'])
                                                    @case('Paid')
                                                        <span class="badge bg-success">Paid</span>
                                                        @break

                                                    @case('Pending')
                                                        <span class="badge light badge-warning">Pending</span>
                                                        @break

                                                    @case('Partial')
                                                        <span class="badge light badge-success">Partial</span>
                                                        @break

                                                    @default
                                                        <span class="badge badge-light">Not Registered</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{route('admin.students.showEdit', $data['student']->id )}}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                                </div>												
                                            </td>	
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="registered" class="tab-pane fade col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Registered Students List</h4>
                        <a href="add-student.html" class="btn btn-primary">+ Add new</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display text-nowrap" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Profile</th>
                                        <th>Roll No.</th>
                                        <th>Name</th>
                                        <th>Education</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Admission Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection