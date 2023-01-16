<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Paymentdetail;
use App\Http\Requests\Payment\NewPaymentRequest;
use App\Http\Requests\Student\NewPaymentRequest as StudentNewPaymentRequest;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;
use DB;
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
        $allPayment = Payment::orderBy('id', 'DESC')->paginate(25);
        return view('payment.index', compact('allPayment'));
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
    public function store(NewPaymentRequest $request)
    {
        /*echo '<pre>';
        print_r($request->all());
        die;*/
        DB::beginTransaction();

        try {

            $paymentId = DB::table('payments')->insert(
                [
                    'paymentDate'       =>  date('Y-m-d',strtotime($request->paymentDate)),
                    'studentId'         =>  encryptor('decrypt', $request->studentId),
                    'executiveId'       =>  $request->executiveId,
                    'createdBy'         =>  encryptor('decrypt', $request->userId),
                    'invoiceId'         =>  $request->invoiceId?$request->invoiceId:null,
                    'tPayable'          =>  $request->tPayable,
                    'paidAmount'        =>  $request->paidAmount,
                    'discount'          =>  $request->discount,
                    'couponId'          =>  $request->couponId?$request->couponId:null,
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
            $m_price	    = $request->post('m_price');
            foreach($request->batch_id as $key => $cdata){
                /*if($cPayable[$key] == $cpaidAmount[$key]){
                    $payment_detail['type']             = 0;
                }*/
                $payment_detail['paymentId']        = $paymentId;
                $payment_detail['mrNo']             = $request->mrNo;
                $payment_detail['studentId']        = encryptor('decrypt', $request->studentId);
                $payment_detail['batchId']          = $batch_id[$key];
                $payment_detail['cPayable']         = $cPayable[$key];
                $payment_detail['cpaidAmount']      = $cpaidAmount[$key];
                $payment_detail['m_price']          = $m_price[$key]?$m_price[$key]:0.00;
                $payment_detail['type']             = ($cPayable[$key] == $cpaidAmount[$key])?0:1;
                $payment_detail['dueDate']          = date('Y-m-d',strtotime($dueDate[$key]));
                $payment_detail['created_at']       = date("Y-m-d h:i:s");
                /*$payment_detail['updated_at']       = date("Y-m-d h:i:s");*/

                DB::table('paymentdetails')->insert($payment_detail);

                /*To Update Account Approve */
                $s_batch_data = DB::table('student_batches')->where(['student_id'=>encryptor('decrypt', $request->studentId),'batch_id'=>$batch_id[$key]])->first();
                $data = array(
                    'acc_approve' => 1,
                    'updated_at' => Carbon::now()
                );
                DB::table('student_batches')->where('id',$s_batch_data->id)->update($data);
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
    public function edit($id)
    {
        
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