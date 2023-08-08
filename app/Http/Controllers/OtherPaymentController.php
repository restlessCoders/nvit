<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OtherPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('other.payment');
    }

    public function searchStudent(Request $request)
    {
        $keyword = $request->sdata;
        $allStudent = DB::table('students')
            ->join('student_courses', 'students.id', '=', 'student_courses.student_id')
            ->join('users', 'students.executiveId', '=', 'users.id')
            ->select('students.id as sId', 'students.name as sName', 'users.name as exName')
            /*->where(function ($query) use ($keyword) {
            $query->where('students.id', 'like', '%'.$keyword.'%')
                ->orWhere('students.name', 'like', '%'.$keyword.'%')
                ->orWhere('students.contact', 'like', '%'.$keyword.'%')
                ->orWhere('students.altContact', 'like', '%'.$keyword.'%')
                ->orWhere('users.name', 'like', '%'.$keyword.'%');
        })*/
            ->where('student_courses.student_id', $request->sdata)
            ->get();
        return response()->json($allStudent);
    }
    public function stData(Request $request)
    {
        $data = '<div class="col-sm-3" id="type"><select class="form-control" id="optType" onchange="optType(this.value)">';
        $data .= '<option value="">Select</option>';
        $data .= '<option value="1" selected>Course (No Batch)</option>';
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
    public function databyStudentId(Request $request)
    {
        if ($request->type == 1) {
            $courseStudent = DB::table('students')
                ->join('student_courses', 'students.id', '=', 'student_courses.student_id')
                ->join('courses', 'courses.id', '=', 'student_courses.course_id')
                ->select('student_courses.course_id', 'courses.courseName as cName')
                ->where('student_courses.student_id', $request->sId)
                /*->where('student_courses.course_id',$request->course_id)*/
                ->where('student_courses.systemId', $request->systemId)
                ->get();

            $data = '<div class="col-sm-3"><select class="js-example-basic-single form-control" id="course_id" name="course_id[]">';
            $data .= '<option value="">All</option>';
            foreach ($courseStudent as $c) {
                $data .= '<option value="' . $c->course_id . '">' . $c->cName . '</option>';
            }
            $data .= '</select></div>';

            $data .= '<div class="col-sm-3" id="type"><select class="form-control" id="opt" name="type">';
            $data .= '<option value="">Select</option>';
            $data .= '<option value="1">Report</option>';
            $data .= '<option value="4" selected>Payment</option>';
            $data .= '</select></div>';
        } else {
            $data = '<div class="col-sm-3" id="type"><select class="form-control" id="opt" name="type">';
            $data .= '<option value="">Select</option>';
            $data .= '<option value="1">Report</option>';
            $data .= '<option value="4">Payment</option>';
            $data .= '</select></div>';
        }
        return response()->json(array('data' => $data));
    }
    public function otherPaymentByStudentId(Request $request)
    {
        $data = '<h5 style="font-size:18px;line-height:70px;">Payment details</h5>';
        $data .= '<table class="table table-bordered mb-5 text-center">
            <thead>
                <tr>
                    <th>Mr No</th>
                    <th>Payment Date</th>
                    <th>Mode</th>
                    <th>Fee Type</th>
                    <th>Paid Amount</th>
                </tr>
            </thead>';

        $data .= '<tr>';
        $data .= '<td><input type="text" id="mrNo" class="form-control" name="mrNo" class="form-control" required></td>';
        $data .= '<td>
                                <div class="input-group">
                                    <input type="text" name="paymentDate" id="paymentDate_" class="form-control" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </td>';
        $data .= '<td><select class="form-control" name="payment_mode" required><option value=""></option><option value="1">Cash</option><option value="2">Bkash</option><option value="3">Card</option></select></td>';
        $data .= '<td><select class="form-control" id="feeType" name="feeType" required><option value="">Select</option>';
        $data .= '<option value="3" selected>Material</option></select></td>';
        $data .= '<td><input type="text" name="cpaidAmount" class="paidpricebyRow form-control" required></td>';
        $data .= '</tr>';
        $data .= '<script>$("#paymentDate_").daterangepicker({
                    singleDatePicker: true,
                    startDate: new Date(),
                    showDropdowns: true,
                    autoUpdateInput: true,
                    format: \'dd/mm/yyyy\',
                }).on(\'changeDate\', function(e) {
                    var date = moment(e.date).format(\'YYYY/MM/DD\');
                    $(this).val(date);
                });</script>';

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
    public function coursePaymentByStudentId(Request $request)
    {
        \DB::connection()->enableQueryLog();
        $stData = DB::table('student_courses')
            ->join('students', 'student_courses.student_id', '=', 'students.id')
            ->join('courses', 'courses.id', '=', 'student_courses.course_id')
            ->leftJoin('paymentdetails', 'student_courses.student_id', '=', 'paymentdetails.studentId')
            ->where('student_courses.student_id', '=', $request->sId)
            ->Where('student_courses.systemId', '=', $request->systemId)
            ->groupBy('student_courses.course_id', 'student_courses.systemId')
            ->select(
                'courses.courseName',
                'courses.id as cId',
                'student_courses.price',
                'student_courses.created_at',
                'student_courses.course_id',
                'student_courses.student_id',
            )
            ->get();
        $queries = \DB::getQueryLog();

        //dd($queries);
        //return response()->json(array('sdata' => $stData));

        $data = '<h5 style="font-size:18px;line-height:70px;">Recipt Details</h5>';
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
        $data .= '<h5 style="font-size:18px;line-height:70px;">Payment details</h5>';
        $data .= '<table class="table table-sm table-bordered mb-5 text-center">
                <thead>
                    <tr>
                        <th>Course</th>
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
            ->where(['paymentdetails.studentId' => $s->student_id,'paymentdetails.course_id' => $s->course_id])
            ->get();
           
            $tPayable += ($s->price - ($pay_detl[0]->cpaid + $pay_detl[0]->discount));
            if ($s->price - ($pay_detl[0]->cpaid + $pay_detl[0]->discount) > 0) {
                $data .= '<tr>';
                $data .= '<td>
                            <input type="hidden" value="' . $s->course_id . '">
                            <p class="my-0">' . $s->courseName . '</p>
                          </td>';
                $data .= '<input type="hidden" name="tPayable" value="' . $tPayable . '">';
                $data .= '<input type="hidden" name="course_id[]" value="' . $s->course_id . '">';
                $data .= '<td><input type="text" class="form-control" readonly value="' . $s->price . '"></td>';
                $data .= '<td><select class="form-control" name="payment_type[]" required><option value=""></option><option value="1">Full</option><option value="2" selected>Partial</option></select></td>';
                $data .= '<td>
                                <div class="input-group">
                                    <input type="text" name="dueDate[]" id="dueDate_' . $key . '" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </td>';
                $data .= '<td><select class="form-control" name="payment_mode[]" required><option value=""></option><option value="1" selected>Cash</option><option value="2">Bkash</option><option value="3">Card</option></select></td>';
                $data .= '<td><select class="form-control" id="feeType" name="feeType[]" required><option value="">Select</option>';
                $data .= '<option value="1" selected>Registration</option><option value="2" required>Course</option></select></td>';
                $data .= '<td><input type="text" name="discount[]" class="paidpricebyRow form-control" id="discountbyRow_' . $key . '"  onkeyup="checkPrice(' . $key . ')"></td>';
                $data .= '<td><input type="text" name="cpaidAmount[]" class="paidpricebyRow form-control" required id="paidpricebyRow_' . $key . '" onkeyup="checkPrice(' . $key . ')"></td>';
                $data .= '<td><input name="cPayable[]" type="text" class="form-control" readonly value="' . ($s->price - ($pay_detl[0]->cpaid + $pay_detl[0]->discount)) . '" id="coursepricebyRow_' . $key . '"></td>';
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
                                <th colspan="7" class="text-right">Sub Total</th>
                                <td><input type="text" name="tPayable" class="tPayable form-control" name="tPayable" value="' . $tPayable . '" readonly></td>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-right">Total Paid</th>
                                <td>
                                    <input type="text" name="paidAmount" class="tPaid form-control" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-right">Total Due</th>
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $paymentId = DB::table('payments')->insert(
                [
                    'paymentDate'       =>  date('Y-m-d', strtotime($request->paymentDate)),
                    'studentId'         =>  $request->studentId,
                    'executiveId'       =>  $request->executiveId,
                    'createdBy'         =>  currentUserId(),
                    'invoiceId'         =>  0,
                    'tPayable'          =>  0,
                    'paidAmount'        =>  $request->post('cpaidAmount'),
                    'accountNote'       =>  $request->accountNote,
                    'created_at'        => date("Y-m-d h:i:s"),
                ]
            );

            // Payment Detail
            $payment_detail['paymentId']        = $paymentId;
            $payment_detail['mrNo']             = $request->mrNo;
            $payment_detail['studentId']        = $request->studentId;
            $payment_detail['batchId']          = 0;
            $payment_detail['cPayable']         = 0;
            $payment_detail['cpaidAmount']      = 0;
            $payment_detail['m_price']          = $request->post('cpaidAmount');
            $payment_detail['payment_type']     = 0;
            $payment_detail['created_at']       = date("Y-m-d h:i:s");
            $payment_detail['discount']         = 0;
            $payment_detail['payment_mode']     = $request->post('payment_mode');
            $payment_detail['feeType']          = $request->post('feeType');

            DB::table('paymentdetails')->insert($payment_detail);
            DB::commit();

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
    public function coursestore(Request $request)
    {

        $rules = [
            'mrNo'                 => 'required|string',
            'paymentDate'       => 'required',
        ];
        $messages = [
            'mrNo.required' => 'The Money Receipt No field is required.',
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

            $paymentId = DB::table('payments')->insert(
                [
                    'paymentDate'       =>  date('Y-m-d', strtotime($request->paymentDate)),
                    'studentId'         =>  $request->studentId,
                    'executiveId'       =>  $request->executiveId,
                    'created_by'         =>  encryptor('decrypt', $request->userId),
                    'invoiceId'         =>  $request->invoiceId ? $request->invoiceId : null,
                    'mrNo'              =>  $request->mrNo ? $request->mrNo : null,
                    'tPayable'          =>  $request->tPayable,
                    'paidAmount'        =>  $request->paidAmount,
                    'accountNote'       =>  $request->accountNote,
                    /*'status'            =>  ($request->tPayable == ($request->paidAmount+$request->disocunt))?0:1,*/
                    'created_at'        => date("Y-m-d h:i:s"),
                    // 'updated_at'        => date("Y-m-d h:i:s"),
                ]
            );

            $paymentId = DB::getPdo()->lastInsertId();
            // Payment Detail
            $course_id       = $request->post('course_id');
            $cPayable       = $request->post('cPayable');
            $cpaidAmount    = $request->post('cpaidAmount');
            $payment_type    = $request->post('payment_type');
            $discount        = $request->post('discount');
            $payment_mode        = $request->post('payment_mode');
            $feeType        = $request->post('feeType');

            //$m_price	    = $request->post('m_price');
            foreach ($request->cpaidAmount as $key => $cdata) {
                /*if($cPayable[$key] == $cpaidAmount[$key]){
                    $payment_detail['type']             = 0;
                }*/
                $payment_detail['paymentId']        = $paymentId;
                /*$payment_detail['mrNo']             = $request->mrNo;*/
                $payment_detail['studentId']        = $request->studentId;
                $payment_detail['course_id']          = $course_id[$key];
                $payment_detail['batchId']          = 0;
                $payment_detail['cPayable']         = $cPayable[$key];
                $payment_detail['cpaidAmount']      = $cpaidAmount[$key];
                //$payment_detail['m_price']          = $m_price[$key]?$m_price[$key]:0.00;
                $payment_detail['payment_type']             = $payment_type[$key]; //($cPayable[$key] == $cpaidAmount[$key])?0:1;
                $payment_detail['created_at']       = date("Y-m-d h:i:s");
                /*$payment_detail['updated_at']       = date("Y-m-d h:i:s");*/
                $payment_detail['discount']     = $discount[$key];
                $payment_detail['payment_mode']     = $payment_mode[$key];
                $payment_detail['feeType']          = $feeType[$key];

                DB::table('paymentdetails')->insert($payment_detail);

                /*To Update Account Approve */
                $s_course_data = DB::table('student_courses')->where(['student_id' => $request->studentId, 'course_id' => $course_id[$key]])->first();
                $data = array(
                    'p_status' => 1,
                    'updated_at' => Carbon::now()
                );
                DB::table('student_courses')->where('id', $s_course_data->id)->update($data);

                /*Insert Data in Batch Wise Enroll */
                $course_type = DB::table('courses')->where('id', $request->course_id)->first()->course_type;
                if ($course_type == 2) {
                    $course = DB::table('student_courses')
                        ->where('student_id', $request->studentId)->where('student_courses.course_id', $course_id[$key])->first();
                    $data = array(
                        'course_id' => $course_id[$key],
                        'batch_id' => 0,
                        'student_id' =>  $request->studentId,
                        'package_id' =>  0,
                        'entryDate' => date('Y-m-d'),
                        'status' => 2,
                        'systemId' => $course->systemId,
                        'course_price' => $course->price,
                        'type' => $course->status,
                        'created_at' => Carbon::now(),
                        'created_by' => currentUserId(),
                    );
                    $batch_row_exixts = DB::table('student_batches')
                        ->where('student_id', $request->studentId)->where('student_batches.course_id', $course_id[$key])->first();
                    if (!$batch_row_exixts) {
                        $bundel_course_id = DB::table('student_batches')->insertGetId($data);
                        /*== Insert Data Into Bundel Courses==*/
                        $bundel_courses = DB::table('bundel_courses')->where('main_course_id', $course_id[$key])->where('status', 1)->get();
                        foreach ($bundel_courses as $bc) {
                            $systemId = substr(uniqid(Str::random(6), true), 0, 6);
                            $data = array(
                                'main_course_id' => $bundel_course_id,
                                'sub_course_id' => $bc->sub_course_id,
                                'student_id' =>  $request->studentId,
                                'systemId' =>   $systemId,
                                'status' => 2,
                                'created_at' => Carbon::now(),
                                'created_by' => currentUserId(),
                            );
                            DB::table('bundel_course_enroll')->insert($data);
                        }
                    }
                } else {
                    $course = DB::table('student_courses')
                        ->where('student_id', $request->studentId)->where('student_courses.course_id', $course_id[$key])->first();
                    $batch_data_exists =  DB::table('student_batches')
                    ->where('student_id', $request->studentId)->where('student_batches.course_id', $course_id[$key])->first();
                    if(empty($batch_data_exists)){
                        $data = array(
                            'course_id' => $course_id[$key],
                            'batch_id' => 0,
                            'student_id' =>  $request->studentId,
                            'package_id' =>  0,
                            'entryDate' => date('Y-m-d'),
                            'status' => 2,
                            'systemId' => $course->systemId,
                            'course_price' => $course->price,
                            'type' => $course->status,
                            'created_at' => Carbon::now(),
                            'created_by' => currentUserId(),
                        );
                        DB::table('student_batches')->insert($data);
                    }
                }
            }
            DB::commit();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
