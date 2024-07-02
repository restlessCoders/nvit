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
        $payment_transfers = Transaction::where('type', 'like', '%amount_transfer%')->orderBy('id', 'desc')->get();
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
        echo Carbon::createFromFormat('m/d/Y', $request->postingDate)->format('Y-m-d');
        dd($request);
        try {
            $to_exe_id = User::where('id', $request->to_exe_id)->first();
            $from_exe_id = User::where('id', $request->from_exe_id)->first();

            $transaction = new Transaction();
            $transaction->studentId = $request->studentId;
            $transaction->course_id = $request->course_id;
            $transaction->batchId = $request->batchId;
            $transaction->exe_id = $request->from_exe_id;
            $transaction->mrNo = $request->mrNo;
            $transaction->amount = -$request->amount;
            $transaction->type = 'amount_transfer';
            $transaction->trx_type = '-';
            $transaction->details = $request->amount . ' Amount transfer to ' . $to_exe_id->username;
            $transaction->postingDate = $request->postingDate ? Carbon::createFromFormat('d/m/Y', $request->postingDate)->format('Y-m-d') : null;
            /*$request->postingDate?date('Y-m-d', strtotime($request->postingDate)):null*/
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
            $transaction->details = $request->amount . ' Amount Receive From ' . $from_exe_id->username;
            $transaction->postingDate = $request->postingDate ? Carbon::createFromFormat('d/m/Y', $request->postingDate)->format('Y-m-d') : null;
            $transaction->save();

            return redirect(route(currentUser() . '.payment-transfer.index'))->with($this->responseMessage(true, null, 'Payment Transfer Successful'));
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
    public function edit($id)
    {
        $p = DB::table('transactions')->where('id', encryptor('decrypt', $id))->first();
        $students = Student::all();
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        return view('payment_transfer.edit', compact('p', 'students', 'executives'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentTransfer  $paymentTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*try {
            $to_exe_id = User::where('id',$request->to_exe_id)->first();
            $from_exe_id = User::where('id',$request->from_exe_id)->first();

            $pt =DB::table('transactions')->where('id',encryptor('decrypt', $id))->first();
            if($pt){
                $pt->studentId = $request->studentId;
                $pt->course_id = $request->course_id;
                $pt->batchId = $request->batchId;
                $pt->exe_id = $request->from_exe_id;
                $pt->mrNo = $request->mrNo;
                $pt->amount = -$request->amount;
                $pt->type = 'amount_transfer';
                $pt->trx_type = '-';
                $pt->details = $request->amount.' Amount transfer to '.$to_exe_id->username;
                $pt->postingDate = $request->postingDate?Carbon::createFromFormat('d/m/Y', $request->postingDate)->format('Y-m-d'):null;
    
    
                if(!!$pt->save()) return redirect(route(currentUser().'.payment_transfer.index'))->with($this->responseMessage(true, null, 'Transfer updated Successfully'));
            }
            
        } catch (Exception $e) {
			dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentTransfer  $paymentTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $p = DB::table('transactions')->where('id', encryptor('decrypt', $id))->first();
        if ($p) {
            $transactionsToDelete = DB::table('transactions')->where('mrNo', $p->mrNo)->get();
            // Delete all transactions with the same mrNo
            foreach ($transactionsToDelete as $transactionToDelete) {
                DB::table('transactions')->where('id', $transactionToDelete->id)->delete();
            }
            return redirect(route(currentUser().'.payment-transfer.index'))->with($this->responseMessage(true, null, 'Transactions deleted Successfully'));
        }
    }

    public function payment_transfer_data(Request $request)
    {
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $courses = Course::all();
        $batches = Batch::all();
        $stu_exe_id = Student::where('id', $request->student_id)->first();



        $data = '<div class="col-lg-3">
        <label>Course: <span class="text-danger sup">*</span></label>
        <select name="course_id" class="form-control js-example-basic-single select2"> <option></option>';
        if (count($courses) > 0) {
            foreach ($courses as $c) {
                if ($c->id == $request->course_id)
                    $data .= '<option value="' . $c->id . '" selected>' . $c->courseName . '</option>';
                else
                    $data .= '<option value="' . $c->id . '">' . $c->courseName . '</option>';
            }
        }
        $data .= '</select></div>';

        $data .= '<div class="col-lg-3">
        <label>Batch: <span class="text-danger sup">*</span></label>
        <select name="batchId" class="form-control js-example-basic-single select2"> <option></option>';
        if (count($batches) > 0) {
            foreach ($batches as $b) {
                if ($b->id == $request->batchId)
                    $data .= '<option value="' . $b->id . '" selected>' . $b->batchId . '</option>';
                else
                    $data .= '<option value="' . $b->id . '">' . $b->batchId . '</option>';
            }
        }
        $data .= '</select></div>';

        $data .= '<div class="col-lg-3">
        <label>From Executive: <span class="text-danger sup">*</span></label>
        <select name="from_exe_id" class="form-control disabled">';
        if (count($executives) > 0) {
            foreach ($executives as $e) {
                if ($e->id == $stu_exe_id->executiveId) {
                    $data .= '<option value="' . $e->id . '">' . $e->username . '</option>';
                }
            }
        }
        $data .= '</select></div>';

        $transfer_exe_data = DB::table('transactions')->where('mrNo', $request->mrNo)->where('exe_id', '!=', $stu_exe_id->executiveId)->first();
        //dd($transfer_exe_data);
        $data .= '<div class="col-lg-3">
        <label>To Executive: <span class="text-danger sup">*</span></label>
        <select name="to_exe_id" class="form-control js-example-basic-single select2">
            <option></option>';
        if (count($executives) > 0) {
            foreach ($executives as $e) {
                if ($transfer_exe_data && $transfer_exe_data->exe_id == $e->id)
                    $data .= '<option value="' . $e->id . '" selected>' . $e->username . '</option>';
                else
                    $data .= '<option value="' . $e->id . '">' . $e->username . '</option>';
            }
        }
        $data .= '</select></div>';

        $data .= '<div class="col-lg-3">
                <label>Mr No<span class="text-danger sup">*</span></label>
                <input id="mrNo" type="text" class="form-control js-example-basic-single select2" name="mrNo" value="' . $transfer_exe_data?->mrNo . '">
            </div>';

        $data .= '<div class="col-lg-3">
                     <label>Amount<span class="text-danger sup">*</span></label>
                    <input id="amount" type="text" class="form-control" name="amount" value="' . $transfer_exe_data?->amount . '">
                </div>';
        $data .= "<script>$('.js-example-basic-single').select2({
                    placeholder: 'Select Option',
                    allowClear: true
                });</script>";

        return response()->json(array('data' => $data));
    }
}
