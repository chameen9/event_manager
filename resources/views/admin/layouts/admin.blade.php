<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>
<body>
    @include('admin.partials.preloader')

    <div id="main-wrapper">
        @include('admin.partials.navbarico')

        @include('admin.partials.header')

        @include('admin.partials.navbar')

        <div class="content-body">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        @include('admin.partials.footer')

        @include('admin.partials.scripts')

        @include('admin.partials.notifications')

    </div>
    
</body>
</html>