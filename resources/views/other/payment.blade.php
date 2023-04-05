@extends('layout.master')
@section('title', 'Student Other Payment')
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
			<h4 class="page-title">Student Other Payment</h4>
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

	
 
$(document).on('submit', '#my-form', function(event) {
		event.preventDefault();
		$('#submit-btn').prop('disabled', true); // disable the button
		var formData = $('#my-form').serialize();
		var optType = $('#optType option:selected').val();
		if(optType == 1){
				// Make the AJAX request
				$.ajax({
				url: "{{route(currentUser().'.payments.coursestore')}}",
				type: "POST",
				// Your data to send in the AJAX request
				data: formData,
				success: function(response) {
					//console.log(response);
					// Redirect with success message
					toastr.success(response.success);
					window.location.href = "{{route(currentUser().'.payments.index') }}";
				},
				error: function (response) {
					// handle errors
				},
			});
			
		}else{
			// Make the AJAX request
			$.ajax({
				url: "{{route(currentUser().'.payments.store')}}",
				type: "POST",
				// Your data to send in the AJAX request
				data: formData,
				success: function(response) {
					//console.log(response);
					// Redirect with success message
					toastr.success(response.success);
					window.location.href = "{{route(currentUser().'.payments.index') }}";
				},
				error: function (response) {
					// handle errors
				},
			});
		}
    });






	function optType(type){
		if(type==1){
			var sId = $('#sId').val();
			$.ajax({
				url: "{{route(currentUser().'.databyStudentId')}}",
				method: 'GET',
				dataType: 'json',
				data: {
					sId: sId,
					type:1
				},
				success: function(res) {
					//console.log(res.data);
					$('#paymentTblData').empty();
					$('#type').siblings().remove();
					$('#type').after(res.data);
				},
				error: function(e) {
					console.log(e);
				}
			});
		}else{
			$.ajax({
				url: "{{route(currentUser().'.databyStudentId')}}",
				method: 'GET',
				dataType: 'json',
				data: {
					sId: sId,
				},
				success: function(res) {
					//console.log(res.data);
					$('#paymentTblData').empty();
					$('#type').siblings().remove();
					$('#type').after(res.data);
				},
				error: function(e) {
					console.log(e);
				}
			});
		}
	}

		$(document).on('click', '#showData', function() {
		
			var optType = $('#optType option:selected').val();

			var opt = $('#opt option:selected').val();
			var sId = $('#sId').val();
			if(optType == 2 && opt == 4){
					$.ajax({
						url: "{{route(currentUser().'.otherPaymentByStudentId')}}",
						method: 'GET',
						dataType: 'json',
						data: {
							sId:sId,
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
				
			}else if(optType == 1 && opt == 4){
				$.ajax({
						url: "{{route(currentUser().'.coursePaymentByStudentId')}}",
						method: 'GET',
						dataType: 'json',
						data: {
							sId:sId,
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
			}
		});
	
/*=== Check Input Price== */
function checkPrice(index){
	
		var paidpricebyRow 		= parseFloat($('#paidpricebyRow_'+index).val());
		var coursepricebyRow 	= parseFloat($('#coursepricebyRow_'+index).val());
		/*To Calculate discount With Paid Price */
		paidpricebyRow 	+= parseFloat($('#discountbyRow_'+index).val())?parseFloat($('#discountbyRow_'+index).val()):0;
		/*console.log(paidpricebyRow);
		console.log(coursepricebyRow);*/
		var tPayable = parseFloat($('.tPayable').val());
		if(paidpricebyRow > coursepricebyRow){
			toastr["warning"]("Paid Amount Cannot be Greater Than Course Price!!");
			return false;
		}else{
			var total = 0;
			$('.paidpricebyRow').each(function(index, element){
			if($(element).val()!="")
            total += parseFloat($(element).val());

		});
		$('.tPaid').val(total);
		$('.tDue').val(tPayable-total);
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
				url: "{{route(currentUser().'.searchStudent')}}", //To Get Enroll Student 
				method: 'GET',
				dataType: 'json',
				data: {
					sdata: data.term
				},
				success: function(res) {
					console.log(res);
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
					}else{
						$('#details_data').html('');
						$('#student_detl_data').html('');
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
			url: "{{route(currentUser().'.stData')}}",
			method: 'GET',
			dataType: 'json',
			data: {
				student_id: student_id
			},
			success: function(res) {
				//console.log(res.data);
				if(res.data){
					$('#details_data').append(res.data);
					$('#student_detl_data').append(res.sdata);
					$('#showData').show();
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