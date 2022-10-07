@extends('layout.master')
@section('title', 'Add New User')
@push('styles')
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
					<li class="breadcrumb-item active">Add</li>
				</ol>
			</div>
			<h4 class="page-title">Add New User</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<form action="{{ route(currentUser().'.addNewUser') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" value="{{ Session::get('user') }}" name="userId">
				<div class="form-group row">
					<div class="image-input image-input-outline" id="kt_image_1">
						<div class="image-input-wrapper" style="background-image: url({{asset('/')}}assets/media/users/blank.png)"></div>
						<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
							<i class="fa fa-pen icon-sm text-muted"></i>
							<input type="file" name="photo" accept=".png, .jpg, .jpeg" />
							<input type="hidden" name="profile_avatar_remove" />
						</label>
						<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
							<i class="ki ki-bold-close icon-xs text-muted"></i>
						</span>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>User Role: <span class="text-danger sup">*</span></label>
						<select name="role" class="js-example-basic-single form-control select2 @if($errors->has('role')) {{ 'is-invalid' }} @endif">
							<option></option>
							@if(count($roles) > 0)
							@foreach($roles as $role)
							<option value="{{ $role->id }}">{{ $role->type }}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('role'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('role') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Full Name: <span class="text-danger sup">*</span></label>
						<input type="text" name="fullName" value="{{ old('fullName') }}" class="form-control @if($errors->has('fullName')) {{ 'is-invalid' }} @endif" placeholder="Full Name" />
						@if($errors->has('fullName'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('fullName') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label class="control-label">Status: </label>
						<select name="status" class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
							<option value="1" selected>Active</option>
							<option value="0">Inactive</option>
						</select>
						@if($errors->has('status'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('status') }}
						</small>
						@endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Email: <span class="text-danger sup">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-envelope"></i>
								</span>
							</div>
							<input type="email" name="email" value="{{ old('email') }}" class="form-control @if($errors->has('email')) {{ 'is-invalid' }} @endif" placeholder="Enter a valid e-mail" />
						</div>
						@if($errors->has('email'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('email') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Mobile Number: <span class="text-danger sup">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-phone"></i>
								</span>
							</div>
							<input name="mobileNumber" required type="text" value="{{ old('mobileNumber') }}" class="form-control  @if($errors->has('email')) {{ 'is-invalid' }} @endif" placeholder="Enter only digits" />
						</div>
						@if($errors->has('mobileNumber'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('mobileNumber') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>NID:</label>
						<input type="text" name="nid" value="{{ old('nid') }}" class="form-control" placeholder="NID Number" />
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Username: <span class="text-danger sup">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-user"></i>
								</span>
							</div>
							<input type="text" required name="username" value="{{ old('username') }}" class="form-control @if($errors->has('username')) {{ 'is-invalid' }} @endif" placeholder="Username" />
						</div>
						@if($errors->has('username'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('username') }}
						</small>
						@endif
					</div>
					<div class="col-md-4">
						<label>Password <span class="text-danger sup">*</span></label>
						<div>
							<input type="password" required name="password" value="{{ old('password') }}" class="form-control @if($errors->has('password')) {{ 'is-invalid' }} @endif" placeholder="******" />
						</div>
						@if($errors->has('password'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('password') }}
						</small>
						@endif
					</div>
					<!--end form-group-->
					<div class="col-md-4">
						<label>Confirm Password</label>
						<div>
							<input type="password" name="password_confirmation" class="form-control" placeholder="******" required />
						</div>
					</div>
				</div>
				<div class="form-group text-right mb-0">
					<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
						Submit
					</button>
					<button type="reset" class="btn btn-secondary waves-effect">
						Cancel
					</button>
				</div>
		</div>
	</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script>
	$('.js-example-basic-single').select2({
  placeholder: 'Select a Role',
  allowClear: true
});
</script>
@endpush