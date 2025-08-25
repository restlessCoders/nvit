@extends('layout.master')
@section('title', 'Reg Report')
@push('styles')
    <link href="{{ asset('backend/libs/multiselect/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('styles')
    <style>
        th {
            font-size: 14px;
        }

        table,
        tr {
            font-size: 13px;

            .btn {
                font-size: 11px;
                margin: 1px;
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reg</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
                <h4 class="page-title">Reg Report</h4>
            </div>
        </div>
        <div class="col-12">
            <div class="card-box">
                <div class="col-md-12 text-center">
                    <h5>NEW VISION INFORMATION TECHNOLOGY LTD.</h5>
                    <p class="p-0" style="font-size:16px"><strong>Reg Report</strong></p>
                </div>

                <form action="{{ route(currentUser() . '.regReport') }}" method="" role="search">
                    @csrf
                    <div class="row">

                        <div class="col-md-3">
                            <label for="name" class="col-form-label">Enrollment From</label>
                            <div class="input-group">
                                <input type="date" id="from" name="from" class="form-control"
                                    value="{{ request()->get('from') }}">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="name" class="col-form-label">Enrollment To</label>
                            <div class="input-group">
                                <input type="date" id="to" name="to" class="form-control"
                                    value="{{ request()->get('to') }}">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-end my-1">
                            <button type="submit" class="btn btn-primary mr-1"><i class="fa fa-search fa-sm"></i></button>
                            <a href="{{ route(currentUser() . '.regReport') }}" class="reset-btn btn btn-warning"><i
                                    class="fa fa-undo fa-sm"></i></a>
                        </div>
                    </div>
                </form>
                <div class="row pb-1">
				<div class="col-12">
					<button type="button" class="btn btn-sm btn-primary float-end" onclick="get_print()"><i class="bi bi-file-excel"></i> Export Excel</button>
				</div>
			</div>
                <table class="table table-sm table-bordered table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mr Date</th>
                            <th>Executive</th>
                            <th>Name</th>
                            <th>Student ID</th>
							<th>Batch|Course</th>
                            <th>Mr No</th>
                            <th>Type</th>
                            <th>Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0;@endphp
                        @forelse ($results as $key => $report)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->paymentDate)->format('d M, Y') }}</td>
                                <td>{{ $report->exName }}</td>
								<td>{{ $report->sName }}</td>
                                <td>{{ $report->sId }}</td>
                                <td>
                                    @if ($report->batch_id)
                                        {{ \DB::table('batches')->where('id', $report->batch_id)->first()->batchId }}
                                    @else
                                        {{ \DB::table('courses')->where('id', $report->course_id)->first()->courseName }}
                                    @endif
                                </td>
								<td>{{ $report->mrNo }}</td>
                                <td>Registration</td>
                                <td>{{-- \Carbon\Carbon::parse($report->entryDate)->format('d M, Y') --}}{{$report->total_paid}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data found for the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div> <!-- end row -->



    <div class="full_page"></div>
    <div id="my-content-div" class="d-none"></div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="{{ asset('backend/libs/multiselect/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('backend/libs/select2/select2.min.js') }}"></script>
    <script>
        $('.js-example-basic-single').select2({
            placeholder: 'Select Option',
            allowClear: true
        });
        $('.reset-btn').on('click', function() {
            $('.js-example-basic-single').val(null).trigger('change');
        });
    </script>
    @if (Session::has('response'))
        <script>
            Command: toastr["{{ Session::get('response')['class'] }}"]("{{ Session::get('response')['message'] }}")
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
    @if (currentUser() == 'superadmin' ||
            currentUser() == 'accountmanager' ||
            currentUser() == 'operationmanager' ||
            currentUser() == 'salesmanager')
        <script>
            function exportReportToExcel(idname, filename) {
                let table = document.getElementsByTagName(
                    idname); // you can use document.getElementById('tableId') as well by providing id to the table tag
                TableToExcel.convert(table[
                    1], { // html code may contain multiple tables so here we are refering to 1st table tag
                    name: `${filename}.xlsx`, // fileName you could use any name
                    sheet: {
                        name: 'Reg Report' // sheetName
                    }
                });
                $("#my-content-div").html("");
                $('.full_page').html("");
            }

            function get_print() {
                $('.full_page').html(
                    '<div style="background:rgba(0,0,0,0.5);width:100vw; height:100vh;position:fixed; top:0; left;0"><div class="loader my-5"></div></div>'
                );
                $.get(
                    "{{ route(currentUser() . '.regReportPrint') }}{!! ltrim(Request()->fullUrl(), Request()->url()) !!}",
                    function(data) {
                        $("#my-content-div").html(data);
                    }
                ).then(function() {
                    exportReportToExcel('table', 'Reg Report')
                })
            }
        </script>
    @endif
@endpush
