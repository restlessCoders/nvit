@extends('layout.master')
@section('title', 'Bundle Course Batch Assign')
@push('styles')
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Bundel Course</a></li>
					<li class="breadcrumb-item active">Batch Assign</li>
				</ol>
			</div>
			<h4 class="page-title">Bundel Course Batch Assign</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<div class="form-group row">
				<div class="col-md-6 col-xl-6 offset-md-3 text-center">
					<div class="card-box tilebox-one">
						<h4>Name:- {{\DB::table('students')->where('id',$enroll_data->student_id)->first()->name}}</h4>
						<h5>ID:- {{$enroll_data->student_id}}</h5>
						<span class="badge badge-success mr-1" style="font-size:large;">Bundel Course Name:- {{\DB::table('courses')->where('id',$enroll_data->course_id)->first()->courseName}}</span>
					</div>
				</div>
				<div class="col-lg-12">
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<th>Bundel Sub Course Name</th>
								<th>Select Batch</th>
								<th>Assign Batch</th>
							</tr>
						</thead>
						<tbody>
							@php $bundel_courses = \DB::table('bundel_course_enroll')->where('main_course_id',$enroll_data->id)->get(); @endphp
							@forelse($bundel_courses as $bc)
							<form action="{{route(currentUser().'.assign_batch_toEnrollStudent',[encryptor('encrypt', $bc->id)])}}" method="POST" enctype="multipart/form-data">
								@csrf
								<input type="hidden" value="{{$bc->sub_course_id}}" name="course_id">
								<input type="hidden" value="{{$bc->student_id}}" name="student_id">
								<input type="hidden" value="{{$bc->id}}" name="bundel_id">
								<input type="hidden" value="{{$bc->systemId}}" name="systemId">
								<tr>
									<td>{{\DB::table('courses')->where('id',$bc->sub_course_id)->first()->courseName}}</td>
									<td width="350px">
										<select name="batch_id" class="js-example-basic-single form-control select2 @if($errors->has('batch_id')) {{ 'is-invalid' }} @endif">
											<option></option>
											@if(count($batches) > 0)
											@foreach($batches as $b)
											@php $bundel = \DB::table('student_batches')->where(['student_id'=> $bc->student_id,'course_id'=> $bc->sub_course_id,'systemId'=> $bc->systemId])->first();@endphp
											<option value="{{ $b->id }}" @if(!empty($bundel->batch_id)) {{ old('batch_id',$b->id) == $bundel->batch_id ? "selected" : "" }} @endif>{{ $b->batchId }}</option>
											@endforeach
											@endif
										</select>
										@if($errors->has('batch_id'))
										<small class="d-block text-danger mb-3">
											{{ $errors->first('batch_id') }}
										</small>
										@endif
									</td>
									@if($bc->status == 2)
									<td>
										<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
											Assign Batch
										</button>
									</td>
									@endif
								</tr>
							</form>
							@empty
							@endforelse
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
	@endsection
	@push('scripts')
	<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
	<script>
		$('.js-example-basic-single').select2({
			placeholder: 'Select Option',
			allowClear: true
		});
	</script>
	@if(Session::has('response'))
	@php print_r(Session::has('response')); @endphp
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