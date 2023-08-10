@extends('layout.master')
@section('title', 'Invoice | Money Receipt Report')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Collection</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">Daily Collection Report (Money Receipt)</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<div class="col-md-12 text-center">
				<h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="p-0" style="font-size:16px"><strong>Collection Report (Money Receipt)</strong></p>
			</div>
			<form action="{{route(currentUser().'.daily_collection_report_by_mr')}}" role="search">
				@csrf
				<div class="row">
					<div class="col-sm-6">
						<label for="name" class="col-form-label">Student ID|Name|Contact</label>
						<input type="text" class="form-control" name="studentId">
					</div>
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Invoice</label>
						<input type="text" class="form-control" name="invoiceId">
					</div>
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Mr No</label>
						<input type="text" class="form-control" name="mrNo">
					</div>
				</div>

				<div class="row">
					<div class="col-sm-1">
						<label for="name" class="col-form-label">Year</label>
						<select name="month" class="js-example-basic-single form-control me-3">
							<option value="">Year</option>
							@php
							for($i=2023;$i<=2023;$i++){ @endphp <option value="{{$i}}">{{$i}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-sm-1">
						<label for="name" class="col-form-label">Month</label>
						<select name="month" class="js-example-basic-single form-control me-3">
							<option value="">Month</option>
							@php
							$months = array("Jan", "Feb", "Mar", "Apr","May","June","July","August","September","October","November","December");
							for($i=0;$i<count($months);$i++){ @endphp <option value="{{date("n", strtotime($months[$i]))}}">{{$months[$i]}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Date</label>
						<div class="input-group">
							<input type="text" name="paymentDate" class="form-control" placeholder="dd/mm/yyyy">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div>
					</div>
					@if(currentUser() != 'salesexecutive')
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Executive</label>
						<select name="executiveId" class="js-example-basic-single form-control me-3">
							<option value="">Select Executive</option>
							@forelse($users as $user)
							<option value="{{$user->id}}">{{$user->username}}</option>
							@empty
							@endforelse
						</select>
					</div>
					@endif
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Batch</label>
						<select name="batch_id" class="js-example-basic-single form-control me-3">
							<option value="">Select Batch</option>
							@forelse($batches as $batch)
							<option value="{{$batch->id}}">{{$batch->batchId}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Fee Type</label>
						<select name="feeType" class="js-example-basic-single form-control me-3">
							<option value="">Select Type</option>
							<option value="1">Registration</option>
							<option value="2">Invoice</option>
							<option value="3">Due</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Payment Mode</label>
						<select name="type" class="js-example-basic-single form-control me-3">
							<option value="">Mode</option>
							<option value="1">Cash</option>
							<option value="2">Bkash</option>
							<option value="3">Bank</option>
						</select>
					</div>
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary me-2"><i class="fa fa-search fa-sm"></i></button>
						<a href="{{route(currentUser().'.daily_collection_report_by_mr')}}" class="reset-btn btn btn-warning"><i class="fa fa-undo fa-sm"></i></a>
					</div>
				</div>
			</form>
								<button type="btn btn-primary excelExport">Excel</button>
			<table class="payment table table-sm table-bordered mb-5 text-center" style="font-size: small;">
				<thead>
					<tr>
						<th width="100px">Date</th>
						<th>AE</th>
						<th colspan="2">Stu ID|Name</th>
						<th>Contact</th>
						<th>Batch</th>
						<th>MR</th>
						<th>Inv</th>
						<th>Type</th>
						<th>Due Date</th>
						<th>Invoice Amt.</th>
						<th>Paid</th>
						<th>Dis</th>
						<th>Due</th>

						<th>Mode</th>
						{{--<th>Partial|Full</th>
						<th>Info</th>--}}
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@php
					$total_paid_amount = 0;
					$total_dis = 0;
					$total_cpyable = 0;
					@endphp
					@foreach($payments as $p)
					@php
					//echo $p->paymentDetail->count();die;
					$rowCount = \DB::table('paymentdetails')->where('paymentId', $p->paymentId)->count();
					//echo $rowCount;
					@endphp
					@php
					$total_paid_amount += $p->cpaidAmount;
					$total_dis += $p->discount;

					@endphp
					<tr>
						<td rowspan="" class="align-middle">
							<p class="p-0 m-1">{{date('d M Y',strtotime($p->paymentDate))}}</p>
						</td>
						<td rowspan="" class="align-middle">{{$p->username}}</td>
						<td class="align-middle">{{$p->studentId}}</td>
						<td class="align-middle">{{$p->name}}</td>
						<td class="align-middle">
						@if(currentUserId() == $p->executiveId || currentUser() == 'salesmanager'  || currentUser() == 'superadmin' || currentUser() == 'operationmanager')
						{{$p->contact}}
						@else
						-	
						@endif
						</td>
						<td class="align-middle">
							@if($p->batchId)
							{{$p->batchId}}
							@else
							{{$p->courseName}}
							@endif
						</td>
						<td class="align-middle">{{$p->mrNo}}</td>
						<td class="align-middle">{{--$p->invoiceId--}}
							@if(\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.batchId' => $p->bid])->whereNotNull('payments.invoiceId')->exists() && $p->feeType==2)
							{{
								\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.batchId' => $p->bid])->whereNotNull('payments.invoiceId')->first()->invoiceId}}
							@else
							-
							@endif
						</td>
						@php
						if($p->feeType==1)
						$text = "Registration";
						else
						$text = "Invoice";
						@endphp
						<td class="align-middle">{{$text}}</td>
						@if($p->feeType==1)
						<td>-</td>
						<td>-</td>
						<td>{{$p->cpaidAmount}}</td>
						<td>-</td>
						<td>-</td>
						@else
						@if($p->cpaidAmount+$p->discount == $p->cPayable)
						<td class="align-middle">-</td>
						@else
						<td class="align-middle"><strong class="text-danger" style="font-size:12px;">@if($p->dueDate){{date('d M Y',strtotime($p->dueDate))}} @else - @endif</strong></td>
						@endif
						<td class="align-middle">
							@if($p->bid)
							{{\DB::table('student_batches')->where('student_id',$p->studentId)->where('batch_id',$p->bid)->first()->course_price}}{{--$p->cPayable--}}
							@else
							{{\DB::table('student_batches')->where('student_id',$p->studentId)->where('course_id',$p->course_id)->first()->course_price}}{{--$p->cPayable--}}
							@endif

						</td>
						<td class="align-middle">{{$p->cpaidAmount}}</td>
						<td class="align-middle">{{$p->discount?$p->discount:0}}</td>
						<td class="align-middle">{{($p->cPayable-($p->cpaidAmount+$p->discount))}}</td>
						@php $total_cpyable += ($p->cPayable-($p->cpaidAmount+$p->discount)); @endphp
						@endif





						<td class="align-middle">@if($p->payment_mode == 1) Cash @elseif($p->payment_mode == 2) Bkash @else Bank @endif</td>
						{{--<td class="align-middle">@if($p->feeType == 1) Partial @else Full @endif</td>
						<td width="150px" class="align-middle">
							<p class="text-left m-0 p-0">Paid By:-</p>
							<p class="text-left m-0 p-0">Paid:{{\Carbon\Carbon::createFromTimestamp(strtotime($p->created_at))->format('j M, Y')}}</p>
						<p class="text-left m-0 p-0">Updated By:-</p>
						<p class="text-left m-0 p-0">Update:@if($p->updated_at){{\Carbon\Carbon::createFromTimestamp(strtotime($p->updated_at))->format('j M, Y')}}@endif</p>
						</td>--}}

						<td width="130px" class="align-middle">
							<a href="" class="text-success" title="print"><i class="fas fa-print mr-1"></i></a>
							{{--|| currentUser() == 'superadmin'--}}
							@if(currentUser() == 'accountmanager')
							@if($p->batchId)
							<a href="{{route(currentUser().'.payment.edit',[encryptor('encrypt', $p->paymentId),$p->studentId])}}" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
							@else
							<a href="{{route(currentUser().'.payment.course.edit',[encryptor('encrypt', $p->paymentId),$p->studentId])}}" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
							@endif
							{{--<form method="POST" action="{{route(currentUser().'.payment.destroy',[encryptor('encrypt', $p->paymentId)])}}" style="display: inline;">
							@csrf
							@method('DELETE')
							<input name="_method" type="hidden" value="DELETE">
							<a href="javascript:void(0)" type="submit" class="delete mr-2 text-danger" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt mr-1"></i></a>
							</form>
							<a href="" class="text-warning" title="reverse"><i class="fas fa-redo-alt mr-1"></i></a>
							<a href="" class="text-info" title="refund"><i class="fas fa-exchange-alt"></i></a>--}}
							@endif
						</td>
					</tr>

					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="11"></td>
						<td>{{$total_paid_amount}}</td>
						<td>{{$total_dis}}</td>
						<td>{{$total_cpyable}}</td>
					</tr>
				</tfoot>
			</table>
			{{$payments->links()}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
	/*$('.excelExport').on('click',  function() {
		TableToExcel.convert(document.getElementById("table1"));
	});*/
	
	TableToExcel.convert(document.getElementById("table1"));
	$('.payment').on('click', '.delete', function(event) {
		event.preventDefault();
		swal({
				title: "Are you sure you want to Delete this",
				text: "If you Delete this, it will be Deleted.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$(this).parent().submit();
				}
			});
	});
	$('.js-example-basic-single').select2();
	$("input[name='paymentDate']").daterangepicker({
		singleDatePicker: true,
		startDate: new Date(),
		showDropdowns: true,
		autoUpdateInput: false,
		format: 'dd/mm/yyyy',
	}).on('changeDate', function(e) {
		var date = moment(e.date).format('YYYY/MM/DD');
		$(this).val(date);
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