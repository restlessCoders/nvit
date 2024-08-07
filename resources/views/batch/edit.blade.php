@extends('layout.master')
@section('title', 'Edit Batch')
@push('styles')
    <link href="{{ asset('backend/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Batch</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Batch</h4>
            </div>
        </div>
        <div class="col-12">
            <div class="card-box">
                <form action="{{ route(currentUser() . '.batch.update', [encryptor('encrypt', $bdata->id)]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{ Session::get('user') }}" name="userId">
                    <input type="hidden" name="uptoken" value="{{ encryptor('encrypt', $bdata->id) }}">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Courses: <span class="text-danger sup">*</span></label>
                            <select name="courseId"
                                class="js-example-basic-single form-control select2 @if ($errors->has('courseId')) {{ 'is-invalid' }} @endif">
                                <option></option>
                                @if (count($allCourses) > 0)
                                    @foreach ($allCourses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ old('courseId', $bdata->courseId) == $course->id ? 'selected' : '' }}>
                                            {{ $course->courseName }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('courseId'))
                                <small class="d-block text-danger mb-3">
                                    {{ $errors->first('courseId') }}
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <label>Batch Name<span class="text-danger sup">*</span></label>
                            <input id="batchId" type="text" class="form-control" name="batchId"
                                value="{{ old('batchId', $bdata->batchId) }}" required>
                            @if ($errors->has('batchId'))
                                <span class="text-danger"> {{ $errors->first('batchId') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <label>Batch Time: <span class="text-danger sup">*</span></label>
                            <select name="btime"
                                class="js-example-basic-single form-control select2 @if ($errors->has('btime')) {{ 'is-invalid' }} @endif">
                                <option></option>
                                @if (count($allBatchTime) > 0)
                                    @foreach ($allBatchTime as $btime)
                                        <option value="{{ $btime->id }}"
                                            {{ old('btime', $bdata->btime) == $btime->id ? 'selected' : '' }}>
                                            {{ $btime->time }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('btime'))
                                <small class="d-block text-danger mb-3">
                                    {{ $errors->first('btime') }}
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <label>Batch Slot: <span class="text-danger sup">*</span></label>
                            <select name="bslot"
                                class="js-example-basic-single form-control select2 @if ($errors->has('bslot')) {{ 'is-invalid' }} @endif">
                                <option></option>
                                @if (count($allBatchSlot) > 0)
                                    @foreach ($allBatchSlot as $bslot)
                                        <option value="{{ $bslot->id }}"
                                            {{ old('bslot', $bdata->bslot) == $bslot->id ? 'selected' : '' }}>
                                            {{ $bslot->slotName }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('bslot'))
                                <small class="d-block text-danger mb-3">
                                    {{ $errors->first('bslot') }}
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <label>Trainer: <span class="text-danger sup">*</span></label>
                            <select name="trainerId"
                                class="js-example-basic-single form-control select2 @if ($errors->has('trainerId')) {{ 'is-invalid' }} @endif">
                                <option></option>
                                @if (count($allTrainer) > 0)
                                    @foreach ($allTrainer as $trainer)
                                        <option value="{{ $trainer->id }}"
                                            {{ old('trainerId', $bdata->trainerId) == $trainer->id ? 'selected' : '' }}>
                                            {{ $trainer->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('trainerId'))
                                <small class="d-block text-danger mb-3">
                                    {{ $errors->first('trainerId') }}
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <label>Classroom: <span class="text-danger sup">*</span></label>
                            <select name="examRoom" class="js-example-basic-single form-control select2">
                                <option></option>
                                @if (count($allClassroom) > 0)
                                    @foreach ($allClassroom as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('examRoom', $bdata->examRoom) == $c->id ? 'selected' : '' }}>
                                            ClassRoom-{{ $c->classroom }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Start Date<span class="text-danger sup">*</span></label>
                            <div class="input-group">
                                <input type="text" name="startDate" class="form-control" placeholder="dd/mm/yyyy">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>
                        <div class="col-lg-4">
                            <label>End Date<span class="text-danger sup">*</span></label>
                            <div class="input-group">
                                <input type="text" name="endDate" class="form-control" placeholder="dd/mm/yyyy">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>
                        <div class="col-lg-4">
                            <label>Exam Date<span class="text-danger sup">*</span></label>
                            <div class="input-group">
                                <input type="text" name="examDate" class="form-control" placeholder="dd/mm/yyyy">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>
                        <div class="col-lg-4">
                            <label>Exam Time<span class="text-danger sup">*</span></label>
                            <div class="input-group">
                                <input id="timepicker" type="text" class="form-control" name="examTime"
                                    value="{{ old('examTime', $bdata->examTime) }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>
                        <div class="col-lg-4">
                            <label>Seat Capacity<span class="text-danger sup">*</span></label>
                            <div class="input-group">
                                <input id="seat" type="text" class="form-control" name="seat"
                                    value="{{ old('seat', $bdata->seat) }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>
                        <!-- <div class="col-lg-4">
          <label>Price: <span class="text-danger sup">*</span></label>
          <input type="text" name="price" value="{{ old('price', $bdata->price) }}" class="form-control @if ($errors->has('price')) {{ 'is-invalid' }} @endif" placeholder="Full Name" />
          @if ($errors->has('price'))
    <small class="d-block text-danger mb-3">
           {{ $errors->first('price') }}
          </small>
    @endif
         </div>
         <div class="col-lg-4">
          <label>Discount: <span class="text-danger sup">*</span></label>
          <div class="input-group">
           <div class="input-group-prepend">
            <span class="input-group-text">
             <i class="fas fa-percent"></i>
            </span>
           </div>
           <input name="discount" type="text" value="{{ old('discount', $bdata->discount) }}" class="form-control  @if ($errors->has('discount')) {{ 'is-invalid' }} @endif" placeholder="Enter Discount" />
          </div>
          @if ($errors->has('discount'))
    <small class="d-block text-danger mb-3">
           {{ $errors->first('discount') }}
          </small>
    @endif
         </div> -->
                        <div class="col-lg-4">
                            <label class="control-label">Status: </label>
                            <select name="status"
                                class="form-control @if ($errors->has('status')) {{ 'is-invalid' }} @endif">
                                <option value="1" @if ($bdata->status == 1) selected @endif>Active</option>
                                <option value="0" @if ($bdata->status == 0) selected @endif>Inactive</option>
                            </select>
                            @if ($errors->has('status'))
                                <small class="d-block text-danger mb-3">
                                    {{ $errors->first('status') }}
                                </small>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label">Course Type: </label>
                            <select name="type" class="form-control">
                                <option value="1" @if ($bdata->type == 1) selected @endif>Regular</option>
                                <option value="2" @if ($bdata->type == 2) selected @endif>Crash</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">Course Duration: </label>
                            <input type="text" name="courseDuration" class="form-control"
                                value="{{ old('courseDuration', $bdata->courseDuration) }}" required>
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">Per Class Hour: </label>
                            <input type="text" name="classHour" class="form-control"
                                value="{{ old('courseDuration', $bdata->classHour) }}" required>
                        </div>
                        {{-- <div class="col-lg-4">
						<label class="control-label">Number Of Class: </label>
						<input type="text" name="totalClass" class="form-control" value="{{$bdata->totalClass}}" readonly>
					</div> --}}
                        <div class="col-lg-12">
                            <label class="control-label">Remarks: </label>
                            <textarea name="remarks" class="form-control" rows="5" style="resize:none;">{{ old('remarks', $bdata->remarks) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group text-right mb-0">
                        <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect">
                            Cancel
                        </button>
                    </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('backend/libs/select2/select2.min.js') }}"></script>
        <script>
            /*$(".js-example-basic-courseId").prop("disabled", false);*/
            $('.js-example-basic-single').select2({
                placeholder: 'Select Option',
                allowClear: true
            });
            if ('{{ $bdata->startDate }}')
                date = '{{ $bdata->startDate }}'
            else
                date = new Date();
            $('input[name="startDate"]').daterangepicker({
                singleDatePicker: true,
                startDate: moment(date).format('DD/MM/YYYY'),
                showDropdowns: true,
                autoUpdateInput: true,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            }).on('changeDate', function(e) {
                var date = moment(e.date).format('YYYY/MM/DD');
                $(this).val(date);
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $(this).trigger('change');
            });

			if ('{{ $bdata->endDate }}')
                date = '{{ $bdata->endDate }}'
            else
                date = new Date();
            $('input[name="endDate"]').daterangepicker({
                singleDatePicker: true,
                startDate: moment(date).format('DD/MM/YYYY'),
                showDropdowns: true,
                autoUpdateInput: true,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            }).on('changeDate', function(e) {
                var date = moment(e.date).format('YYYY/MM/DD');
                $(this).val(date);
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $(this).trigger('change');
            });

			if ('{{ $bdata->examDate }}')
                date = '{{ $bdata->examDate }}'
            else
                date = new Date();
            $('input[name="examDate"]').daterangepicker({
                singleDatePicker: true,
                startDate: moment(date).format('DD/MM/YYYY'),
                showDropdowns: true,
                autoUpdateInput: true,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            }).on('changeDate', function(e) {
                var date = moment(e.date).format('YYYY/MM/DD');
                $(this).val(date);
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $(this).trigger('change');
            });

            /*$('input[name="startDate"],input[name="endDate"],input[name="examDate"]').daterangepicker({
            	singleDatePicker: true,
            	startDate: new Date(),
            	showDropdowns: true,
            	autoUpdateInput: true,
            	locale: {
            		format: 'DD/MM/YYYY'
            	}
            });*/
            $("#timepicker").timepicker({
                defaultTIme: !1,
                icons: {
                    up: "mdi mdi-chevron-up",
                    down: "mdi mdi-chevron-down"
                }
            });
        </script>
    @endpush
