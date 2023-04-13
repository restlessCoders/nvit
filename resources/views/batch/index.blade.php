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
	
				
					<table class="table table-sm table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Batch</th>
								<th>Course</th>
								<th>Op. Date</th>
								<th>E.Date</th>
								<th>W.Slot</th>
								<th>T.Slot</th>
								<!-- <th>Exam Date</th>
								<th>Exam Time</th> -->
								<th>Room</th>
								<th>Available</th>
								<th>Trainer</th>
								<th>T.Class</th>
								<th>Status</th>
								<th>Created</th>
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
								</td>	
								<td>{{$batch->course->courseName}}</td>
								<td>{{$batch->startDate}}</td>
								<td>{{$batch->endDate}}</td>
								<td>{{$batch->batchslot->slotName}}</td>
								<td>{{$batch->batchtime->time}}</td>
								<!-- <td>{{$batch->examDate}}</td>
								<td>{{$batch->examTime}}</td> -->
								<td>{{$batch->examRoom}}</td>
								<td>{{$batch->seat-$batch->tst}}</td>
								<td>{{$batch->trainer->name}}</td>
								<td>{{$batch->totalClass}}</td>
								<td>
									@if($batch->status == 1)
									<span>Active</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>{{optional($batch->createdby)->name}}</td>
								<td width="80px">
									@if(currentUser() == 'superadmin' || currentUser() == 'salesmanager' || currentUser() == 'operationmanager')
									<a href="{{route(currentUser().'.batch.edit',[encryptor('encrypt', $batch->id)])}}" title="edit" class="text-success"><i class="fas fa-edit mr-1"></i></a>
									{{--<a href="{{route(currentUser().'.batch.destroy',[encryptor('encrypt', $batch->id)])}}" title="delete" class="text-danger"><i class="fas fa-trash-alt"></i></a>--}}
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