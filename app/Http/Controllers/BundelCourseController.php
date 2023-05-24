<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\BundelCourse;
use Illuminate\Http\Request;

class BundelCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allCourses = Course::where('status',1)->orderBy('name', 'asc');
        return view('bundel_course.add_new',compact('allCourses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit(BundelCourse $bundelCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BundelCourse  $bundelCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BundelCourse $bundelCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BundelCourse  $bundelCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(BundelCourse $bundelCourse)
    {
        //
    }
}
