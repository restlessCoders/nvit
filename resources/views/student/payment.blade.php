@extends('layout.master')
@section('title', 'Student Payment')
@push('styles')
<link href="{{asset('backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css') }}">
<style>
	.select2-container {
		width: 100% !important;
	}

	.ui-widget {
		font-size: 1em;
	}

	.form-control-sm {
		font-size: small;
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
					<li class="breadcrumb-item"><a href="javascript: void(0);">Student</a></li>
					<li class="breadcrumb-item active">Payment</li>
				</ol>
			</div>
			<h4 class="page-title">Student Payment</h4>
		</div>
	</div>

	<div class="col-12">
		<div class="card-box">
			<div class="form-group row">
				<div class="col-sm-12">
					<input type="text" id="item_search" class="py-3 form-control  ui-autocomplete-input" placeholder="Search By Student ID|Name|Batch">
				</div>
			</div>
			<div class="form-group row" id="details_data">
			</div>
			<form id="my-form">
				{{ csrf_field() }}
				<div class="row" id="student_detl_data">
				</div>
				<div class="table-responsive" id="paymenthisTblData">

				</div>
				<div class="table-responsive" id="paymentTblData">

				</div>
			</form>
		</div>
	</div>

</div>
@endsection
@push('scripts')
<script src="{{asset('backend/libs/multiselect/jquery.multi-select.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script src="{{ asset('backend/js/pages/jquery-ui.min.js') }}"></script>
<script>
	var systemId = "{{request()->get('systemId')}}";
	var sId = "{{request()->get('sId')}}";
	if(sId){
		return_row_with_data(sId);
		databySystemId(systemId);

	}
	function databySystemId(systemId) {
		$.ajax({
			url: "{{route(currentUser().'.databySystemId')}}",
			method: 'GET',
			dataType: 'json',
			data: {
				systemId: systemId
			},
			success: function(res) {
				//console.log(res.data);
				$('#paymentTblData').empty();
				$('#systemId').siblings().remove();
				$('#systemId').after(res.data);
			},
			error: function(e) {
				console.log(e);
			}
		});
	}

	function btn() {
		$('#submit-btn').prop('disabled', false);
	}


	$(document).on('submit', '#my-form', function(event) {
		event.preventDefault();
		$('#submit-btn').prop('disabled', true); // disable the button
		var formData = $('#my-form').serialize();
		// Make the AJAX request
		$.ajax({
			url: "{{route(currentUser().'.payment.store')}}",
			type: "POST",
			// Your data to send in the AJAX request
			data: formData,
			success: function(response) {
				//console.log(response);
				// Redirect with success message
				toastr.success(response.success);

				window.location.href = "{{route(currentUser().'.daily_collection_report') }}";
			},
			error: function(response) {
				// handle errors
				var errors = response.responseJSON.errors;
				if (errors.hasOwnProperty('mrNo')) {
					$('#mrNo').addClass('is-invalid');
					$('#mrNo-error').text(errors.mrNo[0]);
				} else {
					$('#mrNo').removeClass('is-invalid');
					$('#mrNo-error').text('');
				}
				if (errors.hasOwnProperty('paymentDate')) {
					$('#paymentDate').addClass('is-invalid');
					$('#paymentDate-error').text(errors.paymentDate[0]);
				} else {
					$('#paymentDate').removeClass('is-invalid');
					$('#paymentDate-error').text('');
				}
			},
		});
	});



	function paymentType(type) {
		if (type == 1) {
			var data = '<div class="col-sm-3" id="paymentType"><select class="form-control" id="pType" name="type">';
			data += '<option value="">Select</option>';
			data += '<option value="1">Registration</option>';
			data += '<option value="2">Batch</option>';
			data += '</select></div>';
			// append the new div after the element with id="myElement"
			$('#type').after(data);
		} else {
			$('#type').next('#paymentType').remove();
		}
	}

	$(document).on('click', '#showData', function() {
		var opt = $('#opt option:selected').val();
		var systmVal = $('#systmVal option:selected').val();
		var sId = $('#sId').val();
		//if (opt == 1) {
			var feeType = $('#pType option:selected').val();
			var batchId = $('#batch_id option:selected').val();

			$.ajax({
				url: "{{route(currentUser().'.allPaymentReportBySid')}}",
				method: 'GET',
				dataType: 'json',
				data: {
					systmVal: systmVal,
					sId: sId,
					feeType: feeType, //Registration Fee or Course Fee
					batchId: batchId
				},
				success: function(res) {
					console.log(res.data);
					$('#paymenthisTblData').empty();
					$('#paymenthisTblData').append(res.data);
				},
				error: function(e) {
					console.log(e);
				}
			});

		//} else if (opt == 2) {
			$.ajax({
				url: "{{route(currentUser().'.paymentData')}}",
				method: 'GET',
				dataType: 'json',
				data: {
					systmVal: systmVal,
					sId: sId,
					batchId: batchId
				},
				success: function(res) {
					console.log(res.data);
					$('#paymentTblData').empty();
					$('#paymentTblData').append(res.data);
				},
				error: function(e) {
					console.log(e);
				}
			});
		//}
	});

	/*=== Check Input Price== */
	function checkPrice(index) {

		var paidpricebyRow = parseFloat($('#paidpricebyRow_' + index).val());
		var coursepricebyRow = parseFloat($('#coursepricebyRow_' + index).val());
		/*To Calculate discount With Paid Price */
		paidpricebyRow += parseFloat($('#discountbyRow_' + index).val()) ? parseFloat($('#discountbyRow_' + index).val()) : 0;
		/*console.log(paidpricebyRow);
		console.log(coursepricebyRow);*/
		var tPayable = parseFloat($('.tPayable').val());
		if (paidpricebyRow > coursepricebyRow) {
			$('#paidpricebyRow_'+index).val('');
			toastr["warning"]("Paid Amount Cannot be Greater Than Course Price!!");
			return false;
		} else {
			var total = 0;
			$('.paidpricebyRow').each(function(index, element) {
				if ($(element).val() != "")
					total += parseFloat($(element).val());

			});
			$('.tPaid').val(total);
			$('.tDue').val(tPayable - total);
		}
	}
	/*===== Payment Calculation======*/
	$("#item_search").bind("paste", function(e) {
		$("#item_search").autocomplete('search');
	});
	$("#item_search").autocomplete({
		source: function(data, cb) {
			//console.log(data);
			$.ajax({
				autoFocus: true,
				url: "{{route(currentUser().'.searchStData')}}", //To Get Enroll Student 
				method: 'GET',
				dataType: 'json',
				data: {
					sdata: data.term
				},
				success: function(res) {
					//console.log(res);
					var result;
					result = {
						label: 'No Records Found ',
						value: ''
					};
					if (res.length) {
						result = $.map(res, function(el) {
							return {
								label: 'ID :-' + el.sId + ' ' + el.sName + ' ' + el.exName,
								value: '',
								id: el.sId
							};
						});
					} else {
						$('#details_data').html('');
						$('#student_detl_data').html('');
						$('#paymentTblData').html('');
					}
					cb(result);
				},
				error: function(e) {
					console.log(e);
				}
			});
		},
		response: function(e, ui) {
			if (ui.content.length == 1) {
				$(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
				$(this).autocomplete("close");
			}
			//console.log(ui);
		},
		//loader start
		search: function(e, ui) {},
		select: function(e, ui) {
			if (typeof ui.content != 'undefined') {
				if (isNaN(ui.content[0].id)) {
					return;
				}
				var student_id = ui.content[0].id;
			} else {
				var student_id = ui.item.id;
			}

			return_row_with_data(student_id);
			$("#item_search").val('');
		},
		//loader end
	});


	function return_row_with_data(student_id) {
		$("#item_search").addClass('ui-autocomplete-loader-center');
		var rowcount = $("#hidden_rowcount").val();

		$.ajax({
			autoFocus: true,
			url: "{{route(currentUser().'.enrollData')}}",
			method: 'GET',
			dataType: 'json',
			data: {
				student_id: student_id
			},
			success: function(res) {
				//console.log(res.data);
				if (res.data) {
					$('#student_detl_data').html('');
					$('#paymentTblData').html('');
					$('#details_data').append(res.data);
					$('#student_detl_data').append(res.sdata);
					$('#showData').show();
					$('#systmVal option[value="'+systemId+'"]').prop('selected', true);
				}
				$("#item_search").val('');
				$("#item_search").removeClass('ui-autocomplete-loader-center');
			},
			error: function(e) {
				console.log(e);
			}
		});

	}
</script>
@endpush