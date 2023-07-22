@extends('layout.master')
@section('title', 'Adjustment')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('styles')
<style>
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Adjustment</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
            <h4 class="page-title">Add Adjustment</h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card-box">
            <form action="{{route(currentUser().'.refund.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-3">
                        <label>Student ID: <span class="text-danger sup">*</span></label>
                        <input type="text" name="sb_id" value="{{ $student->id }}" class="form-control" readonly />
                    </div>
                    <div class="col-lg-3">
                        <label>Student Name: <span class="text-danger sup">*</span></label>
                        <input type="text" value="{{ $student->name }}" class="form-control" readonly />
                    </div>
                    @php $sl = 1; @endphp
                    <div class="col-lg-3" id="systemId">
                        <label class="control-label">Select Admission: </label>
                        <select name="systemId" class="form-control @if($errors->has('type')) {{ 'is-invalid' }} @endif">
                            <option value="">Select Admission</option>
                            @forelse ($enrollStudent as $key => $e)
                            <option value="{{$e->systemId}}">Admission-{{$sl++}}</option>;
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3">
                        <label class="control-label">Operation Type: </label>
                        <select name="type" class="form-control @if($errors->has('type')) {{ 'is-invalid' }} @endif">
                            <option value="">Select Type</option>
                            <option value="1">Refund</option>
                            <option value="2">Adjustment</option>
                            <option value="3">Deduction</option>
                        </select>
                        @if($errors->has('type'))
                        <small class="d-block text-danger mb-3">
                            {{ $errors->first('type') }}
                        </small>
                        @endif
                    </div>
                    <div class="col-lg-3">
                        <label>Deduction Amount:</label>
                        <input type="text" name="nid" class="form-control"/>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label">Note: </label>
                        <textarea name="note" class="form-control" rows="5"></textarea>
                    </div>
                    <!-- <div class="col-lg-4">
                        <label>NID:</label>
                        <input type="text" name="nid" value="{{ old('nid') }}" class="form-control" placeholder="NID Number" />
                    </div> -->
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
</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script>
    $('select[name="systemId"]').on('change', function() {
        var systemId = $(this).val();
        $.ajax({
			url: "{{route(currentUser().'.enrolldata')}}",
			method: 'GET',
			dataType: 'json',
			data: {
				systemId: systemId
			},
			success: function(res) {
				console.log(res.data);
                $('#systemId').next().remove();
				$('#systemId').after(res.data);
			},
			error: function(e) {
				console.log(e);
			}
		});
    });
    
</script>
@endpush