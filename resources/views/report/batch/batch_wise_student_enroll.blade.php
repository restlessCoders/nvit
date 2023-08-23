@extends('layout.master')
@section('title', 'Batch Wise Studnet Enroll List')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('styles')
<style>
	th{
		font-size:14px;
	}
	table,tr{
		font-size:13px;
	}
	.btn{
		font-size: 11px;
		margin:1px;
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Batch</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Enrolled Students</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<div class="col-md-12 text-center">
				<h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="p-0" style="font-size:16px"><strong>Batch Wise Report</strong></p>
			</div>

			<form action="{{route(currentUser().'.batchwiseEnrollStudent')}}" method="" role="search">
				@csrf
				<div class="row">
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Student ID|Name|Contact</label>
						<input type="text" class="form-control" name="studentId">
					</div>
					<div class="col-sm-2">
						<label for="batch_id" class="col-form-label">Select Batch</label>
						<select name="batch_id" class="js-example-basic-single form-control">
							<option></option>
							@forelse($batches as $batch)
							<option value="{{$batch->id}}" @if(request()->get('batch_id') == $batch->id) selected @endif>{{$batch->batchId}}</option>
							@empty
							@endforelse
						</select>
					</div>
					@if(strtolower(currentUser()) != 'frontdesk')
					<div class="col-sm-2">
						<label for="executiveId" class="col-form-label">Select Executive</label>
						<select name="executiveId" class="js-example-basic-single form-control">
							<option></option>
							@forelse($executives as $e)
							<option value="{{$e->id}}" @if(request()->get('executiveId') == $e->id) selected @endif>{{$e->username}}</option>
							@empty
							@endforelse
						</select>
					</div>
					@endif
					@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesexecutive')
					<div class="col-sm-2">
						<label for="refId" class="col-form-label">Select Reference</label>
						<select name="refId" class="js-example-basic-single form-control">
							<option></option>
							@forelse($references as $ref)
							<option value="{{$ref->id}}" @if(request()->get('refId') == $ref->id) selected @endif>{{$ref->refName}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-2">
						<label for="status" class="col-form-label">Select Status</label>
						<select class="js-example-basic-single form-control" id="status" name="status">
							<option value=""></option>
							<option value="2" @if(request()->get('status') ==2) selected @endif>Enroll</option>
							<option value="3" @if(request()->get('status') ==3) selected @endif>Knocking</option>
							<option value="4" @if(request()->get('status') ==4) selected @endif>Evaluation</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="drop" class="col-form-label">Withdraw</label>
						<select class="js-example-basic-single form-control" id="drop" name="drop">
							<option value=""></option>
							<option value="1" @if(request()->get('drop') ==1) selected @endif>Withdraw</option>
						</select>
					</div>
					@endif
					<div class="col-sm-2">
						<label for="type" class="col-form-label">Type</label>
						<select class="js-example-basic-single form-control" id="type" name="type">
							<option value=""></option>
							<option value="1" @if(request()->get('type') ==1) selected @endif>Due</option>
							<option value="2" @if(request()->get('type') ==2) selected @endif>Registration</option>
							<option value="3" @if(request()->get('type') ==3) selected @endif>Full Paid</option>
						</select>
					</div>
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary mr-1"><i class="fa fa-search fa-sm"></i></button>
						<a href="{{route(currentUser().'.batchwiseEnrollStudent')}}" class="reset-btn btn btn-warning"><i class="fa fa-undo fa-sm"></i></a>
					</div>
				</div>
			</form>

			@if($batchInfo)
			<div class="col-md-12">
				<h4 class="text-center">{{$batchInfo->batchId}}</h4>
				<p class="m-0 text-center text-success"><strong>Start Date: @php echo date('d-m-Y',strtotime($batchInfo->startDate)) @endphp</strong></p>
				<p class="m-0 text-center text-danger"><strong>Seat Available: {{$batchInfo->seat-$batch_seat_count}}</strong></p>
			</div>
			@endif
			<table class="table table-sm table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>SL.</th>
						<th>S.Id</th>
						<th>Student Name</th>
						<th>Executive</th>
						@if(currentUser() == 'superadmin')
						<th>Reference</th>
						@endif
						<th>Batch</th>
						<th width="120px">Inv Date</th>
						<th>Inv</th>
						<th>Inv Price</th>
						<th>Paid</th>
						<th>Contact</th>
						<th>Type</th>
						<th>Status</th>
						<th width="280px">Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allBatches))
					@foreach($allBatches as $batch)
					{{--<form action="{{ route(currentUser().'.addstudentCourseAssign',encryptor('encrypt',$batch->sId)) }}" method="POST" enctype="multipart/form-data">--}}
					<!--<form action="" method="POST" enctype="multipart/form-data">-->
						<tr>
							<td>{{ (($allBatches->currentPage() - 1) * $allBatches->perPage()) + $loop->iteration }}</td>
							<td>{{$batch->sId}}</td>
							<td>{{$batch->sName}}
							@if($batch->is_drop == 1) <strong class="text-danger">(Withdrawn)</strong>@endif
							</td>
							<td>{{$batch->exName}}</td>
							@if(currentUser() == 'superadmin')
							<td>{{\DB::table('references')->where('id',$batch->refId)->first()->refName}}</td>
							@endif
							<td>
							@if(\DB::table('batches')->where('id',$batch->batch_id)->first())
							{{\DB::table('batches')->where('id',$batch->batch_id)->first()->batchId}}
							@else
								@if(\DB::table('courses')->where('id',$batch->course_id)->first())
								{{\DB::table('courses')->where('id',$batch->course_id)->first()->courseName}}
								@endif
							@endif
							</td>
							<td>{{--\Carbon\Carbon::createFromTimestamp(strtotime($batch->entryDate))->format('j M, Y')--}}
								@php $inv = \DB::table('payments')
								->join('paymentdetails','paymentdetails.paymentId','payments.id')
								->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->exists(); @endphp
								@if($inv)
								{{\Carbon\Carbon::createFromTimestamp(strtotime(\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->first()->paymentDate))->format('j M, Y')}}
								@else
								-
								@endif
							</td>
							<td>
								@if(\DB::table('payments')
								->join('paymentdetails','paymentdetails.paymentId','payments.id')
								->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->exists())
								{{\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->first()->invoiceId}}
								@else
								-
								@endif
							</td>
							
							<td>
							@if(currentUserId() == $batch->executiveId || currentUser() == 'salesmanager'  || currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'accountmanager' )
							{{$batch->course_price}}
							@endif 
							</td>
							<td>
							@if(currentUserId() == $batch->executiveId || currentUser() == 'salesmanager'  || currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'accountmanager' ) 
								@if($batch->batch_id != 0)
								{{\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])->sum('cpaidAmount')}}
								@else
								{{\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'course_id' => $batch->course_id])->sum('cpaidAmount')}}
								@endif
							@endif	
							</td>
							
							<td>
							@if(currentUserId() == $batch->executiveId || currentUser() == 'salesmanager'  || currentUser() == 'superadmin' || currentUser() == 'operationmanager' ) 
							{{$batch->contact}}
							@else
							-
							@endif
							</td>
							<td>
								@if($batch->status == 2) Enroll @endif
								@if($batch->status == 3) Knocking @endif
								@if($batch->status == 4)Evaluation @endif
							</td>
							<td>
								@if($batch->type == 1)
								At a Time
								@else
								Installment
								@endif
							</td>
							<td class="d-flex">
								@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager')
									@if($batch->batch_id == 0)
									<a href="{{route(currentUser().'.editEnrollStudent',[encryptor('encrypt', $batch->sb_id)])}}" class="btn btn-info btn-sm"><i class="fas fa-edit mr-2"></i>B.Assign</a>
									@endif
								@endif

								@if($batch->batch_id)

									@php 
									$sum = \DB::table('paymentdetails')
									->selectRaw('COALESCE(SUM(cpaidAmount), 0) + COALESCE(SUM(discount), 0) as total')
									->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])
									->first()
									->total; 

									$deduct = \DB::table('paymentdetails')
									->selectRaw('COALESCE(SUM(deduction), 0) as deduction')
									->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])
									->first()
									->deduction; 
									
									@endphp

									<!-- Withdraw Student From Batch -->
									@php $withdraw_drop_allow = ['superadmin' , 'operationmanager'  , 'salesmanager']; @endphp
									@if(in_array(currentUser(),$withdraw_drop_allow) && $sum > 0 && $batch->status == 2 && $batch->is_drop == 0)
									{{ $batch->batch_id}}
									<form id="withdraw-active-form" action="{{route(currentUser().'.withdraw')}}" style="display: inline;">
									@csrf
                      				<input name="id" type="hidden" value="{{$batch->sb_id}}">              
                      				<a href="javascript:void(0)" data-name="{{$batch->sName}}" data-batch="{{\DB::table('batches')->where('id',$batch->batch_id)->first()->batchId}}" data-student="{{ $batch->sId }}" class="withdraw btn btn-secondary btn-sm" data-toggle="tooltip" title="Withdraw"><i class="fas fa-edit mr-2"></i>Drop</a>
                  					</form>
									@endif

									@if(in_array(currentUser(),$withdraw_drop_allow) && $sum > 0 && $batch->status == 2 && $batch->is_drop == 1)
									<form id="withdraw-undo-form" action="{{route(currentUser().'.withdraw_undo')}}" style="display: inline;">
									@csrf
                      				<input name="id" type="hidden" value="{{$batch->sb_id}}">    
									<input name="batch_id" type="hidden" value="{{$batch->batch_id}}">    
									<input name="course_id" type="hidden" value="{{$batch->course_id}}">         
                      				<a href="javascript:void(0)" data-name="{{$batch->sName}}" data-batch="{{\DB::table('batches')->where('id',$batch->batch_id)->first()->batchId}}" data-student="{{ $batch->sId }}" class="withdraw_undo btn btn-secondary btn-sm" data-toggle="tooltip" title="Undo Withdraw"><i class="fas fa-edit mr-2"></i>Undo</a>
                  					</form>
									@endif
									

									@if($batch->course_price > $sum && $batch->status == 2 && strtolower(currentUser()) == 'accountmanager')
										@if($deduct < 0)
										<button type="button" class="btn btn-info btn-sm">Void</button>
										@else
										<a href="{{route(currentUser().'.payment.index')}}?sId={{$batch->sId}}&systemId={{$batch->systemId}}" class="btn btn-secondary btn-sm"><i class="fas fa-edit mr-2"></i>@if($inv) Due @else Reg. @endif</a>
										@endif
									
									@elseif($batch->course_price == $sum && $batch->status == 2)
										@if($batch->isBundel == 1)
											Bundel Course
										@else
											@if($deduct < 0)
											<button type="button" class="btn btn-danger btn-sm">Void</button>
											@else
											<button type="button" class="btn btn-success btn-sm">Full Paid</button>
											@endif
										@endif
									@else
										@if($deduct < 0)
										<button type="button" class="btn btn-info btn-sm">Void</button>
										@else
										@if($inv && $batch->status == 2) <div class="btn btn-danger btn-sm" style="font-weight:bold;">Due</div> @elseif(empty($inv) && $batch->status == 2) <div class="btn btn-secondary btn-sm" style="font-weight:bold;">Reg.</div> @else - @endif
										@endif
									@endif
									@if($sum > 0 && $deduct == 0)
									<a data-systemid="{{ $batch->systemId }}" data-batch_id="{{ $batch->batch_id }}" data-student-id="{{ $batch->sId }}" data-student-name="{{ $batch->sName }}" href="#" data-toggle="modal" data-target="#payHisModal" class="btn btn-primary btn-sm" title="Payment History">Detail</a>
										@if(currentUser() == 'superadmin' || currentUser() == 'accountmanager' && $deduct ==0)
										<a href="{{route(currentUser().'.refund.edit',$batch->sId)}}" class="btn btn-warning btn-sm"><i class="fa fa-trash"></i>Adjustment</a>
										@endif
									@endif

								@else
									@php $sum = \DB::table('paymentdetails')
									->selectRaw('COALESCE(SUM(cpaidAmount), 0) + COALESCE(SUM(discount), 0) as total')
									->where(['studentId'=>$batch->sId,'course_id' => $batch->course_id])
									->first()
									->total; @endphp
									@if($batch->course_price > $sum && $batch->status == 2 && strtolower(currentUser()) == 'accountmanager')
									<a href="{{route(currentUser().'.payments.index')}}?sId={{$batch->sId}}&systemId={{$batch->systemId}}" class="btn btn-danger btn-sm"><i class="fas fa-edit mr-2"></i>@if($inv) Due @else Reg. @endif</a>
									@elseif($batch->course_price == $sum && $batch->status == 2)
									<button type="button" class="btn btn-success btn-sm">Full Paid</button>
									@else
									<div class="btn btn-danger btn-sm" style="font-weight:bold;">Due</div>
									@endif
									@if($sum > 0)
									<a data-systemid="{{ $batch->systemId }}" data-course_id="{{ $batch->course_id }}" data-student-id="{{ $batch->sId }}" data-student-name="{{ $batch->sName }}" href="#" data-toggle="modal" data-target="#payCourseHisModal" class="btn btn-primary btn-sm" title="Payment History">Detail</a>
									@endif
								@endif
							</td>
						</tr>
					<!--</form>-->
					@endforeach
					@else
					<tr>
						<td colspan="6">No Data Found</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{$allBatches->links()}}
		</div>
	</div>
</div> <!-- end row -->
<div class="modal fade" id="payHisModal" tabindex="-1" role="dialog" aria-labelledby="payHisModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addNoteModalLabel">Student Name:-<span id="student_name"></span>|ID:-<span id="student_id"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="col-md-12">
				<div class="table-responsive" id="paymenthisTblData">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Course History Modal -->
<div class="modal fade" id="payCourseHisModal" tabindex="-1" role="dialog" aria-labelledby="payHisModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addNoteModalLabel">Student Name:-<span id="student_name"></span>|ID:-<span id="student_id"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="col-md-12">
				<div class="table-responsive" id="paymentCoursehisTblData">
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
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
	$('#payHisModal').on('show.bs.modal', function(event) {
		$('#paymenthisTblData').empty();
		var button = $(event.relatedTarget);
		var sId = button.data('student-id');
		var batch_id = button.data('batch_id');
		var systmVal = button.data('systemid');
		var studentName = button.data('student-name');
		var modal = $(this);
		modal.find('#student_id').text(sId);
		modal.find('#student_name').text(studentName);

		$.ajax({
			url: "{{route(currentUser().'.allPaymentReportBySid')}}",
			method: 'GET',
			dataType: 'json',
			data: {
				systmVal: systmVal,
				sId: sId,
				batchId: batch_id
			},
			success: function(res) {
				console.log(res.data);

				$('#paymenthisTblData').append(res.data);
			},
			error: function(e) {
				console.log(e);
			}
		});

	});
	/*==Course Payment History Modal==*/
	$('#payCourseHisModal').on('show.bs.modal', function(event) {
		$('#paymentCoursehisTblData').empty();
		var button = $(event.relatedTarget);
		var sId = button.data('student-id');
		var course_id = button.data('course_id');
		var systmVal = button.data('systemid');
		var studentName = button.data('student-name');
		var modal = $(this);
		modal.find('#student_id').text(sId);
		modal.find('#student_name').text(studentName);

		$.ajax({
			url: "{{route(currentUser().'.allPaymentCourseReportBySid')}}",
			method: 'GET',
			dataType: 'json',
			data: {
				systmVal: systmVal,
				sId: sId,
				course_id: course_id
			},
			success: function(res) {
				console.log(res.data);

				$('#paymentCoursehisTblData').append(res.data);
			},
			error: function(e) {
				console.log(e);
			}
		});

	});

	/*Withdraw Code */
	$('.withdraw').on('click', function(event) {
    var name = $(this).data("name");
	var batch = $(this).data("batch");
	var student_id = $(this).data("student");
    event.preventDefault();
    swal({
        title: `Are want to withdraw Student ID#${student_id}|Name ${name} From Batch ${batch}?`,
        icon: "success",
        buttons: true,
        dangerMode: false,
      })
      .then((willDelete) => {
        if (willDelete) {
			$(this).parent().submit();
        }
      });
  });
  $('.withdraw_undo').on('click', function(event) {
    var name = $(this).data("name");
	var batch = $(this).data("batch");
	var student_id = $(this).data("student");
    event.preventDefault();
    swal({
        title: `Are want to Undo withdraw Student ID#${student_id}|Name ${name} From Batch ${batch}?`,
        icon: "success",
        buttons: true,
        dangerMode: false,
      })
      .then((willDelete) => {
        if (willDelete) {
			$(this).parent().submit();
        }
      });
  });
  
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
@endpush
