<?php

namespace App\Http\Controllers;

use App\Models\Batchtime;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;

class BatchtimeController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBatchtimes = Batchtime::where('status',1)->orderBy('id', 'DESC')->paginate(25);
        return view('batchtime.index', compact('allBatchtimes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('batchtime.add_new');
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
            $batchtime = New BatchTime();
            $batchtime->time = $request->time;
            $batchtime->created_by = encryptor('decrypt', $request->userId);
            if(!!$batchtime->save()) return redirect(route(currentUser().'.batchtime.index'))->with($this->responseMessage(true, null, 'Batch time created'));
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
    public function show(Wallet $wallet)
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
        $batchtime = BatchTime::find(encryptor('decrypt', $id));
        return view('batchtime.edit',compact('batchtime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $batchtime = BatchTime::find(encryptor('decrypt', $id));
            $batchtime->time = $request->time;
            $batchtime->updated_by = encryptor('decrypt', $request->userId);
            $batchtime->save();
            if(!!$batchtime->save()) return redirect(route(currentUser().'.batchtime.index'))->with($this->responseMessage(true, null, 'Batch time Updated'));
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
    public function destroy($id)
    {
        try {
            $batchtime = BatchTime::find(encryptor('decrypt', $id));
            $batchtime->status = 0;
            $batchtime->updated_by = currentUserId();
            if(!!$batchtime->save())return redirect(route(currentUser().'.batchtime.index'))->with($this->responseMessage(false, true, 'Batch Time Deleted'));
            } catch (Exception $e) {
                dd($e);
                return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
                return false;
            }
    }
}