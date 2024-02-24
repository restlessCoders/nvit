<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Note;
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
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $users = User::whereIn('roleId', [1, 3, 5, 9])->get();
        if (strtolower(currentUser()) === 'superadmin' || strtolower(currentUser()) === 'salesmanager' || strtolower(currentUser()) === 'frontdesk' || strtolower(currentUser()) === 'operationmanager') {
            /*== Waiting Students ==*/
            $allwaitingStudent = Student::where('status', '=', 2)->orderBy('id', 'DESC')->paginate(25);
            /*== Active Students ==*/
            if ($request->has('executiveId') || $request->has('sdata')) {
                $allactiveStudent = Student::with('notes')->where('status', '=', 1)->orderBy('id', 'DESC');
            } else {
                $allactiveStudent = Student::with('notes');
                if (strtolower(currentUser()) != 'frontdesk') {
                    $allactiveStudent = $allactiveStudent->where('executiveId', '=', currentUserId());
                }
                $allactiveStudent = $allactiveStudent->where('status', '=', 1)->orderBy('id', 'DESC');
            }
            if ($request->executiveId && $request->executiveId != 'all') {
                $allactiveStudent = $allactiveStudent->where('executiveId', $request->executiveId);
            }
            if ($request->sdata) {
                $allactiveStudent->where(function ($query) use ($request) {
                    $query->where('students.id', '=', $request->sdata)
                        ->orWhere('students.contact', '=', $request->sdata)
                        ->orWhere('students.altContact', '=', $request->sdata);
                });
                $allactiveStudent = $allactiveStudent->orWhere('students.name', 'like', '%' . $request->sdata . '%');
            }
            $perPage = 20;
            $allactiveStudent = $allactiveStudent->paginate($perPage)->appends([
                'executiveId' => $request->executiveId,
            ]);
            /*== Dump Students ==*/
            $alldumpStudent = Student::where('status', '=', 3)->orderBy('id', 'DESC')->paginate(25);
        } else {
            /*== Waiting Students ==*/
            $allwaitingStudent = Student::where('status', '=', 2)->where('executiveId', '=', currentUserId())->orderBy('id', 'DESC')->paginate(25);
            /*== Active Students ==*/
            $allactiveStudent = Student::with('notes')->where('status', '=', 1)->where('executiveId', '=', currentUserId())->orderBy('id', 'DESC');
            if ($request->sdata) {
                $allactiveStudent->where(function ($query) use ($request) {
                    $query->orWhere('students.name', 'like', '%' . $request->sdata . '%')
                        ->orWhere('students.id', '=', $request->sdata)
                        ->orWhere('students.contact', '=', $request->sdata)
                        ->orWhere('students.altContact', '=', $request->sdata);
                });
            }
            $perPage = 20;
            $allactiveStudent = $allactiveStudent->paginate($perPage)->appends([
                'executiveId' =>  currentUserId(),
            ]);
            /*== Dump Students ==*/
            $alldumpStudent = Student::where('status', '=', 3)->where('executiveId', '=', currentUserId())->orderBy('id', 'DESC')->paginate(25);
        }
        //print_r($requestData);die;
        return view('student.index', compact(['allwaitingStudent', 'allactiveStudent', 'alldumpStudent', 'users']));
    }

    public function confirmStudents()
    {
        $allStudent = DB::table('students')
            ->join('student_batches', function ($join) {
                $join->on('students.id', '=', 'student_batches.student_id')
                    ->where('student_batches.status', '=', '2')
                    ->where('student_batches.pstatus', '=', '0');
            })
            ->select('students.*', 'student_batches.batch_id', 'student_batches.course_price', 'student_batches.entryDate')
            ->get();
        return response()->json($allStudent);
        /*$enrollStudents = DB::table('student_batches')
            ->selectRaw("students.id,students.name,students.executiveId,student_batches.entryDate")
            ->join('students', 'student_batches.student_id', '=', 'students.id')
            ->where('student_batches.status', 2)
            ->groupBy('students.id', 'students.name', 'students.executiveId', 'student_batches.entryDate')
            ->orderBy('student_batches.id')
            ->paginate();*/
        /*echo '<pre>';
        print_r($allStudent->toArray());die;*/
        //return view('student.confirmStudent', compact(['enrollStudents']));
        return view('student.payment', compact(['enrollStudents']));
    }
    public function studentenrollById($id)
    {
        $sedata = DB::table('student_batches')
            ->selectRaw("student_batches.id,student_batches.batch_id,student_batches.accountsNote,student_batches.acc_approve")
            ->where('student_id', encryptor('decrypt', $id))
            ->groupBy('student_batches.id', 'student_batches.batch_id', 'student_batches.accountsNote', 'student_batches.acc_approve')
            ->get();
        return view('student.enroll_data_by_student', compact(['sedata']));
    }
    public function paymentStudent($id, $entryDate)
    {
        $stdetl = Student::find(encryptor('decrypt', $id));
        $sdata = DB::table('student_batches')
            ->select('batch_id', 'entryDate')
            ->where(['student_id' => encryptor('decrypt', $id), 'entryDate' => $entryDate, 'status' => 2])
            ->get();
        /* echo '<pre>';
        print_r($sdata);die;*/
        return view('payment.student', compact('sdata', 'stdetl'));
    }
    public function addForm()
    {
        $allDivision    = Division::orderBy('name', 'ASC')->get();
        $allDistrict    = District::orderBy('name', 'ASC')->get();
        $allUpazila     = Upazila::orderBy('name', 'ASC')->get();
        $allReference   = Reference::orderBy('id', 'ASC')->get();
        $allCourse    = Course::where('status', 1)->orderBy('courseName', 'ASC')->get();
        $allBatchTime    = Batchtime::where('status', 1)->orderBy('id', 'ASC')->get();
        $allBatchSlot    = Batchslot::where('status', 1)->orderBy('id', 'ASC')->get();
        $allExecutive   = User::whereIn('roleId', ['1', '3', '5', '9'])->orderBy('name', 'ASC')->get();
        return view('student.add_new', compact(['allDivision', 'allDistrict', 'allUpazila', 'allReference', 'allExecutive', 'allCourse', 'allBatchTime', 'allBatchSlot']));
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
            if ($request->photo) {
                //Photo Upload
                $position = strpos($request->photo, ';');
                $sub = substr($request->photo, 0, $position);
                $ext = explode('/', $sub)[1];
                $name = time() . "." . $ext;
                $img = Image::make($request->photo)->resize(240, 200);
                $upload_path = 'backend/student/';
                $image_url = $upload_path . $name;
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
                if (strtolower(currentUser()) != 'frontdesk') {
                    $student->executiveReminder = $request->executiveReminder ? date('Y-m-d', strtotime($request->executiveReminder)) : null;
                }
                $student->executiveId      = $request->executiveId ? $request->executiveId : currentUserId();
                $student->refId            = $request->refId;
                $student->status           = 1;
            } else {
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
                if (strtolower(currentUser()) != 'frontdesk') {
                    $student->executiveReminder = date('Y-m-d', strtotime($request->executiveReminder));
                }
                $student->executiveId      = $request->executiveId ? $request->executiveId : currentUserId();
                $student->refId            = $request->refId;
                $student->status           = 1;
            }

            if (!!$student->save()) return redirect(route(currentUser() . '.allStudent'))->with($this->responseMessage(true, null, 'Student created'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
    public function studentCourseAssign($id)
    {
        $sdata = Student::find(encryptor('decrypt', $id));
        $allCourse      = Course::where('status', 1)->orderBy('courseName', 'ASC')->get();
        $allBatch       = Batch::where('status', 1)->orderBy('id', 'DESC')->get();
        $allBatchTime   = Batchtime::where('status', 1)->orderBy('id', 'ASC')->get();
        $allBatchSlot   = Batchslot::where('status', 1)->orderBy('id', 'ASC')->get();
        return view('student.courseAssign', compact(['allCourse', 'sdata', 'allBatchTime', 'allBatchSlot', 'allBatch']));
    }
    public function addstudentCourseAssign(StudentCourseRequest $request, $id)
    {
        $s_batch_data = DB::table('student_batches')->where(['student_id' => $request->s_id, 'batch_id' => $request->batch_id])->first();
        if (!empty($s_batch_data)) {
            /*If Student Course Active By Account Change Denied */
            /*if($s_batch_data->acc_approve) {
                return redirect()->back()->with($this->responseMessage(false, null, 'Status Can not be Changed!!'));
            }*/
            /*If Same Course and Status Data Found*/
            /*else if ($s_batch_data->status == $request->status) {
                return redirect()->back()->with($this->responseMessage(true, null, 'Same Status can not be edited!!'));
            }
            else {*/
            if ($s_batch_data->status) {
                /*No Match Proceed To Update */
                //echo 'proceed to update';
                if ($request->status == 2) {
                    $data = array(
                        'status' => $request->status,
                        'updated_at' => Carbon::now(),
                        'updated_by' => currentUserId(),
                    );
                    $seat_data = DB::select("SELECT COUNT(student_batches.id) as tst ,batches.seat as seat_available FROM batches
                        join student_batches on student_batches.batch_id=batches.id
                        WHERE batches.id=$s_batch_data->batch_id and 
                        student_batches.status=2
                        GROUP by student_batches.batch_id,batches.seat");
                    //print_r($seat_data);die;

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
                } elseif ($request->status == 3 || $request->status == 4) {
                    $data = array(
                        'status' => $request->status,
                        'entryDate' => date('Y-m-d'),
                        'updated_at' => Carbon::now(),
                        'updated_by' => currentUserId(),
                    );
                } else {
                    $data = array(
                        'entryDate' => date('Y-m-d'),
                        'updated_at' => Carbon::now(),
                        'updated_by' => currentUserId(),
                    );
                }
                DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);
            }
            if ($s_batch_data->type) {
                /* If Executive change Full to Installment or Installment to Full Payment Course Price Will change until invoice has posted in paymentdetails table */
                /* use to check date | now for both date and time */
                $packages = DB::select("SELECT * from packages where /*curdate()*/ '$s_batch_data->entryDate' BETWEEN startDate and endDate and batchId = $s_batch_data->batch_id and status=1");
                /*echo $request->type.'<br>';
                echo '<pre>';
                print_r($packages);die;*/
                /*==Course Price  is Full or Partial==*/
                if ($request->type == 1) {
                    $course = DB::select("SELECT courses.rPrice as price FROM batches join courses on batches.courseId = courses.id WHERE batches.id =$s_batch_data->batch_id");
                } else {
                    $course = DB::select("SELECT courses.iPrice as price FROM batches join courses on batches.courseId = courses.id WHERE batches.id =$s_batch_data->batch_id");
                }

                if ($request->type == 1) {
                    if ($packages) {
                        if ($packages[0]->price > 0) {
                            $course_price = $packages[0]->price;
                            $package_id = $packages[0]->id;
                        } elseif ($packages[0]->dis > 0) {
                            $course_price = $course[0]->price - ($course[0]->price * $packages[0]->dis / 100);
                            $package_id = $packages[0]->id;
                        }
                    } else {
                        $course_price = $course[0]->price;
                        $package_id = null;
                    }
                } else {
                    if ($packages) {
                        if ($packages[0]->iPrice > 0) {
                            $course_price = $packages[0]->iPrice;
                            $package_id = $packages[0]->id;
                        } elseif ($packages[0]->dis > 0) {
                            $course_price = $course[0]->price - ($course[0]->price * $packages[0]->dis / 100);
                            $package_id = $packages[0]->id;
                        }
                    } else {
                        $course_price = $course[0]->price;
                        $package_id = null;
                    }
                }
                $data = array(
                    'course_price' => $course_price,
                    'type' => $request->type,
                    'updated_at' => Carbon::now(),
                    'updated_by' => currentUserId(),
                );
                /*echo '<pre>';
                print_r($data);
                echo 'ok';die;*/
                DB::table('student_batches')->where('id', $s_batch_data->id)->update($data);

                /*Course Price change in not invoice paymnet and paymentdetails table update */
                $pay_detl = DB::table('paymentdetails')->where('studentId', '=', $request->s_id)->where('batchId', '=', $s_batch_data->batch_id)
                    ->first();
                if ($pay_detl) {
                    DB::table('paymentdetails')->where('id', '=', $pay_detl->id)->update(['cPayable' => $course_price]);
                    DB::table('payments')->where('id', $pay_detl->paymentId)->update(['tPayable' => $course_price]);
                }
                $payable = DB::table('paymentdetails')->where('studentId', '=', $request->s_id)->where('batchId', '=', $s_batch_data->batch_id)->get();
                foreach ($payable as $p) {
                    $sum = DB::table('paymentdetails')
                        ->where('id', '<', $p->id)
                        ->where('studentId', '=', $request->studentId)->where('batchId', '=', $s_batch_data->batch_id)
                        ->sum('cpaidAmount');

                    $sum_cpayable = DB::table('paymentdetails')
                        ->where('paymentId', $p->paymentId)
                        ->sum('cPayable');
                    $sum_cpaidAmount = DB::table('paymentdetails')
                        ->where('paymentId', $p->paymentId)
                        ->sum('cpaidAmount');

                    DB::table('paymentdetails')->where('id', $p->id)
                        ->update(['cPayable' =>  $course_price - $sum]);
                    DB::table('payments')->where('id', $p->paymentId)
                        ->update(['tPayable' => $sum_cpayable, 'paidAmount' => $sum_cpaidAmount]);
                }
            }
            //return redirect()->back()->with($this->responseMessage(true, null, 'Update Successful'));
            return redirect()->back()->with($this->responseMessage(true, null, 'Update Successful'))->withInput(['tab' => 'batch_student']);

            // }
        } else {
            $student_id = $request->post('student_id');
            $batch_id = $request->post('batch_id');
            $status = $request->post('status');
            $type = $request->post('type');
            /*Stystem Id */
            $systemId = substr(uniqid(Str::random(6), true), 0, 6);
            foreach ($request->batch_id as $key => $cdata) {
                /* use to check date | now for both date and time */
                $packages = DB::select("SELECT * from packages where curdate() BETWEEN startDate and endDate and batchId = $batch_id[$key] and status=1");
                /*==Course Price  is Full or Partial==*/
                if ($type[$key] == 1) {
                    $course = DB::select("SELECT courses.rPrice as price FROM batches join courses on batches.courseId = courses.id WHERE batches.id =$batch_id[$key]");
                } else {
                    $course = DB::select("SELECT courses.iPrice as price FROM batches join courses on batches.courseId = courses.id WHERE batches.id =$batch_id[$key]");
                }
                /*echo $type[$key];
                print_r($packages);die;*/
                if ($type[$key] == 1) {
                    if ($packages) {
                        if ($packages[0]->price > 0) {
                            $course_price = $packages[0]->price;
                            $package_id = $packages[0]->id;
                        } elseif ($packages[0]->dis > 0) {
                            $course_price = $course[0]->price - ($course[0]->price * $packages[0]->dis / 100);
                            $package_id = $packages[0]->id;
                        }
                    } else {
                        $course_price = $course[0]->price;
                        $package_id = null;
                    }
                } else {
                    if ($packages) {
                        if ($packages[0]->iPrice > 0) {
                            $course_price = $packages[0]->iPrice;
                            $package_id = $packages[0]->id;
                        } elseif ($packages[0]->dis > 0) {
                            $course_price = $course[0]->price - ($course[0]->price * $packages[0]->dis / 100);
                            $package_id = $packages[0]->id;
                        }
                    } else {
                        $course_price = $course[0]->price;
                        $package_id = null;
                    }
                }


                $data = array(
                    'batch_id' => $batch_id[$key],
                    'student_id' =>  $student_id[$key],
                    'package_id' =>  $package_id,
                    'entryDate' => date('Y-m-d'),
                    'status' => $status[$key],
                    'systemId' => $systemId,
                    'course_price' => $course_price,
                    'type' => $type[$key],
                    'created_at' => Carbon::now(),
                    'created_by' => currentUserId(),
                );
                DB::table('student_batches')->insert($data);
            }
            return redirect()->back()->with($this->responseMessage(true, null, 'Course Assigned Successful'));
        }
    }
    public function deleteEnroll(Request $request, $id)
    {
        if (DB::table('student_batches')->where('id', encryptor('decrypt', $id))->delete());
        return redirect()->back()->with($this->responseMessage(true, null, 'Enrollment Delete Successful'));
    }
    public function editForm($id)
    {
        $sdata = Student::find(encryptor('decrypt', $id));
        $allDivision    = Division::orderBy('name', 'ASC')->get();
        $allDistrict    = District::orderBy('name', 'ASC')->get();
        $allUpazila     = Upazila::orderBy('name', 'ASC')->get();
        $allReference   = Reference::orderBy('id', 'ASC')->get();
        $allExecutive   = User::whereIn('roleId', ['1', '3', '5', '9'])->orderBy('name', 'ASC')->get();


        $allCourse    = Course::where('status', 1)->orderBy('courseName', 'ASC')->get();
        /*echo '<pre>';
        print_r($allCourse->toArray());die;*/
        $allBatch       = Batch::orderBy('id', 'DESC')->get();
        $allBatchTime    = Batchtime::where('status', 1)->orderBy('id', 'ASC')->get();
        $allBatchSlot    = Batchslot::where('status', 1)->orderBy('id', 'ASC')->get();

        $notes = Note::where('student_id', encryptor('decrypt', $id))->orderBy('id', 'desc')->paginate(15);;

        $allassignBatches = DB::table('student_batches')->where('student_id', $sdata->id)->where('batch_id', '!=', 0)->orderBy('batch_id')->get();

        /*Course Preference */
        $allPreference = DB::table('course_preferences')->where('student_id', $sdata->id)->get();

        /*Course Wise Enroll */
        $allcourseEnroll = DB::table('student_courses')->where('student_id', $sdata->id)->get();

        return view('student.edit', compact(['notes', 'allcourseEnroll', 'allPreference', 'sdata', 'allassignBatches', 'allDivision', 'allDistrict', 'allUpazila', 'allReference', 'allExecutive', 'allCourse', 'sdata', 'allBatchTime', 'allBatchSlot', 'allBatch']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, $id)
    {

        try {
            $student = Student::find(encryptor('decrypt', $id));
            if ($request->photo) {
                //Photo Upload
                $position = strpos($request->photo, ';');
                $sub = substr($request->photo, 0, $position);
                $ext = explode('/', $sub)[1];
                $name = time() . "." . $ext;
                $img = Image::make($request->photo)->resize(240, 200);
                $upload_path = 'backend/student/';
                $image_url = $upload_path . $name;
                $img->save($image_url);

                $student->name             = $request->name;
                if (currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager') {
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
                $student->executiveReminder = date('Y-m-d', strtotime($request->executiveReminder));
                $student->executiveId      = $request->executiveId ? $request->executiveId : currentUserId();
                $student->refId            = $request->refId;
            } else {
                $student->name             = $request->name;
                if (strtolower(currentUser()) === 'superadmin' || strtolower(currentUser()) === 'salesmanager' ||  strtolower(currentUser()) === 'operationmanager') {
                    $student->contact          = $request->contact;
                    //$student->executiveId      = $request->executiveId ? $request->executiveId : currentUserId();
                    $student->refId            = $request->refId;
                }
                //$student->course_id        = (!empty($request->course_id)) ? implode(",", $request->course_id) : null;
                $student->altContact       = $request->altContact;
                $student->email            = $request->email;
                $student->address          = $request->address;
                $student->division_id      = $request->division_id;
                $student->district_id      = $request->district_id;
                $student->upazila_id       = $request->upazila_id;
                $student->otherInfo        = $request->otherInfo;
                $student->executiveNote    = $request->executiveNote;
                $student->executiveReminder = date('Y-m-d', strtotime($request->executiveReminder));
            }
            $student->save();
            if (!!$student->save()) return redirect(route(currentUser() . '.allStudent'))->with($this->responseMessage(true, null, 'Student updated'));
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
        //Here We need to use dump if free executive id will be zero 
        // first dump then need to make it delete by superadmin
        // need to keep recored for dumping if reson is wrong data it will delete
        //$dumpStudent->executiveId = 0;
        $dumpStudent->status = 3;
        $dumpStudent->save();
        return back();
    }
    public function active($id)
    {
        $dumpStudent = Student::findOrFail($id);
        $dumpStudent->status = 1;
        $dumpStudent->save();
        return back();
    }
    public function batchTransfer()
    {
        $allStudent = DB::table('students')
            ->selectRaw("students.name,students.id,student_batches.batch_id")
            ->join('student_batches', 'student_batches.student_id', '=', 'students.id', 'left')
            ->join('batch_transfers', 'student_batches.student_id', '=', 'batch_transfers.student_id', 'left')
            ->where(['student_batches.status' => 2])
            ->groupBy('student_batches.student_id')
            ->get();
        return view('student.batchTransfer', compact('allStudent'));
    }
    public function transfer(Request $request)
    {
        DB::beginTransaction();

        /* ======================= Check Step =================================*/
        /*
        1. Student Drop From Batch
        2. Check Seat Available
        3  Check Regular or Bundel Course
        4. Check Course Change or Batch Change
        5. Then Void That Batch Payemnt and Paymentdetails with update op type with payment and payment details and student batches with news batch id and op_type
        6. Then Enroll To New Batch if Course change update course also
        7. if course or batch change invoice has previous course or batch price is less than new batch course has to deposit to student account
           same course but price not same invoice has no price update but if no invoice same course price change amount will update
        8. Then Previous payment and pyamentdetails data added to new batch
        */


        if ($request->curbatchId == $request->newbatchId) {
            return redirect()->back()->with($this->responseMessage(false, null, 'Current Batch and Transferred Batch Same!!'));
        }
        if (DB::table('student_batches')->where(['student_id' => $request->student_id, 'batch_id' => $request->newbatchId])->first()) {
            return redirect()->back()->with($this->responseMessage(false, null, 'Student Already Exists On This Batch'));
        }

        DB::connection()->enableQueryLog();
        $seat_data = DB::select("SELECT IFNULL(sb.tst, 0) AS tst, b.seat AS seat_available FROM batches AS b 
            LEFT JOIN (SELECT batch_id, COUNT(id) AS tst FROM student_batches WHERE status = 2 and is_drop = 0 GROUP BY batch_id) 
            AS sb ON b.id = sb.batch_id WHERE b.id = $request->newbatchId");
        $queries = \DB::getQueryLog();

        //dd($queries);

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

        /* Check Regular or Bundel */
        $new_course = DB::table('batches')->select('courses.course_type', 'courses.id')->join('courses', 'batches.courseId', '=', 'courses.id')
            ->where('batches.id', $request->newbatchId)->first();
        //print_r($course_type);die;
        if ($new_course->course_type == 1) {
            /* ========= if Regular Course =========== */
            /* ============= Check Old Batch and New Batch Couse same or not ============*/
            $old_course = DB::table('batches')->select('courses.course_type', 'courses.id', 'batches.id as bid')->join('courses', 'batches.courseId', '=', 'courses.id')
                ->where('batches.id', $request->curbatchId)->first();
            //echo DB::table('student_batches')->where(['student_id' => $request->student_id, 'batch_id' => $old_course->bid])->first()->course_price;
            //print_r($old_course);die;
            //if($new_course->id == $old_course->id){
            //echo 'same';die;

            DB::table('student_batches')->where(['student_id' => $request->student_id, 'batch_id' => $request->curbatchId])->update(['new_batch_id' => $request->newbatchId, 'acc_approve' => 3, 'op_type' => $request->op_type, 'updated_by' => currentUserId(), 'updated_at' => Carbon::now()]);
            $note               =  new Note;
            $note->student_id   =  $request->student_id;
            $note->note         = $request->note;
            $note->created_by   = currentUserId();
            $note->save();
            /*========== Check Payment Invoice =================== */
            $inv = DB::table('payments')
                ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
                ->where(['paymentdetails.studentId' => $request->student_id, 'paymentdetails.batchId' => $request->curbatchId])->whereNotNull('payments.invoiceId')->exists();
            //dd($inv);
            if ($inv) {
                /*===============As Invoice Done Course Price Remain Same ================= */
                /* Payment and Payment Details */
                $payment_detl = DB::table('paymentdetails')->where(['studentId' => $request->student_id, 'batchId' => $request->curbatchId])
                    ->where('batchId', $request->curbatchId)->get();

                foreach ($payment_detl as $payment) {
                    DB::table('paymentdetails')->where('id', $payment->id)->update(['batchId' => $request->newbatchId, 'op_type' => $request->op_type, 'updated_by' => currentUserId(), 'updated_at' => Carbon::now()]);
                    $payment_data = DB::table('payments')->where('id', $payment->paymentId)->first();
                    DB::table('payments')->where('id', $payment->paymentId)->update(['op_type' => $request->op_type, 'updated_by' => currentUserId(), 'updated_at' => Carbon::now()]);
                }
                $enroll_data = DB::table('student_batches')->where(['student_id' => $request->student_id, 'batch_id' => $request->curbatchId])
                    ->first();
                /*Stystem Id */
                $systemId = substr(uniqid(Str::random(6), true), 0, 6);
                $data = array(
                    'batch_id' => $request->newbatchId,
                    'student_id' =>  $request->student_id,
                    'package_id' =>  0,
                    'entryDate' => date('Y-m-d'),
                    'status' => 2,
                    'systemId' => $systemId,
                    'course_price' => DB::table('student_batches')->where(['student_id' => $request->student_id, 'batch_id' => $request->curbatchId])->first()->course_price,
                    'acc_approve' => 2,
                    'type' => $enroll_data->type,
                    'created_at' => Carbon::now(),
                    'created_by' => currentUserId(),
                );
                DB::table('student_batches')->insert($data);
            } else {
                echo 'ok';
                die;
                /*===============As Invoice Not Done Course Price Will Change ================= */
                /* if payment is greater than main course price need to deposit that amount in student account */
                $enroll_data = DB::table('student_batches')->where(['student_id' => $request->student_id, 'batch_id' => $request->curbatchId])
                    ->first();
                /*Stystem Id */
                $systemId = substr(uniqid(Str::random(6), true), 0, 6);
                if ($enroll_data->type == 1) {
                    $course = DB::select("SELECT courses.rPrice as price FROM batches join courses on batches.courseId = courses.id WHERE batches.id =$request->newbatchId");
                } else {
                    $course = DB::select("SELECT courses.iPrice as price FROM batches join courses on batches.courseId = courses.id WHERE batches.id =$request->newbatchId");
                }

                $data = array(
                    'batch_id' => $request->newbatchId,
                    'student_id' =>  $request->student_id,
                    'package_id' =>  0,
                    'entryDate' => date('Y-m-d'),
                    'status' => 2,
                    'systemId' => $systemId,
                    'course_price' => $course[0]->price,
                    'type' => $enroll_data->type,
                    'created_at' => Carbon::now(),
                    'created_by' => currentUserId(),
                );
                DB::table('student_batches')->insert($data);

                /*==== Update payment and PaymentdDetails for New Batch From Old Batch====== */
                /*Course Price change in not invoice paymnet and paymentdetails table update */
                $pay_detl = DB::table('paymentdetails')->where('studentId', '=', $request->student_id)->where('batchId', '=', $request->newbatchId)
                    ->first();
                if ($pay_detl) {
                    DB::table('paymentdetails')->where('id', '=', $pay_detl->id)->update(['cPayable' => $course[0]->price]);
                    DB::table('payments')->where('id', $pay_detl->paymentId)->update(['tPayable' => $course[0]->price]);
                }
                $payable = DB::table('paymentdetails')->where('studentId', '=', $request->student_id)->where('batchId', '=', $request->newbatchId)->get();
                foreach ($payable as $p) {
                    $sum = DB::table('paymentdetails')
                        ->where('id', '<', $p->id)
                        ->where('studentId', '=', $request->student_id)->where('batchId', '=', $request->newbatchId)
                        ->sum('cpaidAmount');

                    $sum_cpayable = DB::table('paymentdetails')
                        ->where('paymentId', $p->paymentId)
                        ->sum('cPayable');
                    $sum_cpaidAmount = DB::table('paymentdetails')
                        ->where('paymentId', $p->paymentId)
                        ->sum('cpaidAmount');

                    DB::table('paymentdetails')->where('id', $p->id)
                        ->update(['cPayable' =>  $course[0]->price - $sum]);
                    DB::table('payments')->where('id', $p->paymentId)
                        ->update(['tPayable' => $sum_cpayable, 'paidAmount' => $sum_cpaidAmount]);
                }
            }


            /* }else{
                    echo 'not same';die;
                }*/
        } else {
            /* ========= Bundel Course =========== */
        }

        /*=== Here Need To update Payment Details Batch Id If Batch Id Has Changed=== */
        $data2 = array(
            'student_id' => $request->student_id,
            'curbatchId' => $request->curbatchId,
            'newbatchId' =>  $request->newbatchId,
            'op_type' =>  $request->op_type,
            'created_by' => currentUserId(),
            'note' => $request->note,
            'created_at' => Carbon::now()
        );
        DB::table('batch_transfers')->insert($data2);
        DB::commit();
        return redirect()->back()->with($this->responseMessage(true, null, 'Update Successful'));
    }
    public function batchTransferList()
    {
        $student_transfers = DB::table('batch_transfers')
            ->join('students', 'batch_transfers.student_id', 'students.id')
            ->join('users', 'batch_transfers.created_by', 'users.id')
            ->select('batch_transfers.*', 'students.name as stuname', 'users.name as uname')
            ->get();
        return view('student.batchTransferList', compact('student_transfers'));
    }
    public function studentEnrollBatch(Request $request)
    {
        \DB::connection()->enableQueryLog();
        $curbatchId = DB::table('batch_transfers')->where(['student_id' => $request->id])->pluck('curbatchId')->toArray();
       

        $e_data = DB::table('student_batches')
            ->selectRaw("student_batches.batch_id,batches.batchId")
            ->join('batches', 'batches.id', '=', 'student_batches.batch_id', 'left')
            ->where(['student_id' => $request->id, 'student_batches.status' => 2, 'is_drop' => 1])
            ->whereNotIn('student_batches.batch_id', $curbatchId)
            ->groupBy('student_batches.batch_id', 'batches.batchId')
            ->get();
            $queries = \DB::getQueryLog();

            //dd($queries);
        $data = '<label for="curbatchId" class="col-sm-3 col-form-label">From Batch</label>
            <div class="col-sm-9">
            <select class="js-example-basic-single form-control" id="curbatchId" name="curbatchId" required>
            <option value="">Select</option>';
        foreach ($e_data as $e) {
            $data .= '<option value="' . $e->batch_id . '">' . $e->batchId . '</option>';
        }
        $data .= '</select></div>';

        $newbatchId = DB::table('batch_transfers')->where(['student_id' => $request->id])->pluck('newbatchId')->toArray();
        $allBatch = DB::table('batches')->whereNotIn('batches.id', $curbatchId)->get();

        $data2 = '<label for="newbatchId" class="col-sm-3 col-form-label">To Batch</label>
        <div class="col-sm-9">
        <select class="js-example-basic-single form-control" id="newbatchId" name="newbatchId" required>
        <option value="">Select</option>';
        foreach ($allBatch as $b) {
            $data2 .= '<option value="' . $b->id . '">' . $b->batchId . '</option>';
        }
        $data2 .= '</select></div>';
        $data2 .= '<script>$(\'.js-example-basic-single\').select2({
            placeholder: \'Select Option\',
            allowClear: true
        });</script>';
        return response()->json(array('data' => $data, 'data2' => $data2));
    }
    /*=========Course Preference==== */
    public function coursePreference(Request $request)
    {
        $course_id = $request->post('course_id');
        foreach ($course_id as $key => $cp) {
            $data = array(
                'batch_time_id' => $request->batch_time_id,
                'batch_slot_id' => $request->batch_slot_id,
                'student_id' =>  $request->student_id,
                'course_id' => $course_id[$key],
                'created_by' => currentUserId(),
                'created_at' => Carbon::now()
            );
            DB::table('course_preferences')->insert($data);
        }
        return redirect()->back()->with($this->responseMessage(true, null, 'Course Preference Added Successfully'));
    }
    public function coursePreferencEdit(Request $request, $id)
    {
        DB::table('course_preferences')->where('id', $id)->update(['course_id' => $request->course_id, 'batch_time_id' => $request->batch_time_id, 'batch_slot_id' => $request->batch_slot_id, 'updated_at' => Carbon::now()]);
        return redirect()->back()->with($this->responseMessage(true, null, 'Course Preference Updated Successfully'));
    }
    /*=========Course Wise Enrollment==== */

    public function courseEnroll(Request $request)
    {
        $systemId = substr(uniqid(Str::random(6), true), 0, 6);
        $courses = $request->post('course_id');
        foreach ($request->course_id as $key => $c) {
            $course_type = DB::table('courses')->where('id', $courses[$key])->first()->course_type;
            /* Regular Course */
            if ($course_type == 1) {
                if ($request->status == 1) {
                    $course = DB::table('courses')->select('rPrice as price')->where('id', $courses[$key])->first();
                } else {
                    $course = DB::table('courses')->select('iPrice as price')->where('id', $courses[$key])->first();
                }
            } else {
                if ($request->status == 1) {
                    $course = DB::table('bundel_courses')->select(DB::raw('SUM(rPrice) as price'))
                        ->where('main_course_id', $courses[$key])->where('status', 1)->first();
                } else {
                    $course = DB::table('bundel_courses')->select(DB::raw('SUM(iPrice) as price'))
                        ->where('main_course_id', $courses[$key])->where('status', 1)->first();
                }
            }
            $data = array(
                'course_id' => $courses[$key],
                'student_id' =>  $request->student_id,
                'batch_time_id' => $request->batch_time_id,
                'batch_slot_id' => $request->batch_slot_id,
                'price' => $course->price,
                'systemId' => $systemId,
                'status' => $request->status,
                'created_at' => Carbon::now()
            );
            DB::table('student_courses')->insert($data);
        }


        return redirect()->back()->with($this->responseMessage(true, null, 'Course Enroll Successful'));
    }
    public function courseEnrollUpdate(Request $request)
    {
        $course_type = DB::table('courses')->where('id', $request->course_id)->first()->course_type;
        if ($course_type == 1) {
            if ($request->status == 1) {
                $course = DB::table('courses')->select('rPrice as price')->where('id', $request->course_id)->first();
            } else {
                $course = DB::table('courses')->select('iPrice as price')->where('id', $request->course_id)->first();
            }
        } else {
            if ($request->status == 1) {
                $course = DB::table('bundel_courses')->select(DB::raw('SUM(rPrice) as price'))
                    ->where('main_course_id', $request->course_id)->where('status', 1)->first();
            } else {
                $course = DB::table('bundel_courses')->select(DB::raw('SUM(iPrice) as price'))
                    ->where('main_course_id', $request->course_id)->where('status', 1)->first();
            }
        }
        DB::table('student_courses')->where('id', $request->cid)->update(['course_id' => $request->course_id, 'price' => $course->price, 'batch_time_id' => $request->batch_time_id, 'batch_slot_id' => $request->batch_slot_id, 'status' => $request->status, 'updated_at' => Carbon::now()]);
        return redirect()->back()->with($this->responseMessage(true, null, 'Course Enroll Updated Successfully'));
    }
    public function courseEnrollDelete(Request $request, $id)
    {
        if (DB::table('student_courses')->where('id', encryptor('decrypt', $id))->delete());
        return redirect()->back()->with($this->responseMessage(true, null, 'Course Enrollment Delete Successful'));
    }

    /*Student Transfer */
    public function studentTransfer()
    {
        $allStudent = Student::all();
        return view('student.studentTransfer', compact('allStudent'));
    }
    public function studentExecutive(Request $request)
    {
        $old_ex = Student::where('id', $request->id)->first();
        $ex_list = User::whereIn('roleId', [1, 3, 5, 9])->whereNot('id', '=', $old_ex->executiveId)->get();
        $old_ex_data = User::find($old_ex->executiveId);
        $data = '
        <label for="curexId" class="col-sm-3">Old Executive</label>
        <div class="col-sm-9">
        <input type="text" class="form-control" value="' . $old_ex_data->name . '" readonly>
        <input type="hidden" class="form-control" value="' . $old_ex_data->id . '" name="curexId">
        </div>
    ';
        //return response()->json(array('data' => $ex_list));
        $data2 = '<label for="newexId" class="col-sm-3">To Executive</label>
            <div class="col-sm-9">
            <select class="js-example-basic-single form-control" id="newexId" name="newexId" required>
            <option value="">Select</option>';
        foreach ($ex_list as $e) {
            $data2 .= '<option value="' . $e->id . '">' . $e->name . '</option>';
        }
        $data2 .= '</select></div>';


        return response()->json(array('data' => $data, 'data2' => $data2));
    }
    public function stTransfer(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = array(
                'executiveId' => $request->newexId
            );
            DB::table('students')->where('id', $request->student_id)->update($data);
            $data2 = array(
                'student_id' => $request->student_id,
                'curexId' => $request->curexId,
                'newexId' =>  $request->newexId,
                'created_by' => currentUserId(),
                'note' => $request->note,
                'created_at' => Carbon::now()
            );
            DB::table('student_transfers')->insert($data2);
            DB::commit();
            return redirect()->back()->with($this->responseMessage(true, null, 'Update Successful'));
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
    public function studentTransferList()
    {
        $student_transfers = DB::table('student_transfers')
            ->join('students', 'student_transfers.student_id', 'students.id')
            ->join('users', 'student_transfers.created_by', 'users.id')
            ->select('student_transfers.*', 'students.name as stuname', 'users.name as uname')
            ->get();
        return view('student.studentTransferList', compact('student_transfers'));
    }

    /*=== Withdraw | Drop Student ==*/
    public function withdraw(Request $request)
    {
        $withdraw_student = DB::table('student_batches')->where('id', $request->id)->update(['is_drop' => 1]);
        if ($withdraw_student) {
            $data = DB::table('student_batches')->where('id', $request->id)->first();
            $batch = Batch::find($data->batch_id);
            $note               =  new Note;
            $note->student_id   =  $data->student_id;
            $note->note         = 'Withdraw from Batch #' . $batch->batchId;
            $note->created_by   = currentUserId();
            $note->save();
            return redirect()->back()->with($this->responseMessage(true, null, 'Withdraw Successful'));
        }
    }
    public function withdraw_undo(Request $request)
    {
        $seat_data = DB::select("SELECT COUNT(student_batches.id) as tst ,batches.seat as seat_available FROM batches
        left join student_batches on student_batches.batch_id=batches.id
        WHERE batches.id=$request->batch_id and 
        student_batches.status=2 and student_batches.is_drop = 0
        GROUP by student_batches.batch_id,batches.seat");
        /*print_r($seat_data);die;*/
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
        $withdraw_undo_student = DB::table('student_batches')->where('id', $request->id)->update(['is_drop' => 0]);
        if ($withdraw_undo_student) {
            $data = DB::table('student_batches')->where('id', $request->id)->first();
            $batch = Batch::find($data->batch_id);
            $note               =  new Note;
            $note->student_id   =  $data->student_id;
            $note->note         = 'Withdraw Undo from Batch #' . $batch->batchId;
            $note->created_by   = currentUserId();
            $note->save();
            return redirect()->back()->with($this->responseMessage(true, null, 'Withdraw Undo Successful'));
        }
    }
}
