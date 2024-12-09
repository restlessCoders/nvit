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
			<h4 class="page-title">Daily Other Collection Report</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">

			<form action="{{route(currentUser().'.otherPaymentReport')}}" role="search">
				@csrf

				<div class="row">

					<div class="col-sm-3">
						<label for="name" class="col-form-label">Year</label>
						<select name="year" class="js-example-basic-single form-control me-3">
							<option value="">Select Year</option>
							@php
							for($i=2023;$i<=date('Y');$i++){ @endphp <option value="{{$i}}">{{$i}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Month</label>
						<select name="month" class="js-example-basic-single form-control me-3">
							<option value="">Month</option>
							<option value="1">Jan</option>
							<option value="2">Feb</option>
							<option value="3">Mar</option>
							<option value="4">Apr</option>
							<option value="5">May</option>
							<option value="6">June</option>
							<option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
					</div>
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary mr-2"><i class="fa fa-search fa-sm"></i></button>
						<a href="{{route(currentUser().'.otherPaymentReport')}}" class="reset-btn btn btn-warning"><i class="fa fa-undo fa-sm"></i></a>
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
			<button type="btn btn-primary" class="excelExport" style="margin:10px 0;">Excel</button>
			<table class="table table-sm table-bordered mb-5 text-center" style="font-size: small;" id="table1">
				<thead>
					<tr>
						<th rowspan="2">Date</th>
						<th colspan="{{$other_payments->count()}}">Categories</th>
						<th rowspan="2">Total Collection</th>
					</tr>
					<tr>
						@foreach ($other_payments as $other_payment)
						<td>{{$other_payment->category_name}}</td>
						@endforeach
						
					</tr>
				</thead>
				<tbody>
					@php $total_course_fee = 0; @endphp
					@foreach ($payments as $payment)
					<tr>
						<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($payment->paymentDate))->format('j M, Y')}}</td>
						@foreach ($other_payments as $other_payment)
						<td>
							@php
    DB::connection()->enableQueryLog();

	DB::table('other_payments')	
	->join('other_payment_categories','other_payment_categories.id','=','other_payments.other_payment_category_id')
								->where('other_payments.paymentDate', $payment->paymentDate)
								->where('other_payments.other_payment_category_id', $other_payment->id)
								->sum('amount');
$queries = DB::getQueryLog();
@endphp

{{-- dd($queries) --}}
							{{ 	

DB::table('other_payments')	
	->join('other_payment_categories','other_payment_categories.id','=','other_payments.other_payment_category_id')
								->where('other_payments.paymentDate', $payment->paymentDate)
								->where('other_payments.other_payment_category_id', $other_payment->id)
								->sum('amount');

								
							}}
						</td>
						@endforeach
						<td>
							@php 
							$total_course_fee += 
							DB::table('other_payments')	
								->join('other_payment_categories','other_payment_categories.id','=','other_payments.other_payment_category_id')
															->where('other_payments.paymentDate', $payment->paymentDate)
															->sum('amount');
															@endphp
							{{ 	

								DB::table('other_payments')	
									->join('other_payment_categories','other_payment_categories.id','=','other_payments.other_payment_category_id')
																->where('other_payments.paymentDate', $payment->paymentDate)
																->sum('amount');
								
																
															}}
						</td>
					</tr>
					@endforeach
					<tr>
						<th>Total</th>
						@foreach ($other_payments as $other_payment)
						@php $date = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->startOfMonth(); @endphp
						<td>
							{{ 
								DB::table('other_payments')	
								->join('other_payment_categories','other_payment_categories.id','=','other_payments.other_payment_category_id')		
								->whereMonth('other_payments.paymentDate', '=', $date->month)
    							->whereYear('other_payments.paymentDate', '=', $date->year)
								//->whereNull('other_payments.deleted_at')	
								->where('other_payments.other_payment_category_id', $other_payment->id)
								->sum('amount')
								
							}}
						</td>
						@endforeach
						<td>{{$total_course_fee}}</td>
					</tr>
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
	$(document).ready(function() {
		$('.excelExport').on('click', function() {
			TableToExcel.convert(document.getElementById("table1"));
		});
	});
</script>

@endpush