<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Carbon;
use App\Http\Traits\ResponseTrait;
class CertificateController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $certificates = Certificate::select('batch_id','id','created_at','updated_at','created_by')->where('created_by',currentUserId())->groupBy('batch_id')->paginate(20);
        return view('certificates.index',compact('certificates'));
        /*echo '<pre>';
        print_r($certificates->toArray());*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo $request->post('batch_id')[0];die;
        $certificates_data = Certificate::where('batch_id',$request->post('batch_id'))->first();
        if($certificates_data){
            DB::table('certificates')->where('batch_id', '=', $request->post('batch_id')[0])->delete();
        }
        //print_r($request->toArray());die;
       
        $student_id       = $request->post('student_id');
        $count = count($student_id);
        //print_r($student_id);die;
        $batch_id         = $request->post('batch_id');
        $attn             = $request->post('attn');
        $perf             = $request->post('perf');
        $pass             = $request->post('pass');
        $drop             = $request->post('drop');

        for ($key = 0; $key < $count; $key++) {
            $certificate['student_id'] = $student_id[$key];
            $certificate['batch_id']   = $batch_id[$key];
            $certificate['attn']   = $attn[$key];
            $certificate['perf']       = isset($perf[$key]) ? ($perf[$key] == 1 ? 1 : 0) : 0;
            $certificate['pass']       = isset($pass[$key]) ? ($pass[$key] == 1 ? 1 : 0) : 0;
            $certificate['drop']       = isset($drop[$key]) ? ($drop[$key] == 1 ? 1 : 0) : 0;
            $certificate['created_by'] = currentUserId();
            $certificate['created_at'] = Carbon::now();
            DB::table('certificates')->insert($certificate);
        }
        return redirect(route(currentUser().'.certificate.index'))->with($this->responseMessage(true, null, 'Data Saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function edit($batch_id)
    {
        $certificates = Certificate::where('batch_id',$batch_id)->get();
        return view('certificates.edit',compact('certificates'));
        /*echo '<pre>';
        print_r($certificates->toArray());*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificate $certificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {
        //
    }
}
