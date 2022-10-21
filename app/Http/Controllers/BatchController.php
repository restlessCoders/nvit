<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Batchtime;
use App\Models\Batchslot;
use App\Models\Classroom;

use Illuminate\Http\Request;

use App\Http\Requests\Batch\NewBatchRequest;
use App\Http\Requests\Batch\UpdateBatchRequest;
use App\Http\Traits\ResponseTrait;
use Exception;
use DB;
class BatchController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBatch = Batch::paginate();
        return view('batch.index',compact('allBatch'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allCourses = Course::all();
        $allBatchTime    = Batchtime::where('status',1)->orderBy('id', 'ASC')->get();
        $allBatchSlot    = Batchslot::where('status',1)->orderBy('id', 'ASC')->get();
        $allClassroom    = Classroom::where('status',1)->orderBy('id', 'ASC')->get();
        $allTrainer = User::whereIn('roleId', ['7','11'])->get();
        return view('batch.add_new',compact(['allCourses','allBatchTime','allBatchSlot','allTrainer','allClassroom']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewBatchRequest $request)
    {
        try {
            $course = DB::table('batches')->select(DB::raw('count(courseId) as tc')) ->where('courseId',$request->courseId)->groupBy('courseid')
            ->first();
            if ($course == null) {
                $firstReg = 0;
                $courseMax = $firstReg+1;
            }else{
                $courseMax = $course->tc+1;
            }
            //echo $courseMax;die;
            $course = Course::where('id',$request->courseId)->first();
            $batch = new Batch;
            $batch->batchId = $course->courseName.'-'.$courseMax;
            $batch->courseId = $request->courseId;
            $batch->startDate = date('Y-m-d',strtotime($request->startDate));
            $batch->endDate = date('Y-m-d',strtotime($request->endDate));
            $batch->bslot = $request->bslot;
            $batch->btime = $request->btime;
            $batch->trainerId = $request->trainerId;
            $batch->examDate = date('Y-m-d',strtotime($request->examDate));
            $batch->examTime = date('H:i:s',strtotime($request->examTime));
            $batch->examRoom = $request->examRoom;
            $batch->price = $request->price;
            $batch->discount = $request->discount;
            $batch->status =1;
            $batch->userId = encryptor('decrypt', $request->userId);
            if(!!$batch->save()) return redirect(route(currentUser().'.batch.index'))->with($this->responseMessage(true, null, 'Batch created'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allCourses = Course::all();
        $allBatchTime    = Batchtime::where('status',1)->orderBy('id', 'ASC')->get();
        $allBatchSlot    = Batchslot::where('status',1)->orderBy('id', 'ASC')->get();
        $allClassroom    = Classroom::where('status',1)->orderBy('id', 'ASC')->get();
        $allTrainer = User::whereIn('roleId', ['7','11'])->get();
        $bdata = Batch::find(encryptor('decrypt', $id));
        return view('batch.edit',compact(['allCourses','allBatchTime','allBatchSlot','allTrainer','allClassroom','bdata']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
        $batch = Batch::find(encryptor('decrypt', $id));
            $batch->startDate = date('Y-m-d',strtotime($request->startDate));
            $batch->endDate = date('Y-m-d',strtotime($request->endDate));
            $batch->bslot = $request->bslot;
            $batch->btime = $request->btime;
            $batch->trainerId = $request->trainerId;
            $batch->examDate = date('Y-m-d',strtotime($request->examDate));
            $batch->examTime = date('H:i:s',strtotime($request->examTime));
            $batch->examRoom = $request->examRoom;
            $batch->price = $request->price;
            $batch->discount = $request->discount;
            $batch->status =$request->discount;
            $batch->userId = encryptor('decrypt', $request->userId);
        $batch->save();
        if(!!$batch->save()) return redirect(route(currentUser().'.batch.index'))->with($this->responseMessage(true, null, 'Batch updated'));
        } catch (Exception $e) {
			dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function destroy(Division $division)
    {
        //
    }
    public function enableDisable($id){
        $division = Division::findOrFail($id);
        $division->enabled = !$division->enabled;
        $division->save();
        return redirect(route('divisions.index'))->with(
            ['message' =>'Division Updated']
        );
    }
}
