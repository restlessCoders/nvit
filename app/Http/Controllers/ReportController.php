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
use App\Models\Payment;
use DateTime;
use DateInterval;
use App\Http\Traits\ResponseTrait;
use App\Models\Attendance;
use App\Models\Certificate;
use Illuminate\Support\Carbon;
use View;

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

        $allCourses = $allCourses->orderBy('student_courses.created_at', 'desc')->where('student_courses.status', '!=', 3)->paginate(20);

        return view('report.course.course_wise_student_enroll', ['executives' => $executives, 'references' => $references, 'allCourses' => $allCourses, 'courses' => $courses, 'courseInfo' => $courseInfo]);
    }
    //To Show Batchwise Student Enroll Data
    public function batchwiseEnrollStudent(Request $request)
    { // Initializing variables from the request
        $from = !empty($request->from) ? \Carbon\Carbon::parse($request->from)->format('Y-m-d') : null;
        $to = !empty($request->to) ? \Carbon\Carbon::parse($request->to)->format('Y-m-d') : null;

        // Retrieving batches, courses, references, and executives
        $batches = Batch::where('status', 1)->get();
        $courses = Course::where('status', 1)->get();
        $batchInfo = Batch::find($request->batch_id);
        $references = Reference::all();
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $batch_seat_count = DB::table('student_batches')
            ->where('batch_id', $request->batch_id)
            ->where('status', 2)
            ->where('is_drop', 0)
            ->count('student_id');

        // Initialize the query builder
        $allBatches = DB::table('student_batches')
            ->leftJoin('paymentdetails', function ($join) {
                $join->on('student_batches.student_id', '=', 'paymentdetails.studentId')
                    ->on('student_batches.batch_id', '=', 'paymentdetails.batchId')
                    ->on('student_batches.course_id', '=', 'paymentdetails.course_id');
            })
            ->join('students', 'student_batches.student_id', '=', 'students.id') // Join with students
            ->leftJoin('users', 'students.executiveId', '=', 'users.id') // Left join with users (executive data)
            ->leftJoin('payments', 'payments.id', '=', 'paymentdetails.paymentId') // Left join with payments
            ->select(
                'student_batches.op_type',
                'student_batches.id as sb_id',
                'student_batches.systemId',
                'students.id as sId',
                'students.name as sName',
                'students.contact',
                'students.refId',
                'students.executiveId',
                'users.username as exName', // Executive name
                'student_batches.entryDate',
                'student_batches.status',
                'student_batches.batch_id',
                'student_batches.course_id',
                'student_batches.type',
                'student_batches.course_price',
                'student_batches.pstatus',
                'student_batches.isBundel',
                'student_batches.is_drop',
                'payments.paymentDate', // From payments (can be null if no payment is recorded)
                'payments.invoiceId' // From payments (can be null if no payment is recorded)
            ); // Retrieve the results

        // Apply filters based on type
        if ($request->type) {
            // Filter for type 1
            if ($request->type == 1) {
                $allBatches->where(function ($query) use ($from, $to) {
                    $query->where('paymentdetails.feeType', '=', 2)
                        ->whereRaw("(paymentdetails.cPayable) - (COALESCE(paymentdetails.discount, 0) + paymentdetails.cpaidAmount) > 0")
                        ->whereIn('paymentdetails.id', function ($subquery) {
                            $subquery->select(DB::raw('MAX(id)'))
                                ->from('paymentdetails as pd')
                                ->whereRaw('pd.studentId = paymentdetails.studentId')
                                ->whereRaw('pd.batchId = paymentdetails.batchId');
                        })
                        ->whereNull('paymentdetails.deleted_at');

                    // Add date range filter for paymentDate
                    if ($from && $to) {
                        $query->whereBetween('payments.paymentDate', [$from, $to]);
                    }
                });
            }

            // Filter for type 2
            if ($request->type == 2) {
                $allBatches = DB::table('paymentdetails as pd')
                    ->join('students', 'pd.studentId', '=', 'students.id')
                    ->leftJoin('users', 'students.executiveId', '=', 'users.id')
                    ->join('student_batches', function ($join) {
                        $join->on('student_batches.student_id', '=', 'pd.studentId')
                            ->on('student_batches.batch_id', '=', 'pd.batchId');
                    })
                    ->join('payments', 'payments.id', '=', 'pd.paymentId')
                    ->select(
                        DB::raw('student_batches.course_price - COALESCE(SUM(pd.discount), 0) AS inv_price'),
                        'student_batches.id as sb_id',
                        'student_batches.op_type',
                        'student_batches.systemId',
                        'students.id as sId',
                        'students.name as sName',
                        'students.contact',
                        'students.refId',
                        'students.executiveId',
                        'users.username as exName',
                        'student_batches.entryDate',
                        'student_batches.status',
                        'student_batches.batch_id',
                        'student_batches.course_id',
                        'student_batches.type',
                        'student_batches.course_price',
                        'student_batches.pstatus',
                        'student_batches.isBundel',
                        'student_batches.is_drop',
                        'payments.paymentDate'
                    )
                    //->groupBy('pd.studentId', 'pd.batchId', 'pd.course_id', 'student_batches.course_price')
                    ->groupBy('student_batches.student_id', 'student_batches.batch_id')
                    ->havingRaw('SUM(pd.cpaidAmount) < (inv_price * 0.5)');

                // Add date range filter for paymentDate
                /*if ($from && $to) {
                    $allBatches->whereBetween('payments.paymentDate', [$from, $to]);
                }*/
            }

            // Filter for type 3
            if ($request->type == 3) {
                $allBatches->where(function ($query) use ($from, $to) {
                    $query->whereRaw("(paymentdetails.cPayable) - (COALESCE(paymentdetails.discount, 0) + paymentdetails.cpaidAmount) = 0")
                        ->whereIn('paymentdetails.id', function ($subquery) {
                            $subquery->select(DB::raw('MAX(id)'))
                                ->from('paymentdetails as pd')
                                ->whereRaw('pd.studentId = paymentdetails.studentId')
                                ->whereRaw('pd.batchId = paymentdetails.batchId');
                        })
                        ->whereNull('paymentdetails.deleted_at');

                    // Add date range filter for paymentDate
                    if ($from && $to) {
                        $query->whereBetween('payments.paymentDate', [$from, $to]);
                    }
                });
            }
        } else {
            $allBatches = $allBatches->groupBy('student_batches.student_id', 'student_batches.batch_id', 'student_batches.course_id');
        }

        // Additional filters
        if ($request->studentId) {
            $allBatches->where('students.id', $request->studentId)
                ->orWhere('students.name', 'like', '%' . $request->studentId . '%')
                ->orWhere('students.contact', 'like', '%' . $request->studentId . '%');
        }

        if ($request->batch_id) {
            $allBatches->where('student_batches.batch_id', $request->batch_id);
        }
        if ($request->course_id) {
            $allBatches->where('student_batches.course_id', $request->course_id);
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
        if (strtolower(currentUser()) == 'accountmanager') {
            $allBatches->where('student_batches.isBundel', 0);
        }
        if ($request->status) {
            $allBatches->where('student_batches.status', $request->status);
        }
        if ($request->drop) {
            $allBatches->where('student_batches.is_drop', 1);
        } else {
            $allBatches->where('student_batches.is_drop', 0);
        }

        // Pagination
        $perPage = 20;

        $allBatches = $allBatches->orderBy('student_batches.created_at', 'desc')->paginate($perPage)->appends([
            'executiveId' => $request->executiveId,
            'batch_id' => $request->batch_id,
            'course_id' => $request->course_id,
            'status' => $request->status,
            'studentId' => $request->studentId,
            'drop' => $request->drop,
            'type' => $request->type,
            'from' => $from,
            'to' => $to,
        ]);

        return view('report.batch.batch_wise_student_enroll', compact('allBatches', 'batches', 'courses', 'batchInfo', 'references', 'executives', 'batch_seat_count'));
    }


    public function batchwiseEnrollStudentPrint(Request $request)
    {
        $from = !empty($request->from) ? \Carbon\Carbon::parse($request->from)->format('Y-m-d') : null;
        $to = !empty($request->to) ? \Carbon\Carbon::parse($request->to)->format('Y-m-d') : null;

        // Retrieving batches, courses, references, and executives
        $batches = Batch::where('status', 1)->get();
        $courses = Course::where('status', 1)->get();
        $batchInfo = Batch::find($request->batch_id);
        $references = Reference::all();
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $batch_seat_count = DB::table('student_batches')
            ->where('batch_id', $request->batch_id)
            ->where('status', 2)
            ->where('is_drop', 0)
            ->count('student_id');

        // Initialize the query builder
        // Initializing variables from the request
        $from = !empty($request->from) ? \Carbon\Carbon::parse($request->from)->format('Y-m-d') : null;
        $to = !empty($request->to) ? \Carbon\Carbon::parse($request->to)->format('Y-m-d') : null;

        // Retrieving batches, courses, references, and executives
        $batches = Batch::where('status', 1)->get();
        $courses = Course::where('status', 1)->get();
        $batchInfo = Batch::find($request->batch_id);
        $references = Reference::all();
        $executives = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $batch_seat_count = DB::table('student_batches')
            ->where('batch_id', $request->batch_id)
            ->where('status', 2)
            ->where('is_drop', 0)
            ->count('student_id');

        // Initialize the query builder
        $allBatches = DB::table('student_batches')
            ->leftJoin('paymentdetails', function ($join) {
                $join->on('student_batches.student_id', '=', 'paymentdetails.studentId')
                    ->on('student_batches.batch_id', '=', 'paymentdetails.batchId');
            })
            ->join('students', 'student_batches.student_id', '=', 'students.id') // Join with students
            ->leftJoin('users', 'students.executiveId', '=', 'users.id') // Left join with users (executive data)
            ->leftJoin('payments', 'payments.id', '=', 'paymentdetails.paymentId') // Left join with payments
            ->select(
                'student_batches.op_type',
                'student_batches.id as sb_id',
                'student_batches.systemId',
                'students.id as sId',
                'students.name as sName',
                'students.contact',
                'students.refId',
                'students.executiveId',
                'users.username as exName', // Executive name
                'student_batches.entryDate',
                'student_batches.status',
                'student_batches.batch_id',
                'student_batches.course_id',
                'student_batches.type',
                'student_batches.course_price',
                'student_batches.pstatus',
                'student_batches.isBundel',
                'student_batches.is_drop',
                'payments.paymentDate', // From payments (can be null if no payment is recorded)
                'payments.invoiceId' // From payments (can be null if no payment is recorded)
            ); // Retrieve the results

        // Apply filters based on type
        if ($request->type) {
            // Filter for type 1
            if ($request->type == 1) {
                $allBatches->where(function ($query) use ($from, $to) {
                    $query->where('paymentdetails.feeType', '=', 2)
                        ->whereRaw("(paymentdetails.cPayable) - (COALESCE(paymentdetails.discount, 0) + paymentdetails.cpaidAmount) > 0")
                        ->whereIn('paymentdetails.id', function ($subquery) {
                            $subquery->select(DB::raw('MAX(id)'))
                                ->from('paymentdetails as pd')
                                ->whereRaw('pd.studentId = paymentdetails.studentId')
                                ->whereRaw('pd.batchId = paymentdetails.batchId');
                        })
                        ->whereNull('paymentdetails.deleted_at');

                    // Add date range filter for paymentDate
                    if ($from && $to) {
                        $query->whereBetween('payments.paymentDate', [$from, $to]);
                    }
                });
            }

            // Filter for type 2
            if ($request->type == 2) {
                $allBatches = DB::table('paymentdetails as pd')
                    ->join('students', 'pd.studentId', '=', 'students.id')
                    ->leftJoin('users', 'students.executiveId', '=', 'users.id')
                    ->join('student_batches', function ($join) {
                        $join->on('student_batches.student_id', '=', 'pd.studentId')
                            ->on('student_batches.batch_id', '=', 'pd.batchId');
                    })
                    ->join('payments', 'payments.id', '=', 'pd.paymentId')
                    ->select(
                        DB::raw('student_batches.course_price - COALESCE(SUM(pd.discount), 0) AS inv_price'),
                        'student_batches.id as sb_id',
                        'student_batches.op_type',
                        'student_batches.systemId',
                        'students.id as sId',
                        'students.name as sName',
                        'students.contact',
                        'students.refId',
                        'students.executiveId',
                        'users.username as exName',
                        'student_batches.entryDate',
                        'student_batches.status',
                        'student_batches.batch_id',
                        'student_batches.course_id',
                        'student_batches.type',
                        'student_batches.course_price',
                        'student_batches.pstatus',
                        'student_batches.isBundel',
                        'student_batches.is_drop',
                        'payments.paymentDate'
                    )
                    //->groupBy('pd.studentId', 'pd.batchId', 'pd.course_id', 'student_batches.course_price')
                    ->groupBy('student_batches.student_id', 'student_batches.batch_id')
                    ->havingRaw('SUM(pd.cpaidAmount) < (inv_price * 0.5)');

                // Add date range filter for paymentDate
                /*if ($from && $to) {
                    $allBatches->whereBetween('payments.paymentDate', [$from, $to]);
                }*/
            }

            // Filter for type 3
            if ($request->type == 3) {
                $allBatches->where(function ($query) use ($from, $to) {
                    $query->whereRaw("(paymentdetails.cPayable) - (COALESCE(paymentdetails.discount, 0) + paymentdetails.cpaidAmount) = 0")
                        ->whereIn('paymentdetails.id', function ($subquery) {
                            $subquery->select(DB::raw('MAX(id)'))
                                ->from('paymentdetails as pd')
                                ->whereRaw('pd.studentId = paymentdetails.studentId')
                                ->whereRaw('pd.batchId = paymentdetails.batchId');
                        })
                        ->whereNull('paymentdetails.deleted_at');

                    // Add date range filter for paymentDate
                    if ($from && $to) {
                        $query->whereBetween('payments.paymentDate', [$from, $to]);
                    }
                });
            }
        } else {
            $allBatches = $allBatches->groupBy('student_batches.student_id', 'student_batches.batch_id');
        }

        // Additional filters
        if ($request->studentId) {
            $allBatches->where('students.id', $request->studentId)
                ->orWhere('students.name', 'like', '%' . $request->studentId . '%')
                ->orWhere('students.contact', 'like', '%' . $request->studentId . '%');
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
        if (strtolower(currentUser()) == 'accountmanager') {
            $allBatches->where('student_batches.isBundel', 0);
        }
        if ($request->status) {
            $allBatches->where('student_batches.status', $request->status);
        }
        if ($request->drop) {
            $allBatches->where('student_batches.is_drop', 1);
        } else {
            $allBatches->where('student_batches.is_drop', 0);
        }


        $allBatches = $allBatches->orderBy('student_batches.created_at', 'desc')->get();

        return View::make('report.batch.batch_wise_student_enroll_print', ['executives' => $executives, 'batch_seat_count' => $batch_seat_count, 'references' => $references, 'allBatches' => $allBatches, 'batches' => $batches, 'batchInfo' => $batchInfo, 'courses' => $courses]);
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
        if (currentUser() == 'trainer')
            $batches = Batch::where('trainerId', currentUserId())->get();
        else
            $batches = Batch::all();

        return view('report.attendance.batch_wise_attendance', compact('batches'));
    }
    public function batchwiseAttendanceReport(Request $request)
    {
        $batch_data = Batch::find($request->batch_id);
        $image_path = asset('backend/images/logo.webp');
        //$data = '<div class="col-md-12 text-center">';
        //$data .= '<div class="row">';
        $data = '<div style="width:10%;display:inline-block;"><img src=' . $image_path . ' alt="" height="40"></div>';
        $data .=     '<div style="width:90%;display:inline-block;text-align:center;"><h4 class="m-0 p-0 text-center" style="font-size:11px;font-weight:700;">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>';
        $data .= '<p class="m-0 p-0 text-center" style="font-size:9px"><strong class="text-center">Course : ' . \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName . '</strong></p>';
        $data .=     '<p class="m-0 p-0 text-center" style="font-size:9px"><strong>Trainer Attendance Roster</strong></p></div>';



        $data .=     '<p class="m-0 p-0" style="font-size:10px;display:flex;justify-content:space-between">
                        <strong>Started On :'  . \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') . '</strong>
                        <strong>' . \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time . '</strong>
                        <strong>' . \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName . '</strong>
                        <strong>Batch : ' . $batch_data->batchId . '</strong>
                        <strong>Trainer : ' . \DB::table('users')->where('id', $batch_data->trainerId)->first()->name . '</strong>  
                        </p>';


        $startDate = new DateTime($batch_data->startDate);
        $endDate = new DateTime($batch_data->endDate);

        // Create a DateInterval of 1 day
        $interval = new DateInterval('P1D');

        $data .= '<table class="table table-sm" style="border:1px solid #000;color:#000;">
                    <tbody>';
        $data .=    '</tr>
                    <tr height="20px">
                        <th colspan="3" style="border:1px solid #000;;color:#000;font-size:9px;text-align:right"><strong>Trainer Sign:</strong></th>';
        for ($i = 0; $i < 17; $i++) {
            $data .= '<td class="cell" style="border:1px solid #000;color:#000;font-size:9px"></td>';
        }
        $data .=    '</tr>';
        $data .=    '</tr>
                    <tr height="20px">
                        <th colspan="3" style="border:1px solid #000;;color:#000;font-size:9px;text-align:right"><strong>Class Date:</strong></th>';
        for ($i = 0; $i < 17; $i++) {
            $data .= '<td class="cell" style="border:1px solid #000;color:#000;font-size:9px"></td>';
        }
        $data .=    '</tr>';

        $data .=    '   <tr>
                            <th width="135px" class="align-middle" style="border:1px solid #000;;color:#000;font-size:9px;"><strong>Student Name</strong></th>
                            <th width="40px" class="align-middle" style="border:1px solid #000;;color:#000;font-size:9px"><strong>INV</strong></th>
                            <th width="40px" class="align-middle" style="border:1px solid #000;color:#000;font-size:9px"><strong>AE:</strong></th>
                            ';
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
                    //$data .= '<td style="border:1px solid #000;;color:#000;"></td>';
                }
                $count++;
            }
            $date->add($interval);
        }
        if ($count > 17) $count = 17;

        if ($request->batch_id) {
            $batch_students = DB::table('student_batches')->where('batch_id', $request->batch_id)->where('status', 2)->where('is_drop', 0)->get();
        }
        foreach ($batch_students as $batch_student) {
            $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();
            $words = explode(" ", $s_data->name);
            $firstThreeWords = array_slice($words, 0, 3);
            $name = implode(" ", $firstThreeWords);

            $data .= '<tr height="20px">';
            $data .= '<td style="border:1px solid #000;color:#000;font-size:8px">' . strtoupper($name) . '</td>';
            $data .= '<td style="border:1px solid #000;color:#000;font-size:8px">';
            if (\DB::table('payments')
                ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
                ->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->exists()
            ) {
                $data .= \DB::table('payments')->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->first()->invoiceId;
            } else {
                $data .= '-';
            }
            '</td>';
            $data .= '<td style="border:1px solid #000;color:#000;font-size:8px">' . \DB::table('users')->where('id', $s_data->executiveId)->first()->username . '</td>';
            for ($i = 0; $i < $count; $i++) {
                $data .= '<td style="border:1px solid #000;color:#000;font-size:8px"></td>';
            }
            $data .= '</tr>';
        }
        $data .=    '</tbody>
                </table>';





        return response()->json(array('data' => $data));
    }
    public function batchwiseCompletion()
    {
        /* Need To use batch Completion status here in table */
        if (currentUser() == 'trainer')
            $batches = Batch::where('trainerId', currentUserId())->get();
        else
            $batches = Batch::all();
        /*$certificate_batches = Certificate::where('created_by',currentUserId())->pluck('batch_id')->unique()->toArray();
        print_r($certificate_batches);die;
        $batches = Batch::where('trainerId',currentUserId())->whereNotIn('id', $certificate_batches)->get();
        print_r($batches);die;*/
        return view('report.complete.batch_wise_complete', compact('batches'));
    }
    public function batchwiseCompletionReport(Request $request)
    {
        $postingDate = DB::table('attendances')->select('postingDate', 'edit_allow')->where('batch_id', $request->batch_id)->groupBy('postingDate')->get();
        $batch_data = Batch::find($request->batch_id);
        $image_path = asset('backend/images/logo.webp');
        $data = '<div style="width:10%;display:inline-block;"><img src=' . $image_path . ' alt="" height="40"></div>';
        $data .=     '<div style="width:90%;display:inline-block;text-align:center;"><h4 class="m-0 p-0 text-center" style="font-size:11px;font-weight:700;">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>';
        $data .= '<p class="m-0 p-0 text-center" style="font-size:9px"><strong class="text-center">Course : ' . \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName . '</strong></p>';
        $data .=     '<p class="m-0 p-0 text-center" style="font-size:9px"><strong>Batch Completion Report</strong></p><p><strong>Total Class:-' . $postingDate->count() . '</strong></p></div>';



        $data .=     '<p class="m-0 p-0" style="font-size:10px;display:flex;justify-content:space-between">
                        <strong>Started On :'  . \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') . '</strong>
                        <strong>' . \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time . '</strong>
                        <strong>' . \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName . '</strong>
                        <strong>Batch : ' . $batch_data->batchId . '</strong>
                        <strong>Trainer : ' . \DB::table('users')->where('id', $batch_data->trainerId)->first()->name . '</strong>  
                        </p>';


        $startDate = new DateTime($batch_data->startDate);
        $endDate = new DateTime($batch_data->endDate);

        // Create a DateInterval of 1 day
        $interval = new DateInterval('P1D');
        if (currentUser() == 'trainer' || currentUser() == 'trainer') {
            $data .= '<form action="' . route(currentUser() . '.certificate.store') . '" method="post"> ' . csrf_field() . '';
        }
        /*<th style="border:1px solid #000;;color:#000;"><strong>Ins. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>Acc. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>Op. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>GM. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>Ex. Note</strong></th>*/
        $data .= '<table class="table table-sm text-center" style="width:100%;text-align:center;border:1px solid #000;color:#000;">
                    <tbody>
                        <tr>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>ID</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Name</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Invoice</strong></th>
                            <th style="border:1px solid #000;color:#000;"><strong>Executive</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Attn.</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Perf.</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Pass</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Drop</strong></th>
                        </tr>';
        if ($request->batch_id) {
            $batch_students = DB::table('student_batches')->where('batch_id', $request->batch_id)->where('status', 2)->where('is_drop',0)->get();
        }
        foreach ($batch_students as $batch_student) {
            $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();
            $cer_data = Certificate::where('student_id', $batch_student->student_id)->where('batch_id', $batch_data->id)->first();
            $data .= '<tr>';
            $data .= '<td style="border:1px solid #000;color:#000;">' . $s_data->id . '</td>';
            $data .= '<input type="hidden" name="student_id[]" value="' . $s_data->id . '">';
            $data .= '<input type="hidden" name="batch_id[]" value="' . $batch_data->id . '">';
            $data .= '<td style="border:1px solid #000;color:#000;">' . strtoupper($s_data->name) . '</td>';
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
            if ($cer_data) {
                //$data .= '<td style="border:1px solid #000;color:#000;"><input size="2" type="text" name="attn[' . $s_data->id . ']" value="' . $cer_data->attn . '"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="2" type="text" name="attn[' . $s_data->id . ']" value="' . Attendance::where('student_id', $s_data->id)->where('batch_id', $request->batch_id)->where('isPresent', '=', 1)->count()  . '"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="perf[' . $s_data->id . ']" ' . ($cer_data->perf == 1 ? 'checked="checked"' : '') . ' value="1"></td>';

                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="pass[' . $s_data->id . ']" ' . ($cer_data->pass == 1 ? 'checked="checked"' : '') . ' value="1"></td>';

                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="drop[' . $s_data->id . ']" ' . ($cer_data->drop == 1 ? 'checked="checked"' : '') . ' value="1"></td>';
                $data .= '<input type="hidden" name="posting_date[]" value="' . date('Y-m-d', strtotime($cer_data->created_at)) . '">';
            } else {
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="2" type="text" name="attn[' . $s_data->id . ']" value="' . Attendance::where('student_id', $s_data->id)->where('batch_id', $request->batch_id)->where('isPresent', '=', 1)->count()  . '" readonly></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="perf[' . $s_data->id . ']" value="1"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="pass[' . $s_data->id . ']" value="1"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="drop[' . $s_data->id . ']" value="1"></td>';
            }

            /*$data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';
                $data .= '<td style="border:1px solid #000;color:#000;"></td>';*/
            $data .= '</tr>';
        }
        $data .=    '</tbody>
                </table>';

        if (currentUser() == 'trainer' || currentUser() == 'trainingmanager') {
            $data .= '<div class="col-md-12 d-flex justify-content-end"><button class="btn btn-primary" type="submit">Save</button></div>';
            $data .= '</form>';
        }


        return response()->json(array('data' => $data));
    }
    public function batchwiseStudentAttnAdd(Request $request)
    {
        $batch_data = Batch::find($request->batch_id);
        $image_path = asset('backend/images/logo.webp');
        $data = '<div style="width:10%;display:inline-block;"><img src=' . $image_path . ' alt="" height="40"></div>';
        $data .=     '<div style="width:90%;display:inline-block;text-align:center;"><h4 class="m-0 p-0 text-center" style="font-size:11px;font-weight:700;">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>';
        $data .= '<p class="m-0 p-0 text-center" style="font-size:9px"><strong class="text-center">Course : ' . \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName . '</strong></p>';
        $data .=     '<p class="m-0 p-0 text-center" style="font-size:9px"><strong>Student Daily Attendance Report</strong></p></div>';



        $data .=     '<p class="m-0 p-0" style="font-size:10px;display:flex;justify-content:space-between">
                        <strong>Started On :'  . \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') . '</strong>
                        <strong>' . \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time . '</strong>
                        <strong>' . \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName . '</strong>
                        <strong>Batch : ' . $batch_data->batchId . '</strong>
                        <strong>Trainer : ' . \DB::table('users')->where('id', $batch_data->trainerId)->first()->name . '</strong>  
                        </p>';


        $startDate = new DateTime($batch_data->startDate);
        $endDate = new DateTime($batch_data->endDate);

        // Create a DateInterval of 1 day
        $interval = new DateInterval('P1D');

        if (currentUser() == 'trainer' || currentUser() == 'trainingmanager') {
            $data .= '<form action="' . route(currentUser() . '.attendance.store') . '" method="post"> ' . csrf_field() . '';
        }
        $data .=    '<div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="col-md-3">
                                <div class="input-group my-2">
                                    <input type="text" name="postingDate" class="form-control" placeholder="dd/mm/yyyy">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
        $data .= '<table class="table table-sm" style="width:100%;border:1px solid #000;color:#000;">
                    <tbody>
                        <tr>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>ID</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Name</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Invoice</strong></th>
                            <th style="border:1px solid #000;color:#000;"><strong>Executive</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Is Present.</strong></th>
                        </tr>';

        if ($request->batch_id) {
            $batch_students = DB::table('student_batches')->where('batch_id', $request->batch_id)->where('status', 2)->where('is_drop', 0)->get();
        }
        foreach ($batch_students as $batch_student) {
            $data .= '<tr>';
            $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();

            $data .= '<td style="border:1px solid #000;color:#000;">' . $s_data->id . '</td>';
            $data .= '<input type="hidden" name="student_id[]" value="' . $s_data->id . '">';
            $data .= '<input type="hidden" name="batch_id[]" value="' . $batch_data->id . '">';
            $data .= '<td style="border:1px solid #000;color:#000;">' . strtoupper($s_data->name) . '</td>';
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

            $data .= '<td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="isPresent[' . $s_data->id . ']" value="1"></td>';
            $data .= '</tr>';
        }




        $data .=    '</tbody>
                </table>';
        if ($request->batch_id) {
            $postingDate = DB::table('attendances')->select('postingDate')->where('batch_id', $request->batch_id)->groupBy('postingDate')->pluck('postingDate')->toArray(); // Convert the result to an array;
            // Convert the array of postingDates to a JavaScript-friendly string
            $jsDisabledDates = implode("','", $postingDate);
            // Add single quotes at the beginning and end of the JavaScript string
            $jsDisabledDates = "'" . $jsDisabledDates . "'";
        }
        $data .= '<div class="col-md-12 d-flex justify-content-end"><button class="btn btn-primary" type="submit">Submit</button></div>';
        $data .= '</form>';
        $data .= '<script>var jsDisabledDates = [' . $jsDisabledDates . '];console.log(jsDisabledDates);
                $("input[name=\'postingDate\']").daterangepicker({
                    singleDatePicker: true,
                    startDate: new Date(),
                    showDropdowns: true,
                    autoUpdateInput: true,
                    format: \'dd/mm/yyyy\',
                    isInvalidDate: function(date) {
                        // Format the date to match the array format
                        var formattedDate = date.format(\'YYYY-MM-DD\');
                        // Check if the formatted date is in the disabledDates array
                        return jsDisabledDates.includes(formattedDate);
                    }
                }).on(\'changeDate\', function(e) {
                    var date = moment(e.date).format(\'YYYY/MM/DD\');
                    $(this).val(date);
                });</script>';




        return response()->json(array('data' => $data));
    }
    public function batchwiseStudentAttnReport(Request $request)
    {
        $batch_data = Batch::find($request->batch_id);
        $image_path = asset('backend/images/logo.webp');
        $data = '<div style="width:10%;display:inline-block;"><img src=' . $image_path . ' alt="" height="40"></div>';
        $data .=     '<div style="width:90%;display:inline-block;text-align:center;"><h4 class="m-0 p-0 text-center" style="font-size:11px;font-weight:700;">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>';
        $data .= '<p class="m-0 p-0 text-center" style="font-size:9px"><strong class="text-center">Course : ' . \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName . '</strong></p>';
        $data .=     '<p class="m-0 p-0 text-center" style="font-size:9px"><strong>Student Daily Attendance Report</strong></p></div>';



        $data .=     '<p class="m-0 p-0" style="font-size:10px;display:flex;justify-content:space-between">
                        <strong>Started On :'  . \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') . '</strong>
                        <strong>' . \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time . '</strong>
                        <strong>' . \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName . '</strong>
                        <strong>Batch : ' . $batch_data->batchId . '</strong>
                        <strong>Trainer : ' . \DB::table('users')->where('id', $batch_data->trainerId)->first()->name . '</strong>  
                        </p>';


        $startDate = new DateTime($batch_data->startDate);
        $endDate = new DateTime($batch_data->endDate);

        // Create a DateInterval of 1 day
        $interval = new DateInterval('P1D');

        if ($request->batch_id) {
            $postingDate = DB::table('attendances')->select('postingDate', 'edit_allow')->where('batch_id', $request->batch_id)->groupBy('postingDate')->get();
        }
        $data .= '<div class="table-responsive"><table class="table table-sm" style="width:100%;border:1px solid #000;color:#000;">
                    <tbody>
                        <tr class="text-center">
                            <th class="align-middle" rowspan="2" style="border:1px solid #000;;color:#000;width:5px;"><strong>ID</strong></th>
                            <th class="align-middle" rowspan="2" style="border:1px solid #000;;color:#000;width:180px;"><strong>Name</strong></th>
                            <th class="align-middle" rowspan="2" style="border:1px solid #000;;color:#000;width:5px;"><strong>Invoice</strong></th>
                            <th class="align-middle" rowspan="2" style="border:1px solid #000;color:#000;width:5px;"><strong>Executive</strong></th>
                            <th style="border:1px solid #000;;color:#000;" colspan="' . $postingDate->count() . '"><strong>Attendance Details</strong></th>
                            <th style="border:1px solid #000;;color:#000;width:5px;"><strong>Tclass:- ' . $postingDate->count() . '</strong></th>
                        </tr>';
        $data .= '<tr>';
        foreach ($postingDate as $pdate) {

            $data .= '<th style="border:1px solid #000;;color:#000;text-align:center">'
                . \Carbon\Carbon::createFromTimestamp(strtotime($pdate->postingDate))->format('d/m/y');
            if (currentUser() == 'operationmanager' && $pdate->edit_allow == 0) {
                $data .= '<form action="' . route(currentUser() . '.attendance.update', $request->batch_id) . '" method="post"> ' . csrf_field() . ' ' . method_field('PUT') . '';
                $data .= '<input type="hidden" name="postingDate" value="' . $pdate->postingDate . '">';
                $data .= '<input type="hidden" name="type" value="1">';
                $data .= '<button type="submit" class="btn btn-sm btn-warning">Edit Allow</button>';
                $data .= '</form>';
            }
            if (currentUser() == 'trainer' && $pdate->edit_allow == 1) {
                // $data .= '<a class="d-block btn btn-sm btn-info" title="Edit Attendance" href="'.route(currentUser() . '.attendance.edit',$request->batch_id).'">Edit</a>';
                $data .= '<form action="' . route(currentUser() . '.attendance.edit', $request->batch_id) . '"> ' . csrf_field() . '';
                $data .= '<input type="hidden" name="postingDate" value="' . $pdate->postingDate . '">';
                $data .= '<button type="submit" class="btn btn-sm btn-warning">Edit</button>';
                $data .= '</form>';
                $data .= '<form action="' . route(currentUser() . '.attendance.destroy', $request->batch_id) . '" method="post"> ' . csrf_field() . ' ' . method_field('DELETE') . '';
                $data .= '<input type="hidden" name="postingDate" value="' . $pdate->postingDate . '">';
                $data .= '<button type="submit" class="btn btn-sm btn-danger">Delete</button>';
                $data .= '</form>';
            }
            $data .= '<p class="m-0 p-0">' . \Carbon\Carbon::createFromTimestamp(strtotime($pdate->postingDate))->format('D') . '</p>
            </th>';
        }
        $data .= '<th class="text-center">T.Pre</th></tr>';



        if ($request->batch_id) {
            $batch_students = DB::table('student_batches')->where('batch_id', $request->batch_id)->where('status', 2)->where('is_drop', 0)->get();
        }
        foreach ($batch_students as $batch_student) {
            $data .= '<tr>';
            $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();

            $data .= '<td style="border:1px solid #000;color:#000;">' . $s_data->id . '</td>';
            $data .= '<input type="hidden" name="student_id[]" value="' . $s_data->id . '">';
            $data .= '<input type="hidden" name="batch_id[]" value="' . $batch_data->id . '">';
            $data .= '<td style="border:1px solid #000;color:#000;">' . strtoupper($s_data->name) . '</td>';
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

            foreach ($postingDate as $pdate) {
                $attendance_data = Attendance::where('student_id', $batch_student->student_id)->where('batch_id', $batch_data->id)->where('postingDate', '=', \Carbon\Carbon::createFromTimestamp(strtotime($pdate->postingDate))->format('Y-m-d'))->first();
                if ($attendance_data !== null && $attendance_data->isPresent == 1)
                    //if ($attendance_data->isPresent == 1)
                    $data .= '<th style="border:1px solid #000;color:#fff;background-color:green;text-align:center;"><strong>P</strong></th>';
                else
                    $data .= '<th style="border:1px solid #000;color:#fff;background-color:red;text-align:center;"><strong>A</strong></th>';
            }
            $data .= '<th style="border:1px solid #000;color:#000;text-align:center;">' . Attendance::where('student_id', $batch_student->student_id)->where('batch_id', $batch_data->id)->where('isPresent', '=', 1)->count() . '</th>';
            $data .= '</tr>';
        }




        $data .=    '</tbody>
                </table></div>';




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
    public function assign_single_batch_toEnrollStudent(Request $request, $id)
    {
        DB::beginTransaction();
        if ($request->batch_id) {
            $seat_data = DB::select("SELECT COALESCE(COUNT(student_batches.id), 0) as tst ,batches.seat as seat_available FROM batches
                        left join student_batches on student_batches.batch_id=batches.id
                        WHERE batches.id=$request->batch_id and 
                        student_batches.status=2 and student_batches.is_drop = 0
                        GROUP by student_batches.batch_id,batches.seat");
            /*print_r($seat_data);
            die;*/
            if ($seat_data) {
                if ($seat_data[0]->tst >= $seat_data[0]->seat_available) {
                    return redirect()->back()->with($this->responseMessage(false, null, 'No Seat Available!!'));
                }
            } else {
                $seat_data = DB::select("SELECT batches.seat as seat_available FROM batches WHERE batches.id=$request->batch_id");
                if (!$seat_data) {
                    return redirect()->back()->with($this->responseMessage(false, null, 'Please Assing Seat In Bathces'));
                }
            }
            DB::table('student_batches')->where('id', $id)->update(['batch_id' => $request->batch_id]);
            /*Also Have to update Batch Id paymentdetails table */
            $batch_data = DB::table('student_batches')->where('id', $id)->first();
            //print_r($batch_data);die;
            DB::table('paymentdetails')->where('studentId', $batch_data->student_id)->where('course_id', $batch_data->course_id)->update(['batchId' => $request->batch_id]);
            DB::commit();
            return redirect()->route(currentUser() . '.batchwiseEnrollStudent')->with($this->responseMessage(true, null, 'Batch Assigned Successfully'));
        } else {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please Select Batch!'));
        }
        DB::rollback();
        return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
    }

    /*Course Wise Student Enroll Data Delete */
    public function course_wise_student_enroll_data_delete(Request $request)
    {
        DB::table('student_courses')->where('id', $request->id)->update(['status' => 3]);
        return redirect()->back()->with($this->responseMessage(true, 'error', 'Data Deleted'));
    }
}
