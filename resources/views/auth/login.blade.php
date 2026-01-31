<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

                        <h4 class="text-center mb-4">Event Manager</h4>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                    autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-4 position-relative">
                                <label class="form-label">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-row d-flex flex-wrap justify-content-between mt-4 mb-2">
                                <div class="form-group">
                                    <div class="form-check custom-checkbox ms-1">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            id="remember"
                                            name="remember"
                                        >
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group ms-2">
                                    @if (Route::has('password.request'))
                                        <a class="btn-link" href="{{ route('password.request') }}">
                                            Forgot Password?
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Sign In
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template Scripts -->
<script src="{{ asset('vendor/global/global.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/dlabnav-init.js') }}"></script>
</body>
</html>