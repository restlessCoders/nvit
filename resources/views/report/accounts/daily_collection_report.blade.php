@extends('layout.master')
@section('title', 'Daily Collection Report')
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
			<h4 class="page-title">Daily Collection Report</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">

			<form action="{{route(currentUser().'.daily_collection_report')}}" role="search">
				@csrf

				<div class="row">

					<div class="col-sm-3">
						<label for="name" class="col-form-label">Year</label>
						<select name="year" class="js-example-basic-single form-control me-3">
							<option value="">Select Year</option>
							@php
							for($i=2023;$i<=2023;$i++){ @endphp <option value="{{$i}}">{{$i}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-sm-3">
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
					<!-- <div class="col-sm-3">
						<label for="name" class="col-form-label">Date</label>
						<div class="input-group">
							<input type="text" name="paymentDate" class="form-control" placeholder="dd/mm/yyyy">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div>
					</div> 
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Executive</label>
						<select name="executiveId" class="js-example-basic-single form-control me-3">
							<option value="">Select Executive</option>
							@forelse($users as $user)
							<option value="{{$user->id}}">{{$user->username}}</option>
							@empty
							@endforelse
						</select>
					</div>-->
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary mr-2"><i class="fa fa-search fa-sm"></i></button>
						<a href="{{route(currentUser().'.daily_collection_report')}}" class="reset-btn btn btn-warning"><i class="fa fa-undo fa-sm"></i></a>
					</div>
				</div>
			</form>

			<div class="col-md-12 text-center">
				<h5 class="m-0">NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="m-0 p-0" style="font-size:16px"><strong>Collection Report</strong></p>
				<p class="m-0 p-0" style="font-size:14px"><strong>For the Month of -
				{{\Carbon\Carbon::create()->month($currentMonth)->format('F')}}
				{{$currentYear}}</strong>
				</p>
			</div>
			<table class="table table-sm table-bordered mb-5 text-center" style="font-size: small;">
				<thead>
					<tr>
						<th rowspan="2">Date</th>
						<th colspan="{{$salespersons->count()}}">Executives</th>
						@if(strtolower(currentUser()) != 'salesexecutive')
						<th rowspan="2">Total Course Fees</th>
						@endif
					</tr>
					<tr>
						@foreach ($salespersons as $salesperson)
						<td>{{$salesperson->username}}</td>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@php $total_course_fee = 0; @endphp
					@foreach ($payments as $payment)
					<tr>
						<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($payment->paymentDate))->format('j M, Y')}}</td>
						@foreach ($salespersons as $salesperson)
						<td>
							{{ 	
								/* Old Query */
								/*DB::table('payments')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')
								->where('payments.paymentDate', $payment->paymentDate)
								->where('payments.executiveId', $salesperson->executiveId)
								->sum('cpaidAmount')*/
								DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->where('payments.paymentDate', $payment->paymentDate)
								->where('payments.executiveId', $salesperson->executiveId)
								->sum('cpaidAmount')
								/*
								DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->where('payments.paymentDate', $payment->paymentDate)
								->where('payments.executiveId', $salesperson->executiveId)
								->sum('cpaidAmount')*/
								
							}}
						</td>
						@endforeach
						@if(strtolower(currentUser()) != 'salesexecutive')
						<td>
						@php $total_course_fee += DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->where('payments.paymentDate', $payment->paymentDate)
								->sum('cpaidAmount')/*- 
								DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->where('payments.paymentDate', $payment->paymentDate)
								->sum('discount');*/ @endphp
							{{
								DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->where('payments.paymentDate', $payment->paymentDate)
								->sum('cpaidAmount')/*- 
								DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->where('payments.paymentDate', $payment->paymentDate)
								->sum('discount');*/
							}}
						</td>
						@endif
					</tr>
					@endforeach
					<tr>
						<th>Total</th>
						@foreach ($salespersons as $salesperson)
						@php $date = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->startOfMonth(); @endphp
						<td>
							{{ 
								DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->whereMonth('payments.paymentDate', '=', $date->month)
    							->whereYear('payments.paymentDate', '=', $date->year)
								->where('payments.executiveId', $salesperson->executiveId)
								->sum('paymentdetails.cpaidAmount')/*- 
								DB::table('paymentdetails')	
								->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')				
								->whereMonth('paymentDate', '=', $date->month)
    							->whereYear('paymentDate', '=', $date->year)
								->where('payments.executiveId', $salesperson->executiveId)
								->sum('discount')*/
								
							}}
						</td>
						@endforeach
						<td>{{$total_course_fee}}</td>
					</tr>
			</table>
			{{--<thead>
					<tr>
						<th rowspan="2">Date</th>
						<th colspan="{{$payments->count()}}">Executives</th>
						<th rowspan="2">Discount</th>
						<th rowspan="2">Total Course Fees</th>
					</tr>
					<tr>
						@foreach ($payments as $payment)
						<td>{{$payment->executiveName}}</td>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach ($payments as $payment)
					<tr>
						<td>{{ $payment->paymentDate }}</td>
						<td>{{ $payment->paidAmount /*-$payment->discount*/}}</td>
						<td>{{ $payment->discount}}</td>
						<td>{{ $payment->tPayable}}</td>
					</tr>
					@endforeach

				</tbody>--}}

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