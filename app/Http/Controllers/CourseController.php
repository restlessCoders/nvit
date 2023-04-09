<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\Course\NewCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class CourseController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    Public function courseSearch(Request $request){
        $search = $request->get('search');
        if($search != ''){
            $allCourses = Course::where('courseName','like', '%' .$search. '%')->paginate(25);
            $allCourses->appends(array('search'=> $search,));
            if(count($allCourses )>0){
            return view('course.index',['allCourses'=>$allCourses]);
            }
            return back()->with('error','No results Found');
        }   
    }
    public function index()
    {
        $allCourses = Course::orderBy('id', 'DESC')->paginate(10);
        return view('course.index', compact('allCourses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('course.add_new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewCourseRequest $request )
    {
        try {
        $course                     = new Course;
        $course->courseName         = $request->courseName;
        $course->courseDescription  = $request->courseDescription;
        $course->rPrice             = $request->rPrice;
        $course->iPrice             = $request->iPrice;
        $course->mPrice             = $request->mPrice;
        $course->save();
        if(!!$course->save()) return redirect(route(currentUser().'.course.index'))->with($this->responseMessage(true, null, 'Course created'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cdata = course::find(encryptor('decrypt', $id));
        return view('course.edit', compact('cdata'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        try {
            $course = Course::find(encryptor('decrypt', $id));
            $course->courseName         = $request->courseName;
            $course->courseDescription  = $request->courseDescription;
            $course->rPrice             = $request->rPrice;
            $course->iPrice             = $request->iPrice;
            $course->mPrice             = $request->mPrice;
            $course->save();
        if(!!$course->save()) return redirect(route(currentUser().'.course.index'))->with($this->responseMessage(true, null, 'Course created'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        try {
            $course = Course::find(encryptor('decrypt', $id));
            $course->status = !$course->status;
            $course->updated_by = currentUserId();
            if(!!$course->save())return redirect(route(currentUser().'.course.index'))->with($this->responseMessage(false, true, 'Course Deleted'));
            } catch (Exception $e) {
                dd($e);
                return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
                return false;
            }
    }  
}