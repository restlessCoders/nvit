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
use Carbon\Carbon;

class BatchController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function batch_display_data(Request $request){
        $batch = Batch::find($request->batchId);
        
    }*/
    /*=================== */
    public function batchById(Request $request){
        $data_exists = DB::select("SELECT * FROM `student_batches` WHERE `batch_id`=$request->batchId and student_id=$request->student_id");
        //check student id with batch id exists in batch table
        if(!$data_exists){
        $batch = Batch::find($request->batchId);
        $data='<tr class="productlist" id="row_'.$request->rowcount.'" data-item-id="'.$batch->id.'">';
        $data.='<input name="student_id[]" type="hidden" value="'.$request->student_id.'">';
        $data.='<td>'.$batch->batchId.'<input name="batch_id[]" type="hidden" value="'.$batch->id.'"></td>';
        $data.='							
        <td>
            <select class="js-example-basic-single form-control" id="pstatus" name="type[]" required>
                <option value="">Select</option>
                <option value="1" selected>Full</option>
                <option value="2">Intallment(Partial)</option>
            </select>
        </td>';
        $data.='							
        <td>
            <select class="js-example-basic-single form-control" id="status" name="status[]" required>
                <option value="">Select</option>
                <option value="2">Enroll</option>
                <option value="3">Knocking</option>
                <option value="4">Evaluation</option>
            </select>
        </td>';
        $data .='<td id="td_'.$request->rowcount.'" style="text-align: center;">
                <a class=" fa fa-fw fa-minus-square text-red" style="cursor: pointer;font-size: 34px;" onclick="removerow('.$request->rowcount.')" title="Delete ?" id="td_data_'.$request->rowcount.'"></a>
            </td>';
        $data.='</tr>';
        }else{
            $data['error'] = "Already Exisits";
        }

        return response()->json(array('data' =>$data));
    }
    public function batchSearch(Request $request){
        $search = $request->get('search');
        if($search != ''){
            $allBatch = Batch::where('batchId','like', '%' .$search. '%')->orderBy('id', 'DESC')->paginate(25);
            $allBatch->appends(array('search'=> $search,));
            if(count($allBatch )>0){
            return view('batch.index',compact('allBatch'));
            }
            return back()->with('error','No results Found');
        } 
    }
    public function index(Request $request)
    {
        if(currentUser() == 'trainer')
        $allBatch = Batch::where('trainerId', currentUserId())->paginate(25);
        else
        $allBatch = Batch::where('status',1)->orderBy('id', 'DESC')->paginate(25);
        /*$allBatch = DB::table('batches')
        ->join('student_batches','batches.id','=','student_batches.batch_id','left')
        ->selectRaw('batches.id,batches.batchId,batches.courseId,batches.startDate,batches.endDate,batches.bslot,batches.btime,batches.trainerId,batches.examDate,batches.examTime,batches.examRoom,batches.seat,batches.status,batches.created_by,batches.created_at,batches.updated_at,count(student_batches.student_id) as tst')
        ->groupBy('student_batches.batch_id')
        ->paginate();*/
        return view('batch.index',compact('allBatch'));
    }
    public function all(Request $request)
    {
        /*$allBatch = DB::table('batches')
        ->join('student_batches','batches.id','=','student_batches.batch_id','left')
        ->selectRaw('batches.id,batches.batchId,batches.courseId,batches.startDate,batches.endDate,batches.bslot,batches.btime,batches.trainerId,batches.examDate,batches.examTime,batches.examRoom,batches.seat,batches.status,	batches.created_by,batches.created_at,batches.updated_at,count(student_batches.student_id) as tst')
        ->groupBy(['student_batches.batch_id','batches.id','batches.batchId','batches.courseId','batches.startDate','batches.endDate','batches.bslot','batches.btime',	'batches.trainerId','batches.examDate','batches.examTime','batches.examRoom','batches.seat','batches.status','batches.created_by','batches.created_at','batches.updated_at'])
        ->where('batches.batchId', 'like', '%'.$request->name.'%')
        ->where('batches.status',1)
        ->where('student_batches.status',2)
        ->get();*/
        $allBatch = DB::table('batches')
        ->leftJoin('student_batches', function ($join) {
            $join->on('batches.id', '=', 'student_batches.batch_id')
                ->where('student_batches.status', '=', 2)
                ->where('student_batches.is_drop', '=', 0);
        })
        ->selectRaw('
            batches.id, batches.batchId, batches.courseId, batches.startDate, batches.endDate,
            batches.bslot, batches.btime, batches.trainerId, batches.examDate, batches.examTime,
            batches.examRoom, batches.seat, batches.status, batches.created_by, batches.created_at,
            batches.updated_at, COUNT(student_batches.student_id) as tst
        ')
        ->groupBy(
            'batches.id', 'batches.batchId', 'batches.courseId',
            'batches.startDate', 'batches.endDate', 'batches.bslot', 'batches.btime',
            'batches.trainerId', 'batches.examDate', 'batches.examTime', 'batches.examRoom',
            'batches.seat', 'batches.status', 'batches.created_by', 'batches.created_at',
            'batches.updated_at'
        )
        ->where('batches.batchId', 'like', '%' . $request->name . '%')
        ->where('batches.status', 1)
        ->get();
    

        return response()->json($allBatch);
        //->paginate();

        
        //echo '<pre>';
        //print_r($allBatch);
        //die;
        //return view('batch.index',compact('allBatch'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allCourses = Course::where('status',1)->get();
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
            /*if($request->type==2){
                $batch->batchId = $course->courseName.'-'.$courseMax.' (Carsh)';
            }else{
                $batch->batchId = $course->courseName.'-'.$courseMax;
            }*/
            $batch->courseId = $request->courseId;
            $batch->batchId = str_replace(' ', '-', $request->batchId);
            $batch->startDate = date('Y-m-d',strtotime($request->startDate));
            $batch->endDate = date('Y-m-d',strtotime($request->endDate));
            $batch->bslot = $request->bslot;
            $batch->btime = $request->btime;
            $batch->trainerId = $request->trainerId;
            $batch->examDate = date('Y-m-d',strtotime($request->examDate));
            $batch->examTime = date('H:i:s',strtotime($request->examTime));
            $batch->examRoom = $request->examRoom;
            $batch->seat = $request->seat;
            $batch->type = $request->type;
            /*$batch->price = $request->price;
            $batch->discount = $request->discount;*/
            $batch->status =1;
            $batch->created_by = currentUserId();
            $batch->courseDuration = $request->courseDuration;
            $batch->classHour = $request->classHour;
            $batch->totalClass = $request->courseDuration/$request->classHour;
            $batch->remarks = $request->remarks;
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
    public function show()
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
        $allCourses = Course::where('status',1)->get();
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
    public function update(UpdateBatchRequest $request, $id)
    {
        try {
        $batch = Batch::find(encryptor('decrypt', $id));
            $batch->batchId = str_replace(' ', '-', $request->batchId);
            $batch->courseId = $request->courseId;
            $batch->startDate = Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $batch->endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $batch->bslot = $request->bslot;
            $batch->btime = $request->btime;
            $batch->trainerId = $request->trainerId;
            $batch->examDate = Carbon::createFromFormat('d/m/Y', $request->examDate)->format('Y-m-d');
            $batch->examTime = date('H:i:s',strtotime($request->examTime));
            $batch->examRoom = $request->examRoom;
            $batch->status =$request->status;
            $batch->created_by = encryptor('decrypt', $request->userId);
            $batch->seat = $request->seat;//Before update total number of enroll student
            $batch->courseDuration = $request->courseDuration;
            $batch->classHour = $request->classHour;
            $batch->totalClass = $request->courseDuration/$request->classHour;
            $batch->type = $request->type;
            $batch->remarks = $request->remarks;
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
    public function destroy()
    {
        //
    }

}
