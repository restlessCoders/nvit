<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Login|NVIT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="{{asset('backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="{{asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('backend/css/app.min.css')}}" rel="stylesheet" type="text/css"  id="app-stylesheet" />

    </head>

    <body class="authentication-bg">


<div class="account-pages pt-5 my-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="account-card-box">
                            <div class="card mb-0">
                                <div class="card-body p-4">
                                    
                                    <div class="text-center">
                                        <div class="my-3">
                                            <a href="index.html">
                                                <span><img src="{{asset('backend/images/logo.webp')}}" alt="" height="80"></span>
                                            </a>
                                        </div>
                                        <h5 class="text-muted text-uppercase py-3 font-16">Log In</h5>
                                    </div>
    
                                    <form action="{{route('logIn')}}" class="mt-2" method="post">
										@csrf
										@if( Session::has('response') )
											<div class="alert alert-{{Session::get('response')['class']}}" role="alert">
												{{Session::get('response')['message']}}
											</div>
										@endif
    
                                        <div class="form-group mb-3">
                                            <input class="form-control" type="text" placeholder="Enter your username" value="{{old('username')}}" name="username">
											@if($errors->has('username'))
												<small class="d-block text-danger mb-3">
													{{ $errors->first('username') }}
												</small>     
											@endif
                                        </div>
    
                                        <div class="form-group mb-3">
                                            <input class="form-control" type="password" id="password" placeholder="Enter your password" value="{{old('password')}}" name="password">
                                            @if($errors->has('password'))
												<small class="d-block text-danger mb-3">
													{{ $errors->first('password') }}
												</small>     
											@endif
                                        </div>
    
                                        <div class="form-group mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                                <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                            </div>
                                        </div>
    
                                        <div class="form-group text-center">
                                            <button class="btn btn-success btn-block waves-effect waves-light" type="submit"> Log In </button>
                                        </div>
                                        <a href="{{route('forgotPasswordForm')}}"><i class="mdi mdi-lock mr-1"></i> Forgot your password?</a>
                                        <a href="pages-recoverpw.html" class="text-muted"></a>
    
                                    </form>   
									                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-white-50">Don't have an account? <a href="{{route('signUpForm')}}" class="text-white ml-1"><b>Sign Up</b></a></p>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                                </div> <!-- end card-body -->
                            </div>
                            <!-- end card -->
                        </div>



                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->
		        <!-- Vendor js -->
        <script src="{{asset('backend/js/vendor.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('backend/js/app.min.js')}}"></script>
        
    </body>
</html>