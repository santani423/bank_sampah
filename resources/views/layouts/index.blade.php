<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title> Bank Sampah</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('edmate/assets/images/logo/favicon.png') }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/bootstrap.min.css') }}">
    <!-- file upload -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/file-upload.css') }}">
    <!-- file upload -->
    <link rel="stylesheet" href="{{ asset('edmate/assets/css/plyr.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
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

    <!-- ============================ Sidebar Start ============================ -->

<aside class="sidebar">
    <!-- sidebar close btn -->
     <button type="button" class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i class="ph ph-x"></i></button>
    <!-- sidebar close btn -->
    
    <a href="index.html" class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="{{ asset('edmate/assets/images/logo/logo.png') }}" alt="Logo">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            <ul class="sidebar-menu">
                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-squares-four"></i></span>
                        <span class="text">Dashboard</span>
                        <span class="link-badge">3</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item">
                            <a href="index.html" class="sidebar-submenu__link"> Dashboard One </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="index-2.html" class="sidebar-submenu__link"> Dashboard Two </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="index-3.html" class="sidebar-submenu__link"> Dashboard Three </a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-graduation-cap"></i></span>
                        <span class="text">Courses</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item">
                            <a href="student-courses.html" class="sidebar-submenu__link"> Student Courses </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="mentor-courses.html" class="sidebar-submenu__link"> Mentor Courses </a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="create-course.html" class="sidebar-submenu__link"> Create Course </a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                <li class="sidebar-menu__item">
                    <a href="students.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users-three"></i></span>
                        <span class="text">Students</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="assignment.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-clipboard-text"></i></span>
                        <span class="text">Assignments</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="mentors.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Mentors</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="resources.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-bookmarks"></i></span>
                        <span class="text">Resources</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="message.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-chats-teardrop"></i></span>
                        <span class="text">Messages</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="analytics.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-chart-bar"></i></span>
                        <span class="text">Analytics</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="event.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-calendar-dots"></i></span>
                        <span class="text">Events</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="library.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-books"></i></span>
                        <span class="text">Library</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="pricing-plan.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-coins"></i></span>
                        <span class="text">Pricing</span>
                    </a>
                </li>
                
                <li class="sidebar-menu__item">
                    <span class="text-gray-300 text-sm px-20 pt-20 fw-semibold border-top border-gray-100 d-block text-uppercase">Settings</span>
                </li>
                <li class="sidebar-menu__item">
                    <a href="setting.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-gear"></i></span>
                        <span class="text">Account Settings</span>
                    </a>
                </li>

                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-shield-check"></i></span>
                        <span class="text">Authetication</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item">
                            <a href="sign-in.html" class="sidebar-submenu__link">Sign In</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="sign-up.html" class="sidebar-submenu__link">Sign Up</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="forgot-password.html" class="sidebar-submenu__link">Forgot Password</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="reset-password.html" class="sidebar-submenu__link">Reset Password</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="verify-email.html" class="sidebar-submenu__link">Verify Email</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="two-step-verification.html" class="sidebar-submenu__link">Two Step Verification</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>
                
            </ul>
        </div>
        <div class="p-20 pt-80">
            <div class="bg-main-50 p-20 pt-0 rounded-16 text-center mt-74">
                <span class="border border-5 bg-white mx-auto border-primary-50 w-114 h-114 rounded-circle flex-center text-success-600 text-2xl translate-n74">
                    <img src="{{ asset('edmate/assets/images/icons/certificate.png') }}" alt="" class="centerised-img">
                </span>
                <div class="mt-n74">
                    <h5 class="mb-4 mt-22">Get Pro Certificate</h5>
                    <p class="">Explore 400+ courses with lifetime members</p>
                    <a href="pricing-plan.html" class="btn btn-main mt-16 rounded-pill">Get Access</a>
                </div>
            </div>
        </div>
    </div>

</aside>    
<!-- ============================ Sidebar End  ============================ -->

    <div class="dashboard-main-wrapper">
        <div class="top-navbar flex-between gap-16">

    <div class="flex-align gap-16">
        <!-- Toggle Button Start -->
         <button type="button" class="toggle-btn d-xl-none d-flex text-26 text-gray-500"><i class="ph ph-list"></i></button>
        <!-- Toggle Button End -->
        
        <form action="#" class="w-350 d-sm-block d-none">
            <div class="position-relative">
                <button type="submit" class="input-icon text-xl d-flex text-gray-100 pointer-event-none"><i class="ph ph-magnifying-glass"></i></button> 
                <input type="text" class="form-control ps-40 h-40 border-transparent focus-border-main-600 bg-main-50 rounded-pill placeholder-15" placeholder="Search...">
            </div>
        </form>
    </div>

    <div class="flex-align gap-16">
        <div class="flex-align gap-8">
            <!-- Notification Start -->
            <div class="dropdown">
                <button class="dropdown-btn shaking-animation text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="position-relative">
                        <i class="ph ph-bell"></i>
                        <span class="alarm-notify position-absolute end-0"></span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                    <div class="card border border-gray-100 rounded-12 box-shadow-custom p-0 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="py-8 px-24 bg-main-600">
                                <div class="flex-between">
                                    <h5 class="text-xl fw-semibold text-white mb-0">Notifications</h5>
                                    <div class="flex-align gap-12">
                                        <button type="button" class="bg-white rounded-6 text-sm px-8 py-2 hover-text-primary-600"> New </button>
                                        <button type="button" class="close-dropdown hover-scale-1 text-xl text-white"><i class="ph ph-x"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-24 max-h-270 overflow-y-auto scroll-sm">
                                <div class="d-flex align-items-start gap-12">
                                    <img src="{{ asset('edmate/assets/images/thumbs/notification-img1.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
                                    <div class="border-bottom border-gray-100 mb-24 pb-24">
                                        <div class="flex-align gap-4">
                                            <a href="#" class="fw-medium text-15 mb-0 text-gray-300 hover-text-main-600 text-line-2">Ashwin Bose is requesting access to Design File - Final Project. </a>
                                            <!-- Three Dot Dropdown Start -->
                                            <div class="dropdown flex-shrink-0">
                                                <button class="text-gray-200 rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ph-fill ph-dots-three-outline"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                                    <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                                        <div class="card-body p-12">
                                                            <div class="max-h-200 overflow-y-auto scroll-sm pe-8">
                                                                <ul>
                                                                    <li class="mb-0">
                                                                        <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                            <span class="text">Mark as read</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="mb-0">
                                                                        <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                            <span class="text">Delete Notification</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="mb-0">
                                                                        <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                            <span class="text">Report</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Three Dot Dropdown End -->
                                        </div>
                                        <div class="flex-align gap-6 mt-8">
                                            <img src="{{ asset('edmate/assets/images/icons/google-drive.png') }}" alt="">
                                            <div class="flex-align gap-4">
                                                <p class="text-gray-900 text-sm text-line-1">Design brief and ideas.txt</p>
                                                <span class="text-xs text-gray-200 flex-shrink-0">2.2 MB</span>
                                            </div>
                                        </div>
                                        <div class="mt-16 flex-align gap-8">
                                            <button type="button" class="btn btn-main py-8 text-15 fw-normal px-16">Accept</button>
                                            <button type="button" class="btn btn-outline-gray py-8 text-15 fw-normal px-16">Decline</button>
                                        </div>
                                        <span class="text-gray-200 text-13 mt-8">2 mins ago</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-12">
                                    <img src="{{ asset('edmate/assets/images/thumbs/notification-img2.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
                                    <div class="">
                                        <a href="#" class="fw-medium text-15 mb-0 text-gray-300 hover-text-main-600 text-line-2">Patrick added a comment on Design Assets - Smart Tags file:</a>
                                        <span class="text-gray-200 text-13">2 mins ago</span>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="py-13 px-24 fw-bold text-center d-block text-primary-600 border-top border-gray-100 hover-text-decoration-underline"> View All </a>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Notification Start -->
            
            <!-- Language Start -->
            <div class="dropdown">
                <button class="text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ph ph-globe"></i>
                </button>
                <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                    <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                        <div class="card-body">
                            <div class="max-h-270 overflow-y-auto scroll-sm pe-8">
                                <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                  <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="arabic"> 
                                    <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8"> 
                                      <img src="{{ asset('edmate/assets/images/thumbs/flag1.png') }}" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                      <span class="text-15 fw-semibold mb-0">Arabic</span>
                                    </span>
                                  </label>
                                  <input class="form-check-input" type="radio" name="language" id="arabic">
                                </div>
                                <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                  <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="germany"> 
                                    <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8"> 
                                      <img src="{{ asset('edmate/assets/images/thumbs/flag2.png') }}" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                      <span class="text-15 fw-semibold mb-0">Germany</span>
                                    </span>
                                  </label>
                                  <input class="form-check-input" type="radio" name="language" id="germany">
                                </div>
                                <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                  <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="english"> 
                                    <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8"> 
                                      <img src="{{ asset('edmate/assets/images/thumbs/flag3.png') }}" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                      <span class="text-15 fw-semibold mb-0">English</span>
                                    </span>
                                  </label>
                                  <input class="form-check-input" type="radio" name="language" id="english">
                                </div>
                                <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0">
                                  <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="spanish"> 
                                    <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8"> 
                                      <img src="{{ asset('edmate/assets/images/thumbs/flag4.png') }}" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                      <span class="text-15 fw-semibold mb-0">Spanish</span>
                                    </span>
                                  </label>
                                  <input class="form-check-input" type="radio" name="language" id="spanish">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Language Start -->
        </div>


        <!-- User Profile Start -->
        <div class="dropdown">
            <button class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="position-relative">
                    <img src="{{ asset('edmate/assets/images/thumbs/user-img.png') }}" alt="Image" class="h-32 w-32 rounded-circle">
                    <span class="activation-badge w-8 h-8 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                    <div class="card-body">
                        <div class="flex-align gap-8 mb-20 pb-20 border-bottom border-gray-100">
                            <img src="{{ asset('edmate/assets/images/thumbs/user-img.png') }}" alt="" class="w-54 h-54 rounded-circle">
                            <div class="">
                                <h4 class="mb-0">{{ Auth::user()->name }}</h4>
                                <p class="fw-medium text-13 text-gray-200">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <ul class="max-h-270 overflow-y-auto scroll-sm pe-4">
                            <li class="mb-4">
                                <a href="setting.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-gear"></i></span>
                                    <span class="text">Account Settings</span>
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="pricing-plan.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chart-bar"></i></span>
                                    <span class="text">Upgrade Plan</span>
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="analytics.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chart-line-up"></i></span>
                                    <span class="text">Daily Activity</span>
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="message.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chats-teardrop"></i></span>
                                    <span class="text">Inbox</span>
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="email.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-envelope-simple"></i></span>
                                    <span class="text">Email</span>
                                </a>
                            </li>
                            <li class="pt-8 border-top border-gray-100">
                                <a href="sign-in.html" class="py-12 text-15 px-20 hover-bg-danger-50 text-gray-300 hover-text-danger-600 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-danger-600 d-flex"><i class="ph ph-sign-out"></i></span>
                                    <span class="text">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- User Profile Start -->

    </div>
</div>

        
        <div class="dashboard-body">
            <!-- Breadcrumb Start -->
<div class="breadcrumb mb-24">
    <ul class="flex-align gap-4">
        <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
        <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
        <li><span class="text-main-600 fw-normal text-15">Pricing Plan</span></li>
    </ul>
</div>
<!-- Breadcrumb End -->
             
            <div class="card mt-24">
    <div class="card-header border-bottom">
        <h4 class="mb-4">Pricing Breakdown</h4>
        <p class="text-gray-600 text-15">Creating a detailed pricing plan for your course requries considering various factors.</p>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <div class="col-md-4 col-sm-6">
                <div class="plan-item rounded-16 border border-gray-100 transition-2 position-relative">
                    <span class="text-2xl d-flex mb-16 text-main-600"><i class="ph ph-package"></i></span>
                    <h3 class="mb-4">Basic Plan</h3>
                    <span class="text-gray-600">Perfect plan for students</span>
                    <h2 class="h1 fw-medium text-main mb-32 mt-16 pb-32 border-bottom border-gray-100 d-flex gap-4">
                        $50 <span class="text-md text-gray-600">/year</span>
                    </h2>
                    <ul>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Intro video the course
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Interactive quizes 
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Course curriculum
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Community supports
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Certificate of completion
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Sample lesson showcasing
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Access to course community
                        </li>
                    </ul>
                    <a href="#" class="btn btn-outline-main w-100 rounded-pill py-16 border-main-300 text-17 fw-medium mt-32">Get Started</a>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="plan-item rounded-16 border border-gray-100 transition-2 position-relative active">
                    <span class="plan-badge py-4 px-16 bg-main-600 text-white position-absolute inset-inline-end-0 inset-block-start-0 mt-8 text-15">Recommended</span>
                    <span class="text-2xl d-flex mb-16 text-main-600"><i class="ph ph-planet"></i></span>
                    <h3 class="mb-4">Standard Plan</h3>
                    <span class="text-gray-600">For users who want to do more</span>
                    <h2 class="h1 fw-medium text-main mb-32 mt-16 pb-32 border-bottom border-gray-100 d-flex gap-4">
                        $129 <span class="text-md text-gray-600">/year</span>
                    </h2>

                    <ul>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Intro video the course
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Interactive quizes 
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Course curriculum
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Community supports
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Certificate of completion
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Sample lesson showcasing
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Access to course community
                        </li>
                    </ul>
                    <a href="#" class="btn btn-main w-100 rounded-pill py-16 border-main-600 text-17 fw-medium mt-32">Get Started</a>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="plan-item rounded-16 border border-gray-100 transition-2 position-relative">
                    <span class="text-2xl d-flex mb-16 text-main-600"><i class="ph ph-trophy"></i></span>
                    <h3 class="mb-4">Premium Plan</h3>
                    <span class="text-gray-600">Your entire friends in one place</span>
                    <h2 class="h1 fw-medium text-main mb-32 mt-16 pb-32 border-bottom border-gray-100 d-flex gap-4">
                        $280 <span class="text-md text-gray-600">/year</span>
                    </h2>

                    <ul>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Intro video the course
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Interactive quizes 
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Course curriculum
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Community supports
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Certificate of completion
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Sample lesson showcasing
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Access to course community
                        </li>
                    </ul>
                    <a href="#" class="btn btn-outline-main w-100 rounded-pill py-16 border-main-300 text-17 fw-medium mt-32">Get Started</a>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label mb-8 h6 mt-32">Terms & Policy</label>
                <ul class="list-inside">
                    <li class="text-gray-600 mb-4">1. Set up multiple pricing levels with different features and functionalities to maximize revenue</li>
                    <li class="text-gray-600 mb-4">2. Continuously test different price points and discounts to find the sweet spot that resonates with your target audience</li>
                    <li class="text-gray-600 mb-4">3. Price your course based on the perceived value it provides to students, considering factors</li>
                </ul>
                <button type="button" class="btn btn-main text-sm btn-sm px-24 rounded-pill py-12 d-flex align-items-center gap-2 mt-24" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="ph ph-plus me-4"></i>
                    Add New Plan 
                </button>
            </div>
        </div>
    </div>
</div>
           
        </div>
        <div class="dashboard-footer">
    <div class="flex-between flex-wrap gap-16">
        <p class="text-gray-300 text-13 fw-normal"> &copy; Copyright Edmate 2024, All Right Reserverd</p>
        <div class="flex-align flex-wrap gap-16">
            <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">License</a>
            <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">More Themes</a>
            <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">Documentation</a>
            <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">Support</a>
        </div>
    </div>
</div>
    </div>
        
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



    </body>
</html>