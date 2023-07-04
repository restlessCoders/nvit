@extends('layout.master')
@section('title', 'User List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Users</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<ul class="d-flex justify-content-end">
				<form action="{{route(currentUser().'.allUser')}}" role="search" class="d-flex">
					@csrf
					<input type="text" placeholder="Search.." name="search" class="form-control">
					<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
				</form>

			</ul>



			<!-- <form action="{{route(currentUser().'.allUser')}}" role="search">
				@csrf
				<div class="row justify-content-end">
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Status</label>
						<select name="type" class="js-example-basic-single form-control me-3">
							<option value="">Status</option>
							<option value="1">Active</option>
							<option value="0">Inactive</option>
						</select>
					</div>
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
						<a href="{{route(currentUser().'.allUser')}}" class="reset-btn btn btn-warning"><i class="fa fa-undo fa-sm"></i></a>
					</div>
				</div>
			</form> -->




			<table id="" class="table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Role</th>
						<th>Branch</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allUser))
					@foreach($allUser as $user)
					<tr>
						<td>{{$user->name}}</td>
						<td>{{$user->email}}</td>
						<td>{{$user->mobileNumber}}</td>
						<td>{{$user->role->type}}</td>
						<td></td>
						<td>
							@if($user->status == 1)
							<span class="text-info">Active</span>
							@else
							<span class="text-danger">Inactive</span>
							@endif
						</td>
						<td>
							<a href="{{route(currentUser().'.editUser',[Replace($user->name), encryptor('encrypt', $user->id)])}}" class="mr-2"><i class="far fa-edit text-info"></i></a>
							<a href="{{route(currentUser().'.deleteUser', [Replace($user->name), encryptor('encrypt', $user->id)])}}"><i class="far fa-trash-alt text-danger"></i></a>
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			{{$allUser->links()}}
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
	$('#responsive-datatable').DataTable();
</script>
@if(Session::has('response'))
<script>
	Command: toastr["success"]("{{Session::get('response')['message']}}")
	toastr.options = {
		"closeButton": false,
		"debug": false,
		"newestOnTop": false,
		"progressBar": false,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}
</script>
@endif
@endpush