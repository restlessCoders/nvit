@extends('layout.master')
@section('title', 'Daily Collection Repor')
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
			<form action="" method="post" role="search">
				@csrf
				<div class="row">

					<div class="col-sm-4">
						<label for="name" class="col-form-label">Year</label>
						<select name="month" class="js-example-basic-single form-control me-3" required>
							<option value="">Select Year</option>
							@php
							for($i=2023;$i<=2023;$i++){ @endphp <option value="{{$i}}">{{$i}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-sm-4">
						<label for="name" class="col-form-label">Month</label>
						<select name="month" class="js-example-basic-single form-control me-3" required>
							<option value="">Select Month</option>
							@php
							for($i=1;$i<=12;$i++){ @endphp <option value="{{$i}}">{{$i}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-sm-4">
						<label for="name" class="col-form-label">Date</label>
						<div class="input-group">
							<input type="text" name="paymentDate" class="form-control" placeholder="dd/mm/yyyy">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Student ID|Name|Contact</label>
						<input type="text" class="form-control" name="studentId">
					</div>
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Executive</label>
						<select name="month" class="js-example-basic-single form-control me-3" required>
							<option value="">Select Month</option>
							@forelse($users as $user)
							<option value="{{$user->id}}">{{$user->username}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Batch</label>
						<select name="month" class="js-example-basic-single form-control me-3" required>
							<option value="">Select Batch</option>
							@forelse($batches as $batch)
							<option value="{{$batch->id}}">{{$batch->batchId}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Fee Type</label>
						<select name="month" class="js-example-basic-single form-control me-3" required>
						<option selected value="1">Full</option>
						<option value="2">Partial</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Payment Mode</label>
						<select name="month" class="js-example-basic-single form-control me-3" required>
							<option selected value="1">Cash</option>
							<option value="2">Bkash</option>
							<option value="3">Bank</option>
						</select>
					</div>
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
					</div>
				</div>
			</form>

			<table class="table table-sm table-bordered mb-5 text-center" style="font-size: small;">
				<thead>
					<tr>
						<th>Date</th>
						<th>AE</th>
						<th>Inv</th>
						<th>MR</th>
						<th>Stu.Name</th>
						<th>Contact</th>
						<th>Course</th>
						<th>Next Date</th>
						<th>Payable</th>
						<th>Paid</th>
						<th>Dis</th>
						<th>Due</th>
						<th>Type</th>
						<th>Mode</th>
						<th>Partial|Full</th>
						<th>Info</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@php
					$total_paid_amount = 0;
					@endphp
					@foreach($payments as $p)
					@php
					//echo $p->paymentDetail->count();die;
					@endphp
					<tr>
						<td rowspan="{{$p->paymentDetail->count()+ 1}}" class="align-middle">
							<p class="p-0 m-1">{{date('d M Y',strtotime($p->paymentDate))}}</p>
						</td>
						<td rowspan="{{$p->paymentDetail->count()+ 1}}" class="align-middle">{{$p->user->username}}</td>
					</tr>
					@foreach($p->paymentDetail as $p)
					@php $total_paid_amount += $p->cpaidAmount; @endphp
					<tr>
						<td class="align-middle">{{$p->payment->invoiceId}}</td>
						<td class="align-middle">{{$p->payment->mrNo}}</td>
						<td class="align-middle">{{$p->student->name}}</td>
						<td class="align-middle">{{$p->student->contact}}</td>
						<td class="align-middle">{{$p->batch->batchId}}</td>
						<td class="align-middle"><strong class="text-danger" style="font-size:12px;">@if($p->dueDate){{date('d M Y',strtotime($p->dueDate))}} @else - @endif</strong></td>
						<td class="align-middle">{{$p->cPayable}}</td>
						<td class="align-middle">{{$p->cpaidAmount}}</td>
						<td class="align-middle">{{$p->discount?$p->discount:0}}</td>
						<td class="align-middle">{{($p->cPayable-($p->cpaidAmount+$p->discount))}}</td>
						@php
						if($p->feeType==1)
						$text = "Registration";
						else
						$text = "Course";
						@endphp
						<td class="align-middle">{{$text}}</td>
						<td class="align-middle">@if($p->payment_mode == 1) Cash @elseif($p->payment_mode == 2) Bkash @else Bank @endif</td>
						<td class="align-middle">@if($p->feeType == 1) Partial @else Full @endif</td>
						<td width="150px" class="align-middle">
							<p class="text-left m-0 p-0">Paid By:-</p>
							<p class="text-left m-0 p-0">Paid:{{\Carbon\Carbon::createFromTimestamp(strtotime($p->created_at))->format('j M, Y')}}</p>
							<p class="text-left m-0 p-0">Updated By:-</p>
							<p class="text-left m-0 p-0">Update:@if($p->updated_at){{\Carbon\Carbon::createFromTimestamp(strtotime($p->updated_at))->format('j M, Y')}}@endif</p>
						</td>

						<td width="130px" class="align-middle">
							<a href="" class="text-success" title="print"><i class="fas fa-print mr-1"></i></a>
							<a href="{{route(currentUser().'.payment.edit',[encryptor('encrypt', $p->paymentId),$p->studentId])}}" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
							<a href="" class="text-danger" title="delete"><i class="far fa-trash-alt mr-1"></i></a>
							<a href="" class="text-warning" title="reverse"><i class="fas fa-redo-alt mr-1"></i></a>
							<a href="" class="text-info" title="refund"><i class="fas fa-exchange-alt"></i></a>
						</td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="9"></td>
						<td>{{$total_paid_amount}}</td>
					</tr>
				</tfoot>
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
	$('.js-example-basic-single').select2();
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