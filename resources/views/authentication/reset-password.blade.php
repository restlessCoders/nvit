@extends('layout.auth_master')

@section('title', 'Sign In')

@section('content')

<!-- Log In page -->
    <div class="row vh-100 ">
        <div class="col-12 align-self-center">
            <div class="auth-page">
                <div class="card auth-card shadow-lg">
                    <div class="card-body">
                        <div class="px-3">
                            <div class="auth-logo-box">
                                <a href="{{asset('/')}}analytics/analytics-index.html" class="logo logo-admin"><img src="{{asset('/')}}assets/images/logo-sm.png" height="55" alt="logo" class="auth-logo"></a>
                            </div><!--end auth-logo-box-->
                            
                            <div class="text-center auth-logo-text">
                                <h4 class="mt-0 mb-3 mt-5">Let's Get Started Metrica</h4>
                                <p class="text-muted mb-0">Sign in to continue to Metrica.</p>  
                            </div> <!--end auth-logo-text-->  

                            
                        <form class="form-horizontal auth-form my-4" action="{{route('resetPasswordForm')}}" method="POST">
                                @csrf
                                @if( Session::has('response') )
                                    <div class="alert alert-{{Session::get('response')['class']}}" role="alert">
                                        {{Session::get('response')['message']}}
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="userpassword">Password</label>                                            
                                    <div class="input-group mb-3"> 
                                        <span class="auth-form-icon">
                                            <i class="dripicons-lock"></i> 
                                        </span>                                                       
                                        <input type="password" name="password" value="{{old('password')}}" class="form-control @if($errors->has('password')) {{'is-invalid'}} @endif" id="userpassword" placeholder="Enter password">
                                    </div>    
                                    @if($errors->has('password'))
                                        <small class="d-block text-danger mb-3">
                                            {{ $errors->first('password') }}
                                        </small>     
                                    @endif                           
                                </div><!--end form-group--> 
    
                                <div class="form-group">
                                    <label for="userpassword">Confirm Password</label>                                            
                                    <div class="input-group mb-3"> 
                                        <span class="auth-form-icon">
                                            <i class="dripicons-lock"></i> 
                                        </span>                                                       
                                        <input type="password" name="password_confirmation" class="form-control" id="userpassword" placeholder="Enter confirm password">
                                    </div>                           
                                </div><!--end form-group--> 
    
                                <div class="form-group mb-0 row">
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">Reset Password <i class="fas fa-sign-in-alt ml-1"></i></button>
                                    </div><!--end col--> 
                                </div> <!--end form-group-->                           
                            </form><!--end form-->
                        </div><!--end /div-->
                    </div><!--end card-body-->
                </div><!--end card-->
                {{-- <div class="account-social text-center mt-4">
                    <h6 class="my-4">Or Login With</h6>
                    <ul class="list-inline mb-4">
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-facebook-f facebook"></i>
                            </a>                                    
                        </li>
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-twitter twitter"></i>
                            </a>                                    
                        </li>
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-google google"></i>
                            </a>                                    
                        </li>
                    </ul>
                </div> --}}
                <!--end account-social-->
            </div><!--end auth-page-->
        </div><!--end col-->           
    </div><!--end row-->
    <!-- End Log In page -->

@endsection
