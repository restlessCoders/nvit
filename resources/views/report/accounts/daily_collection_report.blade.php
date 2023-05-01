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

			<form action="{{route(currentUser().'.daily_collection_report')}}" method="post" role="search">
				@csrf

				<div class="row">

					<div class="col-sm-2">
						<label for="name" class="col-form-label">Year</label>
						<select name="year" class="js-example-basic-single form-control me-3" required>
							<option value="">Select Year</option>
							@php
							for($i=2023;$i<=2023;$i++){ @endphp <option value="{{$i}}">{{$i}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-sm-2">
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
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Date</label>
						<div class="input-group">
							<input type="text" name="paymentDate" class="form-control" placeholder="dd/mm/yyyy">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Executive</label>
						<select name="month" class="js-example-basic-single form-control me-3" required>
							<option value="">Select Executive</option>
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
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
					</div>
				</div>
			</form>

			<div class="col-md-12 text-center">
				<h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="p-0" style="font-size:16px"><strong>Collection Report</strong></p>
				<p class="p-0" style="font-size:14px"><strong>For the Month of -2023</strong></p>
			</div>
			<table class="table table-bordered dt-responsive nowrap text-center" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th rowspan="2">Date</th>
						<th colspan="{{$payments->count()}}">Executives</th>
						<th rowspan="2">Discount</th>
						<th rowspan="2">Total Course Fees</th>
						<th rowspan="2">Action</th>
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
						<td>{{ $payment->paidAmount -$payment->discount}}</td>
						<td>{{ $payment->discount}}</td>
						<td>{{ $payment->tPayable}}</td>
					</tr>
					@endforeach

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