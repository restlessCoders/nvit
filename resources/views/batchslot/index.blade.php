@extends('layout.master')
@section('title', 'Batch Slot List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Batch Slot</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Batch Slot</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">


			<table class="batchslot table table-bordered">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Slot Name</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allBatchslots))
					@foreach($allBatchslots as $allbatchslot)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{$allbatchslot->slotName}}</td>
						<td>
							@if($allbatchslot->status == 1)
							<span>Active</span>
							@else
							<span>Inactive</span>
							@endif
						</td>
						<td>
							@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager')
							<a href="{{route(currentUser().'.batchslot.edit',[encryptor('encrypt', $allbatchslot->id)])}}" class="text-info"><i class="fas fa-edit"></i></a>
							<form method="POST" action="{{route(currentUser().'.batchslot.destroy',[encryptor('encrypt', $allbatchslot->id)])}}" style="display: inline;">
								@csrf
								@method('DELETE')
								<input name="_method" type="hidden" value="DELETE">
								<a href="javascript:void(0)" data-status="{{$allbatchslot->status}}" data-name="{{$allbatchslot->name}}" type="submit" class="delete mr-2 text-danger" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt mr-1"></i></a>
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
	$('.batchslot').on('click', '.delete', function(event) {
		var name = $(this).data("name");
		var status = $(this).data("status");
		if(status){
			var title = `Are you sure you want to Inactive this ${name}?`
			var mode = true;
		}else{
			var title = `Are you sure you want to Active this ${name}?`
			var mode = false;
		}
		event.preventDefault();
		swal({
				title: title,
				text: "If you Delete this, it will be Deleted.",
				icon: "warning",
				buttons: true,
				dangerMode: mode,
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