@extends('layout.master')
@section('title', 'Batch Wise Studnet Attandance')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
	.table-sm td,
	.table-sm th {
		padding: 0.1rem;
	}
	table,tbody,tr,th,td {
    font-size: 0.9em;
	}
	h5{
		font-size:12px;
	}
	p{
		font-size:10px;
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Batch</a></li>
					<li class="breadcrumb-item active">List</li>
				</ol>
			</div>
			<h4 class="page-title">Students Attandance</h4>
		</div>
	</div>
	<div class="col-12">
		<div class="card-box">




			<div class="row">
				<div class="col-sm-4">
					<label for="batch_id" class="col-form-label">Select Batch</label>
					<select name="batch_id" class="js-example-basic-single form-control" id="batch_id" required>
						<option value="">Select Batch</option>
						@forelse($batches as $batch)
						<option value="{{$batch->id}}">{{$batch->batchId}}</option>
						@empty
						@endforelse
					</select>
				</div>
				<!-- <div class="col-sm-4">
					<label for="start_date" class="col-form-label">Select Date</label>
					<div class="input-group">
						<input type="text" id="start_date" name="start_date" class="form-control" placeholder="dd/mm/yyyy" required>
						<div class="input-group-append">
							<span class="input-group-text"><i class="icon-calender"></i></span>
						</div>
					</div>

				</div>
				<div class="col-sm-4">
					<label for="class_count" class="col-form-label">Total Class</label>
					<input type="text" id="class_count" name="class_count" class="form-control" value="18">
				</div> -->
				<div class="col-sm-12 d-flex justify-content-end my-1">
					<button type="button" class="batch-attn-btn btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
				</div>
				<div class="col-sm-12 d-flex justify-content-end my-1">
				<button class="btn btn-primary" onclick="printSelectedArea()"><i class="fa fa-print fa-sm"></i></button>
				</div>

				<div class="col-md-12 selected-area" id="data">

				</div>




			</div>
		</div>
	</div> <!-- end row -->
	@endsection
	@push('scripts')
	<script>
		$('.responsive-datatable').DataTable();
	</script>
	<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
	<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
	<script>
		$('.js-example-basic-single').select2();
		$(document).ready(function() {
			$('.batch-attn-btn').on('click', function() {
				var batch_id = $('#batch_id option:selected').val();
				var start_date = $('#start_date').val();
				var class_count = $('#class_count').val();
				$.ajax({
					url: "{{route(currentUser().'.batchwiseAttendanceReport')}}",
					method: 'GET',
					dataType: 'json',
					data: {
						batch_id: batch_id,
						start_date: start_date,
						class_count: class_count, //Loop Count
					},
					success: function(res) {
						console.log(res.data);
						$('#data').empty();
						$('#data').append(res.data);
					},
					error: function(e) {
						console.log(e);
					}
				});
			});
			$("input[name='start_date']").daterangepicker({
				singleDatePicker: true,
				startDate: new Date(),
				showDropdowns: true,
				autoUpdateInput: true,
				format: 'dd/mm/yyyy',
			}).on('changeDate', function(e) {
				var date = moment(e.date).format('YYYY/MM/DD');
				$(this).val(date);
			});
		})

		function printSelectedArea() {
  window.print = function() {
    var printContents = document.getElementsByClassName("selected-area")[0].innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
  };
  window.print();
}


	</script>

	@endpush