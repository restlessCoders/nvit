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

    <!-- Date and Time Picker -->
    <link href="{{asset('backend/libs/bootstrap-timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- Notification css (Toastr) -->
    <link href="{{asset('backend/libs/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Table datatable css -->
    <link href="{{asset('backend/libs/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/libs/datatables/select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />



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


        .navbar-custom {
            background-color: #fff;
            border-bottom: 2px solid #ddd;
        }

        #topnav .topbar-menu {
            margin-top: 0px;
        }

        #topnav .navbar-toggle span {
            background-color: #6c757d;
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
            <!-- Topbar Start -->
            <div class="navbar-custom d-md-none">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-right mb-0">

                        <li class="dropdown notification-list">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle nav-link">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>



                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{{asset('backend/images/users/avatar-1.jpg')}}" alt="user-image" class="rounded-circle">
                                <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow text-white m-0">Welcome ! {{ encryptor('decrypt', Session::get('username')) }}</h6>
                                </div>

                                <!-- item-->
                                <a href="{{route(currentUser().'.userProfile')}}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-outline"></i>
                                    <span>Profile</span>
                                </a>

                                <!-- item-->
                                {{--<a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <i class="mdi mdi-settings-outline"></i>
                                            <span>Settings</span>
                                        </a>--}}


                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <a class="dropdown-item notify-item" href="{{route('logOut')}}">
                                    <i class="mdi mdi-logout-variant"></i>
                                    <span>Logout</span>
                                </a>

                            </div>
                        </li>

                    </ul>

                    <!-- LOGO -->
                    <div class="logo-box">
                        <a href="index.html" class="logo text-center">
                            <span class="logo-lg">
                                <img src="{{asset('backend/images/logo.webp')}}" alt="" height="22">
                                <!-- <span class="logo-lg-text-dark">Uplon</span> -->
                            </span>
                            <span class="logo-sm">
                                <!-- <span class="logo-lg-text-dark">U</span> -->
                                <img src="{{asset('backend/images/logo.webp')}}" alt="" width="70px" height="45px">
                            </span>
                        </a>

                    </div>

                </div> <!-- end container-fluid-->
            </div>
            <!-- end Topbar -->
            <div class="topbar-menu">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <!-- LOGO -->
                        <div class="logo-box">
                            <a href="index.html" class="logo text-center">
                                <span class="logo-lg">
                                    <img src="{{asset('backend/images/logo.webp')}}" alt="" width="70px" height="45px">
                                    <!-- <span class="logo-lg-text-dark">Uplon</span> -->
                                </span>
                            </a>
                        </div>
                        <ul class="navigation-menu">
                            <li class="has-submenu @if(Request::segment(2) == 'dashboard') active @endif">
                                <a href="{{route(currentUser().'Dashboard')}}">
                                    <i class="mdi mdi-view-dashboard"></i>Dashboard
                                </a>
                            </li>

                            <li class="has-submenu @if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') @else d-none @endif">
                                <a href="#">
                                    <i class="mdi mdi-package-variant-closed"></i>Settings<div class="arrow-down"></div>
                                </a>
                                <ul class="submenu">
                                    <li class="has-submenu">
                                        <a href="#">Reference<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.reference.create')}} @endif">Add Reference</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.reference.index')}} @endif">All Reference</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Courses <div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.course.create')}} @endif">Add New</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.course.index')}} @endif">All Courses</a></li>
                                        </ul>
                                        <a href="#">Bundle Courses <div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.bundelcourse.create')}} @endif">Add New</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.bundelcourse.index')}} @endif">All Bundle Courses</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Course Package <div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.package.create')}} @endif">Add New</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.package.index')}} @endif">All Package</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Class Room<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.classroom.create')}} @endif">Add New</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.classroom.index')}} @endif">All Classroom</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Batch Slot <div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.batchslot.create')}} @endif">Add New</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.batchslot.index')}} @endif">All Batcch Slot</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Batch Time <div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.batchtime.create')}} @endif">Add New</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.batchtime.index')}} @endif">All Batcch Time</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Division<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.division.create')}} @endif">Add Division</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.division.index')}} @endif">All Division</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">District<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.district.create')}} @endif">Add District</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.district.index')}} @endif">All District</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Upazila<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li class="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.upazila.create')}} @endif">Add Upazila</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {{route(currentUser().'.upazila.index')}} @endif">All Upazila</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu @if(Request::segment(2) == 'user') active @endif @if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') @else d-none @endif">
                                        <a href="#">
                                            <i class="mdi mdi-account-multiple-outline"></i>User<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') {{route(currentUser().'.allUser')}} @endif">All User</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') {{route(currentUser().'.addNewUserForm')}} @endif">Add New User</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>





                            <li class="has-submenu @if(Request::segment(2) == 'student') active @endif">
                                <a href="#">
                                    <i class="fas fa-users"></i>Students <div class="arrow-down"></div></a>
                                <ul class="submenu">
                                    <li class="@if(currentUser() == 'superadmin' || currentUser() == 'frontdesk' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive') @else d-none @endif"><a href="@if(currentUser() == 'superadmin' || currentUser() == 'frontdesk' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive') {{route(currentUser().'.addNewStudentForm')}} @endif">Add New</a></li>
                                    <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesexecutive' || currentUser() == 'salesmanager' ||currentUser() == 'frontdesk' ||currentUser() == 'operationmanager') {{route(currentUser().'.allStudent')}} @endif">All Students</a></li>
                                    <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.studentTransfer')}} @endif">Student Transfer</a></li>
                                    <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.studentTransferList')}} @endif">Student Transfer List</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#">
                                    <i class="ti-layout-grid3"></i>Batches <div class="arrow-down"></div></a>
                                <ul class="submenu">
                                    <li class="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') @else d-none @endif"><a href="@if(currentUser() == 'superadmin'  || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') {{route(currentUser().'.batch.create')}} @endif">Add New</a></li>
                                    <li><a href="{{route(currentUser().'.batch.index')}}">All Batch</a></li>
                                    <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.batchTransfer')}} @endif">Batch Transfer</a></li>
                                    <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager') {{route(currentUser().'.batchTransferList')}} @endif">Batch Transfer List</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu @if(currentUser() == 'accountmanager') @else d-none @endif">
                                <a href="#">
                                    <i class="ti-layout-grid3"></i>Payments <div class="arrow-down"></div></a>
                                <ul class="submenu">
                                    <li class="@if(currentUser() == 'accountmanager') @else d-none @endif"><a href="@if(currentUser() == 'accountmanager') {{route(currentUser().'.payment.index')}} @endif">Batch Payment</a></li>
                                    <li class="@if(currentUser() == 'accountmanager') @else d-none @endif"><a href="@if(currentUser() == 'accountmanager') {{route(currentUser().'.payments.index')}} @endif">Others Payment</a></li>
                                    <li class="@if(currentUser() == 'accountmanager') @else d-none @endif"><a href="@if(currentUser() == 'accountmanager') {{route(currentUser().'.payment-transfer.index')}} @endif">Payment Transfer</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#">
                                    <i class="mdi mdi-package-variant-closed"></i>Reports <div class="arrow-down"></div></a>
                                <ul class="submenu">

                                    <li class="has-submenu">
                                        <a href="#">Batch Enroll<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="{{route(currentUser().'.batchwiseEnrollStudent')}}">List</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu @if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive' || currentUser() == 'accountmanager') @else d-none @endif"">
                                        <a href="#">Course Enroll<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive' || currentUser() == 'accountmanager') {{route(currentUser().'.coursewiseEnrollStudent')}} @endif">List</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu @if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive' || currentUser() == 'accountmanager') @else d-none @endif">
                                        <a href="#">Accounts <div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive' || currentUser() == 'accountmanager') {{route(currentUser().'.daily_collection_report')}} @endif">Daily Collection Report (Executive)</a></li>
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive' || currentUser() == 'accountmanager') {{route(currentUser().'.daily_collection_report_by_mr')}} @endif">Daily Collection Report (Mr)</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Course Interest<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive') {{route(currentUser().'.coursewiseStudent')}} @endif">List</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu @if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') @else d-none @endif">
                                <a href="#">
                                    <i class="mdi mdi-hand"></i>Batch<div class="arrow-down"></div></a>
                                <ul class="submenu">
                                    <li class="has-submenu">
                                        <a href="#">Attendance<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') {{route(currentUser().'.batchwiseAttendance')}} @endif">Report</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Batch Completion Report<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager') {{route(currentUser().'.batchwiseAttendance')}} @endif">Report</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#">
                                    <i class="mdi mdi-certificate"></i>Certification<div class="arrow-down"></div></a>
                                <ul class="submenu">

                                    <li class="has-submenu">
                                        <a href="#">Certificate<div class="arrow-down"></div></a>
                                        <ul class="submenu">
                                            <li><a href="@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager' || currentUser() == 'salesexecutive') {{route(currentUser().'.batchwiseEnrollStudent')}} @endif">List</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                        </ul>


                        <div class="navbar-right d-none d-md-block">
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
                                        {{--<a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <i class="mdi mdi-settings-outline"></i>
                                            <span>Settings</span>
                                        </a>--}}


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

                <!-- <div class="p-4">
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

                    
                </div> -->
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
    <script src="{{asset('backend/libs/morris-js/morris.min.js')}}"></script>
    <script src="{{asset('backend/libs/raphael/raphael.min.js')}}"></script>

    <!-- Dashboard init js-->
    <script src="{{asset('backend/js/pages/dashboard.init.js')}}"></script>

    <!-- Toastr js -->
    <script src="{{asset('backend/libs/toastr/toastr.min.js')}}"></script>

    <!-- Datatable plugin js -->
    <script src="{{asset('backend/libs/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{asset('backend/libs/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables/responsive.bootstrap4.min.js')}}"></script>

    <script src="{{asset('backend/libs/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables/buttons.bootstrap4.min.js')}}"></script>

    <script src="{{asset('backend/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('backend/libs/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('backend/libs/pdfmake/vfs_fonts.js')}}"></script>

    <script src="{{asset('backend/libs/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables/buttons.print.min.js')}}"></script>

    <script src="{{asset('backend/libs/datatables/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('backend/libs/datatables/dataTables.select.min.js')}}"></script>

    <!-- Date and Time Picker plugins -->
    <script src="{{asset('backend/libs/moment/moment.min.js')}}"></script>
    <script src="{{asset('backend/libs/bootstrap-timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('backend/libs/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('backend/js/app.min.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('backend/js/custom.js')}}"></script>
    <!--begin::Page Scripts(used by this page)-->
    @stack('scripts')
    <!--end::Page Scripts-->
</body>

</html>