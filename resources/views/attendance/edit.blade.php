@extends('layout.master')
@section('title', 'Attendance Edit')
@push('styles')
<style>
    .table-sm td,
    .table-sm th {
        padding: 0.1rem;
    }

    table,
    tbody,
    tr,
    th,
    td {
        font-size: 0.9em;
    }

    h4 {
        font-size: 18px;
        color: #000
    }

    p,
    p strong,
    table,
    table td,
    table th {
        font-size: 13px;
        color: #000
    }

    p strong {
        margin-right: 10px;
    }

    body {
        font-size: 12pt;
    }
</style>

@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Attendance</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
            <h4 class="page-title">Attendance Edit For {{ request()->input('postingDate') }}</h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card-box">

            @php
            $batch_data = \App\Models\Batch::find($id);
            $image_path = asset('backend/images/logo.webp');
            @endphp
            <div style="width:10%;display:inline-block;"><img src="{{$image_path}}" alt="" height="40"></div>
            <div style="width:90%;display:inline-block;text-align:center;">
                <h4 class="m-0 p-0 text-center" style="font-size:11px;font-weight:700;">NEW VISION INFORMATION TECHNOLOGY LTD.</h4>
                <p class="m-0 p-0 text-center" style="font-size:9px"><strong class="text-center">Course : {{ \DB::table('courses')->where('id', $batch_data->courseId)->first()->courseName }}</strong></p>
                <p class="m-0 p-0 text-center" style="font-size:9px"><strong>Daily Attendance Sheet</strong></p>
            </div>';



            <p class="m-0 p-0" style="font-size:10px;display:flex;justify-content:space-between">
                <strong>Started On :{{ \Carbon\Carbon::createFromTimestamp(strtotime($batch_data->startDate))->format('j M, Y') }} </strong>
                <strong>{{ \DB::table('batchtimes')->where('id', $batch_data->btime)->first()->time }} </strong>
                <strong>{{ \DB::table('batchslots')->where('id', $batch_data->bslot)->first()->slotName }} </strong>
                <strong>Batch : {{ $batch_data->batchId }} </strong>
                <strong>Trainer : {{ DB::table('users')->where('id', $batch_data->trainerId)->first()->name }}</strong>
            </p>

            <form action="{{route(currentUser() . '.attendance.update',$id)}}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="col-md-3">
                            <div class="input-group my-2">
                                <input type="text" name="postingDate" class="form-control" placeholder="dd/mm/yyyy">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>





                </div>

                @php
                $startDate = new DateTime($batch_data->startDate);
                $endDate = new DateTime($batch_data->endDate);

                // Create a DateInterval of 1 day
                $interval = new DateInterval('P1D');
                @endphp


                {{--<th style="border:1px solid #000;;color:#000;"><strong>Ins. Note</strong></th>
                <th style="border:1px solid #000;;color:#000;"><strong>Acc. Note</strong></th>
                <th style="border:1px solid #000;;color:#000;"><strong>Op. Note</strong></th>
                <th style="border:1px solid #000;;color:#000;"><strong>GM. Note</strong></th>
                <th style="border:1px solid #000;;color:#000;"><strong>Ex. Note</strong></th>--}}
                <table class="table table-sm text-center" style="width:100%;text-align:center;border:1px solid #000;color:#000;">
                    <tbody>
                        <tr>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>ID</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;text-align:left;"><strong>Name</strong></th>
                            <th class="align-middle" style="border:1px solid #000;;color:#000;"><strong>Invoice</strong></th>
                            <th style="border:1px solid #000;color:#000;"><strong>Executive</strong></th>
                            <th style="border:1px solid #000;;color:#000;"><strong>Is Present.</strong></th>
                        </tr>

                        @php
                        @endphp
                        @foreach ($all_students as $batch_student)
                        @php
                        $s_data = \DB::table('students')->where('id', $batch_student->student_id)->first();
                        $cer_data = \App\Models\Attendance::where('student_id', $batch_student->student_id)->where('batch_id', $batch_data->id)->where('postingDate', \Carbon\Carbon::createFromTimestamp(strtotime(request()->get('postingDate')))->format('Y-m-d'))->first();
                        @endphp
                        <tr>
                            <td style="border:1px solid #000;color:#000;">{{$s_data->id}}</td>
                            <input type="hidden" name="student_id[]" value="{{ $s_data->id }}">
                            <input type="hidden" name="batch_id[]" value="{{ $batch_data->id }}">
                            <td style="border:1px solid #000;color:#000;text-align:left;">{{ strtoupper($s_data->name) }}</td>
                            <td style="border:1px solid #000;color:#000;">
                                @php
                                if (\DB::table('payments')
                                ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
                                ->where(['paymentdetails.batchId' => $batch_student->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->exists()
                                ) {
                                @endphp
                                {{\DB::table('payments')->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')->where(['paymentdetails.batchId' => $batch_student->batch_id, 'paymentdetails.studentId' => $batch_student->student_id])->whereNotNull('payments.invoiceId')->first()->invoiceId }}
                                @php
                                } else {
                                echo '-';
                                }
                                @endphp
                            </td>
                            <td style="border:1px solid #000;color:#000;">{{\DB::table('users')->where('id', $s_data->executiveId)->first()->username}}</td>
                            @if ($cer_data)
                            <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="isPresent[{{ $s_data->id }}]" value="{{$cer_data->isPresent}}" @if($cer_data->isPresent == 1) checked @else @endif></td>


                            @else
                            <td style="border:1px solid #000;color:#000;"><input size="1" type="checkbox" name="isPresent[{{ $s_data->id }}]" value="1"></td>
                            @endif


                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <div class="col-md-12 d-flex justify-content-end"><button class="btn btn-primary" type="submit">Update</button></div>
            </form>





        </div>
    </div>
</div> <!-- end row -->
@endsection
@push('scripts')
<script>
    // Extract the postingDate from the URL (e.g., '2023-07-13')
    var postingDateFromURL = '{{ request()->input('postingDate') }}';
    // Convert the postingDate to 'DD/MM/YYYY' format
    var formattedDate = moment(postingDateFromURL, 'YYYY-MM-DD');

    $("input[name='postingDate']").daterangepicker({
        singleDatePicker: true,
        startDate: formattedDate,
        showDropdowns: true,
        autoUpdateInput: true,
        format: 'dd/mm/yyyy',
    }).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY'));
});
</script>
</script>

@endpush