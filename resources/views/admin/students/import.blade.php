@extends('admin.layouts.admin')

@section('content')

<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Import Students</h4>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            
            <li class="breadcrumb-item"><a href="{{route('admin.students.index')}}">Students</a></li>
            <li class="breadcrumb-item active"><a href="">Import</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 col-xxl-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Import</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.moduleCompletion.import') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    >
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Program</label>
                        <select name="program_id" id="program_select" class="form-control" required>
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batch</label>
                        <select name="batch_id" id="batch_selecter" class="form-control" required diabled>
                            <option value="">Select Batch</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->batch_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Excel File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Import Students
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-xxl-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Messages</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mt-3">
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection