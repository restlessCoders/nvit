<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransfer;
use App\Models\Transaction;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Http\Request;

use App\Http\Traits\ResponseTrait;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PaymentTransferController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_transfers = Transaction::where('type','like','%amount_transfer%')->get();
        return view('payment_transfer.index', compact('payment_transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::all();
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        return view('payment_transfer.add_new', compact('students', 'executives'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $to_exe_id = User::where('id',$request->to_exe_id)->first();
            $from_exe_id = User::where('id',$request->from_exe_id)->first();

            $transaction = new Transaction();
            $transaction->studentId = $request->studentId;
            $transaction->course_id = $request->course_id;
            $transaction->batchId = $request->batchId;
            $transaction->exe_id = $request->from_exe_id;
            $transaction->mrNo = $request->mrNo;
            $transaction->amount = -$request->amount;
            $transaction->type = 'amount_transfer';
            $transaction->trx_type = '-';
            $transaction->details = $request->amount.' Amount transfer to '.$to_exe_id->username;
            $transaction->postingDate = $request->postingDate?date('Y-m-d', strtotime($request->postingDate)):null;
            $transaction->save();

            $transaction = new Transaction();
            $transaction->studentId = $request->studentId;
            $transaction->course_id = $request->course_id;
            $transaction->batchId = $request->batchId;
            $transaction->exe_id = $request->to_exe_id;
            $transaction->mrNo = $request->mrNo;
            $transaction->amount = $request->amount;
            $transaction->type = 'amount_transfer';
            $transaction->trx_type = '+';
            $transaction->details = $request->amount.' Amount Receive From '.$from_exe_id->username;
            $transaction->postingDate = $request->postingDate?date('Y-m-d', strtotime($request->postingDate)):null;
            $transaction->save();

            return redirect(route(currentUser().'.payment-transfer.index'))->with($this->responseMessage(true, null, 'Payment Transfer Successful'));

        } catch (\Exception $e) {
            // something went wrong
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentTransfer  $paymentTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentTransfer $paymentTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentTransfer  $paymentTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentTransfer $paymentTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentTransfer  $paymentTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentTransfer $paymentTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentTransfer  $paymentTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentTransfer $paymentTransfer)
    {
        //
    }

    public function payment_transfer_data(Request $request){
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $courses = Course::all();
        $batches = Batch::all();
        $stu_exe_id = Student::where('id',$request->student_id)->first();
        
        $data = '<div class="col-lg-3">
        <label>Course: <span class="text-danger sup">*</span></label>
        <select name="course_id" class="form-control"> <option></option>';
            if(count($courses) > 0){
                foreach($courses as $c){
                    $data .= '<option value="'.$c->id.'">'.$c->courseName.'</option>';
                }
            }
        $data .= '</select></div>';

        $data .= '<div class="col-lg-3">
        <label>Batch: <span class="text-danger sup">*</span></label>
        <select name="batchId" class="form-control"> <option></option>';
            if(count($batches) > 0){
                foreach($batches as $b){
                    $data .= '<option value="'.$b->id.'">'.$b->batchId.'</option>';
                }
            }
        $data .= '</select></div>';

        $data .= '<div class="col-lg-3">
        <label>From Executive: <span class="text-danger sup">*</span></label>
        <select name="from_exe_id" class="form-control disabled">';
            if(count($executives) > 0){
                foreach($executives as $e){
                    if($e->id == $stu_exe_id->executiveId){
                        $data .= '<option value="'.$e->id.'">'.$e->username.'</option>';
                    }
                }
            }
        $data .= '</select></div>';
        $data .= '<div class="col-lg-3">
        <label>To Executive: <span class="text-danger sup">*</span></label>
        <select name="to_exe_id" class="form-control">
            <option></option>';
            if(count($executives) > 0){
                foreach($executives as $e){
                    $data .= '<option value="'.$e->id.'">'.$e->username.'</option>';
                }
            }
        $data .= '</select></div>';

        $data .= '<div class="col-lg-3">
                <label>Mr No<span class="text-danger sup">*</span></label>
                <input id="mrNo" type="text" class="form-control" name="mrNo">
            </div>';

        $data .= '<div class="col-lg-3">
                     <label>Amount<span class="text-danger sup">*</span></label>
                    <input id="amount" type="text" class="form-control" name="amount">
                </div>';

        return response()->json(array('data' => $data));
    }
}
