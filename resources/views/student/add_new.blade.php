@extends('layout.master')
@section('title', 'Add New Studdent')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Student</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
            <h4 class="page-title">Add New Student</h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card-box">
            <form action="{{ route(currentUser().'.addNewStudent') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-4 row">
                        <label for="refId" class="col-sm-3 col-form-label">Select Reference</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="refId" name="refId">
                                <option value="">Select</option>
                                @if(count($allReference) > 0)
                                @foreach($allReference as $reference)
                                <option value="{{ $reference->id }}" {{ old('refId') == $reference->id ? "selected" : "" }}>{{$reference->refName}}</option>
                                @endforeach
                                @endif
                            </select>
                            @if($errors->has('refId'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('refId') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    @if(currentUser() != 'frontdesk')
                    <div class="col-lg-4 row">
                        <label for="name" class="col-sm-2 col-form-label">Time Slot</label>
                        <div class="col-sm-10">
                            <select name="js-example-basic-single batch_time_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Time Slot...">
                                @if(count($allBatchTime))
                                @foreach($allBatchTime as $batchTime)
                                <option value="{{ $batchTime->id}}" {{ old('batch_time_id') == $batchTime->id ? "selected" : "" }}>{{$batchTime->time}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 row">
                        <label for="name" class="col-sm-2 col-form-label">Batch Slot</label>
                        <div class="col-sm-10">
                            <select name="js-example-basic-single batch_slot_id" class="form-control js-example-basic-single" data-toggle="select2" data-placeholder="Choose Batch Slot...">
                                @if(count($allBatchSlot))
                                @foreach($allBatchSlot as $batchSlot)
                                <option value="{{ $batchSlot->id}}" {{ old('batch_slot_id') == $batchSlot->id ? "selected" : "" }}>{{$batchSlot->slotName}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="form-group row">
                    <div class="col-lg-6 row">
                        <label for="name" class="col-sm-2 col-form-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Student Full Name" value="{{old('name')}}">
                            @if($errors->has('name'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('name') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 row">
                        <label for="contact" class="col-sm-2 col-form-label">Contact Number</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Student Contact Number" value="{{old('contact')}}">
                            @if($errors->has('contact'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('contact') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    @if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesexecutive')
                    <div class="col-lg-6 row">
                        <label for="altContact" class="col-sm-2 col-form-label">Alternative Number</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="altContact" name="altContact" placeholder="Student Alternative Contact Number" value="{{old('altContact')}}">
                        </div>
                    </div>
                    <div class="col-lg-6 row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Student Email" value="{{old('email')}}">
                            @if($errors->has('email'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('email') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4 row">
                        <label for="division" class="col-sm-3 col-form-label">Select Division</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="division" name="division">
                                <option value="">Select</option>
                                @if(count($allDivision) > 0)
                                @foreach($allDivision as $division)
                                <option value="{{ $division->id }}" >{{$division->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 row">
                        <label for="district" class="col-sm-3 col-form-label">Select District</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="district" name="district">
                                <option value="">Select</option>
                                @if(count($allDistrict) > 0)
                                @foreach($allDistrict as $district)
                                <option value="{{ $district->id }}">{{$district->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 row">
                        <label for="area" class="col-sm-3 col-form-label">Select Area</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="area" name="area">
                                <option value="">Select</option>
                                @if(count($allUpazila) > 0)
                                @foreach($allUpazila as $upazila)
                                <option value="{{ $upazila->id }}">{{$upazila->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="address" name="address" rows="5" placeholder="Student Address" style="
                                resize:none;"></textarea>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-6 row">
                        <label for="otherInfo" class="col-sm-2 col-form-label">Other Info</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="otherInfo" name="otherInfo" rows="5" placeholder="Other Info" style="
                                resize:none;">{{old('otherInfo')}}</textarea>
                        </div>
                    </div>
                    @if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesexecutive')
                    <div class="col-lg-4 row mt-3">
                        <label for="" class="col-sm-3 col-form-label">Executive Reminder</label>
                        <div class="col-sm-9">
                            <div>
                                <div class="input-group">
                                    <input type="date" class="form-control" placeholder="mm/dd/yyyy" data-provide="datepicker" name="executiveReminder">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div><!-- input-group -->
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-4 row mt-3">
                        <label for="executiveId" class="col-sm-3 col-form-label">Select Executive</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="executiveId" name="executiveId">
                                <option value="">Select</option>
                                @if(count($allExecutive) > 0)
                                @foreach($allExecutive as $executive)
                                <option value="{{ $executive->id }}" {{ old('executiveId') == $executive->id ? "selected" : "" }}>{{$executive->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            @if($errors->has('executiveId'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('executiveId') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <!-- <div class="col-lg-4 row mt-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="status" name="status">
                                <option value="">Select</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                                <option value="2">Waiting</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 row">
                        <label for="operationNote" class="col-sm-2 col-form-label">Operation Note</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="operationNote" name="operationNote" rows="5" placeholder="Operation Note" style="
                                resize:none;"></textarea>
                        </div>
                    </div> -->
                    @if(currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'salesmanager' || currentUser() == 'salesexecutive')
                    <div class="col-lg-6 row">
                        <label for="executiveNote" class="col-sm-2 col-form-label">Executive Note</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="executiveNote" name="executiveNote" rows="5" placeholder="Executive Note" style="
                                resize:none;"></textarea>
                        </div>
                    </div>
                    @endif
                    <!-- <div class="col-lg-12 row mt-3">
                            <label for="photo" class="col-sm-2 col-form-label">Student Photo</label>
                            <div class="col-sm-5">
                                <input type="file" class="form-control" id="photo" name="photo" @change="onFileselected">
                            </div>
                            <div class="col-sm-5">
	                	        <img :src="form.photo" style="height:40px; width: 40px;">
	                        </div>
                        </div>   -->
                </div>
                <div class="form-group text-right mb-0">
                    <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary waves-effect">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection