@extends('layout.master')
@section('title', 'Payment List')
@push('styles')
<style>
	.form-control-sm {
  	font-size: small;
	}
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Payment</a></li>
                    <li class="breadcrumb-item active">Back</li>
                </ol>
            </div>
            <h4 class="page-title">Payment No #</h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card-box">
            <form action="{{ route(currentUser().'.payment.update',[encryptor('encrypt', $paymentdetl->id)]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-sm-3">
                    <label for="name" class="col-form-label">Student ID</label>
                    <input type="text" id="sId" class="form-control" value="{{$sdata->id}}" readonly name="studentId">
                    <input type="hidden" value="" name="userId">
                </div>
                <div class="col-sm-3">
                    <label for="name" class="col-form-label">Student Name</label>
                    <input type="text" class="form-control" value="{{$sdata->name}}" readonly>
                </div>
                <div class="col-sm-3">
                    <label for="name" class="col-form-label">Executive</label>
                    <input type="text" class="form-control" value="{{$sdata->exName}}" readonly name="executiveId">
                </div>
            </div>
            <h5 style="font-size:18px;line-height:70px;">Recipt Details</h5>
            <table class="table table-sm table-bordered mb-5 text-center">
                <thead>
                    <tr>
                        <th><strong>Money Receipt No: </strong></th>
                        <th><strong>Payment Date:</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    //echo '<pre>';
                    //print_r($paymentdetl->toArray());die;
                    @endphp
                    <tr>
                        <td>
                            <input type="text" id="mrNo" class="form-control" name="mrNo" class="form-control" value="{{ $paymentdetl->paymentDetail->first()->mrNo }}">
                            <div class="invalid-feedback" id="mrNo-error"></div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="paymentDate" class="form-control" placeholder="dd/mm/yyyy" value="{{ date('d-m-Y',strtotime($paymentdetl->paymentDate)) }}">
                                
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                                <div class="invalid-feedback" id="paymentDate-error"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h5 style="font-size:18px;line-height:70px;">Payment details</h5>
            <table class="table table-sm table-bordered mb-5 text-center">
                <thead>
                    <tr>
                        <th>Batch|Enroll Date</th>
                        <th width="90px">Inv</th>
                        <th width="100px">Price</th>
                        <th width="120px">Type</th>
                        <th width="160px">Due Date</th>
                        <th width="120px">Mode</th>
                        <th width="120px">Fee Type</th>
                        <th width="110px">Discount</th>
                        <th width="110px">Amount</th>
                        <th width="100px">Due</th>
                    </tr>
                </thead>
                @php $tPayable =0; @endphp
                @foreach($paymentdetl->paymentDetail as $key=> $p)   
                @php 
                    $tPayable += ($p->batch->studentsBatches[0]->course_price-($p->cpaidAmount+$p->discount));
                @endphp
                <tr>
                    <td>
                        <p class="my-0">{{$p->batch->batchId}}</p>
                        <p class="my-0">{{$p->batch->studentsBatches[0]->course_price}}</p>
                    </td>
                    <input type="hidden" name="id[]" value="{{$p->id}}">
                    <input type="text" name="batch_id[]" value="{{$p->batchId}}">
                    <td><input type="text" id="invoiceId" class="form-control" name="invoiceId[]" class="form-control"></td>
                    <td><input type="text" class="form-control" readonly value="{{$p->batch->studentsBatches[0]->course_price}}"></td>
                    <td><select class="form-control" name="payment_type[]">
                            <option value=""></option>
                            <option value="1" @if($p->payment_type == 1) selected @endif>Full</option>
                            <option value="2" @if($p->payment_type == 2) selected @endif>Partial</option>
                        </select></td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="dueDate[]" onfocus="dueDate({{$key}},'{{ $p->dueDate }}')" class="dueDate_{{$key}} form-control" placeholder="dd/mm/yyyy" value="{{ date('d-m-Y',strtotime($p->dueDate)) }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </td>
                    <td><select class="form-control" name="payment_mode[]">
                            <option value=""></option>
                            <option value="1" @if($p->payment_mode == 1) selected @endif>Cash</option>
                            <option value="2" @if($p->payment_mode == 2) selected @endif>Bkash</option>
                            <option value="3" @if($p->payment_mode == 3) selected @endif>Card</option>
                        </select></td>
                    <td><select class="form-control" id="feeType" name="feeType[]">
                            <option value="">Select</option>
                            <option value="1" @if($p->feeType == 1) selected @endif>Reg</option>
                            <option value="2" @if($p->feeType == 2) selected @endif>Course</option>
                        </select></td>
                    <td><input type="text" name="discount[]" class="paidpricebyRow form-control" value="{{$p->discount}}" id="discountbyRow_{{$key}}" onkeyup="checkPrice()"></td>
                    <td><input type="text" name="cpaidAmount[]" class="paidpricebyRow form-control" value="{{$p->cpaidAmount}}" required id="paidpricebyRow_{{$key}}" onkeyup="checkPrice()"></td>
                    <td><input name="cPayable[]" type="text" class="form-control" readonly value="{{($p->batch->studentsBatches[0]->course_price-($p->cpaidAmount+$p->discount))}}" id="coursepricebyRow_{{$key}}"></td>
                </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th colspan="8" class="text-right">Sub Total</th>
                        <td><input type="text" name="tPayable" class="tPayable form-control" name="tPayable" value="{{ $paymentdetl->tPayable }}" readonly></td>
                    </tr>
                    <tr>
                        <th colspan="8" class="text-right">Total Paid</th>
                        <td>
                            <input type="text" name="paidAmount" class="tPaid form-control" readonly  value="{{ $paymentdetl->paidAmount }}">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="8" class="text-right">Total Due</th>
                        <td><input type="text" class="tDue form-control" readonly value="{{ ($paymentdetl->tPayable - $paymentdetl->paidAmount) }}"></td>
                    </tr>
                </tfoot>
            </table>
            <div class="col-lg-12 row">
                <label for="accountNote" class="col-sm-2 col-form-label">Note</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="executiveNote" name="accountNote" rows="5" placeholder="Account Note" style="
										resize:none;">{{ $paymentdetl->accountNote }}</textarea>
                </div>
            </div>
            <div class="float-right mt-2">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit-btn">Update Payment</button>
            </div>
            <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $('input[name="paymentDate"]').daterangepicker({
        singleDatePicker: true,
        startDate: new Date('{{$paymentdetl->paymentDate }}'),
        showDropdowns: true,
        autoUpdateInput: true,
        format: 'dd/mm/yyyy',
    }).on('changeDate', function(e) {
        var date = moment(e.date).format('YYYY/MM/DD');
        $(this).val(date);
    });
    function dueDate(index,due_date) {
        $('.dueDate_'+index).daterangepicker({
        singleDatePicker: true,
        startDate: new Date( due_date),
        showDropdowns: true,
        autoUpdateInput: true,
        format: 'dd/mm/yyyy',
        }).on('changeDate', function(e) {
            var date = moment(e.date).format('YYYY/MM/DD');
            $(this).val(date);
        });
    }  
/*=== Check Input Price== */
function checkPrice(index){
    var paidpricebyRow 		= parseFloat($('#paidpricebyRow_'+index).val());
    var coursepricebyRow 	= parseFloat($('#coursepricebyRow_'+index).val());
    /*To Calculate discount With Paid Price */
    paidpricebyRow 	+= parseFloat($('#discountbyRow_'+index).val())?parseFloat($('#discountbyRow_'+index).val()):0;
    /*console.log(paidpricebyRow);
    console.log(coursepricebyRow);*/
    var tPayable = parseFloat($('.tPayable').val());
    if(paidpricebyRow > coursepricebyRow){
        toastr["warning"]("Paid Amount Cannot be Greater Than Course Price!!");
        return false;
    }else{
        var total = 0;
        $('.paidpricebyRow').each(function(index, element){
        if($(element).val()!="")
        total += parseFloat($(element).val());

    });
    $('.tPaid').val(total);
    $('.tDue').val(tPayable-total);
    }
}      
</script>
@endpush