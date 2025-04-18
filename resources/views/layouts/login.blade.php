<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('edmate/assets/images/logo/favicon.png') }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/bootstrap.min.css') }}">
    <!-- file upload -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/file-upload.css') }}">
    <!-- file upload -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/plyr.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css') }}">
    <!-- full calendar -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/full-calendar.css') }}">
    <!-- jquery Ui -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/jquery-ui.css') }}">
    <!-- editor quill Ui -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/editor-quill.css') }}">
    <!-- apex charts Css -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/apexcharts.css') }}">
    <!-- calendar Css -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/calendar.css') }}">
    <!-- jvector map Css -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/jquery-jvectormap-2.0.5.css') }}">
    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/main.css') }}">
    @yield('style')
</head> 
<body>
    
<!--==================== Preloader Start ====================-->
  <div class="preloader">
    <div class="loader"></div>
  </div>
<!--==================== Preloader End ====================-->

<!--==================== Sidebar Overlay End ====================-->
<div class="side-overlay"></div>
<!--==================== Sidebar Overlay End ====================-->

    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="{{ asset('edmate/assets/images/thumbs/auth-img1.png') }}" alt="">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
           @yield('main') 
        </div>
    </section>

        <!-- Jquery js -->
    <script src="{{ asset('edmate/assets/js/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="{{ asset('edmate/assets/js/boostrap.bundle.min.js') }}"></script>
    <!-- Phosphor Js -->
    <script src="{{ asset('edmate/assets/js/phosphor-icon.js') }}"></script>
    <!-- file upload -->
    <script src="{{ asset('edmate/assets/js/file-upload.js') }}"></script>
    <!-- file upload -->
    <script src="{{ asset('edmate/assets/js/plyr.js') }}"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js') }}"></script>
    <!-- full calendar -->
    <script src="{{ asset('edmate/assets/js/full-calendar.js') }}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('edmate/assets/js/jquery-ui.js') }}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('edmate/assets/js/editor-quill.js') }}"></script>
    <!-- apex charts -->
    <script src="{{ asset('edmate/assets/js/apexcharts.min.js') }}"></script>
    <!-- Calendar Js -->
    <script src="{{ asset('edmate/assets/js/calendar.js') }}"></script>
    <!-- jvectormap Js -->
    <script src="{{ asset('edmate/assets/js/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <!-- jvectormap world Js -->
    <script src="{{ asset('edmate/assets/js/jquery-jvectormap-world-mill-en.js') }}"></script>
    
    <!-- main js -->
    <script src="{{ asset('edmate/assets/js/main.js') }}"></script>

    @yield('script')

    </body>
</html>