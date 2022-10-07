@extends('layout.master')
@section('title', 'Enroll | Registered Student List')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Student</a></li>
					<li class="breadcrumb-item active">Enroll | Registered List</li>
				</ol>
			</div>
			<h4 class="page-title">All Enroll | Registered Students</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
	
				
					<table class="responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
							<tr>
								<th>SL.</th>
								<th>Student ID</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Address</th>
								<th>FDO Note</th>
								<th>Reference</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($enrollStudents))
							@foreach($enrollStudents as $student)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$student->id}}</td>
								<td>{{$student->name}}</td>
								<td>
									{{$student->contact}}
									@if($student->altContact)
									<p>Alt:{{$student->altContact}}</p>
									@endif
									@if($student->altContact)
									<p>Email:{{$student->email}}</p>
									@endif
								</td>
								<td>
									<!-- <p class="my-0">{{$student->address}}</p> -->
									<p class="my-0"><strong class="mr-1">Division:</strong>{{optional($student->division)->name}}</p>
									<p class="my-0"><strong class="mr-1">District:</strong>{{optional($student->district)->name}}</p>
									<p class="my-0"><strong class="mr-1">Upazila:</strong>{{optional($student->upazila)->name}}</p>
								</td>
								<td>{{$student->otherInfo}}</td>
								<td>{{$student->reference->refName}}</td>
								<td>
									@if($student->status == 1)
									<span>Active</span>
									@elseif($student->status == 2)
									<span>Waiting</span>
									@elseif($student->status == 3)
									<span>Dump</span>
									@else
									<span>Inactive</span>
									@endif
								</td>
								<td>
								<a href="" class="text-success"><i class="fas fa-eye mr-2"></i>Invoice Student</a><br/>
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