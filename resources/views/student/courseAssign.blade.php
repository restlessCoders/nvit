@extends('layout.master')
@section('title', 'Student Course Assing')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Student</a></li>
					<li class="breadcrumb-item active">Course Assing</li>
				</ol>
			</div>
			<h4 class="page-title">Assign Course</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<form action="" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="form-group row">
					<div class="col-lg-6 row">
						<label for="name" class="col-sm-2 col-form-label">Student Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="student_name" name="student_id" value="{{ $sdata->name }}" readonly>
						</div>
					</div>
					<div class="col-lg-6 row">
						<label for="name" class="col-sm-2 col-form-label">Student Id</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" value="{{ $sdata->id }}" readonly>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-6 row">
						<label for="name" class="col-sm-2 col-form-label">Select Course</label>
						<div class="col-sm-10">
							<select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose Course...">
								<optgroup label="">
								@if(count($allCourse))
									@foreach($allCourse as $course)
									<option value="{{ $course->id}}">{{$course->courseName}}</option>
									@endforeach
								@endif
								</optgroup>
							</select>
						</div>
					</div>
					<div class="col-lg-3 row">
						<label for="name" class="col-sm-2 col-form-label">Time Slot</label>
						<div class="col-sm-10">
							<select class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Time Slot...">
								@if(count($allBatchTime))
									@foreach($allBatchTime as $batchTime)
									<option value="{{ $batchTime->id}}">{{$batchTime->time}}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="col-lg-3 row">
						<label for="name" class="col-sm-2 col-form-label">Batch Slot</label>
						<div class="col-sm-10">
							<select class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch Slot...">
								@if(count($allBatchSlot))
									@foreach($allBatchSlot as $batchSlot)
									<option value="{{ $batchSlot->id}}">{{$batchSlot->slotName}}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>	
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script>
	$('.select2-multiple').select2();
	$('.js-example-basic-single').select2();
</script>
@endpush