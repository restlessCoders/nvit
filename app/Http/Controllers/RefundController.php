<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Note;
use DB;
use App\Http\Traits\ResponseTrait;
class RefundController extends Controller
{
    use ResponseTrait;
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
        if($request->batch_id !=1){
            $batch_single_info = DB::table('student_batches')->where(['student_id'=>$request->sb_id,'batch_id' => $request->batch_id])->first();
            if($batch_single_info){
                /*==== Refund ===*/
               
                    /* Payment and Payment Details */
                    $payment_detl = DB::table('paymentdetails')->where(['studentId' => $batch_single_info->student_id,'batchId' => $request->batch_id]);
                    if($batch_single_info->batch_id)
                    $payment_detl = $payment_detl->where('batchId',$batch_single_info->batch_id);
                    if($batch_single_info->course_id){
                        $payment_detl = $payment_detl ->where('course_id',$batch_single_info->course_id);
                    }
                    
                    $payment_detl = $payment_detl->get();
            
                    foreach($payment_detl as $payment){
                        DB::table('paymentdetails')->where('id',$payment->id)->update(['deduction' => -$payment->cpaidAmount,'op_type' => $request->op_type]);
                        $payment_data = DB::table('payments')->where('id',$payment->paymentId)->first();
                        DB::table('payments')->where('id',$payment->paymentId)->update(['deduction' => -$payment->cpaidAmount,'op_type' => $request->op_type]);
            
                    }
                    if($batch_single_info){
                        DB::table('student_batches')->where(['student_id'=>$request->sb_id,'batch_id' => $request->batch_id])->update(['acc_approve' => 3,'op_type' => $request->op_type]);
                        $note               =  new Note;
                        $note->student_id   =  $batch_single_info->student_id;
                        $note->note         = $request->note;
                        $note->created_by   = currentUserId();
                        $note->save();
                    }
                    if($request->type == 1){
                        return redirect()->route(currentUser().'.batchwiseEnrollStudent')->with($this->responseMessage(true, null, 'Refund Successful'));
                    }else{
                        return redirect()->route(currentUser().'.batchwiseEnrollStudent')->with($this->responseMessage(true, null, 'Adjustment Successful'));
                    }
            }
        }else{
            $batch_multiple_info = DB::table('student_batches')->where(['systemId'=>$request->systemId])->get();
            if($batch_multiple_info){
                //print_r($batch_info);die;
            }
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
    public function edit(Request $request, $id)
    {
        $student = Student::find($id);
        $enrollStudent = DB::table('student_batches')->where('student_id', $id)->groupBy('systemId')->get();

        return view('adjustment.edit',compact('student','enrollStudent'));
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
    public function databySystemId(Request $request)
    {
        $enrollStudent = DB::table('students')
            ->join('student_batches', function ($join) {
                $join->on('students.id', '=', 'student_batches.student_id')
                    ->where('student_batches.status', '=', '2')
                    /*->where('student_batches.pstatus', '=', '0')*/
                    ->where('student_batches.acc_approve', '!=', '3');
            })
            ->join('batches', 'student_batches.batch_id', '=', 'batches.id')
            ->select('student_batches.batch_id', 'batches.batchId')
            ->where('student_batches.systemId', $request->systemId)
            ->get();
            //print_r($enrollStudent->toArray());die;
            /* Check This data is getting batch data or course data if getting course data prepare another  with if condition course*/ 
        $data = '<div class="col-sm-3"><label>Select Batch|Course<span class="text-danger sup">*</span></label><select class="js-example-basic-single form-control" id="batch_id" name="batch_id" required>';
        $data .= '<option value="">Select</option>';
        $data .= '<option value="1">All</option>';
        foreach ($enrollStudent as $e) {
            $data .= '<option value="' . $e->batch_id . '">' . $e->batchId . '</option>';
        }
        $data .= '</select></div>';


        return response()->json(array('data' => $data));
    }
}
