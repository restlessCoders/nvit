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
								<th>Batch</th>
								<th>Course</th>
								<th>Regular</th>
								<th>Offer</th>
								<th width="90px">Op. Date</th>
								<th width="90px">E.Date</th>
								<th width="100px">W.Slot</th>
								<th width="140px">T.Slot</th>
								<!-- <th>Exam Date</th>
								<th>Exam Time</th> -->
								<th>Room</th>
								<th>T.Seat</th>
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
								<td>
									<table>
										<tr>
											<th>Full</th>
											<th>Installment</th>
										</tr>
										<tr>
											<td>{{$batch->course->rPrice}}</td>
											<td>{{$batch->course->iPrice}}</td>
										</tr>
									</table>
								</td>
								<td width="200px">
									@php $package = DB::table('packages')->where('batchId',$batch->id)->first(); @endphp
									@if($package)
									<table>
										<tr>
											<th>Full</th>
											<th>Installment</th>
										</tr>
										<tr>
											<td>{{$package->price}}</td>
											<td>{{$package->iPrice}}</td>
										</tr>
										<tr>
											<td colspan="2" class="text-danger"><strong>{{\Carbon\Carbon::createFromTimestamp(strtotime($package->startDate))->format('j M, Y')}} - {{\Carbon\Carbon::createFromTimestamp(strtotime($package->endDate))->format('j M, Y')}}</strong> </td>
										</tr>
									</table>
									@else
									No Offer
									@endif
								</td>
								<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($batch->startDate))->format('j M, Y')}}</td>
								<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($batch->endDate))->format('j M, Y')}}</td>
								<td>{{$batch->batchslot->slotName}}</td>
								<td>{{$batch->batchtime->time}}</td>
								<!-- <td>{{$batch->examDate}}</td>
								<td>{{$batch->examTime}}</td> -->
								<td>{{$batch->examRoom}}</td>
								<td>{{$batch->seat-$batch->tst}}</td>
								<td>{{optional($batch->trainer)->username}}</td>
								<td>{{$batch->totalClass}}</td>
								<td>
									@if($batch->status == 1)
									<span>Active</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>{{optional($batch->createdby)->username}}</td>
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
					{{$allBatch->links()}}
				

			
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