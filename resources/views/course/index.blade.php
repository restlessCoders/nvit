@extends('layout.master')
@section('title', 'Courses List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Courses</a></li>
					<li class="breadcrumb-item active"><a href="{{route(currentUser().'.course.index')}}">All</a></li>
				</ol>
			</div>
			<h4 class="page-title">All Courses</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<ul class="pagination justify-content-end" >
			<form action="{{route(currentUser().'.courseSearch')}}" method="post" role="search" class="d-flex">
				@csrf
					<input type="text" placeholder="Search.." name="search" class="form-control">
					<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
				</form>
			</ul>
			<!-- <table class="responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;"> -->

			<table class="table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Course Name</th>
						<th>Course Description</th>
						<th>Regular Price</th>
						<th>Material Price</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allCourses))
					@foreach($allCourses as $course)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{$course->courseName}}</td>
						<td>{{$course->courseDescription}}</td>
						<td>{{$course->rPrice}}</td>
						<td>{{$course->mPrice}}</td>
						<td>
							@if($course->status == 1)
							<span>Active</span>
							@else
							<span>Inactive</span>
							@endif
						</td>
						<td>
							<a href="{{route(currentUser().'.course.edit',[encryptor('encrypt', $course->id)])}}" class="text-info"><i class="fas fa-edit"></i></a>
							<a href="{{route(currentUser().'.course.destroy',[encryptor('encrypt', $course->id)])}}" class="text-danger"><i class="fas fa-trash-alt"></i></a>
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="6">No Data Found</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $allCourses->appends(request()->all())->links() }}
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
	$('.responsive-datatable').DataTable();
</script>
@if(Session::has('response'))
<script>
	Command: toastr["{{Session::get('response')['errors']}}"]("{{Session::get('response')['message']}}")
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