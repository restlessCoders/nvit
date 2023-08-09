@extends('layout.master')
@section('title', 'Student Details')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css') }}">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css'>
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
					<a class="nav-link active" id="edit-student-tab" data-toggle="tab" href="#edit_student" role="tab" aria-controls="edit_student" aria-expanded="true" aria-selected="true">Edit</a>
				</li>
				@if($sdata->executiveId == currentUserId() || currentUser() == 'superadmin')
				<li class="nav-item">
					<a class="nav-link" id="course-pre" data-toggle="tab" href="#course_pre" role="tab" aria-controls="course_pre">Course Interest</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="batch-student-tab" data-toggle="tab" href="#batch_student" role="tab" aria-controls="batch_student">Batch Enroll | Knocking | Evaluation</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="course-student-tab" data-toggle="tab" href="#course_student" role="tab" aria-controls="course_student">Upcoming Batches</a>
				</li>
				@endif
				{{--<li class="nav-item">
					<a class="nav-link" id="notes-student-tab" data-toggle="tab" href="#note_student" role="tab" aria-controls="note_student">Note History</a>
				</li>--}}
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
									<select class="js-example-basic-single form-control select2" id="refId" name="refId">
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
									<input type="text" class="form-control" id="contact" name="contact" value="{{old('contact', $sdata->contact)}}" placeholder="Student Contact Number">
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
									<input type="text" class="form-control" id="altContact" name="altContact" value="{{old('altContact', $sdata->altContact)}}" placeholder="Student Alternative Contact Number">
									@if($errors->has('altContact'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('altContact') }}
									</small>
									@endif
								</div>
							</div>
							<div class="col-lg-6 row">
								<label for="email" class="col-sm-2 col-form-label">Email</label>
								<div class="col-sm-10">
									<input type="email" class="form-control" id="email" name="email" value="{{old('email', $sdata->email)}}" placeholder="Student Email">
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
							@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesexecutive')
							<div class="col-lg-4 row">
								<label for="" class="col-sm-3 col-form-label">Recall Date</label>
								<div class="col-sm-9">
									<div>
										<div class="input-group">
											<input type="text" name="executiveReminder" class="form-control" placeholder="dd/mm/yyyy">
											<div class="input-group-append">
												<span class="input-group-text"><i class="icon-calender"></i></span>
											</div>
										</div><!-- input-group -->
									</div>
								</div>
							</div>
							@endif
							@if(currentUser() == 'superadmin')
							<div class="col-lg-4 row">
								<label for="executiveId" class="col-sm-3 col-form-label">Select Executive</label>
								<div class="col-sm-9">
									<select class="js-example-basic-single form-control select2" id="executiveId" name="executiveId">
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
								<label for="executiveNote" class="col-sm-2 col-form-label">Note</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="executiveNote" name="executiveNote" rows="5" placeholder="First Note" style="
                                resize:none;" @if(!empty($sdata->executiveNote)) readonly @endif>{{old('executiveNote', $sdata->executiveNote)}}</textarea>
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
						{{--<div class="form-group row">
							<div class="col-lg-4 row">
								<label for="division_id" class="col-sm-3 col-form-label">Select Division</label>
								<div class="col-sm-9">
									<select class="js-example-basic-single form-control select2" id="division_id" name="division_id">
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
					<select class="js-example-basic-single form-control select2" id="district_id" name="district_id">
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
					<select class="js-example-basic-single form-control select2" id="upazila_id" name="upazila_id">
						<option value="">Select</option>
						@if(count($allUpazila) > 0)
						@foreach($allUpazila as $upazila)
						<option value="{{ $upazila->id }}" {{ old('upazila_id',$sdata->upazila_id) == $upazila->id ? "selected" : "" }}>{{$upazila->name}}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>--}}
			<!-- <div class="col-lg-12 row mt-3">
                            <label for="photo" class="col-sm-2 col-form-label">Student Photo</label>
                            <div class="col-sm-5">
                                <input type="file" class="form-control" id="photo" name="photo" @change="onFileselected">
                            </div>
                            <div class="col-sm-5">
	                	        <img :src="form.photo" style="height:40px; width: 40px;">
	                        </div>
                        </div>   -->
			{{--</div>--}}
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
		<div class="tab-pane fade" id="batch_student" role="tabpanel" aria-labelledby="batch-student-tab">

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
							<input type="text" class="form-control" value="{{ $sdata->id }}" readonly id="student_id">
							<!-- <input type="text" class="form-control" name="bid" id="bid"> -->
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-12 row">
						<label for="name" class="col-sm-2 col-form-label">Select Course</label>
						<div class="col-sm-10">

							<input type="text" name="" id="item_search" class="form-control  ui-autocomplete-input" placeholder="Search Batch">


							<!-- <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose Course..."> -->
							<!-- <select id="batch_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch..." name="batch_id"> -->
							{{--@if(count($allBatch))
											<option value=""></option>
											@foreach($allBatch as $batch)
											<option value="{{ $batch->id}}">{{$batch->batchId}}</option>
							@endforeach
							@endif
							</select>
							@if($errors->has('batch_id'))
							<small class="d-block text-danger mb-3">
								{{ $errors->first('batch_id') }}
							</small>
							@endif--}}
						</div>
					</div>
					<!-- <div class="col-lg-4 row">
								<label for="entryDate" class="col-sm-2 col-form-label">Entry Date</label>
								<div class="col-sm-10">
									<input type="date" class="form-control" name="entryDate">
									@if($errors->has('entryDate'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('entryDate') }}
									</small>
									@endif
								</div>
							</div> 
							<div class="col-lg-6 row">
								<label for="status" class="col-sm-2 col-form-label">Select Status</label>
								<div class="col-sm-10">
									<select class="js-example-basic-single form-control" id="status" name="status">
										<option value="">Select</option>
										<option value="2" @if($sdata->status == 2) selected @endif>Enroll</option>
										<option value="3" @if($sdata->status == 3) selected @endif>Knocking</option>
										<option value="4" @if($sdata->status == 4) selected @endif>Evloulation</option>
									</select>
									@if($errors->has('status'))
										<small class="d-block text-danger mb-3">
											{{ $errors->first('status') }}
										</small>
									@endif
								</div>
							</div>-->
				</div>

				<input type="hidden" value='1' id="hidden_rowcount" name="hidden_rowcount">
				<table class="mt-3 responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;" id="course_table">
					<thead>
						<tr>
							<th>Batch</th>
							<th>Payment Type</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="details_data">
					</tbody>
				</table>

				<div class="form-group text-right mb-0">
					<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
						Submit
					</button>
					<button type="reset" class="btn btn-secondary waves-effect">
						Cancel
					</button>
				</div>
			</form>
			<h5 class="page-title">Enrollment History</h5>
			<table class="mt-3 enrol table table-bordered">
				<thead>
					<tr>
						<th>SL.|Enrollment Date</th>
						<th>Batch Name</th>
						<th>Note</th>
						<!-- <th>Approved</th> -->
						<th>Status</th>
						<th>Payment Type</th>
						<th>Price</th>
						<th colspan="2">Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allassignBatches))
					@php
					$admissionCount = 0;
					$prevSystemId = '';
					@endphp
					@foreach($allassignBatches as $allassignBatch)
					<tr>
						<form action="{{ route(currentUser().'.addstudentCourseAssign',encryptor('encrypt',$allassignBatch->student_id)) }}" method="POST" enctype="multipart/form-data">
							@csrf
							@if ($allassignBatch->systemId != $prevSystemId)
							@php
							$admissionCount++;
							$prevSystemId = $allassignBatch->systemId;
							@endphp
							<td rowspan="{{DB::table('student_batches')->where('systemId',$allassignBatch->systemId)->count()}}">
								<p class="m-0">Admission {{ $admissionCount }}</p>
								<small>{{$allassignBatch->entryDate}} </small>
							</td>
							@endif
							<input type="hidden" name="s_id" value="{{$allassignBatch->student_id}}">
							<input type="hidden" name="batch_id" value="{{$allassignBatch->batch_id}}">
							<td>
								<select class="form-control" name="" disabled>
									@forelse($allBatch as $batch)
									<option value="{{$batch->id}}" @if($allassignBatch->batch_id == $batch->id) selected @endif>{{$batch->batchId}}</option>
									@empty
									@endforelse
								</select>
							</td>
							<td>{{$allassignBatch->note}}</td>
							{{--<td>@if($allassignBatch->acc_approve) Yes @else No @endif</td>--}}
							<td>
								<select class="js-example-basic-single form-control" id="status" name="status" @if($allassignBatch->acc_approve) disabled @endif>
									<option value="">Select</option>
									<option value="2" @if($allassignBatch->status == 2) selected @endif>Enrolled</option>
									<option value="3" @if($allassignBatch->status == 3) selected @endif>Knocking</option>
									<option value="4" @if($allassignBatch->status == 4) selected @endif>Evaluation</option>
								</select>
								@if($errors->has('status'))
								<small class="d-block text-danger mb-3">
									{{ $errors->first('status') }}
								</small>
								@endif
							</td>
							<td>
								<select class="js-example-basic-single form-control" id="type" name="type" @if($allassignBatch->acc_approve == 2) disabled @endif >
									<option value="">Select</option>
									<option value="1" @if($allassignBatch->type == 1) selected @endif>Full</option>
									<option value="2" @if($allassignBatch->type == 2) selected @endif>Intallment(Partial)</option>
								</select>
							</td>
							<td>{{$allassignBatch->course_price}}</td>
							<td>
							{{--&& currentUser() == 'superadmin'--}}
								@if($allassignBatch->acc_approve != 2 && $allassignBatch->isBundel == 0)
								<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-edit mr-2"></i>Update</button>
								@endif
								
							</td>
						</form>
						<td>
							
							@if($allassignBatch->acc_approve == 0 && $allassignBatch->isBundel == 0)
							<form id="active-form" method="POST" action="{{route(currentUser().'.enrollment.destroy',[encryptor('encrypt', $allassignBatch->id)])}}" style="display: inline;">
								@csrf
								@method('DELETE')
								<input name="_method" type="hidden" value="DELETE">
								<a href="javascript:void(0)" type="submit" class="delete mr-2 btn btn-danger btn-sm" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt mr-1"></i>Delete</a>
							</form>
							@endif
						</td>

					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="course_student" role="tabpanel" aria-labelledby="course-student-tab">
			<form action="{{ route(currentUser().'.courseEnroll') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" class="form-control" value="{{ $sdata->id }}" name="student_id">
				<div class="form-group row">
					<div class="col-lg-3 row">
						<label for="name" class="col-sm-3 col-form-label">Time Slot</label>
						<div class="col-sm-9">
							<select required name="batch_time_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Time Slot...">
								<option value="">Select</option>
								@if(count($allBatchTime))
								@foreach($allBatchTime as $batchTime)
								<option value="{{ $batchTime->id}}" {{ old('batch_time_id',$sdata->batch_time_id) == $batchTime->id ? "selected" : "" }}>{{$batchTime->time}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="col-lg-3 row">
						<label for="name" class="col-sm-3 col-form-label">Batch Slot</label>
						<div class="col-sm-9">
							<select required name="batch_slot_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch Slot...">
								<option value="">Select</option>
								@if(count($allBatchSlot))
								@foreach($allBatchSlot as $batchSlot)
								<option value="{{ $batchSlot->id}}" {{ old('batch_slot_id',$sdata->batch_slot_id) == $batchSlot->id ? "selected" : "" }}>{{$batchSlot->slotName}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="col-lg-4 row">
						<label for="name" class="col-sm-3 col-form-label">Select Course</label>
						<div class="col-sm-9">
							<select required name="course_id[]" class="form-control select2-multiple" multiple="multiple" data-toggle="select2" data-placeholder="Choose Course...">
								<option value="">Select</option>
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
					<div class="col-lg-2 row">
						<label for="name" class="col-sm-3 col-form-label">Status</label>
						<div class="col-sm-9">
							<select class="js-example-basic-single form-control" id="status" name="status">
								<option value="">Select</option>
								<option value="1">Full</option>
								<option value="2">Intallment(Partial)</option>
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
			<h5 class="page-title">Course Wise Enroll History</h5>
			<table class="course mt-3 responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Course Name</th>
						<th>Time Slot</th>
						<th>Batch Slot</th>
						<th>Price</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>

					@if(count($allcourseEnroll))
					@foreach($allcourseEnroll as $cw)
					<tr>
						<form action="{{ route(currentUser().'.courseEnrollUpdate') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" class="form-control" value="{{ $cw->id }}" name="cid">
							<td>{{ $loop->iteration }}</td>
							<td>
								<select name="course_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Course...">
									<optgroup label="">
										@if(count($allCourse))
										@foreach($allCourse as $course)
										<option value="{{ $course->id}}" @if($cw->course_id == $course->id) selected @endif>{{$course->courseName}}</option>
										@endforeach
										@endif
									</optgroup>
								</select>
							</td>
							<td>
								<select required name="batch_time_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Time Slot...">
									<option value="">Select</option>
									@if(count($allBatchTime))
									@foreach($allBatchTime as $batchTime)
									<option value="{{ $batchTime->id}}" {{ old('batch_time_id',$cw->batch_time_id) == $batchTime->id ? "selected" : "" }}>{{$batchTime->time}}</option>
									@endforeach
									@endif
								</select>
							</td>
							<td>
								<select required name="batch_slot_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch Slot...">
									<option value="">Select</option>
									@if(count($allBatchSlot))
									@foreach($allBatchSlot as $batchSlot)
									<option value="{{ $batchSlot->id}}" {{ old('batch_slot_id',$cw->batch_slot_id) == $batchSlot->id ? "selected" : "" }}>{{$batchSlot->slotName}}</option>
									@endforeach
									@endif
								</select>
							</td>
							<td>{{$cw->price}}</td>
							<td>
								<select class="js-example-basic-single form-control" id="status" name="status" @if($cw->p_status == 1) disabled @endif >
									<option value="">Select</option>
									<option value="1" @if($cw->status == 1) selected @endif>Full</option>
									<option value="2" @if($cw->status == 2) selected @endif>Intallment(Partial)</option>
								</select>
							</td>
							<td>
								@if($cw->p_status==0)
								<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-edit mr-2"></i>Update</button>
						</form>
						<form id="active-form-course" method="POST" action="{{route(currentUser().'.courseEnrollDelete',[encryptor('encrypt', $cw->id)])}}" style="display: inline;">
							@csrf
							@method('DELETE')
							<input name="_method" type="hidden" value="DELETE">
							<a href="javascript:void(0)" type="submit" class="delete mr-2 btn btn-danger btn-sm" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt mr-1"></i>Delete</a>
						</form>
						@endif
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="course_pre" role="tabpanel" aria-labelledby="course-pre-tab">
			<form action="{{ route(currentUser().'.coursePreference') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" class="form-control" value="{{ $sdata->id }}" name="student_id">
				<div class="form-group row">
					<div class="col-lg-4 row">
						<label for="name" class="col-sm-3 col-form-label">Time Slot</label>
						<div class="col-sm-9">
							<select required name="batch_time_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Time Slot...">
								<option value="">Select</option>
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
							<select required name="batch_slot_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch Slot...">
								<option value="">Select</option>
								@if(count($allBatchSlot))
								@foreach($allBatchSlot as $batchSlot)
								<option value="{{ $batchSlot->id}}" {{ old('batch_slot_id',$sdata->batch_slot_id) == $batchSlot->id ? "selected" : "" }}>{{$batchSlot->slotName}}</option>
								@endforeach
								@endif
							</select>
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
			<h5 class="page-title">Course Interests History</h5>
			<table class="mt-3 responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Course Name</th>
						<th>Time Slot</th>
						<th>Batch Slot</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allPreference))
					@foreach($allPreference as $p)
					<!-- Edit Course Preference-->
					<form action="{{ route(currentUser().'.coursePreferencEdit',$p->id) }}" method="POST" enctype="multipart/form-data">
						@csrf
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>
								<select name="course_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Course...">
									<optgroup label="">
										@if(count($allCourse))
										@foreach($allCourse as $course)
										<option value="{{ $course->id}}" @if($p->course_id == $course->id) selected @endif>{{$course->courseName}}</option>
										@endforeach
										@endif
									</optgroup>
								</select>
							</td>
							<td>
								<select required name="batch_time_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Time Slot...">
									<option value="">Select</option>
									@if(count($allBatchTime))
									@foreach($allBatchTime as $batchTime)
									<option value="{{ $batchTime->id}}" {{ old('batch_time_id',$p->batch_time_id) == $batchTime->id ? "selected" : "" }}>{{$batchTime->time}}</option>
									@endforeach
									@endif
								</select>
							</td>
							<td>
								<select required name="batch_slot_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch Slot...">
									<option value="">Select</option>
									@if(count($allBatchSlot))
									@foreach($allBatchSlot as $batchSlot)
									<option value="{{ $batchSlot->id}}" {{ old('batch_slot_id',$p->batch_slot_id) == $batchSlot->id ? "selected" : "" }}>{{$batchSlot->slotName}}</option>
									@endforeach
									@endif
								</select>
							</td>

							<td>
								<button type="submit" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Update</button>
							</td>
						</tr>
					</form>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="note_student" aria-labelledby="note-student-tab">
			<h5 class="page-title">Note History</h5>
			<table class="mt-3 responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>Sl.</th>
						<th>Recall Date</th>
						<th>Note</th>
						<th>Created By</th>
						<th>Created On</th>
					</tr>
				</thead>
				<tbody id="note-data">
					@forelse($notes as $note)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td width="120px">{{\Carbon\Carbon::createFromTimestamp(strtotime($note->re_call_date))->format('j M, Y')}}</td>
						<td>{{$note->note}}</td>
						<td>{{$note->noteCreated->name}}</td>
						<td>{{$note->created_at}}</td>
					</tr>
					@empty
					@endforelse
				</tbody>
			</table>
			{{$notes->links()}}
		</div>
	</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script src="{{ asset('backend/js/pages/jquery-ui.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
	/*====Delete Enrollment ===*/
	$('.enrol').on('click', '.delete', function(event) {
		event.preventDefault();
		swal({
				title: `Are you sure you want to Delete this ?`,
				text: "If you Delete this, it will be Deleted.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$('#active-form').submit();
				}
			});
	});
	$('.course').on('click', '.delete', function(event) {
		event.preventDefault();
		swal({
				title: `Are you sure you want to Delete this ?`,
				text: "If you Delete this, it will be Deleted.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$('#active-form-course').submit();
				}
			});
	});


	function change(batch_id, status, bid) {
		$('#batch_id').val(batch_id); // Change the value or make some change to the internal state
		$('#batch_id').trigger('change.select2'); // Notify only Select2 of changes
		$("#status").val(status).change();
		$("#bid").val(bid);
	}
	$('.select2-multiple').select2();
	$('.js-example-basic-single').select2();


	$("#item_search").bind("paste", function(e) {
		$("#item_search").autocomplete('search');
	});
	$("#item_search").autocomplete({
		source: function(data, cb) {
			console.log(data);
			$.ajax({
				autoFocus: true,
				url: "{{route(currentUser().'.allBatches')}}",
				method: 'GET',
				dataType: 'json',
				data: {
					name: data.term
				},
				success: function(res) {
					//console.log(res);
					var result;
					result = {
						label: 'No Records Found ',
						value: ''
					};
					if (res.length) {
						result = $.map(res, function(el) {
							return {
								label: 'Available Seat:-(' + (el.seat - el.tst) + ') ' + el.batchId,
								value: '',
								id: el.id,
								batchId: el.batchId,
								seat: (el.seat - el.tst)
							};
						});
					}
					cb(result);
				},
				error: function(e) {
					console.log(e);
				}
			});
		},
		response: function(e, ui) {
			if (ui.content.length == 1) {
				if(ui.content[0].seat >0){
					$(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
					$(this).autocomplete("close");
				}else{
					toastr['error']("No Seat Available!!");
					return false;
				}
			}
			console.log(ui);
		},
		//loader start
		search: function(e, ui) {},
		select: function(e, ui) {
			if (typeof ui.content != 'undefined') {
				if (isNaN(ui.content[0].id)) {
					return;
				}
				var batchId = ui.content[0].id;
			} else {
				var batchId = ui.item.id;
				var seat = ui.item.seat;
			}
			if (seat == 0) {
				toastr['error']("No Seat Available!!");
				return false;
			}
			return_row_with_data(batchId);
			$("#item_search").val('');
		},
		//loader end
	});


	function return_row_with_data(batchId) {
		$("#item_search").addClass('ui-autocomplete-loader-center');
		var student_id = $('#student_id').val();
		var rowcount = $("#hidden_rowcount").val();
		$.ajax({
			autoFocus: true,
			url: "{{route(currentUser().'.batchById')}}",
			method: 'GET',
			dataType: 'json',
			data: {
				batchId: batchId,
				rowcount: rowcount,
				student_id: student_id
			},
			success: function(res) {
				//console.log(res.data);
				var item_check = check_same_item(batchId);
				if (!item_check) {
					$("#item_search").removeClass('ui-autocomplete-loader-center');
					return false;
				}
				if (res.data.error) {
					toastr['error']("Batch In List!!");
					return false;
				}
				$('#details_data').append(res.data);
				$("#hidden_rowcount").val(parseFloat(rowcount) + 1);
				$("#item_search").val('');
				$("#item_search").removeClass('ui-autocomplete-loader-center');
			},
			error: function(e) {
				console.log(e);
			}
		});

	}

	function check_same_item(item_id) {
		if ($("#course_table tr").length > 1) {
			var rowcount = $("#hidden_rowcount").val();
			for (i = 0; i <= rowcount; i++) {
				if ($("#row_" + i).attr('data-item-id') == item_id) {
					return false;
				}
			} //end for
		}
		return true;
	}

	function removerow(id) { //id=Rowid  
		$("#row_" + id).remove();
	}

	$("input[name='executiveReminder']").daterangepicker({
		singleDatePicker: true,
		minDate: moment().startOf('day'),
		/*maxDate: moment().add(30, 'days').startOf('day'),*/
		startDate: new Date(),
		showDropdowns: true,
		autoUpdateInput: true,
		format: 'dd/mm/yyyy',
	}).on('changeDate', function(e) {
		var date = moment(e.date).format('YYYY/MM/DD');
		$(this).val(date);
	});
</script>

@if(Session::has('response'))
@php print_r(Session::has('response')); @endphp
<script>
	Command: toastr["{{Session::get('response')['class']}}"]("{{Session::get('response')['message']}}")
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
@if(old('tab'))
<script>
	$(function() {
		$('#myTab .nav-link').removeClass('active');
		$('.tab-content .tab-pane').removeClass('in active show');
		var selectedTab = '{{ old("tab") }}';
		// Show the selected tab
		if (selectedTab) {
			$('#myTab a[href="#' + selectedTab + '"]').addClass('active').tab('show');
			$('#' + selectedTab).addClass('in active show');
		}
	});
</script>
@endif
@endpush