<?php

namespace App\Http\Controllers;

use App\Models\Batchslot;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;

class BatchslotController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBatchslots = Batchslot::orderBy('id', 'DESC')->paginate(25);
        return view('batchslot.index', compact('allBatchslots'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('batchslot.add_new');
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
            $batchslot = New Batchslot();
            $batchslot->slotName = $request->slotName;
            $batchslot->created_by = encryptor('decrypt', $request->userId);
            $batchslot->status = 1;
            $batchslot->save();
            if(!!$batchslot->save()) return redirect(route(currentUser().'.batchslot.index'))->with($this->responseMessage(true, null, 'Batch slot created'));
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
        $batchslot = BatchSlot::find(encryptor('decrypt', $id));
        return view('batchslot.edit',compact('batchslot'));
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
            $batchslot = Batchslot::find(encryptor('decrypt', $id));
            $batchslot->slotName = $request->slotName;
            $batchslot->updated_by = encryptor('decrypt', $request->userId);
            $batchslot->save();
            if(!!$batchslot->save()) return redirect(route(currentUser().'.batchslot.index'))->with($this->responseMessage(true, null, 'Batch slot Updated'));
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
            $batchslot = BatchSlot::find(encryptor('decrypt', $id));
            $batchslot->status = !$batchslot->status;
            $batchslot->updated_by = currentUserId();
            if(!!$batchslot->save())return redirect(route(currentUser().'.batchslot.index'))->with($this->responseMessage(false, true, 'BatchSlot Deleted'));
            } catch (Exception $e) {
                dd($e);
                return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
                return false;
            }
    }
}