@extends('layout.master')
@section('title', 'Student Details')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
	.select2-container {
		width: 100% !important;
	}
</style>
@endpush
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Student</a></li>
					<li class="breadcrumb-item active">Details</li>
				</ol>
			</div>
			<h4 class="page-title">Student Details</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<ul class="nav nav-tabs my-3" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="edit-student-tab" data-toggle="tab" href="#edit_student" role="tab" aria-controls="edit_student" aria-expanded="true" aria-selected="true">Edit Student Data</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="course-student-tab" data-toggle="tab" href="#course_student" role="tab" aria-controls="course_student">Student Course Enroll | Register | Evaluation</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="operation-student-tab" data-toggle="tab" href="#operation_student" role="tab" aria-controls="operation_student"></a>
				</li>
			</ul>
			<div class="tab-content text-muted" id="myTabContent">
				<div role="tabpanel" class="tab-pane fade in active show" id="edit_student" aria-labelledby="edit-student-tab">
					<form action="{{ route(currentUser().'.updateStudent',encryptor('encrypt',$sdata->id)) }}" method="POST" enctype="multipart/form-data">
						@csrf
						<!-- <input type="hidden" value="{{Session::get('user')}}" name="userId"> -->
						<div class="form-group row">
							@if(currentUser() != 'salesexecutive')
							<div class="col-lg-4 row">
								<label for="refId" class="col-sm-3 col-form-label">Select Reference</label>
								<div class="col-sm-9">
									<select class="js-example-basic-single form-control" id="refId" name="refId">
										<option value="">Select</option>
										@if(count($allReference) > 0)
										@foreach($allReference as $reference)
										<option value="{{ $reference->id }}" {{ old('refId',$sdata->refId) == $reference->id ? "selected" : "" }}>{{$reference->refName}}</option>
										@endforeach
										@endif
									</select>
									@if($errors->has('refId'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('refId') }}
									</small>
									@endif
								</div>
							</div>
							@endif
							<div class="col-lg-4 row">
								<label for="name" class="col-sm-3 col-form-label">Time Slot</label>
								<div class="col-sm-9">
									<select name="js-example-basic-single batch_time_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Time Slot...">
										@if(count($allBatchTime))
										@foreach($allBatchTime as $batchTime)
										<option value="{{ $batchTime->id}}" {{ old('batch_time_id',$sdata->batch_time_id) == $batchTime->id ? "selected" : "" }}>{{$batchTime->time}}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-lg-4 row">
								<label for="name" class="col-sm-3 col-form-label">Batch Slot</label>
								<div class="col-sm-9">
									<select name="js-example-basic-single batch_slot_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch Slot...">
										@if(count($allBatchSlot))
										@foreach($allBatchSlot as $batchSlot)
										<option value="{{ $batchSlot->id}}" {{ old('batch_slot_id',$sdata->batch_slot_id) == $batchSlot->id ? "selected" : "" }}>{{$batchSlot->slotName}}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-lg-6 row">
								<label for="name" class="col-sm-2 col-form-label">Full Name</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="name" name="name" value="{{old('name', $sdata->name)}}" placeholder="Student Full Name">
									@if($errors->has('name'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('name') }}
									</small>
									@endif
								</div>
							</div>
							<div class="col-lg-6 row">
								<label for="contact" class="col-sm-2 col-form-label">Contact Number</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="contact" name="contact" value="{{old('contact', $sdata->contact)}}" placeholder="Student Contact Number" @if(currentUser() != 'superadmin' || currentUser() != 'operationmanager' || currentUser() != 'salesmanager') readonly @endif>
									@if($errors->has('contact'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('contact') }}
									</small>
									@endif
								</div>
							</div>
							<div class="col-lg-6 row">
								<label for="altContact" class="col-sm-2 col-form-label">Alt. Number</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="altContact" name="altContact" value="{{old('altContact', $sdata->altContact)}}" placeholder="Student Alternative Contact Number" v-model="form.altContact">
								</div>
							</div>
							<div class="col-lg-6 row">
								<label for="email" class="col-sm-2 col-form-label">Email</label>
								<div class="col-sm-10">
									<input type="email" class="form-control" id="email" name="email" value="{{old('email', $sdata->email)}}" placeholder="Student Email" v-model="form.email">
									@if($errors->has('email'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('email') }}
									</small>
									@endif
								</div>
							</div>
							@if(currentUser() == 'frontdesk')
							<div class="col-lg-6 row">
								<label for="otherInfo" class="col-sm-2 col-form-label">Other Info</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="otherInfo" name="otherInfo" rows="5" placeholder="Other Info" style="
                                resize:none;">{{old('otherInfo', $sdata->otherInfo)}}</textarea>
								</div>
							</div>
							@endif
							<div class="col-lg-4 row">
								<label for="" class="col-sm-3 col-form-label">Ex. Reminder</label>
								<div class="col-sm-9">
									<div>
										<div class="input-group">
											<input type="date" class="form-control" placeholder="mm/dd/yyyy" data-provide="datepicker" name="executiveReminder" value="{{old('executiveReminder', $sdata->executiveReminder)}}">
											<div class="input-group-append">
												<span class="input-group-text"><i class="icon-calender"></i></span>
											</div>
										</div><!-- input-group -->
									</div>
								</div>
							</div>
							<div class="col-lg-4 row">
								<label for="name" class="col-sm-3 col-form-label">Select Course</label>
								<div class="col-sm-9">
									<select name="course_id[]" class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose Course...">
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
							@if(currentUser() != 'salesexecutive')
							<div class="col-lg-4 row">
								<label for="executiveId" class="col-sm-3 col-form-label">Select Executive</label>
								<div class="col-sm-9">
									<select class="js-example-basic-single form-control" id="executiveId" name="executiveId">
										<option value="">Select</option>
										@if(count($allExecutive) > 0)
										@foreach($allExecutive as $executive)
										<option value="{{ $executive->id }}" {{ old('executiveId',$sdata->executiveId) == $executive->id ? "selected" : "" }}>{{$executive->name}}</option>
										@endforeach
										@endif
									</select>
									@if($errors->has('executiveId'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('executiveId') }}
									</small>
									@endif
								</div>
							</div>
							@endif
							<!-- <div class="col-lg-4 row mt-3">
								<label for="status" class="col-sm-3 col-form-label">Status</label>
								<div class="col-sm-9">
									<select class="form-control" id="status" name="status">
										<option value="">Select</option>
										<option value="0" @if($sdata->status == 0) selected @endif>Inactive</option>
										<option value="1" @if($sdata->status == 1) selected @endif>Active</option>
										<option value="2" @if($sdata->status == 2) selected @endif>Waiting</option>
									</select>
								</div>
							</div>
							<div class="col-lg-6 row">
								<label for="operationNote" class="col-sm-2 col-form-label">Operation Note</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="operationNote" name="operationNote" rows="5" placeholder="Operation Note" style="
                                resize:none;"></textarea>
								</div>
							</div> -->
							<div class="col-lg-6 row">
								<label for="executiveNote" class="col-sm-2 col-form-label">Ex. Note</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="executiveNote" name="executiveNote" rows="5" placeholder="Executive Note" style="
                                resize:none;">{{old('executiveNote', $sdata->executiveNote)}}</textarea>
								</div>
							</div>
							<div class="col-lg-6 row">
								<label for="address" class="col-sm-2 col-form-label">Address</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="address" name="address" rows="5" placeholder="Student Address" style="
                                resize:none;">{{old('address', $sdata->address)}}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-lg-4 row">
								<label for="division_id" class="col-sm-3 col-form-label">Select Division</label>
								<div class="col-sm-9">
									<select class="js-example-basic-single form-control" id="division_id" name="division_id">
										<option value="">Select</option>
										@if(count($allDivision) > 0)
										@foreach($allDivision as $division)
										<option value="{{ $division->id }}" {{ old('division_id',$sdata->division_id) == $division->id ? "selected" : "" }}>{{$division->name}}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-lg-4 row">
								<label for="district_id" class="col-sm-3 col-form-label">Select District</label>
								<div class="col-sm-9">
									<select class="js-example-basic-single form-control" id="district_id" name="district_id">
										<option value="">Select</option>
										@if(count($allDistrict) > 0)
										@foreach($allDistrict as $district)
										<option value="{{ $district->id }}" {{ old('district_id',$sdata->district_id) == $district->id ? "selected" : "" }}>{{$district->name}}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-lg-4 row">
								<label for="upazila_id" class="col-sm-3 col-form-label">Select Area</label>
								<div class="col-sm-9">
									<select class="js-example-basic-single form-control" id="upazila_id" name="upazila_id">
										<option value="">Select</option>
										@if(count($allUpazila) > 0)
										@foreach($allUpazila as $upazila)
										<option value="{{ $upazila->id }}" {{ old('upazila_id',$sdata->upazila_id) == $upazila->id ? "selected" : "" }}>{{$upazila->name}}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<!-- <div class="col-lg-12 row mt-3">
                            <label for="photo" class="col-sm-2 col-form-label">Student Photo</label>
                            <div class="col-sm-5">
                                <input type="file" class="form-control" id="photo" name="photo" @change="onFileselected">
                            </div>
                            <div class="col-sm-5">
	                	        <img :src="form.photo" style="height:40px; width: 40px;">
	                        </div>
                        </div>   -->
						</div>
						<div class="form-group text-right mb-0">
							<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
								Submit
							</button>
							<button type="reset" class="btn btn-secondary waves-effect">
								Cancel
							</button>
						</div>
					</form>
				</div>
				<div class="tab-pane fade" id="course_student" role="tabpanel" aria-labelledby="course-student-tab">

					<form action="{{ route(currentUser().'.addstudentCourseAssign',encryptor('encrypt',$sdata->id)) }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="form-group row">
							<div class="col-lg-6 row">
								<label for="name" class="col-sm-2 col-form-label">Student Name</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="student_name" value="{{ $sdata->name }}" readonly>
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
									<!-- <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose Course..."> -->
									<select id="course_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Course..." name="course_id">
											@if(count($allCourse))
											<option value=""></option>
											@foreach($allCourse as $course)
											<option value="{{ $course->id}}">{{$course->courseName}}</option>
											@endforeach
											@endif
									</select>
								</div>
							</div>
							<div class="col-lg-6 row">
								<label for="status" class="col-sm-2 col-form-label">Select Status</label>
								<div class="col-sm-10">
									<select class="js-example-basic-single form-control" id="status" name="status">
										<option value="">Select</option>
										<option value="2" @if($sdata->status == 2) selected @endif>Knocking</option>
										<option value="3" @if($sdata->status == 3) selected @endif>Enroll</option>
										<option value="4" @if($sdata->status == 4) selected @endif>Registered</option>
										<option value="5" @if($sdata->status == 5) selected @endif>Evloulation</option>
									</select>
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
					</form>
					<h5 class="page-title">Student Course History</h5>
					<table class="mt-3 responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Course Name</th>
								<th>Description</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($courses as $course)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$course->courseName}}</td>
								<td>{{$course->courseDescription}}</td>
								<td>
									@if($course->pivot->status == 2)  Knocking @endif
									@if($course->pivot->status == 3)  Enroll @endif
									@if($course->pivot->status == 4)  Registered @endif
									@if($course->pivot->status == 5)  Evloulation @endif
								</td>
								<td>
									<button class="text-danger" style="padding:0;outline:none;background:none;border:none" onclick='change("{{$course->pivot->course_id}}","{{$course->pivot->status}}")'><i class="fas fa-lock mr-2"></i>Edit</button>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="tab-pane fade" id="operation_student" role="tabpanel" aria-labelledby="operation-student-tab">
					Operation Data
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script>
		function change(course_id,status){


$('#course_id').val(course_id); // Change the value or make some change to the internal state
$('#course_id').trigger('change.select2'); // Notify only Select2 of changes
$("#status").val(status).change();
	}
	$('.select2-multiple').select2();
	$('.js-example-basic-single').select2();

</script>
@endpush