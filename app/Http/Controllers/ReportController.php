<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use App\Models\Reference;
use DateTime;
use DateInterval;
class ReportController extends Controller
{
    //To Show Batchwise Student Enroll Data
    public function batchwiseEnrollStudent(Request $request){
        $batches = Batch::all();
        $batchInfo = Batch::find($request->batch_id);
        $references = Reference::all();
        $executives = User::whereIn('roleId',[1,3,9])->get();
        $batch_seat_count = DB::table('student_batches')->where('batch_id',$request->batch_id)->count('student_id');
      
        $allBatches = DB::table('student_batches')
            ->select('students.id as sId','students.name as sName','students.contact','students.refId','users.name as exName','student_batches.entryDate','student_batches.status','student_batches.batch_id','student_batches.type','student_batches.course_price','student_batches.pstatus')
            ->join('students','students.id','=','student_batches.student_id')
            ->join('users','users.id','=','students.executiveId');
            
  
        if($request->batch_id){
            $allBatches->where('student_batches.batch_id',$request->batch_id);
        }
        if($request->refId){
            $allBatches->where('students.refId',$request->refId);
            
        }
        if($request->executiveId){
            $allBatches->where('students.executiveId',$request->executiveId);
        }    
        if($request->status){
            $allBatches->where('student_batches.status',$request->status);
            
        }
        $allBatches = $allBatches->get();
        return view('report.batch.batch_wise_student_enroll',['executives'=>$executives,'batch_seat_count'=>$batch_seat_count,'references' => $references,'allBatches'=>$allBatches,'batches' => $batches,'batchInfo' => $batchInfo]);
    }
    public function coursewiseStudent(Request $request){
        $courses = Course::all();
        $courseInfo = Course::find($request->course_id);
        $references = Reference::all();
        $executives = User::whereIn('roleId',[1,3,9])->get();
      
        $courses_pre = DB::table('course_preferences')
            ->select('students.id as sId','students.name as sName','students.contact','students.refId','users.name as exName','course_preferences.course_id','courses.courseName')
            ->join('courses','course_preferences.course_id','=','courses.id')
            ->join('students','students.id','=','course_preferences.student_id')
            ->join('users','users.id','=','students.executiveId');

        if($request->course_id){
            $courses_pre->where('course_preferences.course_id',$request->course_id);
        }
        if($request->refId){
            $courses_pre->where('students.refId',$request->refId);
        }
        if($request->executiveId){
            $courses_pre->where('students.executiveId',$request->executiveId);
        }    
        $courses_pre = $courses_pre->get();
        return view('report.course.course_wise_student',['executives'=>$executives,'references' => $references,'courses_pre'=>$courses_pre,'courses' => $courses,'courseInfo' => $courseInfo]);
    }
    public function batchwiseAttendance(){
        $batches = Batch::all();
        return view('report.attendance.batch_wise_attendance',compact('batches'));
    }
    public function batchwiseAttendanceReport(Request $request){
        $batch_data = Batch::find($request->batch_id);

       $data = '<div class="col-md-12 text-center">';
       $data .=     '<h5 class="m-0">NEW VISION INFORMATION TECHNOLOGY LTD.</h5>';
       $data .=     '<p class="m-0" style="font-size:10px"><strong>Trainer\'s Attendance Roster</strong></p>';
       $data .=     '<p class="m-0 d-flex justify-content-end">
                        <strong style="margin-right:20px;">Started On : '.\Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y').'</strong>
                        <strong style="margin-right:20px;">'.\DB::table('batchslots')->where('id',$batch_data->bslot)->first()->slotName.'</strong>
                        <strong style="margin-right:20px;">'.\DB::table('batchtimes')->where('id',$batch_data->btime)->first()->time.'</strong>
                        <strong style="margin-right:20px;">Trainer : '.\DB::table('users')->where('id',$batch_data->trainerId)->first()->name.'</strong>
                        <strong style="margin-right:20px;">Course : '.\DB::table('courses')->where('id',$batch_data->courseId)->first()->courseName.'</strong>
                        <strong>Batch : '.$batch_data->batchId.'</strong></p>';
       $data .= '</div>';
       


       $startDate = new DateTime($batch_data->startDate);
       $endDate = new DateTime($batch_data->endDate);

       // Create a DateInterval of 1 day
       $interval = new DateInterval('P1D');

        $data .='<table class="table table-sm" style="border:1px solid #000;color:#000;">
                    <tbody>
                        <tr>
                            <th width="120px" rowspan="3" class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Student Name</strong></th>
                            <th width="40px" rowspan="3" class="align-middle" style="border:1px solid #000;;color:#000;"><strong>INV</strong></th>
                            <th width="140px" style="border:1px solid #000;;color:#000;"><strong>Class Date</strong></th>';
                            // Loop through the date range
                            //$count = $request->count_class;
                            $count = 0;
                            $date = $startDate;
                            while ( $date <= $endDate) {
                            
                            // Check if the current date is a Saturday, Monday or Wednesday
                            if ($date->format('l') == 'Saturday' || $date->format('l') == 'Monday' || $date->format('l') == 'Wednesday') {
                            // Display the date in a column
                                if($count < 17){  
                                    /*Carbon\Carbon::createFromTimestamp(strtotime($date->format('Y-m-d')))->format('j/m/y')*/
                                    $data .='<td style="border:1px solid #000;;color:#000;"></td>';
                                }
                                $count ++;    
                                }
                                $date->add($interval);
                            }
                            if($count > 17) $count = 17;
        $data .=    '</tr>
                        <tr>
                            <th style="border:1px solid #000;;color:#000;"><strong>Trainer Sign:</strong></th>';
                            for($i=0;$i< $count;$i++){
                                $data .= '<td rowspan="2" style="border:1px solid #000;color:#000;"></td>';   
                            }
        $data .=    '</tr>';
        $data .=    '<tr>
                            <th style="border:1px solid #000;color:#000;"><strong>AE:</strong></th>';
        $data .=    '</tr>';
                    if($request->batch_id){
                        $batch_students = DB::table('student_batches')->where('batch_id',$request->batch_id)->where('status',2)->get();
                    }
                    foreach($batch_students as $batch_student){
                        $s_data = \DB::table('students')->where('id',$batch_student->id)->first();
                        $data .= '<tr>';
                        $data .= '<td style="border:1px solid #000;color:#000;">'.$s_data->name.'</td>';
                        $data .= '<td style="border:1px solid #000;color:#000;">'.\DB::table('paymentdetails')->where(['batchId'=> $request->batch_id,'studentId'=>$batch_student->id])->whereNotNull('invoiceId')->exists()?'<td>-</td>':'<td>'.\DB::table('paymentdetails')->where(['batchId'=> $request->batch_id,'studentId'=>$batch_student->id])->whereNotNull('invoiceId')->first()->invoiceId.'</td>';
                        $data .= '<td style="border:1px solid #000;color:#000;">'.\DB::table('users')->where('id',$s_data->id)->first()->name.'</td>';
                        for($i=0;$i< $count;$i++){
                            $data .= '<td style="border:1px solid #000;color:#000;"></td>';   
                        }
                        $data .= '</tr>';
                    }
        $data .=    '</tbody>
                </table>';

               
     

       return response()->json(array('data' =>$data));
  
    }
}
