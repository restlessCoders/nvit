@extends('layout.master')
@section('title', 'Add New Payment')
@push('styles')
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')


<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Money Receipt</a></li>
					<li class="breadcrumb-item active">Invoice</li>
				</ol>
			</div>
			<h4 class="page-title">Invoice</h4>
		</div>
	</div>
</div>
<!-- end page title -->
<form action="{{route(currentUser().'.payment.store')}}" method="POST">
	@csrf
<div class="row">
	<div class="col-12">
		<div class="card-box">
			<!-- <div class="panel-heading">
                                <h4>Invoice</h4>
                            </div> -->
			<div class="panel-body">
				<div class="clearfix">
					<div class="float-sm-left">
						<h4 class="text-uppercase mt-0">New Vision Information Technology</h4>
						<strong>Executive Name:-{{ $sdata->executive->name}}</strong>
					</div>
					<div class="float-sm-right mt-4 mt-sm-0">
						<h5>MR # <br>
							<small>2016-04-23654789</small>
						</h5>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-12">
						<div class="float-sm-left mt-4">
							<address>
								<strong>{{ $sdata->name }}</strong><br>
								{{ $sdata->address }}<br>
								{{ $sdata->email }}<br>
								<abbr title="Phone">P:</abbr> {{ $sdata->contact }}
							</address>
						</div>
						<div class="col-lg-3 float-right">
							<strong>Money Receipt No: </strong> <input type="text" name="mrNo" class="form-control" value="{{old('mrNo')}}" placeholder="Mr no">
							@if($errors->has('mrNo'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('mrNo') }}
                            </small>
                            @endif
							<strong>Invoice No: </strong> <input type="text" name="invoiceId" class="form-control" placeholder="Invoice No">
							<strong>Date: </strong>
							<div class="input-group">
								<input type="text" name="paymentDate" class="form-control" value="{{ old('paymentDate') }}" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true">
								<div class="input-group-append">
									<span class="input-group-text"><i class="icon-calender"></i></span>
								</div>
							</div><!-- input-group -->
							@if($errors->has('paymentDate'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('paymentDate') }}
                            </small>
                            @endif
						</div>
						<div class="text-sm-right">

							<!-- <p><strong>Order Status: </strong> <span class="badge badge-danger">Pending</span></p> -->

						</div>
					</div><!-- end col -->
				</div>
				<!-- end row -->

				<div class="row mt-4">
					<div class="col-12">
					
						@csrf
						<input type="hidden" value="{{ Session::get('user') }}" name="userId">
						<div class="table-responsive">
							<table class="table table-nowrap">
								<thead>
									<tr>
										<th>Course Name</th>
										<th>Price</th>
										<th>Discount</th>
										<!-- <th>Type</th> -->
										<th>Due Date</th>
										<th>Payment</th>
									</tr>
								</thead>
								<tbody>
									<input type="hidden" name="studentId" value="{{encryptor('encrypt',$sdata->id)}}">
									<input type="hidden" name="executiveId" value="{{$sdata->executiveId}}">
									@php
									$tPayable =0;
									@endphp
									@foreach($sdata->enroll_data as $s)
									<input type="hidden" name="courseId[]" value="{{ $s->id }}">
									<tr>
										<td><input type="text" class="form-control" value="{{ $s->courseName }}" readonly></td>
										@php
											$batch = \DB::table('batches')->where('courseId',$s->id)->first();
											$tPayable += $batch->price;
										@endphp
										<input type="hidden" name="price[]" value="{{$batch->price}}">
										<input type="hidden" name="discount[]" value="{{$batch->discount}}">
										<td><input type="text" class="form-control" value="{{$batch->discount}}" readonly></td>
										<td><input type="text" class="form-control" value="{{$batch->discount}}" readonly></td>
										<!-- <td>
											<select class="form-control" name="type">
												<option value="">Select</option>
												<option value="1">Full</option>
												<option value="2">Partial</option>
											</select>
										</td> -->
										<td>
											<div class="input-group">
												<input type="text" name="dueDate[]" class="form-control" value="{{ old('endDate') }}" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true">
												<div class="input-group-append">
													<span class="input-group-text"><i class="icon-calender"></i></span>
												</div>
											</div><!-- input-group -->
										</td>
										<td><input type="text" class="form-control" value="{{$batch->price}}"></td>
									</tr>
									@endforeach
									<input type="hidden" name="invoiceId">
								</tbody>
								<tfoot>
									<tr>
										<th colspan="4" class="text-right">Copun Discount</th>
										<td>
											<input type="text" class="coupon form-control">
											<button class="btn btn-primary btn-sm my-1" onclick="coupon()">Apply</button>
										</td>
									</tr>
									<tr>
										<th colspan="4" class="text-right">Sub Total</th>
										<td><input type="text" name="tPayable" class="tPayable form-control" name="tPayable" value="{{$tPayable}}" readonly></td>
									</tr>
									<tr>
										<th colspan="4" class="text-right">Total Paid</th>
										<td>
											<input type="text" name="paidAmount" class="tPaid form-control" onkeyup="calculate()">
											@if($errors->has('paidAmount'))
											<small class="d-block text-danger mb-3">
												{{ $errors->first('paidAmount') }}
											</small>
											@endif
										</td>
									</tr>
									<tr>
										<th colspan="4" class="text-right">Total Due</th>
										<td><input type="text" class="tDue form-control"></td>
									</tr>
									<!-- <tr>
										<th colspan="4" class="text-right">Balance</th>
										<td><input type="text" class="bal form-control"></td>
									</tr> -->
								</tfoot>
							</table>
							<div class="col-lg-12 row">
                        		<label for="accountNote" class="col-sm-2 col-form-label">Note</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="executiveNote" name="accountNote" rows="5" placeholder="Account Note" style="
										resize:none;"></textarea>
								</div>
                    		</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="clearfix mt-5">
							<h5 class="small"><b>PAYMENT TERMS AND POLICIES</b></h5>

							<small class="text-muted">
								All accounts are to be paid within 7 days from receipt of
								invoice. To be paid by cheque or credit card or direct payment
								online. If account is not paid within 7 days the credits details
								supplied as confirmation of work undertaken will be charged the
								agreed quoted fee noted above.
							</small>
						</div>
					</div>
					<div class="col-sm-6">
						
					</div>
				</div>
				<hr>
				<div class="d-print-none">
					<div class="float-right">
						<a href="javascript:window.print()" class="btn btn-dark waves-effect waves-light"><i class="fa fa-print"></i></a>
						<button type="submit" class="btn btn-primary waves-effect waves-light">Payment</button>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>

	</div>

</div>
</form>


	@endsection
	@push('scripts')
	<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
	<script>
		/*$('form').submit(function( event ) {
			event.preventDefault();
		});*/
		function coupon(){
		}
		function calculate(){
			var tPayable = parseFloat($('.tPayable').val());
			var tPaid = parseFloat($('.tPaid').val());
			//var coupon = parseFloat($('.coupon').val())?parseFloat($('.coupon').val()):0;

			if(tPaid > tPayable){
				alert("Paid Amount larger Than Payable Amount");
				$('.tPaid').val('');
				$('.tDue').val('');
			}else if(tPaid == tPayable){
				$('.tDue').val(0);
			}else{
				$('.tDue').val(tPayable-tPaid);
			}
			
		}
		$('.js-example-basic-single').select2({
			placeholder: 'Select Option',
			allowClear: true
		});
		$('input[name="startDate"],input[name="endDate"],input[name="examDate"]').daterangepicker({
			singleDatePicker: true,
			startDate: new Date(),
			showDropdowns: true,
			autoUpdateInput: true,
			locale: {
				format: 'DD/MM/YYYY'
			}
		});
		$("#timepicker").timepicker({
			defaultTIme: !1,
			icons: {
				up: "mdi mdi-chevron-up",
				down: "mdi mdi-chevron-down"
			}
		});
	</script>
	@endpush