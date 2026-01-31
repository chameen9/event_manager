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
    <link rel="icon" type="image/png" sizes="16x16" href="https://esoft.lk/wp-content/uploads/2023/11/cropped-favicon-32x32-1.png">
	
	<!-- STYLESHEETS -->
	<link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link class="main-css" rel="stylesheet" href="css/style.css">

    <link rel="apple-touch-icon" href="https://esoft.lk/wp-content/uploads/2023/11/cropped-favicon-32x32-1.png">

</head>
<body>
    <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
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
            </div>
        </div>
    </div>
    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="vendor/global/global.min.js"></script>
	<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    
    <script src="js/custom.min.js"></script>
    <script src="js/dlabnav-init.js"></script>
    
    
    
</body>
</html>