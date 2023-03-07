@extends('layout.master')
@section('title', 'Batch Time List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Batch Time</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Batch Time</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
	
				
					<table class="batchtime table table-bordered">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Time</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($allBatchtimes))
							@foreach($allBatchtimes as $allBatchtime)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$allBatchtime->time}}</td>
								<td>
									@if($allBatchtime->status == 1)
									<span>Active</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>
									@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager')
									<a href="{{route(currentUser().'.batchtime.edit',[encryptor('encrypt', $allBatchtime->id)])}}" title="edit" class="text-success"><i class="fas fa-edit mr-1"></i></a>
									<form id="active-form" method="POST" action="{{route(currentUser().'.batchtime.destroy',[encryptor('encrypt', $allBatchtime->id)])}}" style="display: inline;">
										@csrf
										@method('DELETE')
										<input name="_method" type="hidden" value="DELETE">
										<a href="javascript:void(0)" data-name="{{$allBatchtime->time}}" type="submit" class="delete mr-2 text-danger" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt mr-1"></i></a>
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
					
				

			
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
	$('.responsive-datatable').DataTable();
	$('.batchtime').on('click', '.delete', function(event) {
		var name = $(this).data("name");
		event.preventDefault();
		swal({
				title: `Are you sure you want to Delete this ${name}?`,
				text: "If you Delete this, it will be Deleted.",
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