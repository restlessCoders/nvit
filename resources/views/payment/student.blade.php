@extends('layout.master')
@section('title', 'Add New Payment')
@push('styles')
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
	.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('https://lkp.dispendik.surabaya.go.id/assets/loading.gif') 50% 50% no-repeat rgb(249,249,249);
}
.table-bordered thead td, .table-bordered thead th{
	border-bottom-width: 1px;
}
</style>
@endpush
@section('content')

<div class="loader"></div>
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
						<strong>Executive Name:-{{ $stdetl->executive->name}}</strong>
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
								<strong>{{ $stdetl->name }}</strong><br>
								{{ $stdetl->address }}<br>
								{{ $stdetl->email }}<br>
								<abbr title="Phone">P:</abbr> {{ $stdetl->contact }}
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
					<div class="col-md-12">
					
						@csrf
						<input type="hidden" value="{{ Session::get('user') }}" name="userId">
						<div class="table-responsive">
							<table class="table table-bordered mb-5 text-center"">
								<thead>
									<tr>
										<th colspan="4">Payment Details</th>
									</tr>
									<tr>
										<th>Batch</th>
										<th>Price</th>
										<!-- <th>Discount</th>
										<th>Type</th> -->
										<th>Due Date</th>
										<th>Payment</th>
									</tr>
								</thead>
								<tbody>
									<input type="hidden" name="studentId" value="{{encryptor('encrypt',$stdetl->id)}}">
									<input type="hidden" name="executiveId" value="{{$stdetl->executiveId}}">
									@php
									$tPayable =0;
									@endphp
									@foreach($sdata as $s)
									<input type="hidden" name="batch_id[]" value="{{ $s->batch_id }}">
									<tr>
										@php
										$batches = \DB::table('batches')->where('id',$s->batch_id)->first();
										$course = \DB::table('courses')->where('id',$batches->courseId)->first();
										@endphp
										<td>
											<input type="text" class="form-control" value="{{$batches->batchId}}" readonly>
										</td>
										@php
										$packages = \DB::select("SELECT * from packages where '$s->entryDate' BETWEEN startDate and endDate and batchId = $s->batch_id");
										//echo '<pre>';
										//print_r($course);
										@endphp
										<td>
											<input type="text" class="form-control" name="cPayable[]" value="@if($packages){{$packages[0]->price}}@else{{$course->rPrice}}@endif" readonly id="coursepricebyRow_{{$loop->index}}">
											<input type="checkbox" value="{{$course->mPrice}}" id="material_{{$loop->index}}" onclick="checkMaterial('{{$loop->index}}')"><span>Material Price: {{$course->mPrice}}</span>
											<input type="text" name="m_price[]" id="m_price_{{$loop->index}}">
										</td>
										@php
										if($packages){
											$tPayable += $packages[0]->price;
										@endphp
										<!-- <td>
											<input type="text" class="form-control" value="{{$packages[0]->price}}" readonly>
											<input type="checkbox" checked><span>Material Price: {{$course->mPrice}}</span>
										</td> -->
										@php
										}
										else{
											$tPayable += $course->rPrice;
										@endphp
										<!-- <td>
											<input type="text" class="form-control" value="{{$course->rPrice}}" readonly>
											<input type="checkbox" checked><span>Material Price: {{$course->mPrice;}}</span>
										</td> -->
										@php
										}
										@endphp
									
										

										<!-- <input type="hidden" name="price[]" value="{{$s->batch_id}}">
										<input type="hidden" name="discount[]" value="{{$s->batch_id}}"> -->
									
										<!-- <td><input type="text" class="form-control" value="{{$s->batch_id}}" readonly></td> -->
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
										<td><input type="text" name="cpaidAmount[]" class="paidpricebyRow form-control" id="paidpricebyRow_{{$loop->index}}" onkeyup="checkPrice('{{$loop->index}}')"></td>
									</tr>
									<tr>
										<th colspan="4">Payment History</th>
									</tr>
									<tr>
										<th>Batch</th>
										<th>Price</th>
										<th>Due Date</th>
										<th>Payment</th>
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<th colspan="3" class="text-right">Copun Discount</th>
										<td>
											<input type="text" class="coupon form-control" name="discount">
											<button type="button" class="btn btn-primary btn-sm my-1" onclick="coupon()">Apply</button>
										</td>
									</tr>
									<tr>
										<th colspan="3" class="text-right">Sub Total</th>
										<td><input type="text" name="tPayable" class="tPayable form-control" name="tPayable" value="{{$tPayable}}" readonly></td>
									</tr>
									<tr>
										<th colspan="3" class="text-right">Total Paid</th>
										<td>
											<input type="text" name="paidAmount" class="tPaid form-control" readonly>
											@if($errors->has('paidAmount'))
											<small class="d-block text-danger mb-3">
												{{ $errors->first('paidAmount') }}
											</small>
											@endif
										</td>
									</tr>
									<tr>
										<th colspan="3" class="text-right">Total Due</th>
										<td><input type="text" class="tDue form-control" readonly></td>
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
		function checkMaterial(index){
			$('#material_'+index).prop("checked") == true;
			var total=0.0;
			var tPayable = parseFloat($('.tPayable').val());
			if($('#material_'+index).prop("checked") == true)
		{
		$('#m_price_'+index).val($('#material_'+index).val())
		total =parseFloat(tPayable)+ parseFloat($('#material_'+index).val());
		} else {
		total =parseFloat(tPayable)- parseFloat($('#material_'+index).val());
		$('#m_price_'+index).val(0)
		}
		$('.tPayable').val(total);
		coupon();
		checkPrice(index)
		}
	
		function coupon(){
			var coupon = parseFloat($('.coupon').val())?parseFloat($('.coupon').val()):0;
			var tPayable = parseFloat($('.tPayable').val());
			var tPaid = parseFloat($('.tPaid').val());
			if(coupon > tPayable){
			toastr["warning"]("Coupon Amount Cannot be Greater Than Course Price!!");
			return false;
			}
			/*alert(tPayable)
			alert(tPaid)
			alert(coupon)*/
			$('.tDue').val(tPayable-tPaid-coupon);
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
		/*=====Un used Code====*/
		/*$(document).on('submit','#addform',function(e){
		$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    	});
        e.preventDefault();
		$.ajax({
           url:"{{route(currentUser().'.payment.store')}}",
		   type:'POST',
           dataType: "json",
			data: new FormData(this),
			processData:false,
			contentType:false,
			beforeSend:function(){
				console.log("Wait data is Loading.......");
			},
			success:function(response){
				console.log(response);
			},
			error:function(request,error){
				console.log(arguments);
				console.log("Error:"+error);
			}
        });
	});*/
	/*== To check Document Loading finished== */
	$(window).on('load', function(){
		$(".loader").fadeOut("slow");
	});
    


	/*$('input[type=checkbox]').each(function () {
		$(this).prop('checked',true);
		var tPayable = parseFloat($('.tPayable').val());
		tPayable += parseFloat($(this).val());
		$('.tPayable').val(tPayable)
 	});*/
	/*=== Check Input Price== */
	function checkPrice(index){
		var paidpricebyRow 		= parseFloat($('#paidpricebyRow_'+index).val());
		var coursepricebyRow 	= parseFloat($('#coursepricebyRow_'+index).val());
		/*console.log(paidpricebyRow);
		console.log(coursepricebyRow);*/
		var tPayable = parseFloat($('.tPayable').val());
		/*if(paidpricebyRow > coursepricebyRow){
			toastr["warning"]("Payable Amount Cannot be Greater Than Course Price!!");
			return false;
		}else{*/
			var total = 0;
			$('.paidpricebyRow').each(function(index, element){
			if($(element).val()!="")
            total += parseFloat($(element).val());
			
			
			

		});
		$('.tPaid').val(total);
		$('.tDue').val(tPayable-total);
		coupon();
		//}
	}
	/*===== Payment Calculation======*/
	
	function calculate(){
		
	}
	
	</script>
	@endpush