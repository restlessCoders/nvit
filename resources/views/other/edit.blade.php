
@extends('layout.master')
@section('title', 'Edit Other Payment')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Edit Other Collection</a></li>
					<li class="breadcrumb-item active">Add</li>
				</ol>
			</div>
			<h4 class="page-title">Edit</h4>
		</div>
	</div>
	@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
	@endif
	<div class="col-12">
		<div class="card-box">
			<form action="{{ route(currentUser() . '.payments.update', encryptor('encrypt', $data->id)) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- ✅ Required for PUT method -->
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Type: <span class="text-danger sup">*</span></label>
						<select name="other_payment_category_id" class="js-example-basic-single form-control select2 @if($errors->has('other_payment_category_id')) {{ 'is-invalid' }} @endif">
							<option></option>
							@if(count($payment_categories) > 0)
							@foreach($payment_categories as $p)
							<option value="{{ $p->id }}" {{ old('other_payment_category_id',$data->other_payment_category_id) == $p->id ? "selected" : "" }}>{{ $p->category_name }}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('other_payment_category_id'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('other_payment_category_id') }}
						</small>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Pay By<span class="text-danger sup">*</span></label>
						<input id="pay_by" type="text" class="form-control" name="pay_by" value="{{ old('pay_by',$data->pay_by) }}">
						@if($errors->has('pay_by'))
							<span class="text-danger"> {{ $errors->first('pay_by') }}</span>
						@endif
					</div>
					<div class="col-lg-4">
						<label>Payment Date<span class="text-danger sup">*</span></label>
						<div class="input-group">
							<input type="date" name="paymentDate" class="form-control" value="{{ old('paymentDate',$data->paymentDate) }}" placeholder="dd/mm/yyyy">
							<div class="input-group-append">
								<span class="input-group-text"><i class="icon-calender"></i></span>
							</div>
						</div><!-- input-group -->
					</div>
					
					<div class="col-lg-4">
						<label class="control-label">Payment Type: </label>
						<select name="payment_mode" class="form-control">
							<option value="1" @if($data->payment_mode == 1) selected @endif>Cash</option>
							<option value="2" @if($data->payment_mode == 2) selected @endif>Bank</option>
							<option value="2" @if($data->payment_mode == 3) selected @endif>Card</option>
						</select>
					</div>
					<div class="col-lg-2">
						<label class="control-label">Amount: </label>
						<input type="text" name="amount" class="form-control" required value="{{old('amount',$data->amount)}}">
					</div>
					<div class="col-lg-2">
						<label class="control-label">MR No </label>
						<input type="text" name="mrNo" class="form-control" required value="{{old('mrNo',$data->mrNo)}}">
					</div>
					<div class="col-lg-12">
						<label class="control-label">Remarks: </label>
						<textarea name="accountNote" class="form-control" rows="5" style="resize:none;">{{old('accountNote',$data->accountNote)}}</textarea>
					</div>
				</div>
				<div class="form-group text-right mb-0">
					<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
						Update
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
	<script>
		$('.js-example-basic-single').select2({
			placeholder: 'Select Option',
			allowClear: true
		});

		/*$("input[name='paymentDate']").daterangepicker({
                    singleDatePicker: true,
                    startDate: new Date(),
                    showDropdowns: true,
                    autoUpdateInput: true,
                    format: 'dd/mm/yyyy',
                }).on('changeDate', function(e) {
                    var date = moment(e.date).format('YYYY/MM/DD');
                    $(this).val(date);
                });*/
		
	</script>
	@endpush