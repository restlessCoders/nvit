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
            <form action="{{ route(currentUser().'.newStore') }}" method="POST">
                @csrf
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
                <h5 style="font-size:18px;line-height:70px;">Payment details</h5>
                <table class="table table-sm table-bordered mb-5 text-center">
                    <thead>
                        <tr>
                            <th>Batch|Enroll Date</th>
                            <th width="85px">Mr No</th>
                            <th width="80px">Invoice</th>
                            <th width="90px">Invoice Price</th>
                            <th width="160px">Payment Date</th>
                            <th width="120px">Type</th>
                            <th width="160px">Due Date</th>
                            <th width="120px">Mode</th>
                            <th width="120px">Fee Type</th>
                            <th width="110px">Discount</th>
                            <th width="110px">Amount</th>
                            <!-- <th width="100px">Due</th> -->
                        </tr>
                    </thead>
                    @php $tPayable =0; $paidAmt =0; $coursPayable = 0; @endphp
                    @foreach($paymentdetl as $key=> $p)
                    @php
                    $payment = DB::table('payments')->where('id',$p->paymentId)->first();
                    @endphp
                    <tr>
                        <td>
                            <p class="my-0">{{\DB::table('batches')->where('id',$p->batchId)->first()->batchId}}</p>
                            <p class="my-0"></p>
                        </td>
                        <input type="text" name="id[]" value="{{$p->id}}"><!-- Payment Details Table ID -->
                        <input type="text" name="pid[]" value="{{$p->id}}"><!-- Payments Table ID -->
                        <input type="hidden" name="batch_id[]" value="{{$p->batchId}}">
                        <td><input type="text" class="form-control" name="mrNo[]" value="{{$payment->mrNo}}"></td>
                        <td><input type="text" class="form-control" name="invoiceId[]" value="{{$payment->invoiceId}}"></td>
                        <td><input type="text" class="form-control" readonly value="{{$p->cPayable}}"></td>
                        <td>

                            <div class="input-group">
                                <input type="text" name="paymentDate[]" value="{{ $payment->paymentDate }}" onfocus="dueDate({{$key}},'{{ $payment->paymentDate }}')" class="dueDate_{{$key}} form-control" placeholder="dd/mm/yyyy">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </td>
                        <td><select class="form-control" name="payment_type[]">
                                <option value=""></option>
                                <option value="1" @if($p->payment_type == 1) selected @endif>Full</option>
                                <option value="2" @if($p->payment_type == 2) selected @endif>Partial</option>
                            </select></td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="dueDate[]" value="{{ $p->dueDate }}" onfocus="dueDate({{$key}},'{{ $p->dueDate }}')" class="dueDate_{{$key}} form-control" placeholder="dd/mm/yyyy">
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
                        <td><input type="text" name="discount[]" class="paidpricebyRow form-control" value="{{$p->discount}}" id="discountbyRow_{{$key}}" onkeyup="checkPrice({{$key}})"></td>
                        <td><input type="text" name="cpaidAmount[]" class="paidpricebyRow form-control" value="{{$p->cpaidAmount}}" required id="paidpricebyRow_{{$key}}" onkeyup="checkPrice({{$key}})"></td>
                        <input type="hidden" class="form-control" readonly value="{{($studentsBatches->course_price)}}" id="coursepricebyRow_{{$key}}">
                        <!-- <tr>
                        <td colspan="1">
                            <label for="accountNote" class="col-sm-2 col-form-label">Note</label>
                        </td>
                        <td colspan="11">
                            <textarea class="form-control" id="executiveNote" name="accountNote" rows="2" placeholder="Account Note" style="
										resize:none;">{{$payment->accountNote}}</textarea>
                        </td>
                    </tr> -->
                    </tr>
                    @endforeach
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total Payable</th>
                            <td><input type="text" name="tPayable" class="tPayable form-control" value="{{($studentsBatches->course_price)}}" readonly></td>
                            <th colspan="6" class="text-right">Total Due</th>
                            <td><input type="text" class="tDue form-control" readonly {{--value="{{ $tPayable }}"--}}></td>

                        </tr>
                    </tfoot>
                </table>

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
        startDate: new Date(),
        showDropdowns: true,
        autoUpdateInput: true,
        format: 'dd/mm/yyyy',
    }).on('changeDate', function(e) {
        var date = moment(e.date).format('YYYY/MM/DD');
        $(this).val(date);
    });

    function dueDate(index, due_date) {
        if (due_date)
            date = due_date;
        else
            date = new Date();
        $('.dueDate_' + index).daterangepicker({
            singleDatePicker: true,
            startDate: moment(date).format('DD/MM/YYYY'),
            showDropdowns: true,
            autoUpdateInput: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        }).on('changeDate', function(e) {
            var date = moment(e.date).format('YYYY/MM/DD');
            $(this).val(date);
        });
    }
    /*=== Check Input Price== */
    function checkPrice(index) {
        var paidpricebyRow = parseFloat($('#paidpricebyRow_' + index).val());
        //console.log(index)
        var coursepricebyRow = parseFloat($('#coursepricebyRow_' + index).val());
        //console.log(paidpricebyRow)
        //console.log(coursepricebyRow)
        /*To Calculate discount With Paid Price */
        paidpricebyRow += parseFloat($('#discountbyRow_' + index).val()) ? parseFloat($('#discountbyRow_' + index).val()) : 0;
        /*console.log(paidpricebyRow);
        console.log(coursepricebyRow);*/
        
        if (paidpricebyRow > coursepricebyRow) {
            toastr["warning"]("Paid Amount Cannot be Greater Than Course Price!!");
            $('#paidpricebyRow_' + index).val();
            /*window.location.reload();
            return false;*/
        } else {
            due();
        }
    }
    due();
    function due() {
        var tPayable = parseFloat($('.tPayable').val());
        var total = 0;
        $('.paidpricebyRow').each(function(index, element) {
            if ($(element).val() != "")
                total += parseFloat($(element).val());
        });
        $('.tPaid').val(total);
        $('.tDue').val(tPayable - total);
    }
    
</script>
@endpush