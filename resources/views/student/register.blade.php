<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Title -->
	<title>Register - Annual Award Ceremony</title>

	<!-- Meta -->
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="ESOFT Metro Campus Galle">
    <meta name="robots" content="index, follow">

    <meta name="keywords" content="ESOFT, ESOFT Metro Campus Galle, Annual Awards Ceremony 2026, graduation ceremony, awards ceremony, higher education Sri Lanka, diplomas, certificates, student achievements, academic awards, ESOFT events, campus events, education Sri Lanka">

    <meta name="description" content="The Annual Awards Ceremony 2026 by ESOFT Metro Campus Galle celebrates student achievements, academic excellence, and successful program completions. Join us in recognizing graduates and outstanding performers at this prestigious academic event.">

    <meta property="og:title" content="Annual Awards Ceremony 2026 | ESOFT Metro Campus Galle">
    <meta property="og:description" content="Celebrate academic excellence at the Annual Awards Ceremony 2026 by ESOFT Metro Campus Galle. Honoring graduates and student achievements across academic programs.">
    <meta property="og:image" content="{{ asset('images/event_icon.jpeg') }}">
    <meta property="og:type" content="website">

    <meta name="format-detection" content="telephone=no">

    <meta name="twitter:title" content="Annual Awards Ceremony 2026 | ESOFT Metro Campus Galle">
    <meta name="twitter:description" content="Join the Annual Awards Ceremony 2026 at ESOFT Metro Campus Galle to celebrate student success, academic milestones, and excellence in higher education.">
    <meta name="twitter:image" content="{{ asset('images/event_icon.jpeg') }}">
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
                                    <img src="/images/esoft-logo.jpg" alt="ESOFT" width="100" height="auto">                                                                                    
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