
<div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
	<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('assets/media/bg/bg-3.jpg');">
		<div class="login-form text-center p-7 position-relative overflow-hidden">
			<!--begin::Login Header-->
			<div class="d-flex flex-center mb-15">
				<a href="#">
					<img src="{{asset('/')}}assets/media/logos/short_logo.png" class="max-h-75px" alt="" />
				</a>
			</div>
			<!--end::Login Header-->
			<!--begin::Login forgot password form-->
			<div class="login-forgot">
				<div class="mb-20">
					<h3>Forgotten Password ?</h3>
					<div class="text-muted font-weight-bold">Enter your email to reset your password</div>
				</div>
				<form class="form" action="{{route('forgotPassword')}}" method="POST">
					@csrf
					<div class="form-group mb-10">
						<input type="email" name="email" value="{{old('email')}}" class="form-control form-control-solid h-auto py-4 px-8 @if($errors->has('email')) {{'is-invalid'}} @endif" id="useremail" placeholder="Enter Email">
					</div>
					<div class="form-group d-flex flex-wrap flex-center mt-10">
						<button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Request</button>
						<a href="{{route('signInForm')}}" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">Sign In</a>
					</div>
				</form>
			</div>
			<!--end::Login forgot password form-->
		</div>
	</div>
</div>