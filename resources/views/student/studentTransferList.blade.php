@extends('layout.master')
@section('title', 'Student Transfer List')
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
                    <li class="breadcrumb-item active">Transfer</li>
                </ol>
            </div>
            <h4 class="page-title">Student Transfer List</h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card-box">
        <table class="mt-3 responsive-datatable table table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>Sl.</th>
                    <th>Student Id</th>
                    <th>Student Name</th>
                    <th>From Executive Id</th>
                    <th>to Executive Id</th>
                    <th>Note</th>
                    <th>Transferred By</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student_transfers as $transfer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$transfer->student_id}}</td>
                    <td>{{$transfer->stuname}}</td>
                    <td>{{\DB::table('users')->where('id',$transfer->curexId)->first()->name}}</td>
                    <td>{{\DB::table('users')->where('id',$transfer->newexId)->first()->name}}</td>
                    <td>{{$transfer->note}}</td>
                    <td>{{$transfer->uname}}</td>
                    <td>{{$transfer->created_at}}</td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>

@if(Session::has('response'))
@php print_r(Session::has('response')); @endphp
<script>
	Command: toastr["{{Session::get('response')['status']}}"]("{{Session::get('response')['message']}}")
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