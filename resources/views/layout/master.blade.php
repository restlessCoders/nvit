<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="nvit" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{asset('backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-stylesheet" />
	<!-- Custom Style -->
	<link href="{{asset('backend/css/style.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .navbar-right .topnav-menu .nav-link {
            padding: 0 15px;
            color: #6e768e;
            min-width: 32px;
            display: block;
            line-height: 70px;
            text-align: center;
            max-height: 70px;
        }
    </style>
	<!--begin::Page Scripts(used by this page)-->
		@stack('styles')
	<!--end::Page Scripts-->
</head>

<body>
    <div id="app">
        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-menu" style="margin-top:0px">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">
                            <li class="has-submenu">
                                <a href="">
                                    <i class="mdi mdi-view-dashboard"></i>Dashboard
                                </a>
                            </li>


                            <li class="has-submenu">
                                <a href="#">
                                    <i class="mdi mdi-package-variant-closed"></i>Students <div class="arrow-down"></div></a>
                                <ul class="submenu">
                                    <li><a href="">Add New</a></li>
                                    <li><a href="">All Students</a></li>
                                </ul>
                            </li>


                        </ul>
                        <div class="navbar-right">
                            <ul class="list-unstyled topnav-menu float-right mb-0">
                                <li class="dropdown notification-list">
                                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                        <img src="{{asset('backend/images/users/avatar-1.jpg')}}" alt="user-image" class="rounded-circle">
                                        <span class="d-none d-sm-inline-block ml-1 font-weight-medium">{{ encryptor('decrypt', Session::get('username')) }}</span>
                                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-39px, 70px, 0px);">
                                        <!-- item-->
                                        <div class="dropdown-header noti-title">
                                            <h6 class="text-overflow text-white m-0">Welcome !</h6>
                                        </div>

                                        <!-- item-->
                                        <a href="{{route(currentUser().'.userProfile')}}" class="dropdown-item notify-item">
                                            <i class="mdi mdi-account-outline"></i>
                                            <span>Profile</span>
                                        </a>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <i class="mdi mdi-settings-outline"></i>
                                            <span>Settings</span>
                                        </a>


                                        <div class="dropdown-divider"></div>

                                        <!-- item-->
                                        <a class="dropdown-item notify-item" href="{{route('logOut')}}">
                                            <i class="mdi mdi-logout-variant"></i>
                                            <span>Logout</span>
                                        </a>

                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- End navigation menu -->

                        <div class="clearfix"></div>
                    </div>
                    <!-- end #navigation -->
                </div>
                <!-- end container -->
            </div>
            <!-- end navbar-custom -->

        </header>
        <!-- End Navigation Bar-->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container-fluid">
				@yield('content')
            </div> <!-- end container-fluid -->
        </div>
        <!-- end wrapper -->

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->



        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        {{date('Y')}} &copy; All Rights Reserved <a href="">New Vision Information Technology</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div class="rightbar-title">
                <a href="javascript:void(0);" class="right-bar-toggle float-right">
                    <i class="mdi mdi-close"></i>
                </a>
                <h4 class="font-18 m-0 text-white">New Vision Information Technology</h4>
            </div>
            <div class="slimscroll-menu">

                <div class="p-4">
                    <div class="alert alert-warning" role="alert">
                        <strong>Customize </strong> the overall color scheme, layout, etc.
                    </div>
                    <div class="mb-2">
                        <img src="assets/images/layouts/light.png" class="img-fluid img-thumbnail" alt="">
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input theme-choice" id="light-mode-switch" checked />
                        <label class="custom-control-label" for="light-mode-switch">Light Mode</label>
                    </div>

                    <div class="mb-2">
                        <img src="assets/images/layouts/dark.png" class="img-fluid img-thumbnail" alt="">
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input theme-choice" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css" data-appStyle="assets/css/app-dark.min.css" />
                        <label class="custom-control-label" for="dark-mode-switch">Dark Mode</label>
                    </div>

                    <div class="mb-2">
                        <img src="assets/images/layouts/rtl.png" class="img-fluid img-thumbnail" alt="">
                    </div>
                    <div class="custom-control custom-switch mb-5">
                        <input type="checkbox" class="custom-control-input theme-choice" id="rtl-mode-switch" data-appStyle="assets/css/app-rtl.min.css" />
                        <label class="custom-control-label" for="rtl-mode-switch">RTL Mode</label>
                    </div>

                    
                </div>
            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <!-- <div class="rightbar-overlay"></div>

        <a href="javascript:void(0);" class="right-bar-toggle demos-show-btn">
            <i class="mdi mdi-settings-outline mdi-spin"></i> &nbsp;Choose Demos
        </a> -->
    </div>
    <!-- Vendor js -->
    <script src="{{asset('backend/js/vendor.min.js')}}"></script>

    <!--Morris Chart-->
    <!-- <script src="{{asset('backend/libs/morris-js/morris.min.js')}}"></script> -->
    <!-- <script src="{{asset('backend/libs/raphael/raphael.min.js')}}"></script> -->

    <!-- Dashboard init js-->
    <!-- <script src="{{asset('backend/js/pages/dashboard.init.js')}}"></script> -->

    <!-- App js -->
    <script src="{{asset('backend/js/app.min.js')}}"></script>
	<!-- App js -->
    <script src="{{asset('backend/js/custom.js')}}"></script>
	<!--begin::Page Scripts(used by this page)-->
		@stack('scripts')
	<!--end::Page Scripts-->
</body>

</html>