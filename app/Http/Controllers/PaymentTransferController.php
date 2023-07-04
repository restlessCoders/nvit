<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransfer;
use App\Models\Transaction;
use App\Models\Student;
use App\Models\User;
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
            $transaction->exe_id = $request->from_exe_id;
            $transaction->amount = -$request->amount;
            $transaction->type = 'amount_transfer';
            $transaction->trx_type = '-';
            $transaction->details = $request->amount.' Amount transfer to '.$to_exe_id->username;
            $transaction->postingDate = $request->postingDate?date('Y-m-d', strtotime($request->postingDate)):null;
            $transaction->save();

            $transaction = new Transaction();
            $transaction->studentId = $request->studentId;
            $transaction->exe_id = $request->to_exe_id;
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
}
