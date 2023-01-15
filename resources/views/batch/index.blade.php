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
	
				
					<table class="responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Batch Id</th>
								<th>Batch Details</th>
								<th>Trainer</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($allBatch))
							@foreach($allBatch as $batch)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
									{{$batch->batchId}}<br>
									By:-{{\DB::table('users')->select('name')->where('id',$batch->userId)->first()->name}}
								</td>
								<td>
									<table class="table table-border table-striped">
										<tr>
											<td><strong>Course Name : </strong></td>
											<td>{{\DB::table('courses')->select('courseName')->where('id',$batch->courseId)->first()->courseName}}</td>
										</tr>
										<tr>
											<td><strong>Start Date :</strong></td>
											<td>{{$batch->startDate}}</td>
										</tr>
										<tr>
											<td><strong>End Date :</strong></td>
											<td>{{$batch->endDate}}</td>
										</tr>
										<tr>
											<td><strong>Batch Slot :</strong></td>
											<td>{{\DB::table('batchslots')->select('slotName')->where('id',$batch->bslot)->first()->slotName}}</td>
										</tr>
										<tr>
											<td><strong>Batch Time :</strong></td>
											<td>{{\DB::table('batchtimes')->select('time')->where('id',$batch->btime)->first()->time}}</td>
										</tr>
										<tr>
											<td><strong>Exam Date :</strong></td>
											<td>{{$batch->examDate}}</td>
										</tr>
										<tr>
											<td><strong>Exam Time :</strong></td>
											<td>{{$batch->examTime}}</td>
										</tr>
										<tr>
											<td><strong>Classroom : </strong></td>
											<td>{{$batch->examRoom}}</td>
										</tr>
										<tr>
											<td><strong>Seat Reamining : </strong></td>
											<td>{{$batch->seat-$batch->tst}}</td>
										</tr>
									</table>
								</td>
								<td>{{\DB::table('users')->select('name')->where('id',$batch->trainerId)->first()->name}}</td>
								<td>
									@if($batch->status == 1)
									<span>Active</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>
									@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager')
									<a href="{{route(currentUser().'.batch.edit',[encryptor('encrypt', $batch->id)])}}" class="text-success"><i class="far fa-edit mr-2"></i>Edit</a><br/>
									<a href="{{route(currentUser().'.batch.destroy',[encryptor('encrypt', $batch->id)])}}" class="text-danger"><i class="far fa-trash-alt mr-2"></i>Delete</a>
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