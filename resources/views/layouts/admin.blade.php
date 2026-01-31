<!DOCTYPE html>
<html lang="en">
<head>
    @include('components.admin.head')
</head>
<body>
    @include('components.admin.preloader')

    <div id="main-wrapper">
        @include('components.admin.navbarico')

        @include('components.admin.header')

        @include('components.admin.navbar')

        <div class="content-body">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        @include('components.admin.footer')

        @include('components.admin.scripts')

    </div>
    
</body>
</html>