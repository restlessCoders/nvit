<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Paymentdetail;

use App\Http\Requests\Student\NewPaymentRequest as StudentNewPaymentRequest;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Student;
use Exception;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$allPayment = Payment::orderBy('id', 'DESC')->paginate(25);
        return view('payment.index', compact('allPayment'));*/
        return view('student.payment');
    }
    public function searchStData(Request $request)
    {
        $allStudent = DB::table('student_batches')
        ->join('students', 'student_batches.student_id', '=', 'students.id')
        ->join('users', 'students.executiveId', '=', 'users.id')
        ->select('students.id as sId','students.name as sName', 'users.name as exName')
        ->where('student_batches.student_id',$request->sdata)
        ->where('student_batches.status',2)
        //->>where('student_batches.pstatus',0)
        //->orWhere('students.name', 'like', '%'.$request->sdata.'%')
       // ->orWhere('students.id', $request->sdata)
       // ->orWhere('student_batches.batch_id', $request->sdata)
       // ->orWhere('students.executiveId', $request->sdata)
        ->groupBy('student_batches.student_id','students.id','students.name', 'users.name')
        ->get();
        return response()->json($allStudent);
    }
    public function databySystemId(Request $request)
    {
        $enrollStudent = DB::table('students')
        ->join('student_batches', function ($join) {
            $join->on('students.id', '=', 'student_batches.student_id')
                 ->where('student_batches.status', '=', '2')
                 ->where('student_batches.pstatus', '=', '0');
        })
        ->join('batches', 'student_batches.batch_id', '=', 'batches.id')
        ->select('student_batches.batch_id','batches.batchId')
        ->where('student_batches.systemId',$request->systemId)
        ->get();
        $data ='<div class="col-sm-3"><select class="js-example-basic-single form-control" id="batch_id" name="batch_id[]">';
        $data.='<option value="">All</option>';
        foreach($enrollStudent as $e){
            $data .='<option value="'.$e->batch_id.'">'.$e->batchId.'</option>';  
        }
        $data .= '</select></div>';

        $data .='<div class="col-sm-3" id="type"><select class="form-control" id="opt" name="type" onchange="paymentType(this.value)">';
        $data.='<option value="">Select</option>';
            $data .='<option value="1">Report</option>';  
            $data .='<option value="2">Batch</option>';  
            $data .='<option value="3">Course (No Batch)</option>';
            $data .='<option value="4">Others Payment</option>';    
        $data .= '</select></div>';
        return response()->json(array('data' =>$data));
    }
    public function enrollData(Request $request)
    {
        $enrollStudent = DB::table('student_batches')
        ->select('student_batches.systemId')
        ->where('student_batches.status', '=', '2')
        ->where('student_batches.pstatus', '=', '0')
        ->where('student_batches.student_id',$request->student_id)
        ->groupBy('student_batches.systemId')
        ->get();
        $data ='<div class="col-sm-3" id="systemId"><select class="js-example-basic-single form-control" id="systmVal" onchange="databySystemId(this.value);">';
        $data.='<option value="">Select</option>';
        foreach($enrollStudent as $e){
            $data .='<option value="'.$e->systemId.'">'.$e->systemId.'</option>';  
        }
        $data .= '</select></div>';
        /*==Student Data==*/
        $studentbyId =  DB::table('students')
                        ->select('students.id','students.name','students.executiveId','users.name as exName')
                        ->join('users', 'students.executiveId', '=', 'users.id')
                        ->where('students.id',$request->student_id)->first();
                        
        $stData =   '<div class="col-sm-3">
                        <label for="name" class="col-form-label">Student ID</label>
                        <input type="text" id="sId" class="form-control" value="'.$studentbyId->id.'" readonly name="studentId">
                        <input type="hidden" value="'.\Session::get('user').'" name="userId">
                    </div>';
        $stData .=   '<div class="col-sm-3">
                    <label for="name" class="col-form-label">Student Name</label>
                    <input type="text" class="form-control" value="'.$studentbyId->name.'" readonly>
                </div>';
        $stData .=   '<div class="col-sm-3">
                    <label for="name" class="col-form-label">Executive</label>
                    <input type="text" class="form-control" value="'.$studentbyId->exName.'" readonly>
                    <input type="hidden" class="form-control" value="'.$studentbyId->executiveId.'" readonly name="executiveId">
                </div>				
                <div class="col-sm-2">
                    <button style="margin-top:36px;display:none" id="showData" class="form-control btn btn-primary waves-effect waves-light" type="button">Show Data</button>
                </div>';

        return response()->json(array('data' =>$data,'sdata' => $stData));
    }
    public function paymentData(Request $request)
    {   
    $stData = DB::table('student_batches')
    ->join('students', 'student_batches.student_id', '=', 'students.id')
    ->join('batches', 'student_batches.batch_id', '=', 'batches.id')
    ->leftJoin('paymentdetails', 'student_batches.batch_id', '=', 'paymentdetails.batchId')
    ->where('student_batches.student_id', '=', $request->sId)
    ->where('student_batches.systemId', '=', $request->systmVal)
    ->groupBy('student_batches.batch_id', 'student_batches.systemId')
    ->select(
        'batches.batchId',
        'student_batches.course_price',
        'student_batches.entryDate',
        'student_batches.batch_id',
        'paymentdetails.discount',
        DB::raw('coalesce(sum(paymentdetails.cpaidAmount), 0) as cpaid')
    )
    ->get();
    //return response()->json(array('sdata' => $stData));
        
        $data ='<h5 style="font-size:18px;line-height:70px;">Recipt Details</h5>';
        $data .= '<table class="table table-bordered mb-5 text-center">
                <thead>
                    <tr>
                        <th><strong>Money Receipt No: </strong></th>
                        <th><strong>Invoice No:</strong></th>
                        <th><strong>Payment Date:</strong></th>
                    </tr>
                </thead> 
                <tbody>
                    <tr>
                        <td>
                            <input type="text" id="mrNo" class="form-control" name="mrNo" class="form-control" required>
                            <div class="invalid-feedback" id="mrNo-error"></div>
                        </td>
                        <td> 
                            <input type="text" id="invoiceId" class="form-control" name="invoiceId" class="form-control">
                            <div class="invalid-feedback" id="invoiceId-error"></div>
                        </td>
                        <td>
                            <div class="input-group">
                            <input type="text" name="paymentDate" class="form-control" placeholder="dd/mm/yyyy">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="icon-calender"></i></span>
                            </div>
                            <div class="invalid-feedback" id="paymentDate-error"></div>
                        </div>
                    </td>
                    </tr>
                </tbody>   
            </table>';
        $data .='<h5 style="font-size:18px;line-height:70px;">Payment details</h5>';    
        $data .='<table class="table table-bordered mb-5 text-center">
            <thead>
                <tr>
                    <th>Batch|Enroll Date</th>
                    <th width="110px">Price</th>
                    <th width="110px">Due</th>
                    <th>Type</th>
                    <th>Due Date</th>
                    <th>Mode</th>
                    <th>Fee Type</th>
                    <th width="110px">Discount</th>
                    <th width="110px">Amount</th>
                </tr>
            </thead>';
            $tPayable =0;
            foreach($stData as $key => $s){
                $tPayable += ($s->course_price-($s->cpaid+$s->discount));
                if($s->course_price-($s->cpaid+$s->discount) > 0 ){
                $data .='<tr>';
                    $data .='<td>
                                <input type="hidden" value="'.$s->batch_id.'">
                                <p class="my-0">'.$s->batchId.'</p>
                                <p class="my-0">'.$s->entryDate.'</p>
                            </td>';
                    $data .='<input type="hidden" name="tPayable" value="'.$tPayable.'">';        
                    $data .='<input type="hidden" name="batch_id[]" value="'.$s->batch_id.'">';        
                    $data .='<td><input type="text" class="form-control" readonly value="'.$s->course_price.'"></td>';
                    $data .='<td><input name="cPayable[]" type="text" class="form-control" readonly value="'.($s->course_price-($s->cpaid+$s->discount)).'" id="coursepricebyRow_'.$key.'"></td>';
                    $data .='<td><select class="form-control" name="payment_type[]" required><option value=""></option><option value="1">Full</option><option value="2">Partial</option></select></td>';
                    $data .='<td>
                                <div class="input-group">
                                    <input type="text" name="dueDate[]" id="dueDate_'.$key.'" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </td>';
                $data .='<td><select class="form-control" name="payment_mode[]" required><option value=""></option><option value="1">Cash</option><option value="2">Bkash</option><option value="3">Card</option></select></td>';
                $data .='<td><select class="form-control" id="feeType" name="feeType[]" required><option value="">Select</option>';
                $data .='<option value="1">Registration</option><option value="2" required>Course</option></select></td>';
                $data .='<td><input type="text" name="discount[]" class="paidpricebyRow form-control" id="discountbyRow_'.$key.'"  onkeyup="checkPrice('.$key.')"></td>';
                $data .='<td><input type="text" name="cpaidAmount[]" class="paidpricebyRow form-control" required id="paidpricebyRow_'.$key.'" onkeyup="checkPrice('.$key.')"></td>';
                $data .='</tr>';
                $data .='<script>$("input[name=\'paymentDate\'],#dueDate_'.$key.'").daterangepicker({
                    singleDatePicker: true,
                    startDate: new Date(),
                    showDropdowns: true,
                    autoUpdateInput: true,
                    format: \'dd/mm/yyyy\',
                }).on(\'changeDate\', function(e) {
                    var date = moment(e.date).format(\'YYYY/MM/DD\');
                    $(this).val(date);
                });</script>';
            }


            }
            /*Footer Part */
            $data .= '<tfoot>
                        <tr>
                            <th colspan="8" class="text-right">Sub Total</th>
                            <td><input type="text" name="tPayable" class="tPayable form-control" name="tPayable" value="'.$tPayable.'" readonly></td>
                        </tr>
                        <tr>
                            <th colspan="8" class="text-right">Total Paid</th>
                            <td>
                                <input type="text" name="paidAmount" class="tPaid form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="8" class="text-right">Total Due</th>
                            <td><input type="text" class="tDue form-control" readonly></td>
                        </tr>
                </tfoot>';
        $data .='</table>';
        $data .='<div class="col-lg-12 row">
                        		<label for="accountNote" class="col-sm-2 col-form-label">Note</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="executiveNote" name="accountNote" rows="5" placeholder="Account Note" style="
										resize:none;"></textarea>
								</div>
                    		</div>';
        $data .='<div class="float-right mt-2">
					<button type="submit" class="btn btn-primary waves-effect waves-light" id="submit-btn">Payment</button>
				</div>
                <div class="clearfix"></div>';
        return response()->json(array('data' =>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(NewPaymentRequest $request)
    {
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'mrNo' 		        => 'required|string',
            'paymentDate'       => 'required',
        ];
        $messages = [
            'mrNo.required' => 'The Money Receipt No field is required.',
            'paymentDate.required' => 'The Payment Date field is required.'
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],422);
        }
        // Code to store data in database
        //return response()->json($request->all(),200);
        DB::beginTransaction();
       
        try {

            $paymentId = DB::table('payments')->insert(
                [
                    'paymentDate'       =>  date('Y-m-d',strtotime($request->paymentDate)),
                    'studentId'         =>  $request->studentId,
                    'executiveId'       =>  $request->executiveId,
                    'createdBy'         =>  encryptor('decrypt', $request->userId),
                    'invoiceId'         =>  $request->invoiceId?$request->invoiceId:null,
                    'tPayable'          =>  $request->tPayable,
                    'paidAmount'        =>  $request->paidAmount,
                    'accountNote'       =>  $request->accountNote,
                    'status'            =>  ($request->tPayable == ($request->paidAmount+$request->disocunt))?0:1,
                    'created_at'        => date("Y-m-d h:i:s"),
                    // 'updated_at'        => date("Y-m-d h:i:s"),
                ]
            );
            

            // Payment Detail
            $batch_id       = $request->post('batch_id');
            $dueDate        = $request->post('dueDate');
            $cPayable       = $request->post('cPayable');
            $cpaidAmount	= $request->post('cpaidAmount');
            $payment_type	= $request->post('payment_type');
            $discount	    = $request->post('discount');
            $payment_mode	    = $request->post('payment_mode');
            $feeType	    = $request->post('feeType');
            
            //$m_price	    = $request->post('m_price');
            foreach($request->cpaidAmount as $key => $cdata){
                /*if($cPayable[$key] == $cpaidAmount[$key]){
                    $payment_detail['type']             = 0;
                }*/
                $payment_detail['paymentId']        = $paymentId;
                $payment_detail['mrNo']             = $request->mrNo;
                $payment_detail['studentId']        = $request->studentId;
                $payment_detail['batchId']          = $batch_id[$key];
                $payment_detail['cPayable']         = $cPayable[$key];
                $payment_detail['cpaidAmount']      = $cpaidAmount[$key];
                //$payment_detail['m_price']          = $m_price[$key]?$m_price[$key]:0.00;
                $payment_detail['payment_type']             = $payment_type[$key];//($cPayable[$key] == $cpaidAmount[$key])?0:1;
                $payment_detail['dueDate']          = date('Y-m-d',strtotime($dueDate[$key]));
                $payment_detail['created_at']       = date("Y-m-d h:i:s");
                /*$payment_detail['updated_at']       = date("Y-m-d h:i:s");*/
                $payment_detail['discount']     = $discount[$key];
                $payment_detail['payment_mode']     = $payment_mode[$key];
                $payment_detail['feeType']          = $feeType[$key];

                DB::table('paymentdetails')->insert($payment_detail);

                /*To Update Account Approve */
                $s_batch_data = DB::table('student_batches')->where(['student_id'=>$request->studentId,'batch_id'=>$batch_id[$key]])->first();
                $data = array(
                    'acc_approve' => 1,
                    'updated_at' => Carbon::now()
                );
                DB::table('student_batches')->where('id',$s_batch_data->id)->update($data);
                DB::commit();
            }
            return response()->json(['success' => 'Payment Complete successfully.']);
            //return redirect(route(currentUser().'.payment.index'))->with($this->responseMessage(true, null, 'Payment Received'));
        
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$sId)
    {
        $sdata = DB::table('students')
        ->select('students.name','students.id','users.name as exName')
        ->join('users', 'students.executiveId', '=', 'users.id')
        ->where('students.id',$sId)->first();

        $paymentdetl = Payment::with('paymentDetail')->find(encryptor('decrypt', $id));



        return view('payment.edit',compact('sdata','paymentdetl'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*$rules = [
            'mrNo' 		        => 'required|string',
            'paymentDate'       => 'required',
        ];
        $messages = [
            'mrNo.required' => 'The Money Receipt No field is required.',
            'paymentDate.required' => 'The Payment Date field is required.'
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],422);
        }*/
        
        // Code to store data in database
        //return response()->json($request->all(),200);
        DB::beginTransaction();
       
        try {
 
            $paymentId = DB::table('payments')->where('id', encryptor('decrypt', $id))
                ->update(
                [
                    'paymentDate'       =>  date('Y-m-d',strtotime($request->paymentDate)),
                    'createdBy'         =>  encryptor('decrypt', $request->userId),
                    'invoiceId'         =>  $request->invoiceId?$request->invoiceId:null,
                    'tPayable'          =>  $request->tPayable,
                    'paidAmount'        =>  $request->paidAmount,
                    'accountNote'       =>  $request->accountNote,
                    'status'            =>  ($request->tPayable == ($request->paidAmount+$request->disocunt))?0:1,
                    'updated_at'        => date("Y-m-d h:i:s"),
                ]
            );
            

            // Payment Detail
            $id       = $request->post('id');
            $dueDate        = $request->post('dueDate');
            $cPayable       = $request->post('cPayable');
            $cpaidAmount	= $request->post('cpaidAmount');
            $payment_type	= $request->post('payment_type');
            $discount	    = $request->post('discount');
            $payment_mode	    = $request->post('payment_mode');
            $feeType	    = $request->post('feeType');
            
            //$m_price	    = $request->post('m_price');
            foreach($request->id as $key => $cdata){
                $payment_detail = Paymentdetail::findOrFail($id[$key]);
                $payment_detail['mrNo']             = $request->mrNo;
                $payment_detail['cPayable']         = $cPayable[$key];
                $payment_detail['cpaidAmount']      = $cpaidAmount[$key];
                //$payment_detail['m_price']          = $m_price[$key]?$m_price[$key]:0.00;
                $payment_detail['payment_type']             = $payment_type[$key];//($cPayable[$key] == $cpaidAmount[$key])?0:1;
                $payment_detail['dueDate']          = date('Y-m-d',strtotime($dueDate[$key]));
                $payment_detail['created_at']       = date("Y-m-d h:i:s");
                /*$payment_detail['updated_at']       = date("Y-m-d h:i:s");*/
                $payment_detail['discount']     = $discount[$key];
                $payment_detail['payment_mode']     = $payment_mode[$key];
                $payment_detail['feeType']          = $feeType[$key];
                $payment_detail->save();


                DB::commit();
            }
            return redirect(route(currentUser().'.payment.index'))->with($this->responseMessage(true, null, 'Payment Received'));
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}