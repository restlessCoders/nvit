@extends('layout.master')
@section('title', 'Package List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Packages</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Package</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
		<ul class="pagination justify-content-end" >
			<form action="{{route(currentUser().'.packageSearch')}}" method="post" role="search" class="d-flex">
				@csrf
					<input type="text" placeholder="Search.." name="search" class="form-control">
					<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
				</form>
			</ul>

			<table class="package table table-sm table-bordered text-center">
				<thead>
					<tr>
						<th rowspan="2">SL.</th>
						<th rowspan="2">Package</th>
						<th rowspan="2">Course</th>
						<th rowspan="2">Batch</th>
						<th colspan="2">Price</th>
						<th rowspan="2">Start</th>
						<th rowspan="2">End</th>
						<th rowspan="2">Dis</th>
						<!--<th rowspan="2">Note</th>
						<th rowspan="2">Declared</th>
						<th rowspan="2">Changed</th>-->
						<th rowspan="2">Status</th>
						<th rowspan="2">Action</th>
					</tr>
					<tr>
						<th>Regular</th>
						<th>Installment</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allPackage))
					@foreach($allPackage as $package)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{$package->pName}}</td>
						<td>{{optional($package->course)->courseName}}</td>
						<td>{{optional($package->batch)->batchId}}</td>
						<td>{{$package->price}}</td>
						<td>{{$package->iPrice}}</td>
						<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($package->startDate))->format('j M, Y')}}</td>
						<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($package->endDate))->format('j M, Y')}}</td>
						<td>{{$package->dis}}</td>
						{{--<td>{{$package->note}}</td>
						<td>{{$package->createdBy}}</td>
						<td>{{$package->updateBy}}</td>--}}
						<td>
							@if($package->status == 1)
							<span>Active</span>
							@else
							<span>Inactive</span>
							@endif
						</td>
						@if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager')
						<td>
							<a href="{{route(currentUser().'.package.edit',[encryptor('encrypt', $package->id)])}}" class="text-info"><i class="fas fa-edit"></i></a>
							<form method="POST" action="{{route(currentUser().'.package.destroy',[encryptor('encrypt', $package->id)])}}" style="display: inline;">
								@csrf
								@method('DELETE')
								<input name="_method" type="hidden" value="DELETE">
								<a href="javascript:void(0)" data-status="{{$package->status}}" data-name="{{$package->pName}}" type="submit" class="delete mr-2 text-danger" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt mr-1"></i></a>
							</form>
						</td>
						@endif
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="6">No Data Found</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{$allPackage->links()}}



		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
	$('.responsive-datatable').DataTable();
	$('.package').on('click', '.delete', function(event) {
		var name = $(this).data("name");
		var status = $(this).data("status");
		if (status) {
			var title = `Are you sure you want to Inactive this ${name}?`
			var mode = true;
		} else {
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