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
	
				
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Package</th>
								<th>Course</th>
								<th>Batch</th>
								<th>Note</th>
								<th>P.Price</th>
								<th>Start</th>
								<th>End</th>
								<th>Till</th>
								<th>Created By</th>
								<th>Updated By</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($allPackage))
							@foreach($allPackage as $package)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$package->pName}}</td>
								<td>{{$package->course->courseName}}</td>
								<td>{{optional($package->batch)->batchId}}</td>
								<td>{{$package->note}}</td>
								<td>{{$package->price}}</td>
								<td>{{$package->startDate}}</td>
								<td>{{$package->endDate}}</td>
								<td>{{$package->endTime}}</td>
								<td>{{$package->createdBy}}</td>
								<td>{{$package->updateBy}}</td>
								<td>
									@if($package->status == 1)
									<span>Active</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>
									<a href="{{route(currentUser().'.package.edit',[encryptor('encrypt', $package->id)])}}" class="text-info"><i class="fas fa-edit"></i></a>
									<a href="{{route(currentUser().'.package.destroy',[encryptor('encrypt', $package->id)])}}" class="text-danger"><i class="fas fa-trash-alt"></i></a>
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