
<table class="table table-sm table-bordered table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
	<thead>
		<tr>
			<th>SL.</th>
			<th>S.Id</th>
			<th>Student Name</th>
			<th>Executive</th>
			@if(currentUser() == 'superadmin')
			<th>Reference</th>
			@endif
			<th>Batch</th>
			<th width="120px">Inv Date</th>
			<th>Inv</th>
			<th>Inv Price</th>
			<th>Paid</th>
			<th>Due</th>
			<th>Contact</th>
			<th>Type</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@php $total_cpyable = 0; @endphp
		@if(count($allBatches))
		@foreach($allBatches as $batch)
		{{--<form action="{{ route(currentUser().'.addstudentCourseAssign',encryptor('encrypt',$batch->sId)) }}" method="POST" enctype="multipart/form-data">--}}
		<!--<form action="" method="POST" enctype="multipart/form-data">-->
		<tr>
			<td>{{ $loop->iteration }}</td>
			<td>{{$batch->sId}}</td>
			<td>{{$batch->sName}}
				@if($batch->is_drop == 1) <strong class="text-danger">(Withdrawn)</strong>@endif
			</td>
			<td>{{$batch->exName}}</td>
			@if(currentUser() == 'superadmin')
			<td>{{\DB::table('references')->where('id',$batch->refId)->first()->refName}}</td>
			@endif
			<td>
				@if(\DB::table('batches')->where('id',$batch->batch_id)->first())
				{{\DB::table('batches')->where('id',$batch->batch_id)->first()->batchId}}
				@else
				@if(\DB::table('courses')->where('id',$batch->course_id)->first())
				{{\DB::table('courses')->where('id',$batch->course_id)->first()->courseName}}
				@endif
				@endif
			</td>
			<td>{{--\Carbon\Carbon::createFromTimestamp(strtotime($batch->entryDate))->format('j M, Y')--}}
				@php $inv = \DB::table('payments')
				->join('paymentdetails','paymentdetails.paymentId','payments.id')
				->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->exists(); @endphp
				@if($inv)
				{{\Carbon\Carbon::createFromTimestamp(strtotime(\DB::table('payments')
				->join('paymentdetails','paymentdetails.paymentId','payments.id')
				->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->first()->paymentDate))->format('j M, Y')}}
				@else
				-
				@endif
			</td>
			<td>
				@if(\DB::table('payments')
				->join('paymentdetails','paymentdetails.paymentId','payments.id')
				->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->exists())
				{{\DB::table('payments')
				->join('paymentdetails','paymentdetails.paymentId','payments.id')
				->where(['paymentdetails.studentId'=>$batch->sId,'paymentdetails.batchId' => $batch->batch_id])->whereNotNull('payments.invoiceId')->first()->invoiceId}}
				@else
				-
				@endif
			</td>
			
			<td>
			@if(currentUserId() == $batch->executiveId || currentUser() == 'salesmanager' || currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'accountmanager' )
				@if($batch->batch_id != 0)
				{{$batch->course_price-\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])->sum('discount')}}
				@else
				{{$batch->course_price-\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'course_id' => $batch->course_id])->sum('discount')}}
				@endif
			@endif
			</td>
			<td>
			@if(currentUserId() == $batch->executiveId || currentUser() == 'salesmanager' || currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'accountmanager' )
				@if($batch->batch_id != 0)
				{{\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])->sum('cpaidAmount')}}
				@else
				{{\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'course_id' => $batch->course_id])->sum('cpaidAmount')}}
				@endif
			@endif
			</td>
			<td>
			@if(currentUserId() == $batch->executiveId || currentUser() == 'salesmanager' || currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'accountmanager' )
				@if($batch->batch_id != 0)
				@php 
				$a = $batch->course_price-(\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])->whereNull('deleted_at')->sum('discount')+\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'batchId' => $batch->batch_id])->whereNull('deleted_at')->sum('cpaidAmount'));
				@endphp
				{{$a}}
				@php $total_cpyable += $a; @endphp
				@else
				@php 
				$b = $batch->course_price-(\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'course_id' => $batch->course_id])->sum('discount')+\DB::table('paymentdetails')->where(['studentId'=>$batch->sId,'course_id' => $batch->course_id])->whereNull('deleted_at')->sum('cpaidAmount'));
				@endphp
				{{$b}}
				@php $total_cpyable += $b; @endphp
				@endif
			@endif
			</td>
			

			<td>
				@if(currentUserId() == $batch->executiveId || currentUser() == 'salesmanager' || currentUser() == 'superadmin' || currentUser() == 'operationmanager' || currentUser() == 'frontdesk')
				{{$batch->contact}}
				@else
				-
				@endif
			</td>
			<td>
				@if($batch->status == 2) Enroll @endif
				@if($batch->status == 3) Knocking @endif
				@if($batch->status == 4)Evaluation @endif
			</td>
			<td>
				@if($batch->type == 1)
				At a Time
				@else
				Installment
				@endif
			</td>

		</tr>
	<!--</form>-->
	@endforeach
	@else
	<tr>
		<td colspan="6">No Data Found</td>
	</tr>
	@endif
	</tbody>
	<tfoot>
		<tr>
			<td colspan="@if(currentUser() == 'Superadmin') 9 @else 9 @endif"></td>
			<td>{{$total_cpyable}}</td>
		</tr>
	</tfoot>
</table>

