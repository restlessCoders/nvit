@extends('layout.master')
@section('title', 'Add New Payment Transfer')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Payment transfer</a></li>
					<li class="breadcrumb-item active">Add</li>
				</ol>
			</div>
			<h4 class="page-title">Add New Payment Transfer</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<form action="{{ route(currentUser().'.payment-transfer.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" value="{{ Session::get('user') }}" name="userId">
				<div class="row">
					<div class="col-lg-3">
						<label>Posting Date: <span class="text-danger sup">*</span></label>
						<div class="input-group">
							<input type="text" name="postingDate" class="form-control" placeholder="dd/mm/yyyy">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<label>Select Student: <span class="text-danger sup">*</span></label>
						<select name="studentId" class="js-example-basic-single form-control select2 @if($errors->has('studentId')) {{ 'is-invalid' }} @endif">
							<option></option>
							@if(count($students) > 0)
							@foreach($students as $s)
							<option value="{{ $s->id }}" {{ old('studentId') == $s->id ? "selected" : "" }}>{{ $s->id }}-{{ $s->name }}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('studentId'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('studentId') }}
						</small>
						@endif
					</div>
					<div class="col-lg-3">
						<label>From Executive: <span class="text-danger sup">*</span></label>
						<select name="from_exe_id" class="js-example-basic-single form-control select2 @if($errors->has('from_exe_id')) {{ 'is-invalid' }} @endif">
							<option></option>
							@if(count($executives) > 0)
							@foreach($executives as $e)
							<option value="{{ $e->id }}" {{ old('from_exe_id') == $e->id ? "selected" : "" }}>{{ $e->username }}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('mrNo'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('mrNo') }}
						</small>
						@endif
					</div>
					<div class="col-lg-3">
						<label>To Executive: <span class="text-danger sup">*</span></label>
						<select name="to_exe_id" class="js-example-basic-single form-control select2 @if($errors->has('to_exe_id')) {{ 'is-invalid' }} @endif">
							<option></option>
							@if(count($executives) > 0)
							@foreach($executives as $pt)
							<option value="{{ $pt->id }}" {{ old('to_exe_id') == $pt->id ? "selected" : "" }}>{{ $pt->username }}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('to_exe_id'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('to_exe_id') }}
						</small>
						@endif
					</div>
					<div class="col-lg-3">
						<label>Amount<span class="text-danger sup">*</span></label>
						<input id="amount" type="text" class="form-control" name="amount" value="{{ old('amount') }}">
					</div>
				</div>
				<div class="form-group text-right mt-1">
					<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
						Submit
					</button>
					<button type="reset" class="btn btn-secondary waves-effect">
						Cancel
					</button>
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
			$('input[name="postingDate"]').daterangepicker({
				singleDatePicker: true,
				startDate: new Date(),
				showDropdowns: true,
				autoUpdateInput: true,
				format: 'dd/mm/yyyy',
			}).on('changeDate', function(e) {
				var date = moment(e.date).format('YYYY/MM/DD');
				$(this).val(date);
			});
		</script>
		@endpush