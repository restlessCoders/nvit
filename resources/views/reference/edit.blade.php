@extends('layout.master')
@section('title', 'Edit Reference')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Reference</a></li>
					<li class="breadcrumb-item active">Edit</li>
				</ol>
			</div>
			<h4 class="page-title">Edit Reference</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<form action="{{ route(currentUser().'.reference.update',[encryptor('encrypt', $reference->id)]) }}" method="POST" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<input type="hidden" value="{{ Session::get('user') }}" name="userId">
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Reference: <span class="text-danger sup">*</span></label>
						<input type="text" name="refName" value="{{ old('refName',$reference->refName) }}" class="form-control @if($errors->has('refName')) {{ 'is-invalid' }} @endif" placeholder="Refernece" />
						@if($errors->has('refName'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('refName') }}
						</small>
						@endif
					</div>
				</div>
				<div class="form-group text-right mb-0">
					<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
						Submit
					</button>
					<button type="reset" class="btn btn-secondary waves-effect">
						Cancel
					</button>
				</div>
		</div>
	</div>
	@endsection
	@push('scripts')
	<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
	@endpush