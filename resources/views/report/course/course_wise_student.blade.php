@extends('layout.master')
@section('title', 'Course Wise Studnet List')
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
			<h4 class="page-title">All Course Students</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<div class="col-md-12 text-center">
				<h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="p-0" style="font-size:16px"><strong>Course Wise Report</strong></p>
			</div>

			<form action="{{route(currentUser().'.coursewiseStudent')}}" method="post" role="search">
				@csrf
				<div class="row">
					<div class="col-sm-3">
						<label for="course_id" class="col-form-label">Select Course</label>
						<select name="course_id" class="js-example-basic-single form-control" required>
							<option value="">Select Course</option>
							@forelse($courses as $course)
							<option value="{{$course->id}}">{{$course->courseName}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-3">
						<label for="executiveId" class="col-form-label">Select Executive</label>
						<select name="executiveId" class="js-example-basic-single form-control">
							<option value="">Select Executive</option>
							@forelse($executives as $e)
							<option value="{{$e->id}}">{{$e->name}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-3">
						<label for="refId" class="col-form-label">Select Reference</label>
						<select name="refId" class="js-example-basic-single form-control">
							<option value="">Select Reference</option>
							@forelse($references as $ref)
							<option value="{{$ref->id}}">{{$ref->refName}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
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
						<th>Student Name</th>
						<th>Executive</th>
						<th>Reference</th>
						<th>Course</th>
					</tr>
				</thead>
				<tbody>
					@if(count($courses_pre))
					@foreach($courses_pre as $course)
					<tr>
						<td>{{$loop->iteration}}</td>
						<td>{{$course->sName}}</td>
						<td>{{$course->exName}}</td>
						<td>{{\DB::table('references')->where('id',$course->refId)->first()->refName}}</td>
						<td>{{\DB::table('courses')->where('id',$course->course_id)->first()->courseName}}</td>
						<!--<td>{{--\Carbon\Carbon::createFromTimestamp(strtotime($course->created_at))->format('j M, Y')--}}</td>-->
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="6">No Data Found</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
	$('.responsive-datatable').DataTable();
</script>
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script>
	$('.js-example-basic-single').select2();
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