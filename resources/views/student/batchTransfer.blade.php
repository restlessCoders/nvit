@extends('layout.master')
@section('title', 'Batch Transfer')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Student</a></li>
                    <li class="breadcrumb-item active">Batch Transfer</li>
                </ol>
            </div>
            <h4 class="page-title">Add New Transfer</h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card-box">
            <form action="{{ route(currentUser().'.transfer') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" value="{{ Session::get('user') }}" name="userId">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-4 row">
                        <label for="student_id" class="col-sm-3 col-form-label">Select student</label>
                        <div class="col-sm-9">
                            <select class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Student..." name="student_id">
                                <option value="">Select</option>
                                @if(count($allStudent) > 0)
                                @foreach($allStudent as $st)
                                <option value="{{ $st->id }}" {{ old('student_id') == $st->id ? "selected" : "" }}>{{$st->id}}-{{$st->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            @if($errors->has('student_id'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('student_id') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row frombatch">
                        <div class="col-lg-4 row">
                        </div>
                    </div>
                    <div class="form-group row tobatch">
                        <div class="col-lg-4 row">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-4 row">
                        <label class="col-sm-3 control-label">Transfer Resson: </label>
                        <div class="col-sm-9">
                            <select name="op_type" class="form-control @if($errors->has('type')) {{ 'is-invalid' }} @endif" required>
                                <option value="">Select Type</option>
                                <option value="3">Batch Transfer</option>
                                <option value="4">Repeat</option>
                                <option value="5">Course Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group row">

                            <label for="note" class="col-sm-2 col-form-label">Note</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="operationNote" name="note" rows="5" placeholder="Note" style="
                                    resize:none;"></textarea>
                            </div>
                        </div>
                    </div>



                    <div class="form-group text-right mb-0">
                        <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                            Submit
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script>
    $('.js-example-basic-single').select2();
    /*===Student Enroll Batch Data=====*/
    $(document).ready(function() {
        $('select[name=student_id]').on('change', function() {
            var student_id = $.trim($("select[name=student_id]").children("option:selected").val());
            $.ajax({
                url: "{{route(currentUser().'.studentEnrollBatch')}}",
                method: 'GET',
                dataType: 'json',
                data: {
                    id: student_id
                },
                success: function(res) {
                    console.log(res);
                    $('.frombatch').html(res.data);
                    $('.tobatch').html(res.data2);
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });

    });
</script>

@if(Session::has('response'))
@php print_r(Session::has('response')); @endphp
<script>
    Command: toastr["{{Session::get('response')['class']}}"]("{{Session::get('response')['message']}}")
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