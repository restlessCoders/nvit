<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Reference;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Student\NewStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Requests\Student\StudentCourseRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Batchslot;
use App\Models\Batchtime;
use Image;
use Exception;
use DB;
use Illuminate\Support\Carbon;
class StudentController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if(strtolower(currentUser()) === 'superadmin' || strtolower(currentUser()) === 'salesmanager' || strtolower(currentUser()) === 'frontdesk' || strtolower(currentUser()) === 'operationmanager'){
            /*== Waiting Students ==*/
            $allwaitingStudent = Student::where('status','=',2)->orderBy('id', 'DESC')->paginate(25);
            /*== Active Students ==*/
            $allactiveStudent = Student::where('status','=',1)->orderBy('id', 'DESC')->paginate(25);
            /*== Dump Students ==*/
            $alldumpStudent = Student::where('status','=',3)->orderBy('id', 'DESC')->paginate(25);
        }
        else{
            /*== Waiting Students ==*/
            $allwaitingStudent = Student::where('status','=',2)->where('executiveId','=',currentUserId())->orderBy('id', 'DESC')->paginate(25);
            /*== Active Students ==*/
            $allactiveStudent = Student::where('status','=',1)->where('executiveId','=',currentUserId())->orderBy('id', 'DESC')->paginate(25);
            /*== Dump Students ==*/
            $alldumpStudent = Student::where('status','=',3)->where('executiveId','=',currentUserId())->orderBy('id', 'DESC')->paginate(25);
        }
        return view('student.index', compact(['allwaitingStudent','allactiveStudent','alldumpStudent']));
    }
    public function confirmStudents(){
        $enrollStudents = DB::table('student_batches')
        ->selectRaw("students.id,students.name,students.executiveId,student_batches.entryDate")
        ->join('students','student_batches.student_id','=','students.id')
        ->where('student_batches.status',2)
        ->groupBy('students.id','students.name','students.executiveId','student_batches.entryDate')
        ->orderBy('student_batches.id')
        ->paginate();
        /*echo '<pre>';
        print_r($enrollStudents->toArray());die;*/
        return view('student.confirmStudent', compact(['enrollStudents']));
    }
    public function studentenrollById($id){
        $sedata = DB::table('student_batches')
        ->selectRaw("student_batches.id,student_batches.batch_id,student_batches.accountsNote,student_batches.acc_approve")
        ->where('student_id',encryptor('decrypt', $id))
        ->groupBy('student_batches.id','student_batches.batch_id','student_batches.accountsNote','student_batches.acc_approve')
        ->get();
        return view('student.enroll_data_by_student', compact(['sedata']));
    }
    public function paymentStudent($id,$entryDate){
        $stdetl = Student::find(encryptor('decrypt', $id))->first();
        $sdata = DB::table('student_batches')
        ->select('batch_id','entryDate')
        ->where(['student_id'=>encryptor('decrypt', $id),'entryDate' => $entryDate])
        ->get();
       /* echo '<pre>';
        print_r($sdata);die;*/
        return view('payment.student',compact('sdata','stdetl'));
    }
    public function addForm(){
        $allDivision    = Division::orderBy('name', 'ASC')->get();
        $allDistrict    = District::orderBy('name', 'ASC')->get();
        $allUpazila     = Upazila::orderBy('name', 'ASC')->get();
        $allReference   = Reference::orderBy('id', 'ASC')->get();
        $allCourse    = Course::where('status',1)->orderBy('courseName', 'ASC')->get();
        $allBatchTime    = Batchtime::where('status',1)->orderBy('id', 'ASC')->get();
        $allBatchSlot    = Batchslot::where('status',1)->orderBy('id', 'ASC')->get();
        $allExecutive   = User::whereIn('roleId',['5','9'])->orderBy('name', 'ASC')->get();
        return view('student.add_new', compact(['allDivision','allDistrict','allUpazila','allReference','allExecutive','allCourse','allBatchTime','allBatchSlot']));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewStudentRequest $request)
    {
        try {
            if($request->photo){
                //Photo Upload
                $position = strpos($request->photo, ';');
                $sub=substr($request->photo, 0 ,$position);
                $ext=explode('/', $sub)[1];
                $name=time().".".$ext;
                $img=Image::make($request->photo)->resize(240,200);
                $upload_path='backend/student/';
                $image_url=$upload_path.$name;
                $img->save($image_url);

                $student = new Student;
                $student->name             = $request->name;
                $student->contact          = $request->contact;
                $student->photo            = $image_url;
                $student->altContact       = $request->altContact;
                $student->email            = $request->email;
                $student->address          = $request->address;
                $student->division_id      = $request->division_id;
                $student->district_id      = $request->district_id;
                $student->upazila_id       = $request->upazila_id;
                $student->otherInfo        = $request->otherInfo;
                //$student->operationNote    = $request->operationNote;
                $student->executiveNote    = $request->executiveNote;
                $student->executiveReminder= date('Y-m-d',strtotime($request->executiveReminder));
                $student->executiveId      = $request->executiveId?$request->executiveId:currentUserId();
                $student->refId            = $request->refId;
                $student->status           = 2;
            }else{
                $student = new Student;
                $student->name             = $request->name;
                $student->contact          = $request->contact;
                $student->altContact       = $request->altContact;
                $student->email            = $request->email;
                $student->address          = $request->address;
                $student->division_id      = $request->division_id;
                $student->district_id      = $request->district_id;
                $student->upazila_id       = $request->upazila_id;
                $student->otherInfo        = $request->otherInfo;
                //$student->operationNote    = $request->operationNote;
                $student->executiveNote    = $request->executiveNote;
                $student->executiveReminder= date('Y-m-d',strtotime($request->executiveReminder));
                $student->executiveId      = $request->executiveId?$request->executiveId:currentUserId();
                $student->refId            = $request->refId;
                $student->status           = 2;
            }

            if(!!$student->save()) return redirect(route(currentUser().'.allStudent'))->with($this->responseMessage(true, null, 'Student created'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
    public function studentCourseAssign($id)
    {
        $sdata = Student::find(encryptor('decrypt', $id));
        $allCourse      = Course::where('status',1)->orderBy('courseName', 'ASC')->get();
        $allBatch       = Batch::where('status',1)->orderBy('id', 'DESC')->get();
        $allBatchTime   = Batchtime::where('status',1)->orderBy('id', 'ASC')->get();
        $allBatchSlot   = Batchslot::where('status',1)->orderBy('id', 'ASC')->get();
        return view('student.courseAssign', compact(['allCourse','sdata','allBatchTime','allBatchSlot','allBatch']));
    }
    public function addstudentCourseAssign(StudentCourseRequest $request, $id){
        $s_id =  encryptor('decrypt', $id);
        $batch_id = $request->post('batch_id');
        foreach($request->batch_id as $key => $cdata){
            $s_batch_data = DB::table('student_batches')->where(['student_id'=>$s_id,'batch_id'=>$batch_id[$key]])->first();
           
            if(!empty($s_batch_data)){
                /*If Student Course Active By Account Change Denied */
                if($s_batch_data->acc_approve){
                    return redirect()->back()->with($this->responseMessage(false, null, 'Status Can not be Changed!!'));
                }
                /*If Same Course and Status Data Found*/
                else if($s_batch_data->status == $request->status){
                    return redirect()->back()->with($this->responseMessage(false, null, 'Same Status can not be edited!!'));
                }else{
                    /*No Match Proceed To Update */
                    //echo 'proceed to update';
                    $data = array(
                        'status' => $request->status,
                        'entryDate'=> date('Y-m-d'),
                        'updated_at' => Carbon::now()
                    );
                    DB::table('student_batches')->where('id',$s_batch_data->id)->update($data);
                    return redirect()->back()->with($this->responseMessage(true, null, 'Update Successful'));;
                }
            }else{
                $data = array(
                    'batch_id' => $batch_id[$key],
                    'student_id' =>  $s_id,
                    'entryDate'=> date('Y-m-d'),
                    'status' => $request->status,
                    'created_at' => Carbon::now()
                );
                DB::table('student_batches')->insert($data);
                return redirect()->back();
            }
        }
    }
    public function editForm($id)
    {
        $sdata = Student::find(encryptor('decrypt', $id));
        $allDivision    = Division::orderBy('name', 'ASC')->get();
        $allDistrict    = District::orderBy('name', 'ASC')->get();
        $allUpazila     = Upazila::orderBy('name', 'ASC')->get();
        $allReference   = Reference::orderBy('id', 'ASC')->get();
        $allExecutive   = User::whereIn('roleId',['5','9'])->orderBy('name', 'ASC')->get();

        $allCourse    = Course::where('status',1)->orderBy('courseName', 'ASC')->get();
        $allBatch       = Batch::where('status',1)->orderBy('id', 'DESC')->get();
        $allBatchTime    = Batchtime::where('status',1)->orderBy('id', 'ASC')->get();
        $allBatchSlot    = Batchslot::where('status',1)->orderBy('id', 'ASC')->get();

        $allassignBatches = DB::table('student_batches')->where('student_id',$sdata->id)->orderBy('batch_id')->get();

        return view('student.edit', compact(['sdata','allassignBatches','allDivision','allDistrict','allUpazila','allReference','allExecutive','allCourse','sdata','allBatchTime','allBatchSlot','allBatch']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request,$id)
    {
        try {
        $student = Student::find(encryptor('decrypt', $id));
        if($request->photo){
            //Photo Upload
            $position = strpos($request->photo, ';');
            $sub=substr($request->photo, 0 ,$position);
            $ext=explode('/', $sub)[1];
            $name=time().".".$ext;
            $img=Image::make($request->photo)->resize(240,200);
            $upload_path='backend/student/';
            $image_url=$upload_path.$name;
            $img->save($image_url);

            $student->name             = $request->name;
            if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager'){
            $student->contact          = $request->contact;
            }
            $student->photo            = $image_url;
            $student->altContact       = $request->altContact;
            $student->email            = $request->email;
            $student->address          = $request->address;
            $student->division_id      = $request->division_id;
            $student->district_id      = $request->district_id;
            $student->upazila_id       = $request->upazila_id;
            $student->otherInfo        = $request->otherInfo;
            $student->executiveNote    = $request->executiveNote;
            $student->executiveReminder= date('Y-m-d',strtotime($request->executiveReminder));
            $student->executiveId      = $request->executiveId?$request->executiveId:currentUserId();
            $student->refId            = $request->refId;
        }else{
            $student->name             = $request->name;
            if (strtolower(currentUser()) === 'superadmin' || strtolower(currentUser()) === 'salesmanager' ||  strtolower(currentUser()) === 'operationmanager') {
            $student->contact          = $request->contact;
            $student->executiveId      = $request->executiveId?$request->executiveId:currentUserId();
            $student->refId            = $request->refId;
            }
            $student->course_id        = (!empty($request->course_id))?implode(",",$request->course_id):null;
            $student->altContact       = $request->altContact;
            $student->email            = $request->email;
            $student->address          = $request->address;
            $student->division_id      = $request->division_id;
            $student->district_id      = $request->district_id;
            $student->upazila_id       = $request->upazila_id;
            $student->otherInfo        = $request->otherInfo;
            $student->executiveNote    = $request->executiveNote;
            $student->executiveReminder= date('Y-m-d',strtotime($request->executiveReminder));
        }
        $student->save();
        if(!!$student->save()) return redirect(route(currentUser().'.allStudent'))->with($this->responseMessage(true, null, 'Student updated'));
        } catch (Exception $e) {
			dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function dump($id)
    {
        $dumpStudent = Student::findOrFail(encryptor('decrypt', $id));
        $dumpStudent->executiveId=0;
        $dumpStudent->status=3;
        $dumpStudent->save();
        return back();
    }
    public function active($id)
    {
        $dumpStudent = Student::findOrFail(encryptor('decrypt', $id));
        $dumpStudent->status=1;
        $dumpStudent->save();
        return back();
    }
}
