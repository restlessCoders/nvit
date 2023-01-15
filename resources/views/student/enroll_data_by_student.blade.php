@extends('layout.master')
@section('title', 'Enroll | Student Wise Enroll Data')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Student Wise</a></li>
					<li class="breadcrumb-item active">Enroll | List</li>
				</ol>
			</div>
			<h4 class="page-title">Student Wise Enroll List</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
	
				
					<table class="responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Batch</th>
								<th>Accounts Note</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($sedata))
							@foreach($sedata as $student)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$student->batch_id}}</td>
								<td>{{$student->accountsNote}}</td>
								<td>{{$student->acc_approve}}</td>
								<td>
									<a href="{{route(currentUser().'.studentenrollById',[encryptor('encrypt', $student->id)])}}" class="text-primary"><i class="fas fa-cart-plus mr-2"></i>Student Enroll Data</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
	$('.responsive-datatable tbody').on('click', '.dump', function (event) {
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to Dump this ${name}?`,
              text: "If you dump this, it will be in dump list.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
				$('#dump-form').submit();
            }
          });
      });    
</script>
<script>
	$('.responsive-datatable tbody').on('click', '.active_student', function (event) {
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to Active this ${name}?`,
              text: "If you Active this, it will be in Active list.",
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
@endpush