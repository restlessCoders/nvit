<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

use App\Models\Batch;

class ReportController extends Controller
{
    //To Show Batchwise Student Enroll Data
    public function batchwiseEnrollStudent(Request $request){
        $batch_id = $request->get('batch_id');
        $batches = Batch::all();
        $batchInfo = Batch::find($batch_id);
        if($batch_id){
            $allBatches = DB::table('student_batches')
            ->select('students.name as sName','students.contact','users.name as exName','student_batches.entryDate','student_batches.status')
            ->join('students','students.id','=','student_batches.student_id')
            ->join('users','users.id','=','students.executiveId')
            ->where(['student_batches.batch_id'=>$batch_id])
            ->get();
        }elseif($request->status){
            $allBatches = DB::table('student_batches')
            ->select('students.name as sName','students.contact','users.name as exName','student_batches.entryDate','student_batches.status')
            ->join('students','students.id','=','student_batches.student_id')
            ->join('users','users.id','=','students.executiveId')
            ->where(['student_batches.batch_id'=>$batch_id,'student_batches.status' => $request->status])
            ->get();
        }else{
            $allBatches = array();
            $batchInfo = array();
            return view('report.batch_wise_student_enroll',compact('batches','allBatches','batchInfo'));
        } 
            
            
        if(count($allBatches )>0){
            return view('report.batch_wise_student_enroll',['allBatches'=>$allBatches,'batches' => $batches,'batchInfo' => $batchInfo]);
            }
            return back()->with('error','No results Found'); 
        
    }
}
