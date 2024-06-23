<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Paymentdetail;

use App\Http\Requests\Student\NewPaymentRequest as StudentNewPaymentRequest;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Student;
use Exception;
use Session;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

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
            ->select('students.id as sId', 'students.name as sName', 'users.name as exName')
            ->where('student_batches.student_id', $request->sdata)
            ->where('student_batches.status', 2)
            //->>where('student_batches.pstatus',0)
            //->orWhere('students.name', 'like', '%'.$request->sdata.'%')
            // ->orWhere('students.id', $request->sdata)
            // ->orWhere('student_batches.batch_id', $request->sdata)
            // ->orWhere('students.executiveId', $request->sdata)
            ->groupBy('student_batches.student_id', 'students.id', 'students.name', 'users.name')
            ->get();
        return response()->json($allStudent);
    }
    public function databySystemId(Request $request)
    {
        $enrollStudent = DB::table('students')
            ->join('student_batches', function ($join) {
                $join->on('students.id', '=', 'student_batches.student_id')
                    ->where('student_batches.status', '=', '2')
                    ->where('student_batches.pstatus', '=', '0')
                    ->where('student_batches.acc_approve', '!=', '3');
            })
            ->join('batches', 'student_batches.batch_id', '=', 'batches.id')
            ->select('student_batches.batch_id', 'batches.batchId')
            ->where('student_batches.systemId', $request->systemId)
            ->get();
        $data = '<div class="col-sm-3"><select class="js-example-basic-single form-control" id="batch_id" name="batch_id[]">';
        $data .= '<option value="">All</option>';
        foreach ($enrollStudent as $e) {
            $data .= '<option value="' . $e->batch_id . '">' . $e->batchId . '</option>';
        }
        $data .= '</select></div>';

        $data .= '<div class="col-sm-3" id="type"><select class="form-control" id="opt" name="type" onchange="paymentType(this.value)">';
        $data .= '<option value="">Select</option>';
        $data .= '<option value="1">Report</option>';
        $data .= '<option value="2" selected>Batch</option>';
        $data .= '</select></div>';
        return response()->json(array('data' => $data));
    }
    public function enrollData(Request $request)
    {
        $enrollStudent = DB::table('student_batches')
            ->select('student_batches.systemId')
            ->where('student_batches.status', '=', '2')
            ->where('student_batches.pstatus', '=', '0')
            ->where('student_batches.student_id', $request->student_id)
            ->groupBy('student_batches.systemId')
            ->get();

        $data = '<div class="col-sm-3" id="systemId"><select class="js-example-basic-single form-control" id="systmVal" onchange="databySystemId(this.value);">';
        $data .= '<option value="">Select</option>';
        $sl = 1;
        foreach ($enrollStudent as $key => $e) {
            $data .= '<option value="' . $e->systemId . '">Admission-' . $sl++ . '</option>';
        }
        $data .= '</select></div>';
        /*==Student Data==*/
        $studentbyId =  DB::table('students')
            ->select('students.id', 'students.name', 'students.executiveId', 'users.name as exName')
            ->join('users', 'students.executiveId', '=', 'users.id')
            ->where('students.id', $request->student_id)->first();

        $stData =   '<div class="col-sm-3">
                        <label for="name" class="col-form-label">Student ID</label>
                        <input type="text" id="sId" class="form-control" value="' . $studentbyId->id . '" readonly name="studentId">
                        <input type="hidden" value="' . \Session::get('user') . '" name="userId">
                    </div>';
        $stData .=   '<div class="col-sm-3">
                    <label for="name" class="col-form-label">Student Name</label>
                    <input type="text" class="form-control" value="' . $studentbyId->name . '" readonly>
                </div>';
        $stData .=   '<div class="col-sm-3">
                    <label for="name" class="col-form-label">Executive</label>
                    <input type="text" class="form-control" value="' . $studentbyId->exName . '" readonly>
                    <input type="hidden" class="form-control" value="' . $studentbyId->executiveId . '" readonly name="executiveId">
                </div>				
                <div class="col-sm-2">
                    <button style="margin-top:36px;display:none" id="showData" class="form-control btn btn-primary waves-effect waves-light" type="button">Show Data</button>
                </div>';

        return response()->json(array('data' => $data, 'sdata' => $stData));
    }
    public function paymentData(Request $request)
    {
        \DB::connection()->enableQueryLog();
        $stData = DB::table('student_batches')
            ->join('students', 'student_batches.student_id', '=', 'students.id')
            //->leftjoin('paymentdetails', 'student_batches.student_id', '=', 'paymentdetails.studentId')
            ->where('student_batches.student_id', '=', $request->sId)
            ->where('student_batches.systemId', '=', $request->systmVal)
            ->where('student_batches.acc_approve', '!=', 3)
            //->groupBy('student_batches.batch_id', 'student_batches.systemId')
            ->distinct('student_batches.batch_id')
            ->select(
                'student_batches.course_price',
                'student_batches.entryDate',
                'student_batches.batch_id',
                'student_batches.course_id',
                'student_batches.student_id',
                //DB::raw('coalesce(sum(paymentdetails.cpaidAmount), 0) as cpaid')
            )
            ->get();
        $queries = \DB::getQueryLog();
        /*echo '<pre>';
print_r($stData);die;*/
        //dd($queries);
        //return response()->json(array('sdata' => $stData));



        $data = '<h5 style="font-size:18px;line-height:20px;">Recipt Details</h5>';
        $data .= '<table class="table table-sm table-bordered mb-3 text-center" style="font-size: small;">
                <thead>
                    <tr>
                        <th><strong>Money Receipt No: </strong></th>
                        <th><strong>Invoice No: </strong></th>
                        <th><strong>Payment Date:</strong></th>
                    </tr>
                </thead> 
                <tbody>
                    <tr>
                        <td>
                            <input type="text" id="mrNo" class="form-control" name="mrNo" class="form-control" required onkeyup="btn()">
                            <div class="invalid-feedback" id="mrNo-error"></div>
                            <input type="hidden" value="' . Session::get("user") . '" name="userId">
                        </td>
                        <td>
                            <input type="text" id="invoiceId" class="form-control" name="invoiceId" class="form-control" value="">
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
        $data .= '<h5 style="font-size:18px;line-height:20px;">Payment details</h5>';
        $data .= '<table class="table table-sm table-bordered mb-3 text-center" style="font-size: small;">
            <thead>
                <tr>
                    <th>Batch|Enroll Date</th>
                    <th width="110px">Price</th>
                    <th width="108px">Type</th>
                    <th width="160px">Due Date</th>
                    <th width="95px">Mode</th>
                    <th width="140">Fee Type</th>
                    <th width="110px">Discount</th>
                    <th width="110px">Amount</th>
                    <th width="110px">Due</th>
                </tr>
            </thead>';
        $tPayable = 0;
        foreach ($stData as $key => $s) {
            $pay_detl = DB::table('paymentdetails')
                ->selectRaw('coalesce(sum(paymentdetails.cpaidAmount), 0) as cpaid, coalesce(sum(paymentdetails.discount), 0) as discount')
                ->where(['paymentdetails.studentId' => $s->student_id, 'paymentdetails.batchId' => $s->batch_id])
                ->where(['paymentdetails.studentId' => $s->student_id, 'paymentdetails.course_id' => $s->course_id])
                ->get();
            $tPayable += ($s->course_price - ($pay_detl[0]->cpaid + $pay_detl[0]->discount));
            if ($s->course_price - ($pay_detl[0]->cpaid + $pay_detl[0]->discount) > 0) {
                $data .= '<tr>';
                if ($s->batch_id) {
                    $data .= '<td>
                        <input type="hidden" value="' . $s->batch_id . '">
                        <p class="my-0">' . DB::table('batches')->where('id', $s->batch_id)->first()->batchId . '</p>
                        <p class="my-0">' . $s->entryDate . '</p>
                    </td>';
                } else {
                    $data .= '<td>
                        <input type="hidden" value="' . $s->course_id . '">
                        <p class="my-0">' . DB::table('courses')->where('id', $s->course_id)->first()->courseName . '</p>
                        <p class="my-0">' . $s->entryDate . '</p>
                    </td>';
                }
                $inv = \DB::table('payments')
                    ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
                    ->where(['paymentdetails.studentId' => $request->sId, 'paymentdetails.batchId' => $s->batch_id])->whereNotNull('payments.invoiceId')->first();
                if ($inv)
                    $data .= '<script>$("input[name=\'invoiceId\']").val(' . $inv->invoiceId . ');$("#feeType").val("2");</script>';
                else
                    $data .= '<script>$("#feeType").val("1");</script>';
                //$inv = DB::table('paymentdetails')->where(['studentId' => $request->sId,'batchId' => $s->bid])->whereNotNull('invoiceId')->first();
                //return response()->json(array('data' =>$inv));
                /*if(is_null($inv)){
                        $data .='<td><input type="text" id="invoiceId" class="form-control" name="invoiceId[]"></td>'; 
                    }else{
                        $data .='<td><input type="text" id="invoiceId" class="form-control" readonly value="'.$inv->invoiceId.'"></td>';
                    }*/
                $data .= '<input type="hidden" name="tPayable" value="' . $tPayable . '">';
                $data .= '<input type="hidden" name="batch_id[]" value="' . $s->batch_id . '">';
                $data .= '<input type="hidden" name="course_id[]" value="' . $s->course_id . '">';
                $data .= '<td><input type="text" class="form-control" readonly value="' . $s->course_price . '"></td>';
                $data .= '<td><select class="form-control" name="payment_type[]" required><option value=""></option><option value="1">Full</option><option selected value="2">Partial</option></select></td>';
                $data .= '<td>
                                <div class="input-group">
                                    <input type="text" name="dueDate[]" id="dueDate_' . $key . '" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </td>';
                $data .= '<td><select class="form-control" name="payment_mode[]" required><option value=""></option><option selected value="1">Cash</option><option value="2">Bkash</option><option value="3">Bank</option></select></td>';
                $data .= '<td><select class="form-control" id="feeType" name="feeType[]" required><option value="">Select</option>';
                $data .= '<option value="1">Registration</option><option value="2" required>Course</option></select></td>';
                $data .= '<td><input type="text" name="discount[]" class="paidpricebyRow form-control" id="discountbyRow_' . $key . '"  onkeyup="checkPrice(' . $key . ')"></td>';
                $data .= '<td><input type="text" name="cpaidAmount[]" class="paidpricebyRow form-control" required id="paidpricebyRow_' . $key . '" onkeyup="checkPrice(' . $key . ')"></td>';
                $data .= '<td><input name="cPayable[]" type="text" class="form-control" readonly value="' . ($s->course_price - ($pay_detl[0]->cpaid + $pay_detl[0]->discount)) . '" id="coursepricebyRow_' . $key . '"></td>';
                $data .= '</tr>';
                $data .= '<script>$("input[name=\'paymentDate\'],#dueDate_' . $key . '").daterangepicker({
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
                            <td><input type="text" name="tPayable" class="tPayable form-control" name="tPayable" value="' . $tPayable . '" readonly></td>
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
        $data .= '</table>';
        $data .= '<div class="col-lg-12 row">
                        		<label for="accountNote" class="col-sm-2 col-form-label">Note</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="executiveNote" name="accountNote" rows="5" placeholder="Account Note" style="
										resize:none;"></textarea>
								</div>
                    		</div>';
        $data .= '<div class="float-right mt-2">
					<button type="submit" class="btn btn-primary waves-effect waves-light" id="submit-btn">Payment</button>
				</div>
                <div class="clearfix"></div>';
        return response()->json(array('data' => $data));
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
            'mrNo'                 => 'required|integer|unique:payments,mrNo',
            'paymentDate'       => 'required',
        ];
        $messages = [
            'mrNo.required' => 'The Money Receipt No field is required.',
            'mrNo.unique' => 'Mr No Alreay Used!',
            'paymentDate.required' => 'The Payment Date field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Code to store data in database
        //return response()->json($request->all(),200);

        DB::beginTransaction();

        try {

            DB::table('payments')->insert(
                [
                    'paymentDate'       =>  date('Y-m-d', strtotime($request->paymentDate)),
                    'studentId'         =>  $request->studentId,
                    'mrNo'              =>  $request->mrNo,
                    'invoiceId'         =>  $request->invoiceId,
                    'executiveId'       =>  $request->executiveId,
                    'tPayable'          =>  $request->tPayable,
                    'paidAmount'        =>  $request->paidAmount,
                    'accountNote'       =>  $request->accountNote,
                    'created_at'        => date("Y-m-d h:i:s"),
                    'created_by'        => encryptor('decrypt', $request->userId),
                    // 'updated_at'        => date("Y-m-d h:i:s"),
                ]
            );
            $paymentId = DB::getPdo()->lastInsertId();

            // Payment Detail
            $batch_id       = $request->post('batch_id');
            $course_id       = $request->post('course_id');
            $dueDate        = $request->post('dueDate');
            $cPayable       = $request->post('cPayable');
            $cpaidAmount    = $request->post('cpaidAmount');
            $payment_type    = $request->post('payment_type');
            $discount        = $request->post('discount');
            $payment_mode    = $request->post('payment_mode');
            $feeType        = $request->post('feeType');
            $invoiceId      = $request->post('invoiceId');

            //$m_price	    = $request->post('m_price');
            foreach ($request->cpaidAmount as $key => $cdata) {
                if ($cpaidAmount[$key] <> 0) {
                    /*if($cPayable[$key] == $cpaidAmount[$key]){
                    $payment_detail['type']             = 0;
                }*/
                    $payment_detail['paymentId']        = $paymentId;
                    //$payment_detail['mrNo']             = $request->mrNo;
                    //$payment_detail['invoiceId']        = $invoiceId[$key];
                    $payment_detail['studentId']        = $request->studentId;
                    $payment_detail['batchId']          = $batch_id[$key];
                    $payment_detail['course_id']          = $course_id[$key];
                    $payment_detail['cPayable']         = $cPayable[$key];
                    $payment_detail['cpaidAmount']      = $cpaidAmount[$key];
                    //$payment_detail['m_price']          = $m_price[$key]?$m_price[$key]:0.00;
                    $payment_detail['payment_type']             = $payment_type[$key]; //($cPayable[$key] == $cpaidAmount[$key])?0:1;

                    if ($cpaidAmount[$key] < $cPayable[$key] + $discount[$key] && $cpaidAmount[$key] <> 0 && $feeType[$key] == 2) {
                        if (isset($dueDate[$key]) && !empty($dueDate[$key])) {
                            $payment_detail['dueDate']      = date('Y-m-d', strtotime($dueDate[$key]));
                        }
                        /*$date = new Carbon($dueDate[$key]);
                    $date->addMonth();
                    $payment_detail['dueDate']      = $date->toDateString();*/
                    }
                    $payment_detail['created_at']       = date("Y-m-d h:i:s");
                    /*$payment_detail['updated_at']       = date("Y-m-d h:i:s");*/
                    $payment_detail['discount']     = $discount[$key];
                    $payment_detail['payment_mode']     = $payment_mode[$key];
                    $payment_detail['feeType']          = $feeType[$key];
                    $payment_detail['created_by']        = encryptor('decrypt', $request->userId);

                    DB::table('paymentdetails')->insert($payment_detail);

                    /*To Update Account Approve */
                    $s_batch_data = DB::table('student_batches')->where(['student_id' => $request->studentId, 'batch_id' => $batch_id[$key]])->first();
                    /* print_r($batch_id);die;
 print_r($s_batch_data);die;*/
                    /*if ($s_batch_data->acc_approve == 0 && $cpaidAmount[$key] < $cPayable[$key]+$discount[$key]) {
                    $data = array(
                        'acc_approve' => $invoiceId ? 2 : 1,
                        'updated_at' => Carbon::now(),
                    );
                    DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                } else if ($s_batch_data->acc_approve == 1 && $cpaidAmount[$key] == $cPayable[$key]+$discount[$key]) {
                    $data = array(
                        'acc_approve' => $invoiceId ? 2 : 1,
                        'updated_at' => Carbon::now(),
                        'pstatus' => 1
                    );
                    DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                } else {
                    if (request()->has($invoiceId)) {
                        $data = array(
                            'acc_approve' => 2,
                            'updated_at' => Carbon::now(),
                        );
                        DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                    }
                    
                }*/
                    if ($request->invoiceId) {
                        $data = array(
                            'acc_approve' => 2,
                            'updated_at' => Carbon::now(),
                        );
                        DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                    }
                    DB::commit();
                }
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
    public function edit($id, $sId)
    {
        $sdata = DB::table('students')
            ->select('students.name', 'students.id', 'users.name as exName')
            ->join('users', 'students.executiveId', '=', 'users.id')
            ->where('students.id', $sId)->first();

        /*$paymentdetl = Payment::with(['paymentDetail' => function ($query) {
           $query->where('paymentdetails.deduction','>=',0);
        }])->find(encryptor('decrypt', $id));*/

        $payment_data = DB::table('paymentdetails')->where('id', encryptor('decrypt', $id))->first();


        if ($payment_data->batchId) {
            $paymentdetl = DB::table('paymentdetails')->where('studentId', $sId)->where('batchId', $payment_data->batchId)->whereNull('deleted_at')->get();
            $studentsBatches = DB::table('student_batches')->where('student_id', $sId)->where('batch_id', '=', $payment_data->batchId)->first();
        } else
            $paymentdetl = DB::table('paymentdetails')->where('studentId', $sId)->where('course_id', $payment_data->course_id)->whereNull('deleted_at')->get();
        /*echo '<pre>';
        print_r($paymentdetl);die;*/


        return view('payment.edit', compact('sdata', 'paymentdetl', 'studentsBatches'));
    }
    public function courseEdit($id, $sId)
    {
        $sdata = DB::table('students')
            ->select('students.name', 'students.id', 'users.name as exName')
            ->join('users', 'students.executiveId', '=', 'users.id')
            ->where('students.id', $sId)->first();

        $paymentdetl = Payment::with('paymentDetail')->find(encryptor('decrypt', $id));



        return view('payment.courseEdit', compact('sdata', 'paymentdetl'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function updateold(Request $request, $id)
    {
        $rules = [
            'mrNo'                 => 'required|integer|unique:payments,mrNo,' . encryptor('decrypt', $id),
            'paymentDate'       => 'required',
        ];
        $messages = [
            'mrNo.required' => 'The Money Receipt No field is required.',
            'mrNo.unique' => 'Mr No Alreay Used!',
            'paymentDate.required' => 'The Payment Date field is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Code to store data in database
        //return response()->json($request->all(),200);
        DB::beginTransaction();

        try {

            $paymentId = DB::table('payments')->where('id', encryptor('decrypt', $id))
                ->update(
                    [
                        'paymentDate'       =>  date('Y-m-d', strtotime($request->paymentDate)),
                        'mrNo'              =>  $request->mrNo,
                        'invoiceId'         =>  $request->invoiceId,
                        'updated_by'         =>  encryptor('decrypt', $request->userId),
                        'tPayable'          =>  $request->tPayable,
                        'paidAmount'        =>  $request->paidAmount,
                        'accountNote'       =>  $request->accountNote,
                        //'status'            =>  ($request->tPayable == ($request->paidAmount+$request->disocunt))?0:1,
                        'updated_at'        => date("Y-m-d h:i:s"),
                    ]
                );


            // Payment Detail
            $id       = $request->post('id');
            $dueDate        = $request->post('dueDate');
            $cPayable       = $request->post('cPayable');
            $cpaidAmount    = $request->post('cpaidAmount');
            $payment_type    = $request->post('payment_type');
            $discount        = $request->post('discount');
            $payment_mode        = $request->post('payment_mode');
            $feeType        = $request->post('feeType');
            $invoiceId      = $request->post('invoiceId');
            $batch_id       = $request->post('batch_id');
            $course_id       = $request->post('course_id');

            //$m_price	    = $request->post('m_price');
            $tpaidAmt = 0;
            foreach ($request->id as $key => $cdata) {

                $payment_detail = Paymentdetail::findOrFail($id[$key]);
                //$payment_detail['mrNo']             = $request->mrNo;
                //$payment_detail['invoiceId']        = $invoiceId[$key]?$invoiceId[$key]:null;
                $payable = DB::table('paymentdetails')->where('studentId', '=', $request->studentId);
                if ($batch_id)
                    $payable = $payable->where('batchId', '=', $batch_id[$key])->get();
                else
                    $payable = $payable->where('course_id', '=', $course_id[$key])->get();
                /*To Update Account Approve */
                $s_batch_data = DB::table('student_batches');
                if ($batch_id)
                    $s_batch_data = $s_batch_data->where(['student_id' => $request->studentId, 'batch_id' => $batch_id[$key]])->first();
                else
                    $s_batch_data = $s_batch_data->where(['student_id' => $request->studentId, 'course_id' => $course_id[$key]])->first();
                $psum = 0;
                $pcourse_price = 0;
                foreach ($payable as $p) {
                    $sum = DB::table('paymentdetails')
                        ->where('id', '<', $p->id)
                        ->where('studentId', '=', $request->studentId);
                    if ($batch_id)
                        $sum = $sum->where('batchId', '=', $batch_id[$key]);
                    else
                        $sum = $sum->where('course_id', '=', $course_id[$key]);

                    $sum = $sum->select([
                        DB::raw('SUM(cpaidAmount) as total_cpaidAmount'),
                        DB::raw('SUM(COALESCE(discount, 0)) as total_discount'),
                    ])
                        ->first();
                    $sum = $cpaidAmount[$key] + $discount[$key];

                    //echo $id[$key];die;
                    $sum_cpayable = DB::table('paymentdetails')
                        ->where('paymentId', $p->paymentId)
                        ->sum('cPayable');
                    $sum_cpaidAmount = DB::table('paymentdetails')
                        ->where('paymentId', $p->paymentId)
                        ->sum('cpaidAmount');


                    DB::table('paymentdetails')->where('id', $p->id)
                        ->update(['cPayable' => $s_batch_data->course_price - $sum]);
                    DB::table('payments')->where('id', $p->paymentId)
                        ->update(['tPayable' => $sum_cpayable, 'paidAmount' => $sum_cpaidAmount]);
                    //$payment_detail['cPayable']         = $cPayable[$key];
                    $payment_detail['cpaidAmount']      = $cpaidAmount[$key];

                    //$payment_detail['m_price']          = $m_price[$key]?$m_price[$key]:0.00;
                    $payment_detail['payment_type']             = $payment_type[$key]; //($cPayable[$key] == $cpaidAmount[$key])?0:1;
                    //if ($cpaidAmount[$key] < $cPayable[$key]) {
                    $payment_detail['dueDate']      = $dueDate[$key] ? Carbon::createFromFormat('d/m/Y', $dueDate[$key])->format('Y-m-d') : null;
                    //$date = new Carbon($dueDate[$key]);
                    //$date->addMonth();
                    //$payment_detail['dueDate']      = $date->toDateString();
                    //}
                    $payment_detail['created_at']       = date("Y-m-d h:i:s");
                    /*$payment_detail['updated_at']       = date("Y-m-d h:i:s");*/
                    $payment_detail['discount']     = $discount[$key];
                    $payment_detail['payment_mode']     = $payment_mode[$key];
                    $payment_detail['feeType']          = $feeType[$key];
                    $payment_detail->save();



                    if ($s_batch_data->acc_approve == 0 && $cpaidAmount[$key] < $s_batch_data->course_price - $sum) {
                        $data = array(
                            'acc_approve' => $invoiceId ? 2 : 1,
                            'updated_at' => Carbon::now(),
                            'pstatus' => 0
                        );
                        DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                    } else if ($s_batch_data->acc_approve == 1 && $cpaidAmount[$key] == $s_batch_data->course_price - $sum) {
                        $data = array(
                            'acc_approve' => $invoiceId ? 2 : 1,
                            'updated_at' => Carbon::now(),
                            'pstatus' => 1
                        );
                        DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                    } else {
                        if (request()->has($invoiceId)) {
                            $data = array(
                                'acc_approve' => 2,
                                'updated_at' => Carbon::now(),
                            );
                            DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                        }
                    }

                    DB::commit();
                }
            }
            //die;
            return redirect(route(currentUser() . '.daily_collection_report_by_mr'))->with($this->responseMessage(true, null, 'Payment Received'));
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

        $paymentdetl = Payment::with('paymentDetail')->find(encryptor('decrypt', $id));
        if ($paymentdetl) {
            $paymentdetl->paymentDetail()->delete(); // Delete the associated payment details
            $paymentdetl->delete(); // Delete the payment record itself
            return redirect(route(currentUser() . '.daily_collection_report_by_mr'))->with($this->responseMessage(false, 'error', 'Payment Deleted'));
        }
    }
    public function newStore(Request $request)
    {
        //  dd($request);
        try {
            DB::beginTransaction();
            // Retrieve IDs from the arrays
            $paymentIds = $request->input('pid', []);
            $paymentDetailIds = $request->input('id', []);
            $mrNos = $request->input('mrNo', []);
            // Delete records from Payment table
            DB::connection()->enableQueryLog();
            //DB::table('payments')->whereIn('id', $paymentIds)->update(['mrNo' => null, 'invoiceId' => null, 'deleted_at' => now()]);
            $queries = \DB::getQueryLog();
            //dd($queries);

            // Delete records from Paymentdetail table
            DB::table('paymentdetails')->whereIn('id', $paymentDetailIds)->update(['deleted_at' => now()]);
            //DB::commit();
                $rules = [
                    //'mrNo'                 => 'required|unique:payments,mrNo',
                    'paymentDate'       => 'required',
                ];
                $messages = [
                    //'mrNo.required' => 'The Money Receipt No field is required.',
                    //'mrNo.unique' => 'Mr No Alreay Used!',
                    'paymentDate.required' => 'The Payment Date field is required.'
                ];
// If there are mrNo values provided, dynamically add validation rules for each mrNo
if (!empty($mrNos)) {
    foreach ($mrNos as $index => $mrNo) {
        $rules['mrNo.' . $index] = [
            'required',
            Rule::unique('payments', 'mrNo')->ignore($request->input('pid.' . $index), 'id')
        ];
    }
}
                $validator = Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                //DB::beginTransaction();
                foreach ($request->pid as $key => $payment) {
                    DB::table('payments')
    ->where('id', $paymentIds[$key]) // assuming 'id' is the primary key or unique identifier for the record
    ->update([
        'paymentDate'       => Carbon::createFromFormat('d/m/Y', $request->paymentDate[$key])->format('Y-m-d'),
        'mrNo'              => $request->mrNo[$key],
        'invoiceId'         => $request->invoiceId[$key],
        'tPayable'          => $request->tPayable,
        'paidAmount'        => $request->cpaidAmount[$key],
        'accountNote'       => $request->accountNote,
        'updated_at'        => date("Y-m-d h:i:s"),
        'updated_by'        => currentUserId(),
    ]);


                    // Payment Detail
                    $batch_id       = $request->post('batch_id');
                    $course_id       = $request->post('course_id');
                    $dueDate        = $request->post('dueDate');
                    $cPayable       = $request->post('cPayable');
                    $cpaidAmount    = $request->post('cpaidAmount');
                    $payment_type    = $request->post('payment_type');
                    $discount        = $request->post('discount');
                    $payment_mode    = $request->post('payment_mode');
                    $feeType        = $request->post('feeType');
                    $invoiceId      = $request->post('invoiceId');
                    $paymentId      = $request->post('pid');
                    //foreach ($request->cpaidAmount as $key => $cdata) {
                        if ($cpaidAmount[$key] <> 0) {
                            $payment_detail['paymentId']        = $paymentId[$key];
                            $payment_detail['studentId']        = $request->studentId;
                            $payment_detail['batchId']          = $batch_id[$key];
                            $payment_detail['course_id']        = $course_id[$key];
                            $payment_detail['cPayable']         = $cPayable[$key];
                            $payment_detail['cpaidAmount']      = $cpaidAmount[$key];
                            $payment_detail['payment_type']     = $payment_type[$key];

                            if ($cpaidAmount[$key] < $cPayable[$key] + $discount[$key] && $cpaidAmount[$key] <> 0 && $feeType[$key] == 2) {
                                if (isset($dueDate[$key]) && !empty($dueDate[$key])) {
                                    $payment_detail['dueDate']      = Carbon::createFromFormat('d/m/Y', $request->dueDate[$key])->format('Y-m-d');
                                }
                            }
                            $payment_detail['created_at']       = date("Y-m-d h:i:s");
                            $payment_detail['discount']     = $discount[$key];
                            $payment_detail['payment_mode']     = $payment_mode[$key];
                            $payment_detail['feeType']          = $feeType[$key];
                            $payment_detail['created_by']        = encryptor('decrypt', $request->userId);

                            DB::table('paymentdetails')->insert($payment_detail);

                            /*To Update Account Approve */
                            $s_batch_data = DB::table('student_batches')->where(['student_id' => $request->studentId, 'batch_id' => $batch_id[$key]])->first();
                            /* print_r($batch_id);die;*/

                        if ($request->invoiceId) {
                            $data = array(
                                'acc_approve' => 2,
                                'updated_at' => Carbon::now(),
                            );
                            DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
                        }
                        }
                    //}
                    
                   
                }
                DB::commit();
                //return response()->json(['success' => 'Payment Complete successfully.']);
                return redirect(route(currentUser().'.daily_collection_report_by_mr'))->with($this->responseMessage(true, null, 'Payment Updated'));
          
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
