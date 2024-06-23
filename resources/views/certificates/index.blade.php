@extends('layout.master')
@section('title', 'Certificate List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Certificate</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">List</h4>
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
								<th>Batch Finishing Date</th>
								<th>Updated On</th>
								<th>Trainer</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($certificates))
							@foreach($certificates as $c)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
									{{DB::table('batches')->where('id',$c->batch_id)->first()->batchId}}
								</td>	
								<td>{{\Carbon\Carbon::createFromTimestamp(strtotime($c->created_at))->format('j M, Y')}}</td>
								<td>@if($c->updated_at) {{\Carbon\Carbon::createFromTimestamp(strtotime($c->updated_at))->format('j M, Y')}} else - @endif</td>
								<td>{{DB::table('users')->where('id',$c->created_by)->first()->name}}</td>
								<td width="80px">
									@if(currentUser() == 'superadmin' || currentUser() == 'trainingmanager' || currentUser() == 'operationmanager' || currentUser() == 'accountmanager' || currentUser() == 'trainer')
									<a href="{{route(currentUser().'.certificate.edit',$c->batch_id)}}" title="edit" class="text-success"><i class="fas fa-edit mr-1"></i></a>
									<!-- <a href="{{route(currentUser().'.certificate.edit',[encryptor('encrypt', $c->id)])}}" title="edit" class="text-success"><i class="fas fa-edit mr-1"></i></a> -->
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
					{{$certificates->links()}}
				

			
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