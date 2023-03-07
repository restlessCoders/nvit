<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;

class ReferenceController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allReference = Reference::where('status',1)->orderBy('id', 'DESC')->paginate(10);
        return view('reference.index', compact('allReference'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reference.add_new');
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
        $reference = New Reference();
        $reference->refName = $request->refName;
        $reference->created_by = encryptor('decrypt', $request->userId);
        $reference->save();
        if(!!$reference->save()) return redirect(route(currentUser().'.reference.index'))->with($this->responseMessage(true, null, 'reference created'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reference = Reference::find(encryptor('decrypt', $id));
        return view('reference.edit',compact('reference'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $reference = Reference::find(encryptor('decrypt', $id));
            $reference->refName = $request->refName;
            $reference->updated_by = encryptor('decrypt', $request->userId);
            if(!!$reference->save()) return redirect(route(currentUser().'.reference.index'))->with($this->responseMessage(true, null, 'reference created'));
            } catch (Exception $e) {
                dd($e);
                return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
                return false;
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        try {
            $reference = Reference::find(encryptor('decrypt', $id));
            $reference->status = 0;
            $reference->updated_by = currentUserId();
            if(!!$reference->save())return redirect(route(currentUser().'.reference.index'))->with($this->responseMessage(false, true, 'reference Deleted'));
            } catch (Exception $e) {
                dd($e);
                return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
                return false;
            }
    }   
}
