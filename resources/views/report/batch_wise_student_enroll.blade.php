@extends('layout.master')
@section('title', 'Batch Wise Studnet Enroll List')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Batch</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">All Enrolled Students</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<ul class="pagination justify-content-end">
				<form action="{{route(currentUser().'.batchwiseEnrollStudent')}}" method="post" role="search" class="d-flex">
					@csrf
					<select name="batch_id" class="js-example-basic-single form-control">
						<option>Select Batch</option>
						@forelse($batches as $batch)
						<option value="{{$batch->id}}">{{$batch->batchId}}</option>
						@empty
						@endforelse
					</select>
					<select class="js-example-basic-single form-control" id="status" name="status">
						<option value="">Select Status</option>
						<option value="2">Enroll</option>
						<option value="3">Knocking</option>
						<option value="4">Evloulation</option>
					</select>
					<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
				</form>
			</ul>
			@if($batchInfo)
			<div class="col-md-12">
				<h4 class="text-center">{{$batchInfo->batchId}}</h4>
				<p class="text-center text-success"><strong>Start Date: @php echo date('d-m-Y',strtotime($batchInfo->startDate)) @endphp</strong></p>
				<p class="text-center text-danger"><strong>Seat Available: {{$batchInfo->seat}}</strong></p>
			</div>
			@endif
			<table class="table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Student Name</th>
						<th>Executive Description</th>
						<th>Enroll Date</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allBatches))
						@foreach($allBatches as $batch)
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{$batch->sName}}</td>
							<td>{{$batch->exName}}</td>
							<td>{{$batch->entryDate}}</td>
							<td>
								@if($batch->status == 2) Enroll @endif
								@if($batch->status == 3) Knocking @endif
								@if($batch->status == 4)Evloulation @endif
							</td>
							<td>
								<a href="" class="text-info"><i class="fas fa-edit"></i></a>
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