@extends('layout.master')
@section('title', 'Payment List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Payment</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Payment</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
	
				
					<table class="responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Student Info.</th>
								<th>Invoice Id</th>
								<th>Payment Info.</th>
								<th>Coupon ID</th>
								<th>Posted By</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($allPayment))
							@foreach($allPayment as $p)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
									<table class="table table-striped">
										<tr>
											<td>Student ID</td>
											<td>{{$p->studentData->id}}</td>
										</tr>
										<tr>
											<td>Student Name</td>
											<td>{{$p->studentData->name}}</td>
										</tr>
										<tr>
											<td>Executive Name</td>
											<td>{{$p->executiveData->name}}</td>
										</tr>
									</table>
								</td>
								<td>{{$p->invoiceId}}</td>
								<td>
									<table class="table table-striped">
										<tr>
											<td>Payable</td>
											<td>{{$p->tPayable}}</td>
										</tr>
										<tr>
											<td>Paid</td>
											<td>{{$p->paidAmount}}</td>
										</tr>
										<tr>
											<td>Discount</td>
											<td>{{$p->discount}}</td>
										</tr>
										<tr>
											<td>Due</td>
											<td>{{$p->tPayable-$p->paidAmount}}</td>
										</tr>
									</table>
								</td>
								<td>{{$p->couponId}}</td>
								<td>{{$p->postedData->name}}</td>
								<td>
									@if($p->status == 1)
									<span>Due</span>
									@else
									<span>Paid</span>
									@endif
								</td>
								<td>
									<a href="{{route(currentUser().'.payment.edit',[encryptor('encrypt', $p->id)])}}" class="text-success"><i class="far fa-edit mr-2"></i>Edit</a><br/>
									<a href="{{route(currentUser().'.payment.destroy',[encryptor('encrypt', $p->id)])}}" class="text-danger"><i class="far fa-trash-alt mr-2"></i>Delete</a>
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
					
				

			
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
	$('.responsive-datatable').DataTable();
	$('input[name="paymentDate"],input[name="dueDate"]').daterangepicker({
		singleDatePicker: true,
		startDate: new Date(),
		showDropdowns: true,
		autoUpdateInput: true,
		locale: {
			format: 'DD/MM/YYYY'
		}
	})
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