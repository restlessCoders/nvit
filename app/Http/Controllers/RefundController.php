<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //print_r($request->toArray());die;
        /*==Batch Informatiom== */
        $batch_info = DB::table('student_batches')->where('id',$request->sb_id)->first();
        //print_r($batch_info);die;
        /* Payment and Payment Details */
        $payment_detl = DB::table('paymentdetails')->where(['studentId' => $batch_info->student_id]);
        if($batch_info->batch_id)
        $payment_detl = $payment_detl->where('batchId',$batch_info->batch_id);
        if($batch_info->course_id){
            $payment_detl = $payment_detl ->where('course_id',$batch_info->course_id);
        }
        
        $payment_detl = $payment_detl->get();

        foreach($payment_detl as $payment){
            DB::table('paymentdetails')->where('id',$payment->id)->update(['deduction' => -$payment->cpaidAmount]);
            $payment_data = DB::table('payments')->where('id',$payment->paymentId)->first();
            DB::table('payments')->where('id',$payment->paymentId)->update(['deduction' => -$payment_data->paidAmount]);

        }
       return redirect()->back();
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
