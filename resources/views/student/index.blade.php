@extends('layout.master')
@section('title', 'Student List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Student</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Students</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<ul class="nav nav-tabs my-3" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="waiting-students-tab" data-toggle="tab" href="#waiting_students" role="tab" aria-controls="waiting_students" aria-expanded="true" aria-selected="true">Waiting Students</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="active-students-tab" data-toggle="tab" href="#active_students" role="tab" aria-controls="active_students">Active Students</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="dump-students-tab" data-toggle="tab" href="#dump_students" role="tab" aria-controls="dump_students">Dump Students</a>
				</li>
			</ul>
			<div class="tab-content text-muted" id="myTabContent">
				<div role="tabpanel" class="tab-pane fade in active show" id="waiting_students" aria-labelledby="waiting-students-tab">
					<table class="waiting-student table table-sm table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Student ID</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Address</th>
								<th>Executive</th>
								<th>FDO Note</th>
								<th>Reference</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($allwaitingStudent))
							@foreach($allwaitingStudent as $student)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$student->id}}</td>
								<td>{{$student->name}}</td>
								<td>
									{{$student->contact}}
									@if($student->altContact)
									<p>Alt:{{$student->altContact}}</p>
									@endif
									@if($student->altContact)
									<p>Email:{{$student->email}}</p>
									@endif
								</td>
								<td>
									<!-- <p class="my-0">{{$student->address}}</p> -->
									<p class="my-0"><strong class="mr-1">Division:</strong>{{optional($student->division)->name}}</p>
									<p class="my-0"><strong class="mr-1">District:</strong>{{optional($student->district)->name}}</p>
									<p class="my-0"><strong class="mr-1">Upazila:</strong>{{optional($student->upazila)->name}}</p>
								</td>
								<td>
									{{optional($student->executive)->name}}
								</td>
								<td>{{$student->otherInfo}}</td>
								<td>{{$student->reference->refName}}</td>
								<td>
									@if($student->status == 1)
									<span>Active</span>
									@elseif($student->status == 2)
									<span>Waiting</span>
									@elseif($student->status == 3)
									<span>Dump</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>
									@if(strtolower(currentUser()) != 'frontdesk')
									@php
									$student_id = encryptor('encrypt', $student->id)
									@endphp
									<form id="active-form" method="POST" action="{{route(currentUser().'.activeStudent',$student_id)}}">
										@csrf
										<input name="_method" type="hidden" value="PUT">
										<a href="javascript:void(0)" data-name="{{$student->name}}" type="submit" class="active_student mr-2 text-info" data-toggle="tooltip" title='Active'><i class="far fa-edit"></i>Active</a>
									</form>
									<form id="dump-form" method="POST" action="{{route(currentUser().'.dumpStudent',$student_id)}}">
										@csrf
										<input name="_method" type="hidden" value="PUT">
										<a href="javascript.void(0)" data-name="{{$student->name}}" type="submit" class="dump text-danger" data-toggle="tooltip" title='Dump'><i class="far fa-trash-alt"></i>Dump</a>
									</form>
									@endif
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
					{{$allwaitingStudent->links()}}
				</div>
				<div class="tab-pane fade" id="active_students" role="tabpanel" aria-labelledby="active-students-tab">
					<table class="table table-sm table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Student ID</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Recall | Note</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($allactiveStudent))
							@foreach($allactiveStudent as $student)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$student->id}}</td>
								<td>{{$student->name}}</td>
								<td>
									<p class="my-0"><span class="text-success">{{$student->contact}}</span>
										@if($student->altContact)
										<span class="text-success"> , {{$student->altContact}}</span>
									</p>
									@endif
								</td>
								<td>
									@if($student->notes->count() > 0)
									@php $note = $student->notes->last(); @endphp
									@if($note->re_call_date)
										<p class="text-center m-0"><strong>Recall :</strong>{{\Carbon\Carbon::createFromTimestamp(strtotime($note->re_call_date))->format('j M, Y')}}</p>
									@endif
									@if($note->note)
										<p class="text-center m-0"><strong>Note :</strong>{{$note->note}}</p>
									@else	
										<p class="text-center m-0">No Notes</p>
									@endif
									@if($note->created_at)
										<p class="text-center m-0"><strong>Posted On :</strong>{{\Carbon\Carbon::createFromTimestamp(strtotime($note->created_at))->format('j M, Y')}}</p>
									@endif
									@else
									<p class="text-center m-0">{{$student->executiveNote}}</p>
									<p class="text-center my-0"><strong class="mr-1"></strong>{{\Carbon\Carbon::createFromTimestamp(strtotime($student->executiveReminder))->format('j M, Y')}}</p>
									@endif
								</td>
								<td>
									@if($student->status == 1)
									<span>Active</span>
									@elseif($student->status == 2)
									<span>Waiting</span>
									@elseif($student->status == 3)
									<span>Dump</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td width="130px">
									@if(strtolower(currentUser()) != 'frontdesk')
									<a data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}" href="#" data-toggle="modal" data-target="#addNoteModal" class="text-info" title="note"><i class="far fa-sticky-note mr-1"></i></a>
									<a href="{{route(currentUser().'.editStudent',[encryptor('encrypt', $student->id)])}}" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
									<a href="" class="text-danger" title="delete"><i class="far fa-trash-alt mr-1"></i></a>
									<a href="" class="text-warning" title="note"><i class="fas fa-redo-alt"></i></a>
									<a href="" class="text-purple" title="dump"><i class="fas fa-dumpster"></i></a>
									@endif
								</td>



								<!-- <button class="text-danger" style="padding:0;outline:none;background:none;border:none" data-id="{{$student->executiveNote}}"  onclick="$('#dataid').text($(this).data('id')); $('#showmodal').modal('show');" ><i class="fas fa-lock mr-2"></i>Note</button> -->


							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
					<!-- Modal -->
					<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="{{ route(currentUser().'.notes.store') }}" method="POST">
									@csrf
									<input type="hidden" id="student_id" name="student_id" value="">
									<input type="hidden" value="{{ Session::get('user') }}" name="userId">
									<div class="modal-header">
										<h5 class="modal-title" id="addNoteModalLabel">Add Note For <span id="student_name"></span></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<label for="note">Re Call Date:</label>
											<input type="date" id="re_call_date" name="re_call_date" class="form-control">
										</div>
										<div class="form-group">
											<label for="note">Note:</label>
											<textarea class="form-control" id="note" name="note" rows="3"></textarea>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary" onclick="disableButton(this)">Add Note</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					{{--$allactiveStudent->links()--}}
				</div>
				<div class="tab-pane fade" id="dump_students" role="tabpanel" aria-labelledby="dump-students-tab">
					<table class="responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Student ID</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Email</th>
								<th>Address</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($alldumpStudent))
							@foreach($alldumpStudent as $student)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$student->id}}</td>
								<td>{{$student->name}}</td>
								<td>
									{{$student->contact}}
									<p>Alt-{{$student->altContact}}</p>
								</td>
								<td>{{$student->email}}</td>
								<td>
									Address:{{$student->Address}}<br />
									Division:{{optional($student->division)->name}}<br />
									District:{{optional($student->district)->name}}<br />
									Upazila:{{optional($student->upazila)->name}}
								</td>
								<td>
									@if($student->status == 1)
									<span>Active</span>
									@elseif($student->status == 2)
									<span>Waiting</span>
									@elseif($student->status == 3)
									<span>Dump</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>
									@if(strtolower(currentUser()) != 'frontdesk')
									<a href="{{route(currentUser().'.editStudent',[encryptor('encrypt', $student->id)])}}" class="mr-2"><i class="fas fa-edit text-info font-16"></i>Edit</a><br>
									<a href="{{route(currentUser().'.studentCourseAssign',[encryptor('encrypt', $student->id)])}}" class="mr-2"><i class="fas fa-pen text-info success-16"></i>Course Assign</a><br>
									<a href="{{route(currentUser().'.editStudent',[encryptor('encrypt', $student->id)])}}" class="mr-2"><i class="fas fa-lock text-primary font-16"></i>Enorll</a><br>
									{{--<a href="{{route(currentUser().'.deleteStudent', [encryptor('encrypt', $student->id)])}}"><i class="fas fa-trash-alt text-danger font-16"></i></a>--}}
									@endif
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
					{{--$alldumpStudent->links()--}}
				</div>
			</div>
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
	//This code will use if data table used
	//$('.responsive-datatable').DataTable();
</script>
@if(Session::has('response'))
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
	$('.responsive-datatable tbody').on('click', '.dump', function(event) {
		var name = $(this).data("name");
		event.preventDefault();
		swal({
				title: `Are you sure you want to Dump this ${name}?`,
				text: "If you dump this, it will be in dump list.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$('#dump-form').submit();
				}
			});
	});
</script>
<script>
	$('.waiting-student tbody').on('click', '.active_student', function(event) {
		var name = $(this).data("name");
		event.preventDefault();
		swal({
				title: `Are you sure you want to Active this ${name}?`,
				text: "If you Active this, it will be in Active list.",
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


	$('#addNoteModal').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget);
		var studentId = button.data('student-id');
		var studentName = button.data('student-name');

		var modal = $(this);
		modal.find('#student_id').val(studentId);
		modal.find('#student_name').text(studentName);
	});
	function disableButton(btn) {
    btn.disabled = true;
    btn.form.submit();
  }

	</script>

@if(old('tab'))
  <script>
    $(function(){
		$('#myTab .nav-link').removeClass('active');
		$('.tab-content .tab-pane').removeClass('in active show');
		var selectedTab = '{{ old("tab") }}';
		// Show the selected tab
		if (selectedTab) {
			$('#myTab a[href="#' + selectedTab + '"]').addClass('active').tab('show');
			$('#'+selectedTab).addClass('in active show');
		}
    });
  </script>
@endif

@endpush