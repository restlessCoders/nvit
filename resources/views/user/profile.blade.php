@extends('layout.master')
@section('title', 'User Profile')
@section('content')
<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">{{ encryptor('decrypt', Session::get('username')) }}</a></li>
					<li class="breadcrumb-item active">Profile</li>
				</ol>
			</div>
			<h4 class="page-title">Dashboard</h4>
		</div>
		<div class="card-box">
			<div class="col-lg-12">
				<div class="mt-4">
					<!--begin::Notice-->
					@if( Session::has('response') )
					<div class="alert alert-custom alert-{{Session::get('response')['class']}} alert-shadow gutter-b" role="alert">
						<div class="alert-icon">
							<i class="flaticon2-bell-4"></i>
						</div>
						<div class="alert-text">
							{{Session::get('response')['message']}}
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
					@endif
					<!--<h4 class="header-title mb-4">Default Tabs</h4>-->

					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-expanded="true" aria-selected="true">Profile Overview</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile">Edit Profile</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password">Change Password</a>
						</li>
					</ul>
					<div class="tab-content text-muted" id="myTabContent">
						<div role="tabpanel" class="tab-pane fade in active show" id="home" aria-labelledby="home-tab">

							<div class="col-lg-6">
								<div class="card">
									<div class="row no-gutters align-items-center">
										<div class="col-md-5">
											@php
											$photo= $UserData->details->photo
											@endphp
											@if($photo)
											<img class="card-img" src="{{asset('storage/images/user/photo/'.$photo)}})">
											@else
											<img class="card-img" src="{{asset('backend/images/small/img-12.jpg')}}" alt="Card image cap">
											@endif

										</div>
										<div class="col-md-7">
											<div class="card-body py-2">
												<h5 class="card-title">User Name : {{ encryptor('decrypt', Session::get('username')) }}</h5>
												<p class="card-text">Role : {{currentUser()}}<br />
													Email: {{ $UserData->email }}<br />
													Phone: {{ $UserData->mobileNumber }}<br />
													Location: {{ $UserData->address }}</p>
												<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
											</div>
										</div>
									</div>
								</div>

							</div>

						</div>
						<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<div class="mt-4">
								<div class="col-lg-6">

									<h4 class="header-title mb-4">Update your Profile Information</h4>
									<form action="{{route(currentUser().'.changePer')}}" method="post" enctype="multipart/form-data">
										@csrf
										<input type="hidden" name="id" value="{{ encryptor('encrypt', $UserData->id) }}">
										<div class="form-group">
											<label for="photo">Photo</label>
											<input type="file" name="photo" accept=".png, .jpg, .jpeg" />
										</div>
										<div class="form-group">
											<label for="name">Name</label>
											<input type="text" class="form-control" id="name" name="name" value="{{$UserData->name}}">
											@if($errors->has('name'))
											<small class="d-block text-danger mb-3">
												{{ $errors->first('name') }}
											</small>
											@endif
										</div>
										<div class="form-group">
											<label for="email">Email address</label>
											<input type="email" class="form-control" id="email" placeholder="email@example.com" name="email" value="{{$UserData->email}}">
											@if($errors->has('email'))
											<small class="d-block text-danger mb-3">
												{{ $errors->first('email') }}
											</small>
											@endif
										</div>
										<div class="form-group">
											<label for="address">Address</label>
											<input type="text" class="form-control" id="address" name="address" value="{{$UserData->details->address}}">
										</div>
										<div class="form-group">
											<label for="mobileNumber">Mobile Number</label>
											<input type="text" class="form-control" id="mobileNumber" name="mobileNumber" value="{{$UserData->mobileNumber}}">
											@if($errors->has('mobileNumber'))
											<small class="d-block text-danger mb-3">
												{{ $errors->first('mobileNumber') }}
											</small>
											@endif
										</div>
										<div class="form-group">
											<label for="username">UserName</label>
											<input type="text" class="form-control" id="username" name="username" value="{{$UserData->username}}">
											@if($errors->has('username'))
											<small class="d-block text-danger mb-3">
												{{ $errors->first('username') }}
											</small>
											@endif
										</div>
										<button type="submit" class="btn btn-primary">Update Profile</button>
									</form>

								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">

							<div class="col-lg-6">

								<h4 class="header-title mb-4">Change your account password</h4>
								<form class="form" action="{{route(currentUser().'.changePass')}}" method="post">
									@csrf
									<input type="hidden" name="id" value="{{ encryptor('encrypt', $UserData->id) }}">
									<div class="form-group">
										<label for="oldpass">Current Password</label>
										<input type="password" class="form-control" id="oldpass" name="oldpass" value="{{old('oldpass')}}">

									</div>
									<div class="form-group">
										<label for="pass">New Password</label>
										<input type="password" class="form-control" id="pass" name="pass" value="{{old('pass')}}">

										@if($errors->has('pass'))
										<small class="d-block text-danger mb-3">
											{{ $errors->first('pass') }}
										</small>
										@endif

									</div>

									<div class="form-group">
										<label for="cpass">Confirm Password</label>
										<input type="password" class="form-control" id="cpass" name="cpass" value="{{old('pass')}}" required>

										@if($errors->has('cpass'))
										<small class="d-block text-danger mb-3">
											{{ $errors->first('cpass') }}
										</small>
										@endif

									</div>
									<button type="submit" class="btn btn-success mr-2">Save Changes</button>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end page title -->

	@endsection