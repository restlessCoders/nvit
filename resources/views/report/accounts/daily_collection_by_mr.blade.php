@extends('layout.master')
@section('title', 'Invoice | Money Receipt Report')
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Collection</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">Daily Collection Report (Money Receipt)</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">
			<div class="col-md-12 text-center">
				<h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
				<p class="p-0" style="font-size:16px"><strong>Collection Report (Money Receipt)</strong></p>
			</div>
			<form action="{{route(currentUser().'.daily_collection_report_by_mr')}}" role="search">
				@csrf
				<div class="row">
					<div class="col-sm-6">
						<label for="name" class="col-form-label">Student Name|Contact</label>
						<input type="text" class="form-control" name="studentId">
					</div>
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Invoice</label>
						<input type="text" class="form-control" name="invoiceId">
					</div>
					<div class="col-sm-3">
						<label for="name" class="col-form-label">Mr No</label>
						<input type="text" class="form-control" name="mrNo">
					</div>
				</div>

				<div class="row">
					<div class="col-sm-1">
						<label for="year" class="col-form-label">Year</label>
						<select name="year" class="js-example-basic-single form-control me-3">
							<option></option>
							@php
							for($i=2023;$i<=date('Y');$i++){ @endphp <option value="{{$i}}" @if(request()->get('year') == $i) selected @endif>{{$i}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					@php
					//echo 'ok';
					//echo request()->get('month');die;
					@endphp
					<div class="col-sm-1">
						<label for="month" class="col-form-label">Month</label>
						<select name="month" class="js-example-basic-single form-control me-3">
							<option></option>
							@php
							$months = array("Jan", "Feb", "Mar", "Apr","May","June","July","August","September","October","November","December");
							for($i=0;$i<count($months);$i++){ $monthValue=$i + 1; // Adding 1 to the index to get the correct month value @endphp <option value="{{$monthValue}}" @if(request()->get('month') == $monthValue) selected @endif>{{$months[$i]}}</option>
								@php
								}
								@endphp
						</select>
					</div>
					<div class="col-md-3">
						<label for="name" class="col-form-label">Select Date Range</label>
						<div class="input-group">
							<input type="text" id="date_range" name="date_range" class="form-control" placeholder="dd/mm/yyyy" autocomplete="off">
							<span class="input-group-text"><i class="bi bi-calendar"></i></span>
						</div>
					</div>
					<div class="col-md-3">
						<label for="startDate" class="col-form-label">Start Date</label>
						<div class="input-group">
							<input type="date" id="startDate" name="startDate" class="form-control">
						</div>
						<label for="endDate" class="col-form-label">End Date</label>
						<div class="input-group">
							<input type="date" id="endDate" name="endDate" class="form-control">
						</div>
					</div>
					@if(currentUser() != 'salesexecutive')
					<div class="col-sm-2">
						<label for="executiveId" class="col-form-label">Executive</label>
						<select name="executiveId" class="js-example-basic-single form-control me-3">
							<option></option>
							@forelse($users as $user)
							<option value="{{$user->id}}" @if(request()->get('executiveId') == $user->id) selected @endif>{{$user->username}}</option>
							@empty
							@endforelse
						</select>
					</div>
					@endif
					<div class="col-sm-2">
						<label for="name" class="col-form-label">Batch</label>
						<select name="batch_id" class="js-example-basic-single form-control me-3">
							<option></option>
							@forelse($batches as $batch)
							<option value="{{$batch->id}}" @if(request()->get('batch_id') == $batch->id) selected @endif>{{$batch->batchId}}</option>
							@empty
							@endforelse
						</select>
					</div>
					<div class="col-sm-1">
						<label for="name" class="col-form-label">Fee Type</label>
						<select name="feeType" class="js-example-basic-single form-control me-3">
							<option></option>
							<option value="1" @if(request()->get('feeType') == 1) selected @endif>Registration</option>
							<option value="2" @if(request()->get('feeType') == 2) selected @endif>Invoice</option>
							<option value="3" @if(request()->get('feeType') == 3) selected @endif>Due</option>
						</select>
					</div>
					<div class="col-sm-1">
						<label for="name" class="col-form-label">Mode</label>
						<select name="type" class="js-example-basic-single form-control me-3">
							<option></option>
							<option value="1" @if(request()->get('type') == 1) selected @endif>Cash</option>
							<option value="2" @if(request()->get('type') == 2) selected @endif>Bkash</option>
							<option value="3" @if(request()->get('type') == 3) selected @endif>Bank</option>
						</select>
					</div>
					<div class="col-sm-1">
						<label for="perPage" class="col-form-label">Per Page</label>
						<select name="perPage" class="js-example-basic-single form-control">
							<option></option>
							<option value="25" @if(request()->get('perPage') == 25) selected @endif>25</option>
							<option value="50" @if(request()->get('perPage') == 50) selected @endif>50</option>
							<option value="100" @if(request()->get('perPage') == 100) selected @endif>100</option>
							<option value="250" @if(request()->get('perPage') == 250) selected @endif>250</option>
							<option value="500" @if(request()->get('perPage') == 500) selected @endif>500</option>
						</select>
					</div>
					<div class="col-sm-12 d-flex justify-content-end my-1">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
						<a href="{{route(currentUser().'.daily_collection_report_by_mr')}}" class="ml-2 reset-btn btn btn-warning"><i class="fa fa-undo fa-sm"></i></a>
					</div>
				</div>
			</form>
			<div class="row pb-1">
				<div class="col-12">
					<button type="button" class="btn btn-sm btn-primary float-end" onclick="get_print()"><i class="bi bi-file-excel"></i> Export Excel</button>
				</div>
			</div>
			<table class="payment table table-sm table-bordered mb-5 text-center" style="font-size: small;">
				<thead>
					<tr>
						<th width="100px">Date</th>
						<th>AE</th>
						<th colspan="2">Stu ID|Name</th>
						<th>Contact</th>
						<th>Batch</th>
						<th>MR</th>
						<th>Inv</th>
						<th>Type</th>
						<th>Due Date</th>
						<th>Invoice Amt.</th>
						<th>Paid</th>
						<th>Dis</th>
						<th>Due</th>

						<th>Mode</th>
						{{--<th>Partial|Full</th>
						<th>Info</th>--}}
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@php
					$total_paid_amount = 0;
					$total_dis = 0;
					$total_cpyable = 0;
					@endphp
					@foreach($payments as $p)
					@php
					//echo $p->paymentDetail->count();die;
					$rowCount = \DB::table('paymentdetails')->where('paymentId', $p->paymentId)->count();
					//echo $rowCount;
					@endphp
					@php
					$total_paid_amount += $p->cpaidAmount;
					$total_dis += $p->discount;

					@endphp
					<tr>
						<td rowspan="" class="align-middle">
							<p class="p-0 m-1">{{date('d M Y',strtotime($p->paymentDate))}}</p>
						</td>
						<td rowspan="" class="align-middle">{{$p->username}}</td>
						<td class="align-middle">{{$p->studentId}}</td>
						<td class="align-middle">{{$p->name}}</td>
						<td class="align-middle">
							@if(currentUserId() == $p->executiveId || currentUser() == 'salesmanager' || currentUser() == 'superadmin' || currentUser() == 'operationmanager')
							{{$p->contact}}
							@else
							-
							@endif
						</td>
						<td class="align-middle">
							@if($p->batchId)
							{{$p->batchId}}
							@else
							{{$p->courseName}}
							@endif
						</td>
						<td class="align-middle">{{$p->mrNo}}</td>
						<td class="align-middle">{{--$p->invoiceId--}}
							@if(\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.batchId' => $p->bid])->whereNotNull('payments.invoiceId')->exists() && $p->feeType==2)
							{{
								\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.batchId' => $p->bid])->whereNotNull('payments.invoiceId')->first()->invoiceId}}
							@else
								@if(\DB::table('payments')
								->join('paymentdetails','paymentdetails.paymentId','payments.id')
								->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.course_id' => $p->course_id])->whereNotNull('payments.invoiceId')->exists() && $p->feeType==2)
								{{
								\DB::table('payments')
								->join('paymentdetails','paymentdetails.paymentId','payments.id')
								->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.course_id' => $p->course_id])->whereNotNull('payments.invoiceId')->first()->invoiceId
								}}
								@else
								-
								@endif
							@endif
						</td>
						@php
						if($p->feeType==1)
						$text = "Registration";
						else
						$text = "Invoice";
						@endphp
						<td class="align-middle">{{$text}}</td>
						@if($p->feeType==1)
						<td>-</td>
						<td>-</td>
						<td>{{$p->cpaidAmount}}</td>
						<td>-</td>
						<td>-</td>
						@else
						@if($p->cpaidAmount+$p->discount == $p->cPayable)
						<td class="align-middle">-</td>
						@else
						<td class="align-middle"><strong class="text-danger" style="font-size:12px;">@if($p->dueDate){{date('d M Y',strtotime($p->dueDate))}} @else - @endif</strong></td>
						@endif
						<td class="align-middle">
							@if($p->bid)
							{{\DB::table('student_batches')->where('student_id',$p->studentId)->where('batch_id',$p->bid)->first()->course_price}}{{--$p->cPayable--}}
							@else
							{{\DB::table('student_batches')->where('student_id',$p->studentId)->where('course_id',$p->course_id)->first()->course_price}}
							@endif
							@if($p->course_id)
							{{-- \DB::table('student_batches')->where('student_id',$p->studentId)->where('course_id',$p->course_id)->first()->course_price --}}{{--$p->cPayable--}}
							@endif
						</td>
						<td class="align-middle">{{$p->cpaidAmount}}</td>
						<td class="align-middle">{{$p->discount?$p->discount:0}}</td>
						<td class="align-middle">{{($p->cPayable-($p->cpaidAmount+$p->discount))}}</td>
						@php $total_cpyable += ($p->cPayable-($p->cpaidAmount+$p->discount)); @endphp
						@endif





						<td class="align-middle">@if($p->payment_mode == 1) Cash @elseif($p->payment_mode == 2) Bkash @else Bank @endif</td>
						{{--<td class="align-middle">@if($p->feeType == 1) Partial @else Full @endif</td>
						<td width="150px" class="align-middle">
							<p class="text-left m-0 p-0">Paid By:-</p>
							<p class="text-left m-0 p-0">Paid:{{\Carbon\Carbon::createFromTimestamp(strtotime($p->created_at))->format('j M, Y')}}</p>
						<p class="text-left m-0 p-0">Updated By:-</p>
						<p class="text-left m-0 p-0">Update:@if($p->updated_at){{\Carbon\Carbon::createFromTimestamp(strtotime($p->updated_at))->format('j M, Y')}}@endif</p>
						</td>--}}

						<td width="130px" class="align-middle">
							<a href="" class="text-success" title="print"><i class="fas fa-print mr-1"></i></a>
							{{--|| currentUser() == 'superadmin'--}}
							@if(currentUser() == 'accountmanager')
							@if($p->batchId)
							<a href="{{route(currentUser().'.payment.edit',[encryptor('encrypt', $p->id),$p->studentId])}}" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
							@else
							<a href="{{route(currentUser().'.payment.course.edit',[encryptor('encrypt', $p->id),$p->studentId])}}" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
							@endif
							{{--<form method="POST" action="{{route(currentUser().'.payment.destroy',[encryptor('encrypt', $p->id)])}}" style="display: inline;">
							@csrf
							@method('DELETE')
							<input name="_method" type="hidden" value="DELETE">
							<a href="javascript:void(0)" type="submit" class="delete mr-2 text-danger" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt mr-1"></i></a>
							</form>
							<a href="" class="text-warning" title="reverse"><i class="fas fa-redo-alt mr-1"></i></a>
							<a href="" class="text-info" title="refund"><i class="fas fa-exchange-alt"></i></a>--}}
							@endif
						</td>
					</tr>

					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="11"></td>
						<td>{{$total_paid_amount}}</td>
						<td>{{$total_dis}}</td>
						<td>{{$total_cpyable}}</td>
					</tr>
				</tfoot>
			</table>
			{{$payments->links()}}
		</div>
	</div>
</div> <!-- end row -->

<div class="full_page"></div>
<div id="my-content-div" class="d-none"></div>

@endsection
@push('scripts')
<script>
	$('.responsive-datatable').DataTable();
</script>
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
	$('.js-example-basic-single').select2({
		placeholder: 'Select Option',
		allowClear: true
	});

	$('.payment').on('click', '.delete', function(event) {
		event.preventDefault();
		swal({
				title: "Are you sure you want to Delete this",
				text: "If you Delete this, it will be Deleted.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$(this).parent().submit();
				}
			});
	});
	$('.js-example-basic-single').select2();

	$(document).ready(function() {

		/*var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#date_range').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);*/

    // Define default start and end dates
    /*var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#date_range').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    }

    $("input[name='date_range']").daterangepicker({
        singleDatePicker: false,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, cb);

    // Initialize the default date range display
    cb(start, end);

    // Event listener to update the input value on selection
    $("input[name='date_range']").on('apply.daterangepicker', function(ev, picker) {
        cb(picker.startDate, picker.endDate);
    });*/
});


</script>
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
	<script>
    function exportReportToExcel(idname,filename) {
        let table = document.getElementsByTagName(idname); // you can use document.getElementById('tableId') as well by providing id to the table tag
        TableToExcel.convert(table[1], { // html code may contain multiple tables so here we are refering to 1st table tag
        name: `${filename}.xlsx`, // fileName you could use any name
        sheet: {
            name: 'Daily Collection Report' // sheetName
        }
        });
        $("#my-content-div").html("");
        $('.full_page').html("");
    }
    function get_print(){
        $('.full_page').html('<div style="background:rgba(0,0,0,0.5);width:100vw; height:100vh;position:fixed; top:0; left;0"><div class="loader my-5"></div></div>');
        $.get(
            "{{route(currentUser().'.daily_collection_report_by_mr_report_print')}}{!! ltrim(Request()->fullUrl(),Request()->url()) !!}",
            function (data) {
                $("#my-content-div").html(data);
            }
        ).then(function(){exportReportToExcel('table','Daily Collection Report')})
    }
</script>
@endpush