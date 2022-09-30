@extends('layout.admin.admin_master')
@section('title', 'User List')
@section('content')
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
<!--end::Notice-->
<!--begin::Card-->
<div class="card card-custom">
	<div class="card-header flex-wrap border-0 pt-6 pb-0">
		<div class="card-title">
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">User</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">list</a>
				</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<div class="card-toolbar">
			<!--begin::Button-->
			<a href="{{route(currentUser().'.addNewUserForm')}}" class="btn btn-primary font-weight-bolder">
				<span class="svg-icon svg-icon-md">
					<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
						height="24px" viewBox="0 0 24 24" version="1.1">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<rect x="0" y="0" width="24" height="24" />
							<circle fill="#000000" cx="9" cy="15" r="6" />
							<path
								d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
								fill="#000000" opacity="0.3" />
						</g>
					</svg>
					<!--end::Svg Icon-->
				</span>New User</a>
			<!--end::Button-->
		</div>
	</div>
	<div class="card-body">
		<!--begin: Datatable-->
		<table class="table table-bordered table-hover table-checkable" id="kt_datatable">
			<thead class="thead-light">
				<tr>
					<th>Shop Code</th>
					<th>Name</th>
					<th>Contact No</th>
					<th>Email</th>
					<th>Role</th>
					<th>Division</th>
					<th>District</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@if(count($allUser))
				@foreach($allUser as $user)
				<tr>
					<td>@if($user->company){{$user->company->shopCode}}@endif</td>
					<td><img src="{{asset('/')}}assets/images/users/user-3.jpg" alt=""
							class="rounded-circle thumb-sm mr-1"> {{$user->name}}</td>
					<td>{{$user->mobileNumber}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->role->type}}</td>
					<td>@if($user->state){{$user->state->name}} @endif</td>
					<td>@if($user->zone){{$user->zone->name}} @endif</td>
					<td>
						@if($user->status == 1)
						<span class="badge badge-soft-success">Active</span>
						@else
						<span class="badge badge-soft-danger">Inactive</span>
						@endif
					</td>
					<td>
						<a href="{{route(currentUser().'.editUser',[Replace($user->name), encryptor('encrypt', $user->id)])}}"
							class="mr-2"><i class="fas fa-edit text-info font-16"></i></a>
						<a
							href="{{route(currentUser().'.deleteUser', [Replace($user->name), encryptor('encrypt', $user->id)])}}"><i
								class="fas fa-trash-alt text-danger font-16"></i></a>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		<!--end: Datatable-->
		<div class="d-flex align-items-center justify-content-between">
			{{$allUser->links()}}
		</div>
	</div>
</div>
@endsection