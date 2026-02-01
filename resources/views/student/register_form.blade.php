<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Title -->
	<title>Register - Annual Award Ceremony</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="dexignlabs">
	<meta name="robots" content="index, follow">

	<meta name="keywords" content="admin, dashboard, admin dashboard, admin template, template, admin panel, administration, analytics, bootstrap, modern, responsive, creative, retina ready, modern Dashboard responsive dashboard, responsive template, user experience, user interface, Bootstrap Dashboard, Analytics Dashboard, Customizable Admin Panel, EduMin template, ui kit, web app, EduMin, School Management,Dashboard Template, academy, course, courses, e-learning, education, learning, learning management system, lms, school, student, teacher">   

	<meta name="description" content="EduMin - Empower your educational institution with the all-in-one Education Admin Dashboard Template. Streamline administrative tasks, manage courses, track student performance, and gain valuable insights with ease. Elevate your education management experience with a modern, responsive, and feature-packed solution. Explore EduMin now for a smarter, more efficient approach to education administration.">

	<meta property="og:title" content="EduMin - Education Admin Dashboard Template | dexignlabs">
	<meta property="og:description" content="EduMin - Empower your educational institution with the all-in-one Education Admin Dashboard Template. Streamline administrative tasks, manage courses, track student performance, and gain valuable insights with ease. Elevate your education management experience with a modern, responsive, and feature-packed solution. Explore EduMin now for a smarter, more efficient approach to education administration.">
	
	<meta property="og:image" content="https://edumin.dexignlab.com/xhtml/social-image.png">

	<meta name="format-detection" content="telephone=no">

	<meta name="twitter:title" content="EduMin - Education Admin Dashboard Template | dexignlabs">
	<meta name="twitter:description" content="EduMin - Empower your educational institution with the all-in-one Education Admin Dashboard Template. Streamline administrative tasks, manage courses, track student performance, and gain valuable insights with ease. Elevate your education management experience with a modern, responsive, and feature-packed solution. Explore EduMin now for a smarter, more efficient approach to education administration.">

	<meta name="twitter:image" content="https://edumin.dexignlab.com/xhtml/social-image.png">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="https://esoft.lk/wp-content/uploads/2023/11/cropped-favicon-32x32-1.png"
	
	<!-- STYLESHEETS -->
	<link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- Form step -->
    <link href="vendor/jquery-smartwizard/dist/css/smart_wizard.min.css" rel="stylesheet">
    <link class="main-css" rel="stylesheet" href="css/style.css">

    <link rel="apple-touch-icon" href="https://esoft.lk/wp-content/uploads/2023/11/cropped-favicon-32x32-1.png">


</head>
<body>
    <div class="fix-wrapper">
        <div class="container">
            <!-- <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="card mb-0 h-auto">
                        <div class="card-body">
                            <div class="text-center mb-2">
                                <a href="{{url('/')}}">
                                    <img src="/images/esoft-logo.jpg" alt="" width="100" height="auto">                                                                                    
                                </a>
                            </div>
                            <h4 class="text-center mb-4">Annual Awards Ceremony 2026</h4>
                            <form action="{{ route('student.checkEligibility') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="studentID">Student ID</label>
                                    <input type="text" class="form-control" placeholder="Ex: E123456" id="studentID" name="studentID" minlength="7" maxlength="7" required>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-block">Check Eligibility Status</button>
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                @if (session('success'))
                                    <div class="alert alert-success mt-3">
                                        <div style="text-align: center;">{{ session('success') }}</div>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger mt-3">
                                        <div style="text-align: center;">{{ session('error') }}</div>
                                    </div>
                                @endif
                                <p style="text-align: center; font-size: 11px;">Please enter the Student ID issued by the ESOFT Metro Campus Galle to verify eligibility for the Annual Awards Ceremony 2026.
                                    <br><br>This verification confirms academic completion and eligibility status on diploma programs only.</p>
                                <p style="text-align: center; font-size: 14px; font-style: 'Arial'; font-weight: bold; text-transform: uppercase;">
                                    ESOFT Metro Campus Galle
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- row -->
            <div class="row">
                <div class="col-xl-12 col-xxl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-2">
                                <a href="{{ route('student.register') }}">
                                    <img src="/images/esoft-logo.jpg" alt="ESOFT" width="100" height="auto">                                                                                    
                                </a>
                            </div>
                            <h4 class="text-center mb-4">Annual Awards Ceremony 2026</h4>
                            <div id="smartwizard" class="form-wizard order-create">
                                <ul class="nav nav-wizard">
                                    <li><a class="nav-link" href="#student_details"> 
                                        <span>1</span> 
                                    </a></li>
                                    <li><a class="nav-link" href="#course_completion">
                                        <span>2</span>
                                    </a></li>
                                    <li><a class="nav-link" href="#payments">
                                        <span>3</span>
                                    </a></li>
                                    <li><a class="nav-link" href="#summary">
                                        <span>4</span>
                                    </a></li>
                                </ul>
                                <form action="{{route('student.register.submit')}}" method="post">
                                @csrf
                                    <input type="hidden" name="student_id" value="{{$student->student_id}}">
                                    <input type="hidden" name="full_name" value="{{$student->full_name}}">
                                    <div class="tab-content">
                                        <div id="student_details" class="tab-pane" role="tabpanel">
                                            <div class="row">
                                                <h4 class="mb-4">Student Details</h4>
                                                <div class="col-lg-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="text-label form-label">Student ID<span class="required">*</span></label>
                                                        <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="{{ $student->student_id }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="text-label form-label">Full Name<span class="required">*</span></label>
                                                        <input type="text" name="full_name" class="form-control" placeholder="Full Name" value="{{ $student->full_name }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="text-label form-label">Email Address<span class="required">*</span></label>
                                                        <input type="text" name="email" class="form-control" placeholder="example@example.com" value="{{ old('email', $student->email) }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="text-label form-label">Phone Number<span class="required">*</span></label>
                                                        <input type="text" maxlength="10" name="phone" class="form-control" placeholder="07XXXXXXXX" value="{{ old('phone', $student->phone) }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <div class="mb-3">
                                                        <p>Please note that these details only use for the Awarding Ceremony 2026. and these details won't affect your certificates / academic qualifications.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="course_completion" class="tab-pane" role="tabpanel">
                                            <div class="row">
                                                <h4 class="mb-4">Course Completion : {{ $student->student_id }}</h4>

                                                @foreach ($registrationsData as $data)
                                                    <div class="col-lg-6 mb-3">

                                                        <div class="card-header">
                                                            <p style="font-size: 16px;">
                                                                <strong>{{ $data['program_name'] }}</strong>
                                                                
                                                            </p>
                                                                @if ($data['isEligible'])
                                                                    <div class="alert alert-success">
                                                                        Completed
                                                                    </div>
                                                                @else
                                                                    <div class="alert alert-danger">
                                                                        Incomplete
                                                                    </div>
                                                                @endif
                                                        </div>

                                                        <div class="card-body">

                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Module Name</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($data['modules'] as $module)
                                                                        <tr>
                                                                            <td>{{ $module['module_order'] }}</td>
                                                                            <td>{{ $module['name'] }}</td>
                                                                            <td>
                                                                                @if ($module['completed'])
                                                                                    <span class="badge bg-success">Completed</span>
                                                                                @else
                                                                                    <span class="badge bg-warning">Pending</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                @endforeach

                                                @if ($hasIncompletePrograms)
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="alert alert-warning mt-4">
                                                            <div class="form-check">
                                                                <input
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    id="completion_acknowledgement"
                                                                    name="completion_acknowledgement"
                                                                    {{ old('completion_acknowledgement') ? 'checked' : '' }}
                                                                >
                                                                <label class="form-check-label" for="completion_acknowledgement">
                                                                    <div style="text-align: justify;">
                                                                        <strong>Declaration</strong><br>
                                                                        I confirm that I am willing to complete all remaining modules on or before
                                                                        <strong>15 March 2026</strong>.
                                                                        I am aware that failure to complete the pending modules by this date will
                                                                        result in non-issuance of official photographs and the academic transcript.
                                                                    </div>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div id="payments" class="tab-pane" role="tabpanel">
                                            <div class="row align-items-center">
                                                <h4 class="mb-4">Payment Details</h4>
                                                <div class="row">
                                                    <div class="col-sm-4 mb-2">
                                                        <span><strong>Package</strong><span class="required">*</span></span>
                                                    </div>
                                                    <div class="col-8 col-sm-5 mb-2">
                                                        <span>
                                                            <ul>
                                                                <li>1 Seat for Student</li>
                                                                <li>1 Seat for Parent/Guardian</li>
                                                                <li>1 Stage Photo (12x8" Landscape)</li>
                                                                <li>2 Refreshment packs</li>
                                                            </ul>
                                                        </span>
                                                    </div>
                                                    <div class="col-4 col-sm-3 mb-2">
                                                        <span class="float-end" id="package_total">LKR. 6500.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row align-items-center">
                                                <div class="row">
                                                    <div class="col-sm-4 mb-2">
                                                        <span><strong>Photos</strong></span>
                                                    </div>
                                                    <div class="col-8 col-sm-5 mb-2">
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-6 col-form-label" style="font-weight: normal;">12x8" Burst Photo</label>
                                                            <div class="col-sm-3">
                                                                <input type="number" class="form-control" id="burst_photo_count" name="burst_photo_count" value="{{ old('burst_photo_count', 0) }}" min="0" max="10" required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 row">
                                                            <label class="col-sm-6 col-form-label" style="font-weight: normal;">12x8" Full Photo</label>
                                                            <div class="col-sm-3">
                                                                <input type="number" class="form-control" id="full_photo_count" name="full_photo_count" value="{{ old('full_photo_count', 0) }}" min="0" max="10" required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 row">
                                                            <label class="col-sm-6 col-form-label" style="font-weight: normal;">12x8" Familiy Photo</label>
                                                            <div class="col-sm-3">
                                                                <input type="number" class="form-control" id="family_photo_count" name="family_photo_count" value="{{ old('family_photo_count', 0) }}" min="0" max="10" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 row">
                                                            <p class="text-muted">LKR. 1000 will be added for each photo</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-sm-3 mb-2">
                                                        <span id="photo_total" class="float-end">LKR. 0.00</span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <hr>
                                            <div class="row align-items-center">
                                                <div class="row">
                                                    <div class="col-sm-4 mb-2">
                                                        <span><strong>Additional Seats</strong></span>
                                                    </div>
                                                    <div class="col-8 col-sm-5 mb-2">
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-6 col-form-label" style="font-weight: normal;">Seat Count</label>
                                                            <div class="col-sm-3">
                                                                <input type="number" class="form-control" id="additional_seat_count" name="additional_seat_count" value="{{ old('additional_seat_count', 0) }}" min="0" max="5" required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 row">
                                                            <p class="text-muted">LKR. 1500 will be added for each additional seat</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-sm-3 mb-2">
                                                        <span id="additional_seat_total" class="float-end">LKR. 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row align-items-center">
                                                <div class="row">
                                                    <div class="col-sm-4 mb-2">
                                                        <span><strong>Shuttle Service</strong></span>
                                                    </div>
                                                    <div class="col-8 col-sm-5 mb-2">
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-6 col-form-label" style="font-weight: normal;">Seat Count</label>
                                                            <div class="col-sm-3">
                                                                <input type="number" class="form-control" id="shuttle_seat_count" name="shuttle_seat_count" value="{{ old('shuttle_seat_count', 0) }}" min="0" max="7" required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 row">
                                                            <p class="text-muted">LKR. 1000 will be added for each seat in shuttle service</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-sm-3 mb-2">
                                                        <span id="shuttle_seat_total" class="float-end">LKR. 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row align-items-center">
                                                <div class="row">
                                                    <div class="col-sm-12 mb-2">
                                                        <span id="final_amount" class="fw-bold float-end" style="font-size: 18px;">LKR. 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="final_amount_input" name="final_amount" value="{{ old('final_amount', 0) }}">

                                        </div>
                                        <div id="summary" class="tab-pane" role="tabpanel">

                                            <div class="row">
                                                <h4 class="mb-4">Summary</h4>


                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h5 class="mb-2">Student Details</h5>

                                                        <ul class="list-group">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Student ID</span>
                                                                <span>{{ $student->student_id }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Full Name</span>
                                                                <span>{{ $student->full_name }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Phone Number</span>
                                                                <span>{{ $student->phone }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Email Address</span>
                                                                <span>{{ $student->email }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h5 class="mb-2">Course Completion Status</h5>

                                                        <ul class="list-group">
                                                            @foreach ($registrationsData as $data)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <span>{{ $data['program_name'] }}</span>

                                                                    @if ($data['isEligible'])
                                                                        <span class="badge bg-success">Completed</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Incomplete</span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <h5 class="mb-2">Payment Summary</h5>

                                                        <ul class="list-group">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Total Amount Payable</span>
                                                                <span>
                                                                    <strong><span id="final_amount_2">0.00</span></strong>
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <div class="alert alert-info" style="text-align: justify;">
                                                            <p class="mb-2">
                                                                A confirmation email will be sent to your registered email address after successful submission.
                                                            </p>

                                                            <p class="mb-2">
                                                                If all registered programs are completed, your eligibility will be confirmed in the email along
                                                                with further event details.
                                                            </p>

                                                            <p class="mb-2">
                                                                If one or more programs are incomplete, a <strong>conditional offer letter</strong> will be sent to you
                                                                via email. You are required to download, print, and sign the document and submit it together with the
                                                                payment at the campus.
                                                            </p>

                                                            <p class="mb-0">
                                                                Event tickets, reporting time, seating information, and other important instructions will also be
                                                                communicated via email.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        @if (session('success'))
                                                            <div class="alert alert-success mt-3">
                                                                <div style="text-align: center;">{{ session('success') }}</div>
                                                            </div>
                                                        @endif
                                                        @if (session('error'))
                                                            <div class="alert alert-danger mt-3">
                                                                <div style="text-align: center;">{{ session('error') }}</div>
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

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row mt-4">
                                                        <div class="col-12 text-center">
                                                            <button type="submit" class="btn btn-primary px-5">
                                                                Confirm & Register
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('student.partials.register_scripts')
    
</body>
</html>