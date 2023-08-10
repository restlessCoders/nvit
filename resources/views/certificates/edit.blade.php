@extends('layout.master')
@section('title', 'Certificate Edit')
@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
					<li class="breadcrumb-item"><a href="javascript: void(0);">Certificate</a></li>
					<li class="breadcrumb-item active">Edit</li>
				</ol>
			</div>
			<h4 class="page-title">Cerficate Edit</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">

		$batch_data = Batch::find($request->batch_id);
        $image_path = asset('backend/images/logo.webp');
        $data = '<div style="width:10%;display:inline-block;"><img src=' . $image_path . ' alt="" height="40"></div>';
        $data .=     '<div style="width:90%;display:inline-block;text-align:center;"><h4 class="m-0 p-0 text-center" style="font-size:11px;font-weight:700;">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>';
        $data .= '<p class="m-0 p-0 text-center" style="font-size:9px"><strong class="text-center">Course : ' . \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName . '</strong></p>';
        $data .=     '<p class="m-0 p-0 text-center" style="font-size:9px"><strong>Batch Completion Report</strong></p></div>';



        $data .=     '<p class="m-0 p-0" style="font-size:10px;display:flex;justify-content:space-between">
                        <strong>Started On :'  . \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') . '</strong>
                        <strong>' . \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time . '</strong>
                        <strong>' . \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName . '</strong>
                        <strong>Batch : ' . $batch_data->batchId . '</strong>
                        <strong>Trainer : ' . \DB::table('users')->where('id', $batch_data->trainerId)->first()->name . '</strong>  
                        </p>';


        $startDate = new DateTime($batch_data->startDate);
        $endDate = new DateTime($batch_data->endDate);

        // Create a DateInterval of 1 day
        $interval = new DateInterval('P1D');
        if (currentUser() == 'trainer') {
            $data .= '<form action="' . route(currentUser() . '.certificate.store') . '" method="post"> ' . csrf_field() . '';
        }
        /*<th style="border:1px solid #000;;color:#000;"><strong>Ins. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>Acc. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>Op. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>GM. Note</strong></th>
        <th style="border:1px solid #000;;color:#000;"><strong>Ex. Note</strong></th>*/
        $data .= '<table class="table table-sm text-center" style="width:100%;text-align:center;border:1px solid #000;color:#000;">
                    <tbody>
                        <tr>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>ID</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Name</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Invoice</strong></th>
                            <th style="border:1px solid #000;color:#000;"><strong>Executive</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Attn.</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Perf.</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Pass</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Drop</strong></th>
                        </tr>';
        if ($request->batch_id) {
            $batch_students = DB::table('student_batches')->where('batch_id', $request->batch_id)->where('status', 2)->get();
        }
        foreach ($batch_students as $batch_student) {
            $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();
            $cer_data = Certificate::where('student_id', $batch_student->student_id)->where('batch_id', $batch_data->id)->first();
            $data .= '<tr>';
            $data .= '<td style="border:1px solid #000;color:#000;">' . $s_data->id . '</td>';
            $data .= '<input type="hidden" name="student_id[]" value="' . $s_data->id . '">';
            $data .= '<input type="hidden" name="batch_id[]" value="' . $batch_data->id . '">';
            $data .= '<td style="border:1px solid #000;color:#000;">' . $s_data->name . '</td>';
            $data .= '<td style="border:1px solid #000;color:#000;">';
            if (\DB::table('payments')
                ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
                ->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->exists()
            ) {
                $data .= \DB::table('payments')->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')->where(['paymentdetails.batchId' => $request->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->first()->invoiceId;
            } else {
                $data .= '-';
            }
            '</td>';
            <td style="border:1px solid #000;color:#000;">{{\DB::table('users')->where('id', $s_data->executiveId)->first()->username}}</td>
            if ($cer_data) {
                <td style="border:1px solid #000;color:#000;"><input size="2" type="text" name="attn[]" value="' . $cer_data->attn . '"></td>
                <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="perf[]" ($cer_data->perf == 1 ? 'checked="checked"' : '')></td>

                <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="pass[]" ($cer_data->pass == 1 ? 'checked="checked"' : '')></td>

                <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="drop[]" ($cer_data->drop == 1 ? 'checked="checked"' : '')></td>
            } else {
                <td style="border:1px solid #000;color:#000;"><input size="2" type="text" name="attn[]"></td>
                <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="perf[]"></td>
                <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="pass[]"></td>
                <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="drop[]"></td>
            }
            <input type="hidden" name="perf[]" value="0">
            <input type="hidden" name="pass[]" value="0">
            <input type="hidden" name="drop[]" value="0">

            </tr>
        }
        </tbody>
                </table>

        if (currentUser() == 'trainer') {
            <div class="col-md-12 d-flex justify-content-end"><button class="btn btn-primary" type="submit">Save</button></div>
            </form>
        }

 

			
		</div>
	</div>
</div> <!-- end row -->
@endsection
@push('scripts')
@if(Session::has('response'))
<script>
	Command: toastr["{{Session::get('response')['errors']}}"]("{{Session::get('response')['message']}}")
	toastr.options = {
		"closeButton": false,
		"debug": false,
		"newestOnTop": false,
		"progressBar": false,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}
</script>
@endif
@endpush