@extends('layout.master')
@section('title', 'Batch List')
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
			<h4 class="page-title">All Batches</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
		<ul class="pagination justify-content-end" >
			<form action="{{route(currentUser().'.batchSearch')}}" method="post" role="search" class="d-flex">
				@csrf
					<input type="text" placeholder="Search.." name="search" class="form-control">
					<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
				</form>
			</ul>
				
					<table id="" class="table table-sm table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:small;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Mr No.</th>
								<th>Pay By</th>
								<th>Other Payment Category</th>
								<th>Amount</th>
								<th>Payment Mode</th>
								<th>Payment Date</th>
								<th>Note</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($other_payments))
							@php
								$category = [1 => 'IDB',2=> 'Exam', 3=> 'Other'];
								$mode = [1 => 'Cash',2=> 'Bank', 3=> 'Card'];
							@endphp
							@foreach($other_payments as $op)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $op->mrNo }}</td>
								<td>{{ $op->pay_by }}</td>
								<td>{{ $category[$op->other_payment_category_id] ?? 'N/A' }}</td>
								<td>{{ $op->amount }}</td>
								<td>{{ $mode[$op->payment_mode] ?? 'N/A' }}</td>
								<td>{{ $op->paymentDate }}</td>
								<td>{{ $op->accountNote }}</td>
								<td>
									@if(currentUser() == 'superadmin' ||  currentUser() == 'accountsmanager')
									<a href="{{route(currentUser().'.otherPaymentEdit',[encryptor('encrypt', $op->id)])}}" title="edit" class="text-success"><i class="fas fa-edit mr-1"></i></a>
									<form action="{{ route(currentUser().'.otherPaymentDelete', encryptor('encrypt', $op->id)) }}" method="POST" style="display:inline;">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Are you sure you want to delete this item?');" title="Delete">
											<i class="fas fa-trash-alt"></i>
										</button>
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
					{{$other_payments->links()}}
				

			
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
	$('#responsive-datatable').DataTable();
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