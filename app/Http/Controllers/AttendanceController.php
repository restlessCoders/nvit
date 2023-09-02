<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Carbon;
use App\Http\Traits\ResponseTrait;
use Exception;
class AttendanceController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if(currentUser() == 'trainer')
       $batches = Batch::where('trainerId', currentUserId())->get();
       else
       $batches = Batch::all();
       return view('attendance.report.batch_wise', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(currentUser() == 'trainer')
        $batches = Batch::where('trainerId', currentUserId())->get();
        else
        $batches = Batch::all();
        return view('attendance.report.batch_wise_attendance_store', compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attendance_exists = Attendance::where('batch_id',$request->post('batch_id'))->where('postingDate',\Carbon\Carbon::createFromTimestamp(strtotime($request->postingDate))->format('Y-m-d'))->first();
        if($attendance_exists){
            return redirect()->back()->with($this->responseMessage(false, null, 'Attendance Saved For This Date'));
        }
        $student_id       = $request->post('student_id');
        $batch_id         = $request->post('batch_id');
        $isPresent             = $request->post('isPresent');
        foreach ($student_id as $key=>$st) {
            $attendance['student_id'] = $st;
            $attendance['postingDate'] = Carbon::parse($request->postingDate)->format('Y-m-d');
            $attendance['batch_id']   = $batch_id[$key];
            $attendance['trainer_id']   = currentUserId();
            $attendance['isPresent']   = isset($isPresent[$st]) ? $isPresent[$st] : 0;
            $attendance['created_by'] = currentUserId();
            $attendance['created_at'] = Carbon::now();
            DB::table('attendances')->insert($attendance);
        }
        return redirect(route(currentUser().'.attendance.index'))->with($this->responseMessage(true, null, 'Attendance Saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $all_students = DB::table('student_batches')->where('batch_id',$id)->where('status',2)->where('is_drop',0)->get();
        return view('attendance.edit',compact('id','all_students'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
