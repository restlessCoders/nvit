@extends('layout.master')
@section('title', 'Regular Course Batch Assign')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Regular Course</a></li>
					<li class="breadcrumb-item active">Batch Assign</li>
				</ol>
			</div>
			<h4 class="page-title">Regular Course Batch Assign</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<form action="" method="POST" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<input type="hidden" value="{{ Session::get('user') }}" name="userId">
				<div class="form-group row">
					<div class="col-lg-12">
						<table class="table table-sm table-bordered">
							<thead>
								<tr>
									<th>Student ID</th>
									<th>Student Name</th>
									<th>Enroll Course</th>
									<th>Select Batch</th>
									<th>Assign Batch</th>
								</tr>
								<tr>
									<td>{{$enroll_data->student_id}}</td>
									<td>
										{{\DB::table('students')->where('id',$enroll_data->student_id)->first()->name}}
									</td>
									<td>
										{{\DB::table('courses')->where('id',$enroll_data->course_id)->first()->courseName}}
									</td>
									<td>
										<select name="batch_id" class="js-example-basic-single form-control select2 @if($errors->has('batch_id')) {{ 'is-invalid' }} @endif">
											<option></option>
											@if(count($batches) > 0)
											@foreach($batches as $b)
											<option value="{{ $b->id }}" {{ old('batch_id',$b->id) == $enroll_data->batch_id ? "selected" : "" }}>{{ $b->batchId }}</option>
											@endforeach
											@endif
										</select>
										@if($errors->has('batch_id'))
										<small class="d-block text-danger mb-3">
											{{ $errors->first('batch_id') }}
										</small>
										@endif
									</td>
									<td>
									<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
						Assign Batch
					</button>
									</td>
								</tr>
							</thead>
						</table>
					</div>

				</div>
		</div>
	</div>
	@endsection
	@push('scripts')
	<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
	<script>
		$('.js-example-basic-single').select2({
			placeholder: 'Select Option',
			allowClear: true
		});
	</script>
	@endpush