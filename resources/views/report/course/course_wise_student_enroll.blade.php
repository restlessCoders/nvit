@extends('layout.master')
@section('title', 'Course Wise Studnet Enroll List')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Batch</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Course Enrolled Students</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<div class="col-md-12 text-center">
				<h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="p-0" style="font-size:16px"><strong>Course Enroll Report</strong></p>
			</div>

			<form action="{{route(currentUser().'.coursewiseEnrollStudent')}}" method="post" role="search">
				@csrf
				<div class="row">
					<div class="col-sm-3">
						<label for="batch_id" class="col-form-label">Select Course</label>
						<select name="course_id" class="js-example-basic-single form-control">
							<option></option>
							@forelse($courses as $c)
							<option value="{{$c->id}}">{{$c->courseName}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-3">
						<label for="executiveId" class="col-form-label">Select Executive</label>
						<select name="executiveId" class="js-example-basic-single form-control">
							<option></option>
							@forelse($executives as $e)
							<option value="{{$e->id}}">{{$e->username}}</option>
							@empty
							@endforelse
						</select>
					</div>
					@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesexecutive')
					<div class="col-sm-3">
						<label for="refId" class="col-form-label">Select Reference</label>
						<select name="refId" class="js-example-basic-single form-control">
							<option></option>
							@forelse($references as $ref)
							<option value="{{$ref->id}}">{{$ref->refName}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-3">
						<label for="status" class="col-form-label">Select Status</label>
						<select class="js-example-basic-single form-control" id="status" name="status">
							<option value=""></option>
							<option value="1">Paid</option>
							<option value="2">Due</option>
						</select>
					</div>
					@endif
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary mr-1"><i class="fa fa-search fa-sm"></i></button>
						<a href="{{route(currentUser().'.coursewiseEnrollStudent')}}" class="reset-btn btn btn-warning"><i class="fa fa-undo fa-sm"></i></a>
					</div>
				</div>
			</form>

			@if($courseInfo)
			<div class="col-md-12">
				<h4 class="text-center">{{$courseInfo->courseName}}</h4>
			</div>
			@endif
			<table class="table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>SL.</th>
						<th>S.Id</th>
						<th>Student Name</th>
						<th>Executive</th>
						@if(currentUser() == 'superadmin')
						<th>Reference</th>
						@endif
						<th>Course</th>
						<th width="120px">Date</th>
						<th>Price</th>
						<th>Status</th>
						<th width="120px">Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allCourses))
					@foreach($allCourses as $course)
					<form action="" method="POST" enctype="multipart/form-data">
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{$course->sId}}</td>
							<td>{{$course->sName}}</td>
							<td>{{$course->exName}}</td>
							@if(currentUser() == 'superadmin')
							<td>{{\DB::table('references')->where('id',$course->refId)->first()->refName}}</td>
							@endif
							<td>{{\DB::table('courses')->where('id',$course->course_id)->first()->courseName}}</td>
							<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($course->created_at))->format('j M, Y')}}</td>
							<td>{{$course->price}}</td>
							<td>
								@if($course->p_status == 1) Paid @endif
								@if($course->p_status == 2) Due @endif
							</td>
							<td>
								@if(strtolower(currentUser()) == 'accountmanager' && $course->p_status == 0)
									<a href="{{route(currentUser().'.payments.index')}}?sId={{$course->sId}}&systemId={{$course->systemId}}" class="btn btn-danger btn-sm"><i class="fas fa-edit mr-2"></i>Payment</a>
								@endif
							</td>
						</tr>
					</form>
					@endforeach
					@else
					<tr>
						<td colspan="6">No Data Found</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{$allCourses->links()}}
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script>
	$('.js-example-basic-single').select2({
		placeholder: 'Select Option',
		allowClear: true
	});
	$('.reset-btn').on('click', function() {
		$('.js-example-basic-single').val(null).trigger('change');
	});
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