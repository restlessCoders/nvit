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
        $certificates_data = Certificate::where('created_by',currentUserId())->distinct('batch_id')->get();
        echo '<pre>';
        print_r($certificates_data->toArray());
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
        //print_r($request->toArray());die;
       
        $student_id       = $request->post('student_id');
        //print_r($student_id);die;
        $batch_id         = $request->post('batch_id');
        $attn             = $request->post('attn');
        $perf             = $request->post('perf');
        $drop             = $request->post('drop');
        $pass             = $request->post('pass');
        foreach ( $student_id as $key => $value) {
            $certificate['student_id'] = $student_id[$key];
            $certificate['batch_id']   = $batch_id[$key];
            $certificate['attn']   = isset($attn[$key])?$attn[$key]:0;
            $certificate['perf']   = isset($perf[$key])?$perf[$key]:0;
            $certificate['drop']   = isset($drop[$key])?$drop[$key]:0;
            $certificate['pass']   = isset($pass[$key])?$pass[$key]:0;
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
    public function edit(Certificate $certificate)
    {
        //
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
