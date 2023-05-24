@extends('layout.master')
@section('title', 'Add New Bundel Course')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Bundel Course</a></li>
					<li class="breadcrumb-item active"><a href="{{ route(currentUser().'.course.index') }}">List</a></li>
				</ol>
			</div>
			<h4 class="page-title">Add New Bundel Course</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<form action="{{ route(currentUser().'.course.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" value="{{ Session::get('user') }}" name="userId">


				<div class="form-group row">
				<div class="col-lg-4">
						<label>Select Bundel Course: <span class="text-danger sup">*</span></label>
						<select name="courseId" class="js-example-basic-single form-control select2 @if($errors->has('courseId')) {{ 'is-invalid' }} @endif">
							<option></option>
							@if(count($allCourses) > 0)
							@foreach($allCourses as $course)
							<option value="{{ $course->id }}" {{ old('courseId') == $course->id ? "selected" : "" }}>{{ $course->courseName }}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('courseId'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('courseId') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Regular Price: <span class="text-danger sup">*</span></label>
						<input type="text" name="rPrice" value="{{ old('rPrice') }}" class="form-control @if($errors->has('rPrice')) {{ 'is-invalid' }} @endif" placeholder="Regular Price" />
						@if($errors->has('rPrice'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('rPrice') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Installment Price: <span class="text-danger sup">*</span></label>
						<input type="text" name="iPrice" value="{{ old('iPrice') }}" class="form-control @if($errors->has('iPrice')) {{ 'is-invalid' }} @endif" placeholder="Regular Price" />
						@if($errors->has('iPrice'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('iPrice') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Material Price: <span class="text-danger sup">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-percent"></i>
								</span>
							</div>
							<input name="mPrice" type="text" value="{{ old('mPrice') }}" class="form-control  @if($errors->has('mPrice')) {{ 'is-invalid' }} @endif" placeholder="Course Material Price" />
						</div>
						@if($errors->has('mPrice'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('mPrice') }}
						</small>
						@endif
					</div>
					<!-- <div class="col-lg-4">
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
					</div> -->
					<div class="col-lg-8">
						<label class="control-label">Course Description: </label>
						<textarea name="courseDescription" class="form-control" rows="5">{{ old('courseDescription') }}</textarea>
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
			placeholder: 'Select Option',
			allowClear: true
		});
		$('input[name="startDate"],input[name="endDate"],input[name="examDate"]').daterangepicker({
			singleDatePicker: true,
			startDate: new Date(),
			showDropdowns: true,
			autoUpdateInput: true,
			locale: {
				format: 'DD/MM/YYYY'
			}
		});
		$("#timepicker").timepicker({
			defaultTIme: !1,
			icons: {
				up: "mdi mdi-chevron-up",
				down: "mdi mdi-chevron-down"
			}
		});
	</script>
	@endpush