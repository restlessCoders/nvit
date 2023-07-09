<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Models\Batch;
use App\Models\Batchslot;
use App\Models\Batchtime;
use App\Models\Course;
use App\Models\User;
use App\Models\Reference;
use DateTime;
use DateInterval;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    use ResponseTrait;
    //To Show Course Wise Student Enroll Data
    public function coursewiseEnrollStudent(Request $request)
    {
        $courses = Course::where('status', 1)->get();
        $courseInfo = Course::find($request->course_id);
        $references = Reference::all();
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();

        $allCourses = DB::table('student_courses')
            ->select('student_courses.id as sc_id', 'students.id as sId', 'students.name as sName', 'students.contact', 'students.refId', 'users.username as exName', 'student_courses.created_at', 'student_courses.status', 'student_courses.course_id', 'student_courses.price', 'student_courses.p_status', 'student_courses.systemId')
            ->join('students', 'students.id', '=', 'student_courses.student_id')
            ->join('users', 'users.id', '=', 'students.executiveId');
     


        if ($request->course_id) {
            $allCourses->where('student_courses.course_id', $request->course_id);
        }
        if ($request->refId) {
            $allCourses->where('students.refId', $request->refId);
        }
        if ($request->executiveId) {
            $allCourses->where('students.executiveId', $request->executiveId);
        }
        if ($request->status) {
            $allCourses->where('student_courses.status', $request->status);
        }
        
        $allCourses = $allCourses->orderBy('student_courses.created_at', 'desc')->paginate(20);

        return view('report.course.course_wise_student_enroll', ['executives' => $executives, 'references' => $references, 'allCourses' => $allCourses, 'courses' => $courses, 'courseInfo' => $courseInfo]);
    }
    //To Show Batchwise Student Enroll Data
    public function batchwiseEnrollStudent(Request $request)
    {
        $batches = Batch::where('status', 1)->get();
        $batchInfo = Batch::find($request->batch_id);
        $references = Reference::all();
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $batch_seat_count = DB::table('student_batches')->where('batch_id', $request->batch_id)->count('student_id');

        $allBatches = DB::table('student_batches')
            ->select('student_batches.id as sb_id', 'student_batches.systemId', 'students.id as sId', 'students.name as sName', 'students.contact', 'students.refId', 'users.username as exName', 'student_batches.entryDate', 'student_batches.status', 'student_batches.batch_id', 'student_batches.course_id', 'student_batches.type', 'student_batches.course_price', 'student_batches.pstatus','student_batches.isBundel')
            ->join('students', 'students.id', '=', 'student_batches.student_id')
            ->join('users', 'users.id', '=', 'students.executiveId');

        if($request->studentId){
            $allBatches->where('students.id', $request->studentId)
            ->orWhere('students.name', 'like', '%'.$request->studentId.'%')
            ->orWhere('students.name', 'like', '%'.$request->studentId.'%')
            ->orWhere('students.contact', 'like', '%'.$request->studentId.'%');
        }

        if ($request->batch_id) {
            $allBatches->where('student_batches.batch_id', $request->batch_id);
        }
        if ($request->refId) {
            $allBatches->where('students.refId', $request->refId);
        }
        if ($request->executiveId) {
            $allBatches->where('students.executiveId', $request->executiveId);
        }
        if (strtolower(currentUser()) == 'accountmanager' || strtolower(currentUser()) == 'frontdesk') {
            $allBatches->where('student_batches.status', 2);
        }
        if (strtolower(currentUser()) == 'accountmanager'){
            $allBatches->where('student_batches.isBundel', 0);
        }
        if ($request->status) {
            $allBatches->where('student_batches.status', $request->status);
        }
        $perPage = 20;

        $allBatches = $allBatches->orderBy('student_batches.created_at', 'desc')->paginate($perPage)->appends([
            'executiveId' => $request->executiveId,
            'studentId' => $request->studentId,
            'batch_id' => $request->batch_id,
            'refId' => $request->refId,
            'status' => $request->status,
        ]);
        return view('report.batch.batch_wise_student_enroll', ['executives' => $executives, 'batch_seat_count' => $batch_seat_count, 'references' => $references, 'allBatches' => $allBatches, 'batches' => $batches, 'batchInfo' => $batchInfo]);
    }
    public function coursewiseStudent(Request $request)
    {
        $courses = Course::where('status', 1)->get();
        $courseInfo = Course::find($request->course_id);
        $references = Reference::all();
        $batch_slots = Batchslot::all();
        $batch_times = Batchtime::all();
        $executives = User::whereIn('roleId', [1, 3, 9])->get();

        $courses_pre = DB::table('course_preferences')
            ->select('course_preferences.created_at', 'course_preferences.updated_at', 'students.id as sId', 'students.name as sName', 'students.contact', 'students.refId', 'users.username as exName', 'course_preferences.course_id', 'courses.courseName', 'course_preferences.batch_slot_id', 'course_preferences.batch_time_id')
            ->join('courses', 'course_preferences.course_id', '=', 'courses.id')
            ->join('students', 'students.id', '=', 'course_preferences.student_id')
            ->join('batchslots', 'course_preferences.batch_slot_id', '=', 'batchslots.id')
            ->join('batchtimes', 'course_preferences.batch_time_id', '=', 'batchtimes.id')
            ->join('users', 'users.id', '=', 'students.executiveId');

        if ($request->course_id) {
            $courses_pre->where('course_preferences.course_id', $request->course_id);
        }
        if ($request->refId) {
            $courses_pre->where('students.refId', $request->refId);
        }
        if ($request->executiveId) {
            $courses_pre->where('students.executiveId', $request->executiveId);
        }
        if ($request->executiveId) {
            $courses_pre->where('students.executiveId', $request->executiveId);
        }
        if ($request->slotId) {
            $courses_pre->where('course_preferences.batch_slot_id', $request->slotId);
        }
        if ($request->timeId) {
            $courses_pre->where('course_preferences.batch_time_id', $request->timeId);
        }
        $courses_pre = $courses_pre->orderBy('course_preferences.created_at', 'desc')->paginate(20);
        return view('report.course.course_wise_student', ['batch_times' => $batch_times, 'batch_slots' => $batch_slots, 'executives' => $executives, 'references' => $references, 'courses_pre' => $courses_pre, 'courses' => $courses, 'courseInfo' => $courseInfo]);
    }
    public function batchwiseAttendance()
    {
        $batches = Batch::where('status', 1)->get();
        return view('report.attendance.batch_wise_attendance', compact('batches'));
    }
    public function batchwiseAttendanceReport(Request $request)
    {
        $batch_data = Batch::find($request->batch_id);
        $image_path = asset('backend/images/logo.webp');
        $data = '<div class="col-md-12 text-center">';
        $data .= '<div class="row">';
        $data .= '<div class="col-md-2"><img src=' . $image_path . ' alt="" height="80"></div>';
        $data .= '<div class="col-md-10">';
        $data .=     '<h4 class="m-0">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>';
        $data .=     '<p class="m-0" style="font-size:10px"><strong>Batch Completion Report</strong></p>';
        $data .=     '<p class="m-0 d-flex justify-content-center">
                        <strong>Started On : ' . \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') . '</strong>
                        <strong>' . \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName . '</strong>
                        <strong>' . \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time . '</strong>';
        $data .= '</div>';
        $data .= '<div class="col-md-12">';
        $data .=     '<p class="m-0 d-flex justify-content-between">
                        <strong>Trainer : ' . \DB::table('users')->where('id', $batch_data->trainerId)->first()->name . '</strong>
                        <strong>Course : ' . \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName . '</strong>
                        <strong>Batch : ' . $batch_data->batchId . '</strong></p>';
        $data .= '</div>';
        $data .= '</div>';
        $data .= '</div>';


        $startDate = new DateTime($batch_data->startDate);
        $endDate = new DateTime($batch_data->endDate);

        // Create a DateInterval of 1 day
        $interval = new DateInterval('P1D');

        $data .= '<table class="table table-sm" style="border:1px solid #000;color:#000;">
                    <tbody>
                        <tr>
                            <th width="120px" rowspan="3" class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Student Name</strong></th>
                            <th width="40px" rowspan="3" class="align-middle" style="border:1px solid #000;;color:#000;"><strong>INV</strong></th>
                            <th width="140px" style="border:1px solid #000;;color:#000;"><strong>Class Date</strong></th>';
        // Loop through the date range
        //$count = $request->count_class;
        $count = 0;
        $date = $startDate;
        while ($date <= $endDate) {

            // Check if the current date is a Saturday, Monday or Wednesday
            if ($date->format('l') == 'Saturday' || $date->format('l') == 'Monday' || $date->format('l') == 'Wednesday') {
                // Display the date in a column
                if ($count < 17) {
                    /*Carbon\Carbon::createFromTimestamp(strtotime($date->format('Y-m-d')))->format('j/m/y')*/
                    $data .= '<td style="border:1px solid #000;;color:#000;"></td>';
                }
                $count++;
            }
            $date->add($interval);
        }
        if ($count > 17) $count = 17;
        $data .=    '</tr>
                        <tr>
                            <th style="border:1px solid #000;;color:#000;"><strong>Trainer Sign:</strong></th>';
        for ($i = 0; $i < $count; $i++) {
            $data .= '<td class="cell" rowspan="2" style="border:1px solid #000;color:#000;"></td>';
        }
        $data .=    '</tr>';
        $data .=    '<tr>
                            <th style="border:1px solid #000;color:#000;"><strong>AE:</strong></th>';
        $data .=    '</tr>';
        if ($request->batch_id) {
            $batch_students = DB::table('student_batches')->where('batch_id', $request->batch_id)->where('status', 2)->get();
        }
        foreach ($batch_students as $batch_student) {
            $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();
            $data .= '<tr>';
            $data .= '<td style="border:1px solid #000;color:#000;">' . $s_data->name . '</td>';
            $data .= '<td style="border:1px solid #000;color:#000;">';
            if (\DB::table('payments')
                ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
                ->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->exists()
            ) {
                $data .= \DB::table('payments')->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->first()->invoiceId;
            } else {
                $data .= '-';
            }
            '</td>';
            $data .= '<td style="border:1px solid #000;color:#000;">' . \DB::table('users')->where('id', $s_data->executiveId)->first()->username . '</td>';
            for ($i = 0; $i < $count; $i++) {
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
            }
            $data .= '</tr>';
        }
        $data .=    '</tbody>
                </table>';





        return response()->json(array('data' => $data));
    }
    public function batchwiseCompletion()
    {
        $batches = Batch::all();
        return view('report.complete.batch_wise_complete', compact('batches'));
    }
    public function batchwiseCompletionReport(Request $request)
    {
        $batch_data = Batch::find($request->batch_id);
        $image_path = asset('backend/images/logo.webp');
        $data = '<div class="col-md-12 text-center">';
        $data .= '<div class="row">';
        $data .= '<div class="col-md-2"><img src=' . $image_path . ' alt="" height="80"></div>';
        $data .= '<div class="col-md-10">';
        $data .=     '<h4 class="m-0">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>';
        $data .=     '<p class="m-0" style="font-size:10px"><strong>Batch Completion Report</strong></p>';
        $data .=     '<p class="m-0 d-flex justify-content-center">
                        <strong>Started On : ' . \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') . '</strong>
                        <strong>' . \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName . '</strong>
                        <strong>' . \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time . '</strong>';
        $data .= '</div>';
        $data .= '<div class="col-md-12">';
        $data .=     '<p class="m-0 d-flex justify-content-between">
                        <strong>Trainer : ' . \DB::table('users')->where('id', $batch_data->trainerId)->first()->name . '</strong>
                        <strong>Course : ' . \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName . '</strong>
                        <strong>Batch : ' . $batch_data->batchId . '</strong></p>';
        $data .= '</div>';
        $data .= '</div>';
        $data .= '</div>';


        $startDate = new DateTime($batch_data->startDate);
        $endDate = new DateTime($batch_data->endDate);

        // Create a DateInterval of 1 day
        $interval = new DateInterval('P1D');
        if(currentUser() == 'trainer'){
            $data .= '<form action="'.route(currentUser().'.certificate.store').'" method="post"> ' . csrf_field() . '';
        }
       
        $data .= '<table class="table table-sm" style="border:1px solid #000;color:#000;">
                    <tbody>
                        <tr>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Student Name</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Invoice</strong></th>
                            <th style="border:1px solid #000;color:#000;"><strong>Executive</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Attn.</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Perf.</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Pass</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Drop</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Ins. Note</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Acc. Note</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Op. Note</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>GM. Note</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Ex. Note</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Issued</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Delivered</strong></th>
                        </tr>';
        if ($request->batch_id) {
            $batch_students = DB::table('student_batches')->where('batch_id', $request->batch_id)->where('status', 2)->get();
        }
        foreach ($batch_students as $batch_student) {
            $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();
            $data .= '<tr>';
            $data .= '<td style="border:1px solid #000;color:#000;">' . $s_data->name . '</td>';
            $data .= '<td style="border:1px solid #000;color:#000;">';
            if (\DB::table('payments')
                ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
                ->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->exists()
            ) {
                $data .= \DB::table('payments')->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->first()->invoiceId;
            } else {
                $data .= '-';
            }
            '</td>';
            $data .= '<td style="border:1px solid #000;color:#000;">' . \DB::table('users')->where('id', $s_data->executiveId)->first()->username . '</td>';
            
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="2" type="text" name="attn[]"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="perf[]"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="pass[]"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="drop[]"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="issue_status[]"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="delivery_status[]"></td>';
            
            $data .= '</tr>';
        }
        $data .=    '</tbody>
                </table>';
       
        if(currentUser() == 'trainer'){   
            $data .= '<div class="col-md-12 d-flex justify-content-end"><button class="btn btn-primary" type="submit">Save</button></div>';  
            $data .= '</form>';
        }




        return response()->json(array('data' => $data));
    }
    public function editEnrollStudent($id)
    {
        $batches = Batch::where('status', 1)->get();
        $enroll_data = DB::table('student_batches')->where('id', encryptor('decrypt', $id))->first();
        /*echo '<pre>';
        print_r($enroll_data);die;*/
        $course_type = DB::table('courses')->where('id', $enroll_data->course_id)->first()->course_type;
        /*Course Type 1 For Regualr and 2 For Bundel */
        if ($course_type == 1) {
            return view('enroll.regular_course_assign', compact('enroll_data', 'batches'));
        } elseif ($course_type == 2) {
            return view('enroll.bundel_course_assign', compact('enroll_data', 'batches'));
        } else {
        }
        //print_r($enroll_data);die;
    }
    public function assign_batch_toEnrollStudent(Request $request, $id)
    {
        DB::beginTransaction();
        if ($request->batch_id) {
            $seat_data = DB::select("SELECT COUNT(student_batches.id) as tst ,batches.seat as seat_available FROM batches
                        left join student_batches on student_batches.batch_id=batches.id
                        WHERE batches.id=$request->batch_id
                        GROUP by student_batches.batch_id,batches.seat");
            /*print_r($seat_data);
            die;*/
            if ($seat_data[0]->tst > $seat_data[0]->seat_available)
                return redirect()->back()->with($this->responseMessage(false, null, 'No Seat Available!!'));
            else {
                $data = array(
                    'course_id' => $request->course_id,
                    'batch_id' => $request->batch_id,
                    'student_id' =>  $request->student_id,
                    'package_id' =>  0,
                    'entryDate' => date('Y-m-d'),
                    'status' => 2,
                    'systemId' =>  $request->systemId,
                    'course_price' => 0.00,
                    'type' => 0,
                    'isBundel' => 1,
                    'created_at' => Carbon::now(),
                    'created_by' => currentUserId(),
                );
                DB::table('student_batches')->insert($data);
                $data = DB::table('bundel_course_enroll')->where('id', $request->bundel_id)->update([
                    'status' => 1,
                    'updated_by' => currentUserId(),
                ]);
                DB::commit();
                return redirect()->back()->with($this->responseMessage(true, null, 'Batch Assigned Successfully'));
            }
        } else {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please Select Batch!'));
        }
        DB::rollback();
        return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
    }
}
