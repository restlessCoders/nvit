@extends('layout.master')
@section('title', 'Add New Package')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Package</a></li>
					<li class="breadcrumb-item active">Add</li>
				</ol>
			</div>
			<h4 class="page-title">Add New Package</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<form action="{{ route(currentUser().'.package.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" value="{{ Session::get('user') }}" name="userId">
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Package Name: <span class="text-danger sup">*</span></label>
						<input type="text" name="pName" value="{{ old('pName') }}" class="form-control @if($errors->has('pName')) {{ 'is-invalid' }} @endif" placeholder="Package Name" />
						@if($errors->has('pName'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('pName') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
					<label>Select Package Option:</label>
						<select name="packageType" class="js-example-basic-single form-control select2 @if($errors->has('packageType')) {{ 'is-invalid' }} @endif">
							<option></option>
							<option value="1" {{ old('packageType') == 1 ? "selected" : "" }}>Batch|Course Offer</option>
							<option value="2" {{ old('packageType') == 2 ? "selected" : "" }}>Other Offer</option>
						</select>
						@if($errors->has('packageType'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('packageType') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Select Courses:</label>
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
					<label>Select Batch:</label>
						<select name="batchId" class="js-example-basic-single form-control select2 @if($errors->has('batchId')) {{ 'is-invalid' }} @endif">
							<option></option>
							@if(count($allBatch) > 0)
							@foreach($allBatch as $batch)
							<option value="{{ $batch->id }}" {{ old('batchId') == $batch->id ? "selected" : "" }}>{{ $batch->batchId }}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('batchId'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('batchId') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Regular Price: <span class="text-danger sup">*</span></label>
						<input type="text" name="price" value="{{ old('price') }}" class="form-control @if($errors->has('price')) {{ 'is-invalid' }} @endif" placeholder="Regular Price" />
						@if($errors->has('price'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('price') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Installment Price: <span class="text-danger sup">*</span></label>
						<input type="text" name="iPrice" value="{{ old('iPrice') }}" class="form-control @if($errors->has('iPrice')) {{ 'is-invalid' }} @endif" placeholder="Installment Price" />
						@if($errors->has('iPrice'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('iPrice') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Start Date<span class="text-danger sup">*</span></label>
						<div class="input-group">
							<input type="text" name="startDate" class="form-control" value="{{ old('startDate') }}">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div><!-- input-group -->
						@if($errors->has('startDate'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('startDate') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>End Date<span class="text-danger sup">*</span></label>
						<div class="input-group">
							<input type="text" name="endDate" class="form-control" value="{{ old('endDate') }}" placeholder="dd/mm/yyyy">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div><!-- input-group -->
						@if($errors->has('endDate'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('endDate') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Package Discount Ratio<span class="text-danger sup">*</span></label>
						<div class="input-group">
							<input id="dis" type="text" class="form-control" name="dis" value="{{ old('dis') }}">
							<div class="input-group-append">
								<span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
							</div>
						</div><!-- input-group -->
						@if($errors->has('dis'))
							<small class="d-block text-danger mb-3">
								{{ $errors->first('dis') }}
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
					<div class="col-lg-12">
						<label class="control-label">Note: </label>
						<textarea name="note" class="form-control" rows="5">{{ old('note') }}</textarea>
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
		$('input[name="startDate"],input[name="endDate"]').daterangepicker({
			singleDatePicker: true,
			//startDate: new Date(),
			//showDropdowns: true,
			autoUpdateInput: true,
			/*locale: {
				format: 'DD/MM/YYYY'
			}*/
		});
		$("#timepicker").timepicker({
			showMeridian: !1,
			icons: {
				up: "mdi mdi-chevron-up",
				down: "mdi mdi-chevron-down"
			}
		});
	</script>
	@endpush