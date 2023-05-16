@extends('layout.master')
@section('title', 'Batch Wise Studnet Enroll List')
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
			<h4 class="page-title">All Enrolled Students</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<div class="col-md-12 text-center">
				<h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="p-0" style="font-size:16px"><strong>Batch Wise Report</strong></p>
			</div>

			<form action="{{route(currentUser().'.batchwiseEnrollStudent')}}" method="post" role="search">
				@csrf
				<div class="row">
					<div class="col-sm-3">
						<label for="batch_id" class="col-form-label">Select Batch</label>
						<select name="batch_id" class="js-example-basic-single form-control">
							<option></option>
							@forelse($batches as $batch)
							<option value="{{$batch->id}}">{{$batch->batchId}}</option>
							@empty
							@endforelse
						</select>
					</div>
					@if(strtolower(currentUser()) != 'frontdesk')
					<div class="col-sm-3">
						<label for="executiveId" class="col-form-label">Select Executive</label>
						<select name="executiveId" class="js-example-basic-single form-control">
							<option></option>
							@forelse($executives as $e)
							<option value="{{$e->id}}">{{$e->name}}</option>
							@empty
							@endforelse
						</select>
					</div>
					@endif
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
							<option value="2">Enroll</option>
							<option value="3">Knocking</option>
							<option value="4">Evloulation</option>
						</select>
					</div>
					@endif
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
			<table class="table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Stu. Id</th>
						<th>Student Name</th>
						<th>Executive</th>
						@if(currentUser() == 'superadmin')
						<th>Reference</th>
						@endif
						<th>Batch</th>
						<th width="150px">Invoice Date</th>
						<th>Inv</th>
						@if(currentUser() == 'superadmin')
						<th>Course Price</th>
						<th>Paid Amount</th>
						@endif
						<th>Type</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allBatches))
					@foreach($allBatches as $batch)
					{{--<form action="{{ route(currentUser().'.addstudentCourseAssign',encryptor('encrypt',$batch->sId)) }}" method="POST" enctype="multipart/form-data">--}}
					<form action="" method="POST" enctype="multipart/form-data">
					<tr>
						<td>{{$loop->iteration}}</td>
						<td>{{$batch->sId}}</td>
						<td>{{$batch->sName}}</td>
						<td>{{$batch->exName}}</td>
						@if(currentUser() == 'superadmin')
						<td>{{\DB::table('references')->where('id',$batch->refId)->first()->refName}}</td>
						@endif
						<td>{{\DB::table('batches')->where('id',$batch->batch_id)->first()->batchId}}</td>
						<td>{{--\Carbon\Carbon::createFromTimestamp(strtotime($batch->entryDate))->format('j M, Y')--}}
							@if(\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->exists())
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
						@if(currentUser() == 'superadmin')
						<td>{{$batch->course_price}}</td>
						<td>{{\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])->sum('cpaidAmount')}}</td>
						@endif
						<td>
							@if($batch->status == 2) Enroll @endif
							@if($batch->status == 3) Knocking @endif
							@if($batch->status == 4)Evloulation @endif
						</td>
						<td>
							@if($batch->type == 1)
							At a Time
							@else
							Installment
							@endif
						</td>
						<td width="200px">
						{{--<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-edit mr-2"></i>Update</button>--}}
							@php $sum = \DB::table('paymentdetails')
							->selectRaw('COALESCE(SUM(cpaidAmount), 0) + COALESCE(SUM(discount), 0) as total')
							->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])
							->first()
							->total; @endphp
							@if($batch->course_price > $sum && $batch->status == 2 && strtolower(currentUser()) == 'accountmanager')
							<a href="{{route(currentUser().'.payment.index')}}?sId={{$batch->sId}}&systemId={{$batch->systemId}}" class="btn btn-success btn-sm"><i class="fas fa-edit mr-2"></i>Payment</a>
							@else
							<p class="text-primary" style="font-weight:700;">Full Paid</p>
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
	$('.js-example-basic-single').select2({
		placeholder: 'Select Option',
		allowClear: true
	});
	$('.reset-btn').on('click',function(){
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