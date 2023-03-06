<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Http\Request;

use App\Http\Requests\Package\NewPackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Http\Traits\ResponseTrait;
use Exception;
use DB;
class PackageController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allPackage = Package::paginate();
        return view('package.index',compact('allPackage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allCourses = Course::all();
        $allBatch = Batch::all();
        return view('package.add_new',compact(['allCourses','allBatch']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewPackageRequest $request)
    {
        try {
            $package = new Package;
            $package->pName = $request->pName;
            $package->courseId = $request->courseId;
            $package->price = $request->price;
            $package->startDate = date('Y-m-d',strtotime($request->startDate));
            $package->endDate = date('Y-m-d',strtotime($request->endDate));
            $package->endTime = date('H:i',strtotime($request->endTime));
            $package->status =0;
            $package->note =$request->note;
            $package->created_by = encryptor('decrypt', $request->userId);
            if(!!$package->save()) return redirect(route(currentUser().'.package.index'))->with($this->responseMessage(true, null, 'Package created'));
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
        $pdata = Package::find(encryptor('decrypt', $id));
        return view('package.edit',compact(['allCourses','pdata']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageRequest $request, $id)
    {
        try {
            $package = Package::find(encryptor('decrypt', $id));
            $package->pName = $request->pName;
            $package->courseId = $request->courseId;
            $package->price = $request->price;
            $package->startDate = date('Y-m-d',strtotime($request->startDate));
            $package->endDate = date('Y-m-d',strtotime($request->endDate));
            $package->endTime = date('H:i',strtotime($request->endTime));
            $package->updateBy = encryptor('decrypt', $request->userId);
            $package->status =$request->status;
            $package->note =$request->note;
            $package->updated_by = encryptor('decrypt', $request->userId);
            $package->save();
        if(!!$package->save()) return redirect(route(currentUser().'.package.index'))->with($this->responseMessage(true, null, 'Package updated'));
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
