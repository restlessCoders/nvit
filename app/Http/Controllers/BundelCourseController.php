<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\BundelCourse;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;

class BundelCourseController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBundelCourses = BundelCourse::select('main_course_id')->distinct()->where('status',1)->orderBy('id', 'DESC')->paginate(10);
        return view('bundel_course.index', compact('allBundelCourses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allCourses = Course::where('status', 1)->orderBy('courseName', 'asc')->get();
        return view('bundel_course.add_new', compact('allCourses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $course                     = new BundelCourse;
            $course->main_course_id     = $request->main_course_id;
            $course->sub_course_id      = $request->sub_course_id;
            $course->rPrice             = $request->rPrice;
            $course->iPrice             = $request->iPrice;
            $course->mPrice             = $request->mPrice;
            $course->save();
            if (!!$course->save()) return redirect(route(currentUser() . '.bundelcourse.create'))->with($this->responseMessage(true, null, 'Bundel Course created'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BundelCourse  $bundelCourse
     * @return \Illuminate\Http\Response
     */
    public function show(BundelCourse $bundelCourse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BundelCourse  $bundelCourse
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bundel_course = BundelCourse::find(encryptor('decrypt', $id));
        $allCourses = Course::where('status', 1)->orderBy('courseName', 'asc')->get();
        return view('bundel_course.edit', compact('allCourses','bundel_course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BundelCourse  $bundelCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $bc = BundelCourse::find(encryptor('decrypt', $id));
            $bc->rPrice             = $request->rPrice;
            $bc->iPrice             = $request->iPrice;
            $bc->mPrice             = $request->mPrice;
            $bc->save();
        if (!!$bc->save()) return redirect(route(currentUser() . '.bundelcourse.index'))->with($this->responseMessage(true, null, 'Bundel Course Updated'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BundelCourse  $bundelCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $course = BundelCourse::find(encryptor('decrypt', $id));
            $course->status = !$course->status;
            $course->updated_by = currentUserId();
            if (!!$course->save()) return redirect(route(currentUser() . '.bundelcourse.index'))->with($this->responseMessage(false, true, 'Course Deleted'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
